@extends('layouts.app')

@section('title', 'Dashboard Analitik Admin — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Analitik SortiPay</h1>
        <p class="text-sm text-slate-600">Ringkasan operasional bank sampah, sirkulasi poin, dan komposisi sampah terpilah.</p>
    </div>

    {{-- KPI Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Total Sampah Masuk</p>
            <p class="text-3xl font-extrabold text-emerald-700 mt-1">{{ number_format($totalWeightKg, 2) }} <span class="text-sm font-bold text-slate-500">kg</span></p>
        </div>
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Poin Beredar (Saldo Nasabah)</p>
            <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ number_format($totalPointsCirculating) }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Total diterbitkan: {{ number_format($totalIssuedPoints) }} pts</p>
        </div>
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Total Poin Ditukar</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalPointsRedeemed) }}</p>
            <p class="text-[11px] text-amber-700 mt-1">{{ $pendingRedemptionsCount }} tiket menunggu pengambilan</p>
        </div>
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Nasabah & Petugas</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $activeCustomersCount }} <span class="text-sm font-semibold text-slate-500">Nasabah</span></p>
            <p class="text-[11px] text-slate-400 mt-1">{{ $officersCount }} petugas posko aktif</p>
        </div>
    </div>

    {{-- Waste Composition by Category --}}
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-4">Komposisi Sampah per Kategori</h2>
            <div class="flex flex-col gap-4">
                @foreach ($categoryStats as $stat)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-bold text-slate-800">{{ $stat['category']->label() }}</span>
                            <span class="text-slate-600">
                                {{ number_format($stat['total_qty'], 2) }} unit &bull; <strong>{{ number_format($stat['total_points']) }} Poin</strong>
                            </span>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-600" style="width: {{ max(4, $stat['percent']) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Point Ledger Entries --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">Aktivitas Point Ledger Terbaru</h2>
                <a href="{{ route('admin.audit-logs.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua &rarr;</a>
            </div>
            <div class="flex flex-col gap-2.5">
                @forelse ($recentLedgers as $log)
                    <div class="flex items-center justify-between gap-3 py-2 border-b border-slate-100 last:border-0 text-xs">
                        <div class="min-w-0">
                            <p class="font-bold text-slate-900 truncate">{{ $log->user->name }} <span class="font-mono font-normal text-slate-500">({{ $log->user->member_code }})</span></p>
                            <p class="text-slate-500 truncate">{{ $log->notes ?? $log->type->label() }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-extrabold {{ $log->amount >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $log->amount >= 0 ? '+' : '' }}{{ number_format($log->amount) }}
                            </p>
                            <p class="text-[11px] text-slate-400">Saldo: {{ number_format($log->balance_after) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada catatan buku besar poin.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
