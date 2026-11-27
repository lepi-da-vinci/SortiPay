@extends('layouts.app')

@section('title', 'SortiPay — Bank Sampah Digital Berbasis Poin & Barang')

@section('content')
<div class="min-h-screen flex flex-col bg-eco-pattern">
    {{-- Header --}}
    <header class="bg-white/90 backdrop-blur border-b border-slate-200/80 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('landing') }}">
                <x-brand-logo subtitle="Bank Sampah Digital" />
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-emerald-700 transition">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition">Daftar Nasabah</a>
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-12 gap-8 lg:gap-10 items-center">
                <div class="md:col-span-6 flex flex-col gap-5">
                    <span class="inline-flex items-center gap-2 w-fit px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold tracking-wide uppercase shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Pilah Sampah &bull; Raih Poin &bull; Tukar Barang
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Ubah Sampah Terpilah Menjadi <span class="text-emerald-600">Poin &amp; Kebutuhan Harian</span>.
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                        <strong>SortiPay</strong> memudahkan warga menyetor sampah organik, plastik PET, kardus, logam, hingga minyak jelantah ke posko terdekat. Poin langsung tercatat transparan di buku besar digital dan dapat ditukar sembako maupun voucher.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition">
                            Mulai Jadi Nasabah
                        </a>
                        <a href="{{ route('login') }}" class="px-6 py-3.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-800 font-bold text-sm border border-slate-200 shadow-xs transition">
                            Masuk Portal / Posko
                        </a>
                    </div>

                    {{-- Quick Category Pills --}}
                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                        <div class="flex items-center gap-2 p-2.5 rounded-2xl bg-white/90 border border-slate-200/80 shadow-2xs">
                            <x-waste-icon category="ORGANIK" />
                            <span class="text-xs font-bold text-slate-800">Organik</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-2xl bg-white/90 border border-slate-200/80 shadow-2xs">
                            <x-waste-icon category="ANORGANIK" />
                            <span class="text-xs font-bold text-slate-800">Anorganik</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-2xl bg-white/90 border border-slate-200/80 shadow-2xs">
                            <x-waste-icon category="DAUR_ULANG" />
                            <span class="text-xs font-bold text-slate-800">Daur Ulang</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-2xl bg-white/90 border border-slate-200/80 shadow-2xs">
                            <x-waste-icon category="KHUSUS_B3" />
                            <span class="text-xs font-bold text-slate-800">Jelantah / B3</span>
                        </div>
                    </div>
                </div>

                {{-- Hero Visual Card --}}
                <div class="md:col-span-6">
                    <div class="relative rounded-3xl overflow-hidden border border-slate-200/90 bg-white shadow-xl shadow-emerald-900/5 p-3">
                        <img src="{{ asset('images/hero-eco-waste.jpg') }}" alt="Ilustrasi Bank Sampah Digital SortiPay" class="w-full h-auto rounded-2xl object-cover">
                        <div class="mt-3 grid grid-cols-3 gap-2.5 text-center">
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-3">
                                <p class="text-xs font-bold text-emerald-700">1. Pilah &amp; Setor</p>
                                <p class="text-[11px] text-slate-600 mt-0.5">Scan QR Member</p>
                            </div>
                            <div class="rounded-2xl bg-amber-50 border border-amber-100 p-3">
                                <p class="text-xs font-bold text-amber-700">2. Poin Instan</p>
                                <p class="text-[11px] text-slate-600 mt-0.5">Ledger Anti-Fraud</p>
                            </div>
                            <div class="rounded-2xl bg-slate-100 border border-slate-200 p-3">
                                <p class="text-xs font-bold text-slate-800">3. Tukar Hadiah</p>
                                <p class="text-[11px] text-slate-600 mt-0.5">Sembako &amp; Voucher</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Eco Banner CTA --}}
    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl overflow-hidden shadow-lg border border-emerald-700/20">
                <img src="{{ asset('images/eco-bg-banner.jpg') }}" alt="Background Bank Sampah SortiPay" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/90 via-emerald-900/80 to-emerald-800/70"></div>
                <div class="relative p-8 sm:p-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 text-white">
                    <div class="max-w-xl">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-300">Gerakan Lingkungan Berkelanjutan</p>
                        <h2 class="text-2xl sm:text-3xl font-extrabold mt-1">Setiap Kilogram Sampah Terpilah Bernilai untuk Bumi &amp; Keluarga.</h2>
                        <p class="text-sm text-emerald-100 mt-2">Pantau riwayat setoran, cetak struk digital, dan tukarkan poin menjadi minyak goreng, beras, hingga token listrik.</p>
                    </div>
                    <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-sm shadow-md transition shrink-0">
                        Daftar Gratis Sekarang &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-auto border-t border-slate-200 bg-white/90 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} SortiPay &mdash; Platform Bank Sampah Digital.
    </footer>
</div>
@endsection
