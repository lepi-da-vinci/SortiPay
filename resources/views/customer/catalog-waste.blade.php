@extends('layouts.app')

@section('title', 'Katalog Tarif Sampah — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Katalog Tarif Poin Sampah</h1>
            <p class="text-sm text-slate-600">Daftar nilai poin untuk setiap jenis sampah terpilah. Kondisi bersih mendapat 100% tarif dasar.</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs transition w-fit">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    {{-- Category Filter Pills --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('customer.catalog-waste') }}"
            class="px-4 py-2 rounded-full text-xs font-bold border transition {{ ! $selectedCategory ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
            Semua Kategori
        </a>
        @foreach ($categories as $cat)
            <a href="{{ route('customer.catalog-waste', ['category' => $cat->value]) }}"
                class="px-4 py-2 rounded-full text-xs font-bold border transition {{ $selectedCategory === $cat->value ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                {{ $cat->label() }}
            </a>
        @endforeach
    </div>

    {{-- Waste Cards Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($wasteTypes as $waste)
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs hover:shadow-md hover:border-emerald-200 transition flex flex-col justify-between gap-4">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <x-waste-icon :category="$waste->category" />
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $waste->category->badgeClasses() }}">
                                {{ $waste->category->label() }}
                            </span>
                            @if ($waste->is_recyclable)
                                <span class="text-[11px] font-semibold text-emerald-700">&#9851; Daur Ulang</span>
                            @endif
                        </div>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 mt-3">{{ $waste->name }}</h2>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $waste->description ?? 'Pastikan sampah dalam kondisi terpilah saat disetor.' }}</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-baseline justify-between">
                    <span class="text-xs text-slate-500">Tarif Dasar</span>
                    <span class="text-lg font-extrabold text-amber-600">
                        {{ number_format($waste->points_per_unit) }} <span class="text-xs font-semibold text-slate-600">Poin / {{ $waste->unit->label() }}</span>
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-slate-200 p-8 text-center text-sm text-slate-500">
                Belum ada jenis sampah pada kategori ini.
            </div>
        @endforelse
    </div>
</div>
@endsection
