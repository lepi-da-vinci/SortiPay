@extends('layouts.app')

@section('title', 'Pengguna & Petugas — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengguna &amp; Petugas</h1>
            <p class="text-sm text-slate-600">Tambahkan akun Petugas Posko, Admin, atau Nasabah baru dan pantau saldo poin.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs transition w-fit">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    {{-- Create User Form --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 mb-4">Tambah Akun Baru</h2>
        <form method="POST" action="{{ route('admin.users.store') }}" class="grid sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                <input name="name" type="text" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                <input name="email" type="email" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">No HP</label>
                <input name="phone" type="text" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Role</label>
                <select name="role" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white">
                    @foreach ($roles as $r)
                        <option value="{{ $r->value }}">{{ $r->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi</label>
                <input name="password" type="password" required placeholder="Min. 8 karakter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm cursor-pointer">
                    + Buat Akun
                </button>
            </div>
        </form>
    </div>

    {{-- Filter & Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col gap-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ ! $roleFilter ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200' }}">Semua</a>
            @foreach ($roles as $r)
                <a href="{{ route('admin.users.index', ['role' => $r->value]) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $roleFilter === $r->value ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200' }}">{{ $r->label() }}</a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 pr-4">Member Code</th>
                        <th class="py-2.5 pr-4">Nama</th>
                        <th class="py-2.5 pr-4">Email & HP</th>
                        <th class="py-2.5 pr-4">Role</th>
                        <th class="py-2.5 pr-4 text-right">Saldo Poin</th>
                        <th class="py-2.5 text-right">Reset Sandi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $u)
                        <tr>
                            <td class="py-3 pr-4 font-mono text-xs font-bold text-slate-800">{{ $u->member_code }}</td>
                            <td class="py-3 pr-4 font-semibold text-slate-900">{{ $u->name }}</td>
                            <td class="py-3 pr-4 text-xs text-slate-600">{{ $u->email }}<br>{{ $u->phone ?? '-' }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">{{ $u->role->label() }}</span>
                            </td>
                            <td class="py-3 pr-4 text-right font-extrabold text-amber-600">{{ number_format($u->points_balance) }}</td>
                            <td class="py-3 text-right">
                                <form method="POST" action="{{ route('admin.users.reset-password', $u) }}" class="inline-flex items-center gap-1.5" onsubmit="return confirm('Reset kata sandi untuk {{ $u->name }}?')">
                                    @csrf
                                    @method('PUT')
                                    <input name="password" type="password" required minlength="8" placeholder="Sandi baru (8+)" class="w-32 rounded-lg border border-slate-300 px-2 py-1 text-xs focus:border-emerald-500 outline-none">
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold cursor-pointer transition">Reset</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
