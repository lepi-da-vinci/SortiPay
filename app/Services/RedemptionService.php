<?php

namespace App\Services;

use App\Enums\PointLedgerType;
use App\Enums\RedemptionStatus;
use App\Models\PointLedger;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class RedemptionService
{
    /**
     * Reserve reward stock and debit points atomically for a customer redemption.
     */
    public function createRedemption(User $customer, Reward $reward, int $quantity = 1): Redemption
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Jumlah penukaran minimal 1.');
        }

        return DB::transaction(function () use ($customer, $reward, $quantity): Redemption {
            $lockedCustomer = User::query()->lockForUpdate()->findOrFail($customer->id);
            $lockedReward = Reward::query()->lockForUpdate()->findOrFail($reward->id);

            if (! $lockedReward->is_active) {
                throw new InvalidArgumentException('Barang hadiah sedang tidak tersedia.');
            }

            if ($lockedReward->stock < $quantity) {
                throw new InvalidArgumentException('Stok barang hadiah tidak mencukupi.');
            }

            $totalPointsSpent = $lockedReward->points_required * $quantity;

            if ($lockedCustomer->points_balance < $totalPointsSpent) {
                throw new InvalidArgumentException('Saldo poin Anda tidak mencukupi untuk penukaran ini.');
            }

            $lockedReward->stock -= $quantity;
            $lockedReward->save();

            $lockedCustomer->points_balance -= $totalPointsSpent;
            $lockedCustomer->save();

            $redemption = Redemption::query()->create([
                'claim_code' => $this->generateClaimCode(),
                'user_id' => $lockedCustomer->id,
                'reward_id' => $lockedReward->id,
                'quantity' => $quantity,
                'total_points_spent' => $totalPointsSpent,
                'status' => RedemptionStatus::Pending,
                'expires_at' => now()->addDays(7),
            ]);

            PointLedger::query()->create([
                'user_id' => $lockedCustomer->id,
                'type' => PointLedgerType::RedemptionDebit,
                'amount' => -$totalPointsSpent,
                'balance_after' => $lockedCustomer->points_balance,
                'reference_type' => 'redemption',
                'reference_id' => $redemption->id,
                'notes' => "Penukaran {$lockedReward->name} ({$redemption->claim_code})",
                'created_by' => $lockedCustomer->id,
                'created_at' => now(),
            ]);

            return $redemption->load(['user', 'reward']);
        });
    }

    /**
     * Confirm physical handover of a redeemed reward by an officer/admin.
     */
    public function completeRedemption(Redemption $redemption, User $officer): Redemption
    {
        return DB::transaction(function () use ($redemption, $officer): Redemption {
            $locked = Redemption::query()->lockForUpdate()->findOrFail($redemption->id);

            if ($locked->status !== RedemptionStatus::Pending) {
                throw new InvalidArgumentException('Tiket penukaran ini tidak dalam status Menunggu Pengambilan.');
            }

            if ($locked->expires_at->isPast()) {
                $this->refundLockedRedemption($locked, $officer, RedemptionStatus::Expired);
                throw new InvalidArgumentException('Tiket penukaran sudah kedaluwarsa. Poin dan stok telah dikembalikan otomatis.');
            }

            $locked->status = RedemptionStatus::Completed;
            $locked->verified_by = $officer->id;
            $locked->verified_at = now();
            $locked->save();

            return $locked->load(['user', 'reward', 'verifier']);
        });
    }

    /**
     * Cancel a pending redemption and refund both points and stock atomically.
     */
    public function cancelRedemption(Redemption $redemption, User $actor): Redemption
    {
        return DB::transaction(function () use ($redemption, $actor): Redemption {
            $locked = Redemption::query()->lockForUpdate()->findOrFail($redemption->id);

            if ($locked->status !== RedemptionStatus::Pending) {
                throw new InvalidArgumentException('Hanya tiket berstatus Menunggu Pengambilan yang dapat dibatalkan.');
            }

            return $this->refundLockedRedemption($locked, $actor, RedemptionStatus::Cancelled);
        });
    }

    /**
     * Expire overdue pending redemptions and refund points & stock.
     */
    public function expireOverdueRedemptions(?User $actor = null): int
    {
        $overdueIds = Redemption::query()
            ->where('status', RedemptionStatus::Pending)
            ->where('expires_at', '<', now())
            ->pluck('id');

        $count = 0;

        foreach ($overdueIds as $id) {
            DB::transaction(function () use ($id, $actor, &$count): void {
                $locked = Redemption::query()->lockForUpdate()->find($id);

                if (! $locked instanceof Redemption || $locked->status !== RedemptionStatus::Pending || ! $locked->expires_at->isPast()) {
                    return;
                }

                $systemActor = $actor ?? User::query()->find($locked->user_id);
                if (! $systemActor instanceof User) {
                    return;
                }

                $this->refundLockedRedemption($locked, $systemActor, RedemptionStatus::Expired);
                $count++;
            });
        }

        return $count;
    }

    private function refundLockedRedemption(
        Redemption $locked,
        User $actor,
        RedemptionStatus $targetStatus,
    ): Redemption {
        $lockedCustomer = User::query()->lockForUpdate()->findOrFail($locked->user_id);
        $lockedReward = Reward::query()->lockForUpdate()->findOrFail($locked->reward_id);

        $lockedReward->stock += $locked->quantity;
        $lockedReward->save();

        $lockedCustomer->points_balance += $locked->total_points_spent;
        $lockedCustomer->save();

        $locked->status = $targetStatus;
        $locked->verified_by = $actor->id;
        $locked->verified_at = now();
        $locked->save();

        PointLedger::query()->create([
            'user_id' => $lockedCustomer->id,
            'type' => PointLedgerType::RedemptionRefund,
            'amount' => $locked->total_points_spent,
            'balance_after' => $lockedCustomer->points_balance,
            'reference_type' => 'redemption',
            'reference_id' => $locked->id,
            'notes' => $targetStatus === RedemptionStatus::Expired
                ? "Refund otomatis tiket kedaluwarsa ({$locked->claim_code})"
                : "Refund pembatalan penukaran ({$locked->claim_code})",
            'created_by' => $actor->id,
            'created_at' => now(),
        ]);

        return $locked->load(['user', 'reward', 'verifier']);
    }

    private function generateClaimCode(): string
    {
        do {
            $candidate = 'CLM-'.strtoupper(Str::random(5));
        } while (Redemption::query()->where('claim_code', $candidate)->exists());

        return $candidate;
    }
}
