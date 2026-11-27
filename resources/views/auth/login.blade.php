@extends('layouts.app')

@section('title', 'Masuk — SortiPay')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-slate-50">
    <div class="w-full max-w-md flex flex-col gap-6">
        <div class="text-center">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2.5 justify-center">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-xl shadow-sm">S</div>
                <span class="font-extrabold text-2xl tracking-tight text-slate-900">Sorti<span class="text-emerald-600">Pay</span></span>
            </a>
            <p class="text-sm text-slate-600 mt-2">Masuk ke akun Nasabah, Petugas Posko, atau Admin</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-xs text-rose-700 font-medium">
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

                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-xs transition cursor-pointer">
                    Masuk ke SortiPay
                </button>
            </form>

            <p class="mt-5 text-center text-xs text-slate-600">
                Belum punya akun nasabah?
                <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-700">Daftar sekarang</a>
            </p>
        </div>

        {{-- Demo Accounts Reference --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 text-xs text-slate-600">
            <p class="font-bold text-slate-800 mb-2">Akun Demo (Kata sandi semua akun: <code class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-900">password</code>)</p>
            <div class="grid grid-cols-1 gap-1.5 font-mono text-[11px]">
                <div class="flex justify-between"><span>Admin:</span><span class="text-slate-900">admin@sortipay.id</span></div>
                <div class="flex justify-between"><span>Petugas:</span><span class="text-slate-900">petugas@sortipay.id</span></div>
                <div class="flex justify-between"><span>Nasabah 1:</span><span class="text-slate-900">siti@sortipay.id</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
