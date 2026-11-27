@extends('layouts.app')

@section('title', 'Manajemen Katalog Barang — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Katalog Barang Hadiah</h1>
        <p class="text-sm text-slate-600">Tambah barang baru, atur nilai poin penukaran, dan perbarui stok gudang posko.</p>
    </div>

    {{-- Create Form --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 mb-4">Tambah Barang Hadiah</h2>
        <form method="POST" action="{{ route('admin.rewards.store') }}" class="grid sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            @csrf
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Barang</label>
                <input name="name" type="text" required placeholder="Misal: Kecap Manis 500ml"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                <input name="category" type="text" required placeholder="Sembako / Voucher"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Harga (Poin)</label>
                <input name="points_required" type="number" min="1" required placeholder="500"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Stok Awal</label>
                <input name="stock" type="number" min="0" required placeholder="20"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 outline-none">
            </div>
            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm cursor-pointer">
                    + Tambah
                </button>
            </div>
            <div class="sm:col-span-2 lg:col-span-6">
                <input name="description" type="text" placeholder="Deskripsi barang (opsional)"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-emerald-500 outline-none">
            </div>
        </form>
    </div>

    {{-- Rewards List --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col gap-3">
        <h2 class="text-base font-bold text-slate-900">Daftar Barang Hadiah ({{ $rewards->count() }})</h2>
        @foreach ($rewards as $reward)
            <form method="POST" action="{{ route('admin.rewards.update', $reward) }}" class="p-4 rounded-2xl border border-slate-200 grid sm:grid-cols-12 gap-3 items-center {{ ! $reward->is_active ? 'opacity-60 bg-slate-50' : '' }}">
                @csrf
                @method('PUT')
                <div class="sm:col-span-4">
                    <input name="name" type="text" value="{{ $reward->name }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-sm font-semibold">
                </div>
                <div class="sm:col-span-2">
                    <input name="category" type="text" value="{{ $reward->category }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[10px] text-slate-400">Poin</label>
                    <input name="points_required" type="number" min="1" value="{{ $reward->points_required }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1 text-sm font-bold text-amber-700">
                </div>
                <div class="sm:col-span-1">
                    <label class="block text-[10px] text-slate-400">Stok</label>
                    <input name="stock" type="number" min="0" value="{{ $reward->stock }}" required class="w-full rounded-lg border border-slate-300 px-2 py-1 text-sm font-bold">
                </div>
                <div class="sm:col-span-1 flex items-center text-xs">
                    <label class="flex items-center gap-1"><input type="checkbox" name="is_active" value="1" @checked($reward->is_active)> Aktif</label>
                </div>
                <div class="sm:col-span-2 text-right">
                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold cursor-pointer transition">Simpan</button>
                </div>
                <input type="hidden" name="description" value="{{ $reward->description }}">
            </form>
        @endforeach
    </div>
</div>
@endsection
