@extends('layouts.app')

@section('title', 'Ganti Kata Sandi — SortiPay')

@section('content')
<div class="max-w-xl mx-auto flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Kata Sandi</h1>
        <p class="text-sm text-slate-600">Perbarui kata sandi akun Anda secara berkala untuk menjaga keamanan saldo poin.</p>
    </div>

    {{-- Account Summary --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-extrabold text-lg shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="font-bold text-slate-900 truncate">{{ $user->name }}</p>
            <p class="text-xs text-slate-500">{{ $user->email }} &bull; <span class="font-mono font-semibold text-slate-700">{{ $user->member_code }}</span></p>
            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                {{ $user->role->label() }}
            </span>
        </div>
    </div>

    {{-- Change Password Form --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <form method="POST" action="{{ route('account.password.update') }}" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
                <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                    placeholder="Masukkan kata sandi lama"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    placeholder="Ulangi kata sandi baru"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
            </div>

            <button type="submit" class="mt-2 w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition cursor-pointer">
                Simpan Kata Sandi Baru
            </button>
        </form>
    </div>
</div>
@endsection
