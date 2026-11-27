<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PointLedgerType;
use App\Enums\RedemptionStatus;
use App\Enums\UserRole;
use App\Enums\WasteCategory;
use App\Http\Controllers\Controller;
use App\Models\DepositItem;
use App\Models\DepositTransaction;
use App\Models\PointLedger;
use App\Models\Redemption;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalWeightKg = (float) DepositTransaction::query()->sum('total_weight_kg');
        $totalPointsCirculating = (int) User::query()->where('role', UserRole::User)->sum('points_balance');
        $totalPointsRedeemed = (int) Redemption::query()
            ->whereIn('status', [RedemptionStatus::Pending, RedemptionStatus::Completed])
            ->sum('total_points_spent');
        $activeCustomersCount = User::query()->where('role', UserRole::User)->count();
        $officersCount = User::query()->where('role', UserRole::Officer)->count();
        $pendingRedemptionsCount = Redemption::query()->where('status', RedemptionStatus::Pending)->count();

        $categoryStatsRaw = DepositItem::query()
            ->join('waste_types', 'deposit_items.waste_type_id', '=', 'waste_types.id')
            ->select(
                'waste_types.category',
                DB::raw('SUM(deposit_items.quantity) as total_qty'),
                DB::raw('SUM(deposit_items.subtotal_points) as total_points'),
                DB::raw('COUNT(deposit_items.id) as items_count'),
            )
            ->groupBy('waste_types.category')
            ->get()
            ->keyBy('category');

        $maxPoints = max(1, (int) $categoryStatsRaw->max('total_points'));

        $categoryStats = collect(WasteCategory::cases())->map(function (WasteCategory $cat) use ($categoryStatsRaw, $maxPoints): array {
            $row = $categoryStatsRaw->get($cat->value);
            $points = (int) ($row->total_points ?? 0);

            return [
                'category' => $cat,
                'total_qty' => (float) ($row->total_qty ?? 0),
                'total_points' => $points,
                'items_count' => (int) ($row->items_count ?? 0),
                'percent' => (int) round(($points / $maxPoints) * 100),
            ];
        });

        $recentLedgers = PointLedger::query()
            ->with(['user:id,name,member_code', 'creator:id,name'])
            ->latest('id')
            ->limit(8)
            ->get();

        return view('admin.dashboard', [
            'totalWeightKg' => $totalWeightKg,
            'totalPointsCirculating' => $totalPointsCirculating,
            'totalPointsRedeemed' => $totalPointsRedeemed,
            'activeCustomersCount' => $activeCustomersCount,
            'officersCount' => $officersCount,
            'pendingRedemptionsCount' => $pendingRedemptionsCount,
            'categoryStats' => $categoryStats,
            'recentLedgers' => $recentLedgers,
            'totalIssuedPoints' => (int) PointLedger::query()->where('type', PointLedgerType::DepositCredit)->sum('amount'),
        ]);
    }
}
