<?php

namespace App\Http\Controllers\Officer;

use App\Enums\RedemptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Redemption;
use App\Services\RedemptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class RedemptionVerificationController extends Controller
{
    public function __construct(public RedemptionService $redemptionService) {}

    public function index(Request $request): View
    {
        $this->redemptionService->expireOverdueRedemptions($request->user());

        $code = strtoupper(trim((string) $request->query('code', '')));
        $searchedRedemption = null;

        if ($code !== '') {
            $searchedRedemption = Redemption::query()
                ->with(['user', 'reward', 'verifier'])
                ->where('claim_code', $code)
                ->first();
        }

        $pendingRedemptions = Redemption::query()
            ->with(['user', 'reward'])
            ->where('status', RedemptionStatus::Pending)
            ->orderBy('expires_at')
            ->get();

        $recentCompleted = Redemption::query()
            ->with(['user', 'reward', 'verifier'])
            ->whereIn('status', [RedemptionStatus::Completed, RedemptionStatus::Cancelled, RedemptionStatus::Expired])
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('officer.redemptions.index', [
            'code' => $code,
            'searchedRedemption' => $searchedRedemption,
            'pendingRedemptions' => $pendingRedemptions,
            'recentCompleted' => $recentCompleted,
        ]);
    }

    public function complete(Request $request, Redemption $redemption): RedirectResponse
    {
        try {
            $this->redemptionService->completeRedemption($redemption, $request->user());
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('officer.redemptions.index')
            ->with('success', "Penyerahan barang untuk tiket {$redemption->claim_code} selesai dicatat.");
    }

    public function cancel(Request $request, Redemption $redemption): RedirectResponse
    {
        try {
            $this->redemptionService->cancelRedemption($redemption, $request->user());
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('officer.redemptions.index')
            ->with('success', "Tiket {$redemption->claim_code} dibatalkan. Poin nasabah dan stok barang telah dikembalikan otomatis.");
    }
}
