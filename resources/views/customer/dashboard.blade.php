@extends('layouts.app')

@section('title', 'Dashboard Nasabah — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Halo, {{ $user->name }}!</h1>
        <p class="text-sm text-slate-600">Pantau saldo poin sampah, QR Member Anda, dan dampak lingkungan yang sudah tercipta.</p>
    </div>

    {{-- Top Cards: Balance + QR Member --}}
    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Points Balance & Stats --}}
        <div class="lg:col-span-2 bg-gradient-to-br from-emerald-600 to-emerald-800 text-white rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col justify-between gap-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Saldo Poin Tersedia</p>
                    <p class="text-4xl sm:text-5xl font-extrabold tracking-tight mt-2">
                        {{ number_format($user->points_balance) }}
                        <span class="text-lg font-bold text-amber-300">Poin</span>
                    </p>
                </div>
                <a href="{{ route('customer.rewards') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-xs transition shrink-0">
                    Tukar Barang &rarr;
                </a>
            </div>

            <div class="grid grid-cols-3 gap-3 pt-4 border-t border-white/15">
                <div class="rounded-2xl bg-white/10 p-3.5">
                    <p class="text-[11px] text-emerald-100">Total Sampah</p>
                    <p class="text-lg font-extrabold mt-0.5">{{ number_format($totalWeightKg, 2) }} kg</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-3.5">
                    <p class="text-[11px] text-emerald-100">Setoran</p>
                    <p class="text-lg font-extrabold mt-0.5">{{ $totalDepositsCount }} kali</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-3.5">
                    <p class="text-[11px] text-emerald-100">Est. CO&#8322; Dicegah</p>
                    <p class="text-lg font-extrabold mt-0.5">{{ number_format($estimatedCo2SavedKg, 1) }} kg</p>
                </div>
            </div>
        </div>

        {{-- Digital QR Member Card --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs flex flex-col items-center text-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">QR Member ID</p>
                <p class="text-xs text-slate-500 mt-0.5">Tunjukkan ke petugas saat setor sampah</p>
            </div>
            <div data-qr-value="{{ $user->member_code }}" data-qr-size="160" class="w-40 h-40 rounded-2xl border border-slate-200 p-2 bg-white flex items-center justify-center"></div>
            <div>
                <p class="font-mono font-extrabold text-base text-slate-900 tracking-wider">{{ $user->member_code }}</p>
                <p class="text-xs text-slate-500">{{ $user->phone }}</p>
            </div>
        </div>
    </div>

    {{-- Active Pending Redemption Tickets --}}
    @if ($pendingRedemptions->isNotEmpty())
        <div class="bg-white rounded-3xl border border-amber-200 p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">Tiket Klaim Barang Aktif ({{ $pendingRedemptions->count() }})</h2>
                <a href="{{ route('customer.history', ['tab' => 'redemptions']) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($pendingRedemptions as $ticket)
                    <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 flex items-center gap-4">
                        <div data-qr-value="{{ $ticket->claim_code }}" data-qr-size="88" class="w-22 h-22 rounded-xl bg-white p-1.5 border border-amber-200 shrink-0 flex items-center justify-center"></div>
                        <div class="min-w-0 flex-1">
                            <span class="font-mono font-extrabold text-sm text-amber-800">{{ $ticket->claim_code }}</span>
                            <p class="font-bold text-slate-900 text-sm truncate mt-0.5">{{ $ticket->reward->name }} (&times;{{ $ticket->quantity }})</p>
                            <p class="text-xs text-slate-600">{{ number_format($ticket->total_points_spent) }} Poin</p>
                            <p class="text-[11px] text-slate-500 mt-1">Berlaku s/d {{ $ticket->expires_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Deposits --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900">Setoran Sampah Terakhir</h2>
            <a href="{{ route('customer.history') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Riwayat Lengkap &rarr;</a>
        </div>

        @if ($recentDeposits->isEmpty())
            <div class="py-8 text-center">
                <p class="text-sm text-slate-500">Belum ada setoran sampah. Yuk bawa sampah terpilah ke posko SortiPay!</p>
                <a href="{{ route('customer.catalog-waste') }}" class="inline-block mt-3 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                    Lihat Katalog Tarif Sampah
                </a>
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach ($recentDeposits as $dep)
                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-700">{{ $dep->receipt_number }}</span>
                                <span class="text-xs text-slate-400">&bull;</span>
                                <span class="text-xs text-slate-500">{{ $dep->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1">
                                {{ $dep->items->map(fn ($i) => $i->wasteType->name.' ('.number_format((float) $i->quantity, 2).' '.$i->wasteType->unit->value.')')->implode(', ') }}
                            </p>
                        </div>
                        <span class="text-base font-extrabold text-emerald-700 shrink-0">+{{ number_format($dep->total_points) }} Poin</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
