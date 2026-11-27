@extends('layouts.app')

@section('title', 'Master Jenis Sampah — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Master Jenis Sampah & Tarif Poin</h1>
        <p class="text-sm text-slate-600">Kelola kategori, satuan, dan tarif poin. Perubahan tarif tidak mengubah histori poin lama (snapshot).</p>
    </div>

    {{-- Create Form --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 mb-4">Tambah Jenis Sampah Baru</h2>
        <form method="POST" action="{{ route('admin.waste-types.store') }}" class="grid sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            @csrf
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Jenis Sampah</label>
                <input name="name" type="text" required placeholder="Misal: Besi Tua / Seng"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                <select name="category" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Satuan</label>
                <select name="unit" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white">
                    @foreach ($units as $unit)
                        <option value="{{ $unit->value }}">{{ $unit->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Poin / Satuan</label>
                <input name="points_per_unit" type="number" min="1" required placeholder="250"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 outline-none">
            </div>
            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm cursor-pointer">
                    + Tambah
                </button>
            </div>
            <div class="sm:col-span-2 lg:col-span-5">
                <input name="description" type="text" placeholder="Deskripsi singkat / panduan pemilahan (opsional)"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-emerald-500 outline-none">
            </div>
            <label class="flex items-center gap-2 text-xs text-slate-700">
                <input type="checkbox" name="is_recyclable" value="1" checked class="rounded text-emerald-600">
                Daur Ulang
            </label>
        </form>
    </div>

    {{-- Table / Inline Edit List --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col gap-3">
        <h2 class="text-base font-bold text-slate-900">Daftar Master Sampah ({{ $wasteTypes->count() }})</h2>
        @foreach ($wasteTypes as $waste)
            <form method="POST" action="{{ route('admin.waste-types.update', $waste) }}" class="p-4 rounded-2xl border border-slate-200 grid sm:grid-cols-12 gap-3 items-center {{ ! $waste->is_active ? 'opacity-60 bg-slate-50' : '' }}">
                @csrf
                @method('PUT')
                <div class="sm:col-span-3">
                    <input name="name" type="text" value="{{ $waste->name }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-sm font-semibold">
                </div>
                <div class="sm:col-span-2">
                    <select name="category" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs bg-white">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->value }}" @selected($waste->category === $cat)>{{ $cat->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-1">
                    <select name="unit" class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs bg-white">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->value }}" @selected($waste->unit === $unit)>{{ $unit->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <input name="points_per_unit" type="number" min="1" value="{{ $waste->points_per_unit }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-sm font-bold text-amber-700">
                </div>
                <div class="sm:col-span-2 flex items-center gap-3 text-xs">
                    <label class="flex items-center gap-1"><input type="checkbox" name="is_recyclable" value="1" @checked($waste->is_recyclable)> Daur</label>
                    <label class="flex items-center gap-1"><input type="checkbox" name="is_active" value="1" @checked($waste->is_active)> Aktif</label>
                </div>
                <div class="sm:col-span-2 text-right">
                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold cursor-pointer transition">Simpan</button>
                </div>
                <input type="hidden" name="description" value="{{ $waste->description }}">
            </form>
        @endforeach
    </div>
</div>
@endsection
