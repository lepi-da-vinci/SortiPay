<?php

namespace App\Services;

use App\Enums\PointLedgerType;
use App\Enums\WasteUnit;
use App\Models\DepositTransaction;
use App\Models\PointLedger;
use App\Models\User;
use App\Models\WasteType;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DepositService
{
    /**
     * Record a multi-item waste deposit atomically and credit customer points.
     *
     * @param  array<int, array{waste_type_id: int, quantity: float|int|string, quality_multiplier: float|int|string}>  $items
     */
    public function recordDeposit(
        User $customer,
        User $officer,
        array $items,
        ?string $notes = null,
    ): DepositTransaction {
        if ($items === []) {
            throw new InvalidArgumentException('Minimal satu item sampah harus diinput.');
        }

        return DB::transaction(function () use ($customer, $officer, $items, $notes): DepositTransaction {
            $lockedCustomer = User::query()->lockForUpdate()->findOrFail($customer->id);

            $wasteTypeIds = collect($items)->pluck('waste_type_id')->unique()->all();
            $wasteTypes = WasteType::query()
                ->whereIn('id', $wasteTypeIds)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

            $preparedItems = [];
            $totalWeightKg = 0.0;
            $totalPoints = 0;

            foreach ($items as $item) {
                $wasteTypeId = (int) $item['waste_type_id'];
                $wasteType = $wasteTypes->get($wasteTypeId);

                if (! $wasteType instanceof WasteType) {
                    throw new InvalidArgumentException('Jenis sampah tidak valid atau sudah nonaktif.');
                }

                $quantity = round((float) $item['quantity'], 2);
                $multiplier = round((float) $item['quality_multiplier'], 2);

                if ($quantity <= 0) {
                    throw new InvalidArgumentException('Kuantitas sampah harus lebih besar dari 0.');
                }

                if ($multiplier <= 0 || $multiplier > 1.0) {
                    throw new InvalidArgumentException('Pengali kondisi sampah tidak valid.');
                }

                $subtotalPoints = (int) floor($quantity * $wasteType->points_per_unit * $multiplier);

                if ($wasteType->unit === WasteUnit::Kg) {
                    $totalWeightKg += $quantity;
                }

                $totalPoints += $subtotalPoints;

                $preparedItems[] = [
                    'waste_type_id' => $wasteType->id,
                    'quantity' => $quantity,
                    'quality_multiplier' => $multiplier,
                    'points_per_unit_snapshot' => $wasteType->points_per_unit,
                    'subtotal_points' => $subtotalPoints,
                ];
            }

            $deposit = DepositTransaction::query()->create([
                'receipt_number' => $this->generateReceiptNumber(),
                'user_id' => $lockedCustomer->id,
                'officer_id' => $officer->id,
                'total_weight_kg' => round($totalWeightKg, 2),
                'total_points' => $totalPoints,
                'notes' => $notes,
            ]);

            $deposit->items()->createMany($preparedItems);

            $lockedCustomer->points_balance += $totalPoints;
            $lockedCustomer->save();

            PointLedger::query()->create([
                'user_id' => $lockedCustomer->id,
                'type' => PointLedgerType::DepositCredit,
                'amount' => $totalPoints,
                'balance_after' => $lockedCustomer->points_balance,
                'reference_type' => 'deposit_transaction',
                'reference_id' => $deposit->id,
                'notes' => "Setoran sampah {$deposit->receipt_number}",
                'created_by' => $officer->id,
                'created_at' => now(),
            ]);

            return $deposit->load(['user', 'officer', 'items.wasteType']);
        });
    }

    private function generateReceiptNumber(): string
    {
        $datePrefix = 'DEP-'.now()->format('Ymd').'-';

        $countToday = DepositTransaction::query()
            ->where('receipt_number', 'like', "{$datePrefix}%")
            ->count();

        $sequence = $countToday + 1;

        do {
            $candidate = $datePrefix.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
            $sequence++;
        } while (DepositTransaction::query()->where('receipt_number', $candidate)->exists());

        return $candidate;
    }
}
