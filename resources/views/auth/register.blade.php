@extends('layouts.app')

@section('title', 'Daftar Nasabah — SortiPay')

@section('content')
<div class="min-h-screen relative flex items-center justify-center px-4 py-12 overflow-hidden">
    {{-- Eco Waste Atmospheric Background --}}
    <img src="{{ asset('images/eco-bg-banner.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-emerald-950/75 backdrop-blur-[2px]"></div>

    <div class="relative w-full max-w-md flex flex-col gap-4">
        <div>
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold backdrop-blur-xs border border-white/20 transition">
                &larr; Kembali ke Beranda
            </a>
        </div>

        <div class="text-center">
            <a href="{{ route('landing') }}" class="inline-block">
                <x-brand-logo :dark="true" size="lg" subtitle="Bank Sampah Digital" />
            </a>
            <p class="text-xs text-emerald-100 mt-2">Buat akun Nasabah &amp; dapatkan QR Member Code otomatis</p>
        </div>

        <div class="bg-white rounded-3xl border border-white/20 shadow-2xl p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-xs text-rose-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                        placeholder="Siti Aminah"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        placeholder="nama@email.com"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                        placeholder="081234567890"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>

                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat (Opsional)</label>
                    <textarea id="address" name="address" rows="2" placeholder="Jl. Melati No. 14, RT 02 / RW 05"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                        <input id="password" name="password" type="password" required placeholder="Min. 8 karakter"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Ulangi Sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Ulangi sandi"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-sm transition cursor-pointer">
                    Daftar &amp; Buat QR Member
                </button>
            </form>

            <p class="mt-5 text-center text-xs text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
