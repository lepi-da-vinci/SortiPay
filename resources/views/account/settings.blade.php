@extends('layouts.app')

@section('title', 'Detail & Pengaturan Akun — SortiPay')

@section('content')
<div class="max-w-4xl mx-auto flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Detail &amp; Pengaturan Akun</h1>
            <p class="text-sm text-slate-600">Kelola informasi profil, nomor HP, alamat, dan keamanan kata sandi Anda.</p>
        </div>
        <a href="{{ route($user->role->dashboardRoute()) }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs transition w-fit">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    {{-- Account Overview Card --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-2xl shadow-sm shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-lg font-extrabold text-slate-900 truncate">{{ $user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                        {{ $user->role->label() }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }} &bull; {{ $user->phone ?? 'No HP belum diisi' }}</p>
                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-mono font-bold text-slate-800">{{ $user->member_code }}</span>
                    <span class="text-slate-400">Bergabung {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        @if ($user->isCustomer())
            <div class="flex items-center gap-4 pt-4 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                <div class="text-left sm:text-right">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Saldo Poin</p>
                    <p class="text-2xl font-extrabold text-amber-600">{{ number_format($user->points_balance) }} <span class="text-xs font-bold text-slate-500">Poin</span></p>
                    <p class="text-[11px] text-slate-500">{{ $totalDepositsCount }} setoran &bull; {{ number_format($totalWeightKg, 2) }} kg</p>
                </div>
                <div data-qr-value="{{ $user->member_code }}" data-qr-size="76" class="w-20 h-20 rounded-xl border border-slate-200 p-1.5 bg-white shrink-0 flex items-center justify-center"></div>
            </div>
        @endif
    </div>

    {{-- Two Column Forms: Profile Info + Password --}}
    <div class="grid md:grid-cols-2 gap-6 items-start">
        {{-- Edit Profile Form --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs">
            <div class="mb-5">
                <h3 class="text-base font-bold text-slate-900">Informasi Profil</h3>
                <p class="text-xs text-slate-500 mt-0.5">Ubah nama lengkap, email, nomor HP, atau alamat Anda.</p>
            </div>

            <form method="POST" action="{{ route('account.profile.update') }}" class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                </div>

                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat</label>
                    <textarea id="address" name="address" rows="3" placeholder="Jl. Melati No. 14, RT 02 / RW 05"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">{{ old('address', $user->address) }}</textarea>
                </div>

                <button type="submit" class="mt-1 w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition cursor-pointer">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>

        {{-- Change Password Form --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs">
            <div class="mb-5">
                <h3 class="text-base font-bold text-slate-900">Keamanan &amp; Kata Sandi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan kata sandi lama Anda untuk menetapkan kata sandi baru.</p>
            </div>

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

                <button type="submit" class="mt-1 w-full py-3 rounded-xl bg-slate-900 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition cursor-pointer">
                    Perbarui Kata Sandi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
