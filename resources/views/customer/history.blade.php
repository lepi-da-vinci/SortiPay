@extends('layouts.app')

@section('title', 'Riwayat Transaksi — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Transaksi</h1>
            <p class="text-sm text-slate-600">Pantau rincian setoran sampah dan status tiket klaim penukaran barang Anda.</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs transition w-fit">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('customer.history', ['tab' => 'deposits']) }}"
            class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $tab === 'deposits' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Riwayat Setor Sampah
        </a>
        <a href="{{ route('customer.history', ['tab' => 'redemptions']) }}"
            class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $tab === 'redemptions' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Riwayat Tukar Barang
        </a>
    </div>

    @if ($tab === 'deposits')
        <div class="flex flex-col gap-4">
            @forelse ($deposits as $dep)
                <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <div>
                            <span class="font-mono text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">{{ $dep->receipt_number }}</span>
                            <p class="text-xs text-slate-500 mt-1.5">{{ $dep->created_at->format('d M Y, H:i') }} &bull; Petugas: {{ $dep->officer->name }}</p>
                        </div>
                        <div class="sm:text-right">
                            <p class="text-lg font-extrabold text-emerald-700">+{{ number_format($dep->total_points) }} Poin</p>
                            <p class="text-xs text-slate-500">Total Berat: {{ number_format((float) $dep->total_weight_kg, 2) }} kg</p>
                        </div>
                    </div>

                    <div class="pt-3 flex flex-col gap-2">
                        @foreach ($dep->items as $item)
                            <div class="flex items-center justify-between text-xs text-slate-700">
                                <span>
                                    <strong>{{ $item->wasteType->name }}</strong>
                                    &mdash; {{ number_format((float) $item->quantity, 2) }} {{ $item->wasteType->unit->value }}
                                    &times; {{ number_format($item->points_per_unit_snapshot) }} pts
                                    ({{ (int) ($item->quality_multiplier * 100) }}%)
                                </span>
                                <span class="font-bold text-slate-900">+{{ number_format($item->subtotal_points) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200 p-8 text-center text-sm text-slate-500">
                    Belum ada riwayat setoran sampah.
                </div>
            @endforelse

            {{ $deposits->withQueryString()->links() }}
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-4">
            @forelse ($redemptions as $redemption)
                <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between gap-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-extrabold text-sm text-slate-900">{{ $redemption->claim_code }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $redemption->status->badgeClasses() }}">
                                    {{ $redemption->status->label() }}
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-2">{{ $redemption->reward->name }} &times; {{ $redemption->quantity }}</h3>
                            <p class="text-xs text-amber-700 font-bold mt-0.5">{{ number_format($redemption->total_points_spent) }} Poin</p>
                            <p class="text-xs text-slate-500 mt-1">Diajukan: {{ $redemption->created_at->format('d M Y H:i') }}</p>
                            @if ($redemption->status === \App\Enums\RedemptionStatus::Pending)
                                <p class="text-xs text-slate-500">Berlaku hingga: {{ $redemption->expires_at->format('d M Y H:i') }}</p>
                            @elseif ($redemption->verified_at)
                                <p class="text-xs text-slate-500">Diproses: {{ $redemption->verified_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>

                        @if ($redemption->status === \App\Enums\RedemptionStatus::Pending)
                            <div data-qr-value="{{ $redemption->claim_code }}" data-qr-size="96" class="w-24 h-24 rounded-xl border border-slate-200 p-1.5 bg-white shrink-0 flex items-center justify-center"></div>
                        @endif
                    </div>

                    @if ($redemption->status === \App\Enums\RedemptionStatus::Pending)
                        <form method="POST" action="{{ route('customer.redemptions.cancel', $redemption) }}" onsubmit="return confirm('Batalkan penukaran ini? Poin akan dikembalikan ke saldo Anda.')">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs cursor-pointer transition">
                                Batalkan Penukaran & Kembalikan Poin
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl border border-slate-200 p-8 text-center text-sm text-slate-500">
                    Belum ada riwayat penukaran barang.
                </div>
            @endforelse
        </div>

        {{ $redemptions->withQueryString()->links() }}
    @endif
</div>
@endsection
