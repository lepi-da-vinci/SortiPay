@extends('layouts.app')

@section('title', 'Katalog Tukar Barang — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Katalog Tukar Barang</h1>
            <p class="text-sm text-slate-600">Tukarkan poin Anda dengan sembako, voucher, atau produk ramah lingkungan.</p>
        </div>
        <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-amber-50 border border-amber-200">
            <span class="text-xs font-semibold text-amber-800">Saldo Poin Anda:</span>
            <span class="text-base font-extrabold text-amber-700">{{ number_format($user->points_balance) }} Poin</span>
        </div>
    </div>

    {{-- Category Filter --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('customer.rewards') }}"
            class="px-4 py-2 rounded-full text-xs font-bold border transition {{ ! $selectedCategory ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
            Semua Barang
        </a>
        @foreach ($categories as $cat)
            <a href="{{ route('customer.rewards', ['category' => $cat]) }}"
                class="px-4 py-2 rounded-full text-xs font-bold border transition {{ $selectedCategory === $cat ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Rewards Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($rewards as $reward)
            @php
                $canAfford = $user->points_balance >= $reward->points_required;
                $inStock = $reward->stock > 0;
                $canRedeem = $canAfford && $inStock;
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between gap-4">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">{{ $reward->category }}</span>
                        <span class="text-xs font-bold {{ $inStock ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ $inStock ? "Stok: {$reward->stock}" : 'Stok Habis' }}
                        </span>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 mt-3">{{ $reward->name }}</h2>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $reward->description }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
                    <div class="flex items-baseline justify-between">
                        <span class="text-xs text-slate-500">Harga Tukar</span>
                        <span class="text-xl font-extrabold text-amber-600">{{ number_format($reward->points_required) }} Poin</span>
                    </div>

                    @if ($canRedeem)
                        <form method="POST" action="{{ route('customer.rewards.redeem', $reward) }}" onsubmit="return confirm('Tukar {{ number_format($reward->points_required) }} poin untuk {{ $reward->name }}?')">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition cursor-pointer">
                                Tukar Sekarang
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="w-full py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed">
                            @if (! $inStock)
                                Stok Habis
                            @else
                                Poin Kurang ({{ number_format($reward->points_required - $user->points_balance) }} pts lagi)
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-slate-200 p-8 text-center text-sm text-slate-500">
                Belum ada barang hadiah yang tersedia saat ini.
            </div>
        @endforelse
    </div>
</div>
@endsection
