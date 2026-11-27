@extends('layouts.app')

@section('title', 'Struk Digital '.$deposit->receipt_number.' — SortiPay')

@section('content')
<div class="max-w-xl mx-auto flex flex-col gap-5">
    <div class="no-print flex items-center justify-between">
        <a href="{{ route('officer.deposit.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">&larr; Kembali ke POS</a>
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-xs cursor-pointer">
            Cetak Struk
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <div class="text-center pb-5 border-b border-dashed border-slate-200">
            <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto mb-2">S</div>
            <h1 class="text-xl font-extrabold text-slate-900">Struk Setoran Sampah</h1>
            <p class="text-xs text-slate-500 mt-0.5">Bank Sampah Digital SortiPay</p>
            <p class="mt-2 inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-mono text-xs font-bold border border-emerald-200">
                {{ $deposit->receipt_number }}
            </p>
        </div>

        <div class="py-4 border-b border-dashed border-slate-200 grid grid-cols-2 gap-y-2 text-xs">
            <span class="text-slate-500">Tanggal & Waktu</span>
            <span class="text-right font-semibold text-slate-900">{{ $deposit->created_at->format('d M Y, H:i') }}</span>

            <span class="text-slate-500">Nasabah</span>
            <span class="text-right font-semibold text-slate-900">{{ $deposit->user->name }}</span>

            <span class="text-slate-500">Member Code</span>
            <span class="text-right font-mono font-bold text-slate-800">{{ $deposit->user->member_code }}</span>

            <span class="text-slate-500">Petugas Posko</span>
            <span class="text-right font-semibold text-slate-900">{{ $deposit->officer->name }}</span>
        </div>

        <div class="py-4 border-b border-dashed border-slate-200">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Rincian Item Sampah</p>
            <div class="flex flex-col gap-3">
                @foreach ($deposit->items as $item)
                    <div class="flex items-start justify-between gap-3 text-sm">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $item->wasteType->name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ number_format((float) $item->quantity, 2) }} {{ $item->wasteType->unit->value }}
                                &times; {{ number_format($item->points_per_unit_snapshot) }} pts
                                &times; {{ (int) ($item->quality_multiplier * 100) }}%
                            </p>
                        </div>
                        <span class="font-bold text-emerald-700 shrink-0">+{{ number_format($item->subtotal_points) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 flex flex-col gap-2">
            <div class="flex justify-between text-xs text-slate-600">
                <span>Total Berat (kg)</span>
                <span class="font-bold text-slate-900">{{ number_format((float) $deposit->total_weight_kg, 2) }} kg</span>
            </div>
            <div class="flex justify-between items-baseline">
                <span class="text-sm font-bold text-slate-900">Total Poin Diperoleh</span>
                <span class="text-2xl font-extrabold text-amber-600">+{{ number_format($deposit->total_points) }} Poin</span>
            </div>
            <div class="flex justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                <span>Saldo Poin Nasabah Saat Ini</span>
                <span class="font-bold text-slate-900">{{ number_format($deposit->user->points_balance) }} Poin</span>
            </div>
        </div>

        @if ($deposit->notes)
            <div class="mt-4 p-3 rounded-xl bg-slate-50 text-xs text-slate-600">
                <span class="font-semibold text-slate-800">Catatan:</span> {{ $deposit->notes }}
            </div>
        @endif
    </div>
</div>
@endsection
