<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SortiPay — Bank Sampah Digital')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-eco-pattern text-slate-900 antialiased min-h-screen flex flex-col">
    @auth
        @php
            $user = auth()->user();
            $role = $user->role;
        @endphp

        <div class="min-h-screen flex flex-col lg:flex-row">
            {{-- Desktop Sidebar --}}
            <aside class="no-print hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-white border-r border-slate-200 z-30">
                <div class="h-16 flex items-center px-5 border-b border-slate-100">
                    <x-brand-logo :subtitle="$role->label()" />
                </div>

                <nav class="flex-1 px-3 py-5 flex flex-col gap-1 overflow-y-auto">
                    @if ($user->isCustomer())
                        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('customer.dashboard') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('customer.catalog-waste') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('customer.catalog-waste') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Tarif Sampah
                        </a>
                        <a href="{{ route('customer.rewards') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('customer.rewards') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                            Tukar Barang
                        </a>
                        <a href="{{ route('customer.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('customer.history') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Riwayat Transaksi
                        </a>
                    @elseif ($user->isOfficer())
                        <a href="{{ route('officer.deposit.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('officer.deposit.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                            POS Setor Sampah
                        </a>
                        <a href="{{ route('officer.redemptions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('officer.redemptions.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Verifikasi Klaim
                        </a>
                    @elseif ($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Dashboard Analitik
                        </a>
                        <a href="{{ route('admin.waste-types.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.waste-types.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                            Master Jenis Sampah
                        </a>
                        <a href="{{ route('admin.rewards.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.rewards.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Katalog Barang
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Pengguna & Petugas
                        </a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Audit Log Poin
                        </a>
                        <div class="pt-4 mt-4 border-t border-slate-100">
                            <p class="px-3.5 text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Operasional Posko</p>
                            <a href="{{ route('officer.deposit.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                POS Setor Sampah
                            </a>
                            <a href="{{ route('officer.redemptions.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                Verifikasi Klaim
                            </a>
                        </div>
                    @endif
                </nav>

                <div class="p-4 border-t border-slate-100">
                    <a href="{{ route('account.edit') }}" class="flex items-center gap-3 mb-3 p-1.5 rounded-xl hover:bg-slate-50 transition group">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-emerald-700">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500 font-mono">{{ $user->member_code }}</p>
                        </div>
                    </a>
                    <div class="flex flex-col gap-1.5">
                        <a href="{{ route('account.edit') }}" class="w-full py-2 px-3 rounded-xl text-xs font-semibold text-center transition {{ request()->routeIs('account.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700' }}">
                            Detail &amp; Pengaturan Akun
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-2 px-3 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-rose-50 hover:text-rose-700 transition cursor-pointer">
                                Keluar Akun
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <div class="flex-1 lg:pl-64 flex flex-col min-h-screen pb-20 lg:pb-0">
                {{-- Mobile Top Bar --}}
                <header class="no-print lg:hidden sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-slate-200 px-4 h-14 flex items-center justify-between">
                    <x-brand-logo size="sm" :subtitle="$role->label()" />
                    <div class="flex items-center gap-2">
                        @if ($user->isCustomer())
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold">
                                {{ number_format($user->points_balance) }} Poin
                            </span>
                        @endif
                        <a href="{{ route('account.edit') }}" class="text-xs font-semibold text-slate-600 hover:text-emerald-700 px-2 py-1">Akun</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-rose-600 px-2 py-1 cursor-pointer">Keluar</button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div data-auto-dismiss class="no-print mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800 flex items-center justify-between gap-3 shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="no-print mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm text-rose-800 flex items-center gap-2.5 shadow-xs">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="no-print mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm text-rose-800">
                            <p class="font-semibold mb-1">Periksa kembali input Anda:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>

                {{-- Mobile Bottom Navigation --}}
                <nav class="no-print lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 z-30">
                    <div class="flex justify-around items-center h-16 px-2">
                        @if ($user->isCustomer())
                            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('customer.dashboard') ? 'text-emerald-600' : 'text-slate-500' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Beranda
                            </a>
                            <a href="{{ route('customer.catalog-waste') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('customer.catalog-waste') ? 'text-emerald-600' : 'text-slate-500' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2"/></svg>
                                Tarif
                            </a>
                            <a href="{{ route('customer.rewards') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('customer.rewards') ? 'text-emerald-600' : 'text-slate-500' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                Tukar
                            </a>
                            <a href="{{ route('customer.history') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold {{ request()->routeIs('customer.history') ? 'text-emerald-600' : 'text-slate-500' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Riwayat
                            </a>
                        @elseif ($user->isOfficer())
                            <a href="{{ route('officer.deposit.index') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ request()->routeIs('officer.deposit.*') ? 'text-emerald-600' : 'text-slate-500' }}">
                                POS Setor Sampah
                            </a>
                            <a href="{{ route('officer.redemptions.index') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ request()->routeIs('officer.redemptions.*') ? 'text-emerald-600' : 'text-slate-500' }}">
                                Verifikasi Klaim
                            </a>
                        @elseif ($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-[11px] font-semibold {{ request()->routeIs('admin.dashboard') ? 'text-emerald-600' : 'text-slate-500' }}">Analitik</a>
                            <a href="{{ route('admin.waste-types.index') }}" class="text-[11px] font-semibold {{ request()->routeIs('admin.waste-types.*') ? 'text-emerald-600' : 'text-slate-500' }}">Sampah</a>
                            <a href="{{ route('admin.rewards.index') }}" class="text-[11px] font-semibold {{ request()->routeIs('admin.rewards.*') ? 'text-emerald-600' : 'text-slate-500' }}">Hadiah</a>
                            <a href="{{ route('admin.users.index') }}" class="text-[11px] font-semibold {{ request()->routeIs('admin.users.*') ? 'text-emerald-600' : 'text-slate-500' }}">Akun</a>
                            <a href="{{ route('admin.audit-logs.index') }}" class="text-[11px] font-semibold {{ request()->routeIs('admin.audit-logs.*') ? 'text-emerald-600' : 'text-slate-500' }}">Audit</a>
                        @endif
                    </div>
                </nav>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    @stack('scripts')
</body>
</html>
