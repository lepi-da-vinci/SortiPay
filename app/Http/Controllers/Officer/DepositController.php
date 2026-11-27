<?php

namespace App\Http\Controllers\Officer;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\DepositTransaction;
use App\Models\User;
use App\Models\WasteType;
use App\Services\DepositService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use InvalidArgumentException;

class DepositController extends Controller
{
    public function __construct(public DepositService $depositService) {}

    public function index(): View
    {
        $wasteTypes = WasteType::query()
            ->active()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $customers = User::query()
            ->where('role', UserRole::User)
            ->orderBy('name')
            ->limit(25)
            ->get(['id', 'member_code', 'name', 'phone', 'points_balance']);

        $recentDeposits = DepositTransaction::query()
            ->with(['user:id,name,member_code', 'officer:id,name'])
            ->latest()
            ->limit(8)
            ->get();

        $wasteTypesJson = $wasteTypes->map(fn (WasteType $w): array => [
            'id' => $w->id,
            'name' => $w->name,
            'category' => $w->category->label(),
            'unit' => $w->unit->value,
            'points_per_unit' => $w->points_per_unit,
        ])->values()->all();

        return view('officer.deposit.index', [
            'wasteTypes' => $wasteTypes,
            'wasteTypesJson' => $wasteTypesJson,
            'customers' => $customers,
            'recentDeposits' => $recentDeposits,
        ]);
    }

    public function searchCustomers(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $customers = User::query()
            ->where('role', UserRole::User)
            ->when($query !== '', function ($builder) use ($query): void {
                $builder->where(function ($sub) use ($query): void {
                    $sub->where('member_code', 'like', "%{$query}%")
                        ->orWhere('name', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                });
            })
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'member_code', 'name', 'phone', 'points_balance']);

        return response()->json(['data' => $customers]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', UserRole::User->value),
            ],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.waste_type_id' => [
                'required',
                'integer',
                Rule::exists('waste_types', 'id')->where('is_active', true),
            ],
            'items.*.quantity' => ['required', 'numeric', 'gt:0', 'max:10000'],
            'items.*.quality_multiplier' => ['required', 'numeric', Rule::in([1, 1.0, '1', '1.00', 0.75, '0.75'])],
        ], [
            'user_id.required' => 'Pilih nasabah terlebih dahulu.',
            'user_id.exists' => 'Nasabah tidak ditemukan.',
            'items.required' => 'Minimal tambahkan satu item sampah.',
            'items.*.waste_type_id.required' => 'Jenis sampah wajib dipilih.',
            'items.*.quantity.gt' => 'Berat/kuantitas sampah harus lebih dari 0.',
        ]);

        $customer = User::query()->findOrFail($validated['user_id']);

        try {
            $deposit = $this->depositService->recordDeposit(
                customer: $customer,
                officer: $request->user(),
                items: $validated['items'],
                notes: $validated['notes'] ?? null,
            );
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('officer.deposit.receipt', $deposit)
            ->with('success', "Setoran {$deposit->receipt_number} berhasil disimpan! +".number_format($deposit->total_points).' poin masuk ke akun '.$customer->name.'.');
    }

    public function receipt(DepositTransaction $deposit): View
    {
        $deposit->load(['user', 'officer', 'items.wasteType']);

        return view('officer.deposit.receipt', [
            'deposit' => $deposit,
        ]);
    }
}
