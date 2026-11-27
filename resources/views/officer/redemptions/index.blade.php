@extends('layouts.app')

@section('title', 'Verifikasi Klaim Barang — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Verifikasi Penukaran Barang</h1>
            <p class="text-sm text-slate-600">Scan atau ketik Kode Klaim nasabah untuk menyerahkan barang hadiah.</p>
        </div>
        <a href="{{ route('officer.deposit.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
            &larr; Kembali ke POS Setoran
        </a>
    </div>

    {{-- Claim Code Lookup --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <form method="GET" action="{{ route('officer.redemptions.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input name="code" type="text" value="{{ $code }}" placeholder="Masukkan atau scan Kode Klaim (contoh: CLM-8X92A)"
                class="flex-1 rounded-xl border border-slate-300 px-4 py-3 text-sm font-mono uppercase focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
            <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition cursor-pointer">
                Cari Kode Klaim
            </button>
        </form>

        @if ($code !== '' && ! $searchedRedemption)
            <div class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-sm text-rose-700 font-medium">
                Kode klaim <span class="font-mono font-bold">{{ $code }}</span> tidak ditemukan.
            </div>
        @endif

        @if ($searchedRedemption)
            <div class="mt-5 p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-extrabold text-base text-slate-900">{{ $searchedRedemption->claim_code }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $searchedRedemption->status->badgeClasses() }}">
                            {{ $searchedRedemption->status->label() }}
                        </span>
                    </div>
                    <p class="text-sm font-bold text-slate-900 mt-1">{{ $searchedRedemption->reward->name }} &times; {{ $searchedRedemption->quantity }}</p>
                    <p class="text-xs text-slate-600">
                        Nasabah: <strong>{{ $searchedRedemption->user->name }}</strong> ({{ $searchedRedemption->user->member_code }})
                        &bull; {{ number_format($searchedRedemption->total_points_spent) }} Poin
                    </p>
                    <p class="text-xs text-slate-500">Berlaku hingga: {{ $searchedRedemption->expires_at->format('d M Y H:i') }}</p>
                </div>

                @if ($searchedRedemption->status === \App\Enums\RedemptionStatus::Pending)
                    <div class="flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('officer.redemptions.complete', $searchedRedemption) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs cursor-pointer">
                                Serahkan Barang & Selesaikan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('officer.redemptions.cancel', $searchedRedemption) }}" onsubmit="return confirm('Batalkan tiket ini dan kembalikan poin + stok secara otomatis?')">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 font-bold text-sm cursor-pointer">
                                Batalkan & Refund
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Pending Queue --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 mb-4">Antrean Klaim Menunggu Pengambilan ({{ $pendingRedemptions->count() }})</h2>
        @if ($pendingRedemptions->isEmpty())
            <p class="text-sm text-slate-500 py-4 text-center">Tidak ada tiket penukaran yang menunggu pengambilan saat ini.</p>
        @else
            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($pendingRedemptions as $item)
                    <div class="p-4 rounded-2xl border border-slate-200 flex flex-col justify-between gap-4">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono font-extrabold text-sm text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">{{ $item->claim_code }}</span>
                                <span class="text-xs text-slate-500">Exp: {{ $item->expires_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-bold text-slate-900 mt-3">{{ $item->reward->name }} <span class="text-slate-500 font-normal">&times; {{ $item->quantity }}</span></h3>
                            <p class="text-xs text-slate-600 mt-1">{{ $item->user->name }} ({{ $item->user->member_code }}) &bull; {{ number_format($item->total_points_spent) }} Poin</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('officer.redemptions.complete', $item) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs cursor-pointer">
                                    Serahkan & Selesaikan
                                </button>
                            </form>
                            <form method="POST" action="{{ route('officer.redemptions.cancel', $item) }}" onsubmit="return confirm('Batalkan tiket dan refund poin nasabah?')">
                                @csrf
                                <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs cursor-pointer">
                                    Batalkan
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Processed --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 mb-4">Riwayat Verifikasi Terbaru</h2>
        @if ($recentCompleted->isEmpty())
            <p class="text-sm text-slate-500">Belum ada riwayat verifikasi.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 pr-4">Kode Klaim</th>
                            <th class="py-2.5 pr-4">Nasabah</th>
                            <th class="py-2.5 pr-4">Barang</th>
                            <th class="py-2.5 pr-4">Status</th>
                            <th class="py-2.5">Verifikator</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentCompleted as $r)
                            <tr>
                                <td class="py-3 pr-4 font-mono text-xs font-bold">{{ $r->claim_code }}</td>
                                <td class="py-3 pr-4">{{ $r->user->name }}</td>
                                <td class="py-3 pr-4 font-medium">{{ $r->reward->name }} (&times;{{ $r->quantity }})</td>
                                <td class="py-3 pr-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $r->status->badgeClasses() }}">{{ $r->status->label() }}</span>
                                </td>
                                <td class="py-3 text-xs text-slate-500">{{ $r->verifier?->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
