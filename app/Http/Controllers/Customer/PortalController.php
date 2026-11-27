<?php

namespace App\Http\Controllers\Customer;

use App\Enums\RedemptionStatus;
use App\Enums\WasteCategory;
use App\Http\Controllers\Controller;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\WasteType;
use App\Services\RedemptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class PortalController extends Controller
{
    public function __construct(public RedemptionService $redemptionService) {}

    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $this->redemptionService->expireOverdueRedemptions($user);
        $user->refresh();

        $totalWeightKg = (float) $user->deposits()->sum('total_weight_kg');
        $totalDepositsCount = $user->deposits()->count();
        $estimatedCo2SavedKg = round($totalWeightKg * 2.5, 1);

        $pendingRedemptions = $user->redemptions()
            ->with('reward')
            ->where('status', RedemptionStatus::Pending)
            ->latest()
            ->get();

        $recentDeposits = $user->deposits()
            ->with(['items.wasteType', 'officer:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', [
            'user' => $user,
            'totalWeightKg' => $totalWeightKg,
            'totalDepositsCount' => $totalDepositsCount,
            'estimatedCo2SavedKg' => $estimatedCo2SavedKg,
            'pendingRedemptions' => $pendingRedemptions,
            'recentDeposits' => $recentDeposits,
        ]);
    }

    public function catalogWaste(Request $request): View
    {
        $selectedCategory = $request->query('category');

        $wasteTypes = WasteType::query()
            ->active()
            ->when($selectedCategory && WasteCategory::tryFrom((string) $selectedCategory), function ($query) use ($selectedCategory): void {
                $query->where('category', $selectedCategory);
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('customer.catalog-waste', [
            'wasteTypes' => $wasteTypes,
            'categories' => WasteCategory::cases(),
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function rewards(Request $request): View
    {
        $user = $request->user();
        $this->redemptionService->expireOverdueRedemptions($user);
        $user->refresh();

        $selectedCategory = $request->query('category');

        $categories = Reward::query()
            ->active()
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $rewards = Reward::query()
            ->active()
            ->when($selectedCategory, fn ($query) => $query->where('category', $selectedCategory))
            ->orderBy('points_required')
            ->get();

        return view('customer.rewards', [
            'user' => $user,
            'rewards' => $rewards,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function redeem(Request $request, Reward $reward): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);

        try {
            $redemption = $this->redemptionService->createRedemption(
                customer: $request->user(),
                reward: $reward,
                quantity: $quantity,
            );
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('customer.history', ['tab' => 'redemptions'])
            ->with('success', "Penukaran berhasil! Tunjukkan Kode Klaim {$redemption->claim_code} ke petugas posko sebelum {$redemption->expires_at->format('d M Y')}.");
    }

    public function cancelRedemption(Request $request, Redemption $redemption): RedirectResponse
    {
        if ($redemption->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak berhak membatalkan tiket ini.');
        }

        try {
            $this->redemptionService->cancelRedemption($redemption, $request->user());
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('customer.history', ['tab' => 'redemptions'])
            ->with('success', "Tiket {$redemption->claim_code} dibatalkan. Poin telah dikembalikan ke saldo Anda.");
    }

    public function history(Request $request): View
    {
        $user = $request->user();
        $this->redemptionService->expireOverdueRedemptions($user);
        $user->refresh();

        $tab = $request->query('tab') === 'redemptions' ? 'redemptions' : 'deposits';

        $deposits = $user->deposits()
            ->with(['items.wasteType', 'officer:id,name'])
            ->latest()
            ->paginate(10, ['*'], 'deposits_page');

        $redemptions = $user->redemptions()
            ->with(['reward', 'verifier:id,name'])
            ->latest()
            ->paginate(10, ['*'], 'redemptions_page');

        return view('customer.history', [
            'tab' => $tab,
            'deposits' => $deposits,
            'redemptions' => $redemptions,
        ]);
    }
}
