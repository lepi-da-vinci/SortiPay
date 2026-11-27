<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\WasteCategory;
use App\Enums\WasteUnit;
use App\Models\Reward;
use App\Models\User;
use App\Models\WasteType;
use App\Services\DepositService;
use App\Services\RedemptionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $admin = User::query()->create([
            'member_code' => 'SRT-2026-0001',
            'name' => 'Raka Pratama (Admin)',
            'email' => 'admin@sortipay.id',
            'phone' => '081100000001',
            'address' => 'Kantor Pusat Bank Sampah SortiPay, Jl. Hijau Lestari No. 1',
            'role' => UserRole::Admin,
            'points_balance' => 0,
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        $officer = User::query()->create([
            'member_code' => 'SRT-2026-0002',
            'name' => 'Budi Santoso (Petugas)',
            'email' => 'petugas@sortipay.id',
            'phone' => '081200000002',
            'address' => 'Posko SortiPay RW 05, Kelurahan Sukamaju',
            'role' => UserRole::Officer,
            'points_balance' => 0,
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        $nasabah1 = User::query()->create([
            'member_code' => 'SRT-2026-0003',
            'name' => 'Siti Aminah',
            'email' => 'siti@sortipay.id',
            'phone' => '081311112222',
            'address' => 'Jl. Melati No. 14, RT 02 / RW 05',
            'role' => UserRole::User,
            'points_balance' => 0,
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        $nasabah2 = User::query()->create([
            'member_code' => 'SRT-2026-0004',
            'name' => 'Andi Wijaya',
            'email' => 'andi@sortipay.id',
            'phone' => '081333334444',
            'address' => 'Jl. Kenanga No. 8, RT 04 / RW 05',
            'role' => UserRole::User,
            'points_balance' => 0,
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        $nasabah3 = User::query()->create([
            'member_code' => 'SRT-2026-0005',
            'name' => 'Dewi Lestari',
            'email' => 'dewi@sortipay.id',
            'phone' => '081355556666',
            'address' => 'Jl. Flamboyan No. 21, RT 01 / RW 03',
            'role' => UserRole::User,
            'points_balance' => 0,
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        $wasteTypesData = [
            [
                'category' => WasteCategory::DaurUlang,
                'name' => 'Botol PET Bening (Bersih)',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 300,
                'is_recyclable' => true,
                'description' => 'Botol minuman plastik bening tanpa isi, tutup dan label sebaiknya dipisah.',
            ],
            [
                'category' => WasteCategory::Anorganik,
                'name' => 'Gelas Plastik PP / Emberan',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 200,
                'is_recyclable' => true,
                'description' => 'Gelas air mineral, wadah plastik rumah tangga, ember plastik bersih.',
            ],
            [
                'category' => WasteCategory::DaurUlang,
                'name' => 'Kardus Kering / Karton',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 180,
                'is_recyclable' => true,
                'description' => 'Kardus kemasan dilipat rapi dalam kondisi kering tidak berminyak.',
            ],
            [
                'category' => WasteCategory::DaurUlang,
                'name' => 'Kertas HVS / Buku Bekas',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 220,
                'is_recyclable' => true,
                'description' => 'Kertas putih bekas cetak, buku tulis, atau arsip kantor.',
            ],
            [
                'category' => WasteCategory::Anorganik,
                'name' => 'Kaleng Aluminium',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 800,
                'is_recyclable' => true,
                'description' => 'Kaleng minuman bersoda/susu berbahan aluminium yang sudah dibilas.',
            ],
            [
                'category' => WasteCategory::Anorganik,
                'name' => 'Botol Kaca Utuh',
                'unit' => WasteUnit::Pcs,
                'points_per_unit' => 50,
                'is_recyclable' => true,
                'description' => 'Botol kecap, sirup, atau minuman kaca dalam kondisi utuh tidak pecah.',
            ],
            [
                'category' => WasteCategory::Organik,
                'name' => 'Sampah Dapur / Sisa Sayur (Kompos)',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 50,
                'is_recyclable' => true,
                'description' => 'Sisa potongan sayur, kulit buah, dan bahan organik untuk pengomposan.',
            ],
            [
                'category' => WasteCategory::Organik,
                'name' => 'Daun Kering / Ranting Terpilah',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 40,
                'is_recyclable' => true,
                'description' => 'Dedaunan kering halaman dan ranting kecil bebas plastik.',
            ],
            [
                'category' => WasteCategory::KhususB3,
                'name' => 'Minyak Jelantah',
                'unit' => WasteUnit::Liter,
                'points_per_unit' => 400,
                'is_recyclable' => true,
                'description' => 'Minyak goreng bekas pakai yang disaring dan dikemas dalam botol tertutup.',
            ],
            [
                'category' => WasteCategory::KhususB3,
                'name' => 'E-Waste (Baterai / Kabel / Elektronik Kecil)',
                'unit' => WasteUnit::Kg,
                'points_per_unit' => 500,
                'is_recyclable' => true,
                'description' => 'Kabel bekas, charger rusak, baterai bekas, dan perangkat elektronik kecil.',
            ],
        ];

        $wasteTypes = collect($wasteTypesData)->map(
            fn (array $data): WasteType => WasteType::query()->create($data)
        );

        $rewardsData = [
            [
                'name' => 'Minyak Goreng Kemasan 1 Liter',
                'category' => 'Sembako',
                'points_required' => 500,
                'stock' => 25,
                'description' => 'Minyak goreng sawit berkualitas kemasan pouch 1 liter.',
            ],
            [
                'name' => 'Beras Premium 2.5 kg',
                'category' => 'Sembako',
                'points_required' => 900,
                'stock' => 18,
                'description' => 'Beras pulen pilihan kemasan 2.5 kg untuk kebutuhan keluarga.',
            ],
            [
                'name' => 'Gula Pasir Kristal 1 kg',
                'category' => 'Sembako',
                'points_required' => 450,
                'stock' => 30,
                'description' => 'Gula pasir putih bersih kemasan 1 kg.',
            ],
            [
                'name' => 'Sabun Cuci Piring Ramah Lingkungan 750ml',
                'category' => 'Kebutuhan Rumah',
                'points_required' => 350,
                'stock' => 20,
                'description' => 'Cairan pencuci piring berbasis bahan nabati biodegradable.',
            ],
            [
                'name' => 'Tas Belanja Lipat Kanvas Eco',
                'category' => 'Produk Ramah Lingkungan',
                'points_required' => 300,
                'stock' => 40,
                'description' => 'Tote bag kanvas tebal yang kuat dan dapat dicuci ulang.',
            ],
            [
                'name' => 'Voucher Token Listrik Rp 20.000',
                'category' => 'Voucher',
                'points_required' => 750,
                'stock' => 15,
                'description' => 'Voucher token listrik prabayar senilai Rp 20.000.',
            ],
        ];

        $rewards = collect($rewardsData)->map(
            fn (array $data): Reward => Reward::query()->create($data)
        );

        // Sample initial transactions so dashboards, history, and analytics are populated
        $depositService = app(DepositService::class);
        $redemptionService = app(RedemptionService::class);

        $depositService->recordDeposit($nasabah1, $officer, [
            ['waste_type_id' => $wasteTypes[0]->id, 'quantity' => 3.5, 'quality_multiplier' => 1.00],
            ['waste_type_id' => $wasteTypes[2]->id, 'quantity' => 4.0, 'quality_multiplier' => 1.00],
            ['waste_type_id' => $wasteTypes[6]->id, 'quantity' => 2.5, 'quality_multiplier' => 1.00],
        ], 'Setoran rutin mingguan kondisi bersih dan terpilah.');

        $depositService->recordDeposit($nasabah2, $officer, [
            ['waste_type_id' => $wasteTypes[4]->id, 'quantity' => 1.2, 'quality_multiplier' => 1.00],
            ['waste_type_id' => $wasteTypes[8]->id, 'quantity' => 2.0, 'quality_multiplier' => 1.00],
        ], 'Setoran kaleng aluminium dan minyak jelantah.');

        $depositService->recordDeposit($nasabah3, $officer, [
            ['waste_type_id' => $wasteTypes[3]->id, 'quantity' => 3.0, 'quality_multiplier' => 0.75],
            ['waste_type_id' => $wasteTypes[1]->id, 'quantity' => 2.0, 'quality_multiplier' => 1.00],
        ], 'Buku bekas agak lembab (75%) dan gelas plastik bersih.');

        // Create one pending redemption for Siti so officer claim verification can be tested immediately
        $redemptionService->createRedemption($nasabah1->fresh(), $rewards[0], 1);

        // Create one completed redemption for Andi
        $andiRedemption = $redemptionService->createRedemption($nasabah2->fresh(), $rewards[4], 1);
        $redemptionService->completeRedemption($andiRedemption, $officer);

        // Silence unused variable warning
        unset($admin);
    }
}
