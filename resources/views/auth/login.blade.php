@extends('layouts.app')

@section('title', 'Masuk — SortiPay')

@section('content')
<div class="min-h-screen relative flex items-center justify-center px-4 py-12 overflow-hidden">
    {{-- Eco Waste Atmospheric Background --}}
    <img src="{{ asset('images/eco-bg-banner.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-emerald-950/75 backdrop-blur-[2px]"></div>

    <div class="relative w-full max-w-md md:max-w-4xl flex flex-col gap-4">
        <div>
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold backdrop-blur-xs border border-white/20 transition">
                &larr; Kembali ke Beranda
            </a>
        </div>

        <div class="w-full grid md:grid-cols-12 rounded-3xl overflow-hidden shadow-2xl border border-white/15 bg-white">
        {{-- Left Visual Panel --}}
        <div class="hidden md:flex md:col-span-5 relative flex-col justify-between p-7 text-white overflow-hidden">
            <img src="{{ asset('images/eco-bg-banner.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-900/80 via-emerald-900/75 to-emerald-950/95"></div>

            <div class="relative">
                <a href="{{ route('landing') }}">
                    <x-brand-logo :dark="true" subtitle="Bank Sampah Digital" />
                </a>
            </div>

            <div class="relative flex flex-col gap-4">
                <img src="{{ asset('images/hero-eco-waste.jpg') }}" alt="SortiPay Recycling" class="w-full rounded-2xl border border-white/20 shadow-lg">
                <div>
                    <h2 class="text-lg font-extrabold leading-snug">Pilah Sampah, Kumpulkan Poin, Tukar Sembako.</h2>
                    <p class="text-xs text-emerald-100 mt-1">Portal terpadu untuk Nasabah, Petugas Posko, dan Administrator.</p>
                </div>
            </div>
        </div>

        {{-- Right Login Form --}}
        <div class="md:col-span-7 p-6 sm:p-9 flex flex-col justify-center gap-5">
            <div class="md:hidden text-center">
                <a href="{{ route('landing') }}" class="inline-block">
                    <x-brand-logo subtitle="Bank Sampah Digital" />
                </a>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
                <p class="text-xs text-slate-500 mt-1">Masuk menggunakan akun Nasabah, Petugas Posko, atau Admin.</p>
            </div>

            @if ($errors->any())
                <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-xs text-rose-700 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        placeholder="contoh@sortipay.id"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                    <input id="password" name="password" type="password" required
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                </div>

                <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-sm transition cursor-pointer">
                    Masuk ke SortiPay
                </button>
            </form>

            <p class="text-center text-xs text-slate-600">
                Belum punya akun nasabah?
                <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700">Daftar sekarang</a>
            </p>

            {{-- Demo Accounts Reference --}}
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-3.5 text-xs text-slate-600">
                <p class="font-bold text-slate-800 mb-1.5">Akun Demo (Kata sandi: <code class="px-1.5 py-0.5 rounded bg-white border text-slate-900">password</code>)</p>
                <div class="grid grid-cols-1 gap-1 font-mono text-[11px]">
                    <div class="flex justify-between"><span>Admin:</span><span class="text-slate-900">admin@sortipay.id</span></div>
                    <div class="flex justify-between"><span>Petugas:</span><span class="text-slate-900">petugas@sortipay.id</span></div>
                    <div class="flex justify-between"><span>Nasabah:</span><span class="text-slate-900">siti@sortipay.id</span></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
