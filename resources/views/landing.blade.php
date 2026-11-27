@extends('layouts.app')

@section('title', 'SortiPay — Bank Sampah Digital Berbasis Poin & Barang')

@section('content')
<div class="min-h-screen flex flex-col bg-slate-50">
    {{-- Header --}}
    <header class="bg-white/90 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-lg shadow-sm">S</div>
                <span class="font-extrabold text-xl tracking-tight text-slate-900">Sorti<span class="text-emerald-600">Pay</span></span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-emerald-700 transition">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition">Daftar Nasabah</a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="flex flex-col gap-6">
                    <span class="inline-flex items-center gap-2 w-fit px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold tracking-wide uppercase">
                        Bank Sampah Digital Modern
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Pilah Sampahmu, Kumpulkan Poin, Tukar Jadi <span class="text-emerald-600">Kebutuhan Harian</span>.
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                        <strong>SortiPay</strong> menghubungkan nasabah dengan posko bank sampah secara transparan. Setor sampah organik, anorganik, daur ulang, hingga minyak jelantah — poin masuk instan ke buku besar digital dan dapat ditukar sembako maupun voucher.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
                            Mulai Jadi Nasabah
                        </a>
                        <a href="{{ route('login') }}" class="px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 text-slate-800 font-semibold text-sm border border-slate-200 transition">
                            Masuk Portal / Posko
                        </a>
                    </div>
                </div>

                {{-- Feature Preview Card --}}
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col gap-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alur SortiPay</p>
                            <h2 class="text-lg font-bold text-slate-900">3 Langkah Mudah</h2>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                            100% Transparan
                        </span>
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0">1</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Pilah & Setor ke Posko</h3>
                                <p class="text-xs text-slate-600 mt-1">Tunjukkan QR Member Code Anda. Petugas menimbang multi-jenis sampah & memilih kondisi kebersihan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shrink-0">2</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Poin Otomatis Masuk Ledger</h3>
                                <p class="text-xs text-slate-600 mt-1">Rumus transparan <code class="px-1 py-0.5 rounded bg-white border text-[11px]">floor(qty × tarif × kondisi)</code> langsung tercatat permanen.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold shrink-0">3</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Tukar Sembako & Voucher</h3>
                                <p class="text-xs text-slate-600 mt-1">Ajukan penukaran dari HP, dapatkan Tiket QR Klaim, dan ambil barang di posko terdekat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-auto border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} SortiPay — Platform Bank Sampah Digital.
    </footer>
</div>
@endsection
