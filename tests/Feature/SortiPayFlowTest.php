<?php

namespace Tests\Feature;

use App\Enums\PointLedgerType;
use App\Enums\RedemptionStatus;
use App\Enums\UserRole;
use App\Enums\WasteCategory;
use App\Enums\WasteUnit;
use App\Models\DepositTransaction;
use App\Models\PointLedger;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\User;
use App\Models\WasteType;
use App\Services\RedemptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SortiPayFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_and_gets_unique_member_code(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Rina Kartika',
            'email' => 'rina@example.com',
            'phone' => '081299998888',
            'address' => 'Jl. Anggrek No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('customer.dashboard'));

        $user = User::query()->where('email', 'rina@example.com')->firstOrFail();
        $this->assertSame(UserRole::User, $user->role);
        $this->assertMatchesRegularExpression('/^SRT-\d{4}-\d{4}$/', $user->member_code);
        $this->assertSame(0, $user->points_balance);
    }

    public function test_rbac_blocks_unauthorized_roles(): void
    {
        $customer = User::factory()->create(['role' => UserRole::User]);
        $officer = User::factory()->officer()->create();

        $this->actingAs($customer)->get(route('officer.deposit.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($officer)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($officer)->get(route('customer.dashboard'))->assertForbidden();
    }

    public function test_officer_can_record_multi_item_deposit_and_rate_changes_do_not_affect_snapshot(): void
    {
        $admin = User::factory()->admin()->create();
        $officer = User::factory()->officer()->create();
        $customer = User::factory()->create(['role' => UserRole::User, 'points_balance' => 100]);

        $pet = WasteType::query()->create([
            'category' => WasteCategory::DaurUlang,
            'name' => 'Botol PET Bening',
            'unit' => WasteUnit::Kg,
            'points_per_unit' => 300,
            'is_recyclable' => true,
            'is_active' => true,
        ]);

        $organic = WasteType::query()->create([
            'category' => WasteCategory::Organik,
            'name' => 'Sampah Dapur',
            'unit' => WasteUnit::Kg,
            'points_per_unit' => 50,
            'is_recyclable' => true,
            'is_active' => true,
        ]);

        // PET: floor(2.5 * 300 * 1.00) = 750
        // Organic: floor(3.3 * 50 * 0.75) = floor(123.75) = 123
        // Total: 873 points, weight: 5.80 kg
        $response = $this->actingAs($officer)->post(route('officer.deposit.store'), [
            'user_id' => $customer->id,
            'notes' => 'Setoran uji coba',
            'items' => [
                ['waste_type_id' => $pet->id, 'quantity' => 2.5, 'quality_multiplier' => 1.00],
                ['waste_type_id' => $organic->id, 'quantity' => 3.3, 'quality_multiplier' => 0.75],
            ],
        ]);

        $deposit = DepositTransaction::query()->firstOrFail();
        $response->assertRedirect(route('officer.deposit.receipt', $deposit));

        $this->assertSame(873, $deposit->total_points);
        $this->assertSame('5.80', (string) $deposit->total_weight_kg);
        $this->assertSame(973, $customer->fresh()->points_balance);

        $ledger = PointLedger::query()->where('user_id', $customer->id)->firstOrFail();
        $this->assertSame(PointLedgerType::DepositCredit, $ledger->type);
        $this->assertSame(873, $ledger->amount);
        $this->assertSame(973, $ledger->balance_after);

        // Admin updates PET rate from 300 to 500 — old deposit snapshot must stay 300!
        $this->actingAs($admin)->put(route('admin.waste-types.update', $pet), [
            'category' => WasteCategory::DaurUlang->value,
            'name' => 'Botol PET Bening',
            'unit' => WasteUnit::Kg->value,
            'points_per_unit' => 500,
            'is_recyclable' => true,
            'is_active' => true,
        ])->assertRedirect();

        $oldItem = $deposit->items()->where('waste_type_id', $pet->id)->firstOrFail();
        $this->assertSame(300, $oldItem->points_per_unit_snapshot);
        $this->assertSame(750, $oldItem->subtotal_points);
    }

    public function test_customer_redemption_and_officer_cancellation_refunds_points_and_stock(): void
    {
        $officer = User::factory()->officer()->create();
        $customer = User::factory()->create(['role' => UserRole::User, 'points_balance' => 600]);

        $reward = Reward::query()->create([
            'name' => 'Minyak Goreng 1L',
            'category' => 'Sembako',
            'points_required' => 500,
            'stock' => 5,
            'is_active' => true,
        ]);

        // Redeem 1 item
        $this->actingAs($customer)
            ->post(route('customer.rewards.redeem', $reward), ['quantity' => 1])
            ->assertRedirect(route('customer.history', ['tab' => 'redemptions']));

        $redemption = Redemption::query()->firstOrFail();
        $this->assertSame(RedemptionStatus::Pending, $redemption->status);
        $this->assertSame(100, $customer->fresh()->points_balance);
        $this->assertSame(4, $reward->fresh()->stock);

        // Insufficient points for a second redemption
        $this->actingAs($customer)
            ->post(route('customer.rewards.redeem', $reward), ['quantity' => 1])
            ->assertSessionHas('error');

        // Officer cancels redemption -> atomic refund
        $this->actingAs($officer)
            ->post(route('officer.redemptions.cancel', $redemption))
            ->assertRedirect(route('officer.redemptions.index'));

        $this->assertSame(RedemptionStatus::Cancelled, $redemption->fresh()->status);
        $this->assertSame(600, $customer->fresh()->points_balance);
        $this->assertSame(5, $reward->fresh()->stock);

        $this->assertSame(2, PointLedger::query()->where('user_id', $customer->id)->count());
    }

    public function test_expired_redemption_automatically_refunds_points_and_stock(): void
    {
        $customer = User::factory()->create(['role' => UserRole::User, 'points_balance' => 500]);
        $reward = Reward::query()->create([
            'name' => 'Gula Pasir 1kg',
            'category' => 'Sembako',
            'points_required' => 450,
            'stock' => 3,
            'is_active' => true,
        ]);

        $service = app(RedemptionService::class);
        $redemption = $service->createRedemption($customer, $reward, 1);

        // Fast-forward expiration
        $redemption->update(['expires_at' => now()->subMinute()]);

        $expiredCount = $service->expireOverdueRedemptions($customer);

        $this->assertSame(1, $expiredCount);
        $this->assertSame(RedemptionStatus::Expired, $redemption->fresh()->status);
        $this->assertSame(500, $customer->fresh()->points_balance);
        $this->assertSame(3, $reward->fresh()->stock);
    }
}
