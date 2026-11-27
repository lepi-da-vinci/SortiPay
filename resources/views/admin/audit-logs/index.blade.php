@extends('layouts.app')

@section('title', 'Audit Log Poin — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Audit Log Buku Besar Poin (Point Ledger)</h1>
        <p class="text-sm text-slate-600">Catatan permanen (immutable) seluruh mutasi kredit, debit, dan refund poin nasabah.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.audit-logs.index') }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ ! $typeFilter ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200' }}">Semua Tipe</a>
        @foreach ($types as $t)
            <a href="{{ route('admin.audit-logs.index', ['type' => $t->value]) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $typeFilter === $t->value ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200' }}">{{ $t->label() }}</a>
        @endforeach
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2.5 pr-4">Waktu</th>
                    <th class="py-2.5 pr-4">Nasabah</th>
                    <th class="py-2.5 pr-4">Tipe</th>
                    <th class="py-2.5 pr-4">Keterangan</th>
                    <th class="py-2.5 pr-4 text-right">Mutasi</th>
                    <th class="py-2.5 pr-4 text-right">Saldo Akhir</th>
                    <th class="py-2.5">Operator</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($ledgers as $log)
                    <tr>
                        <td class="py-3 pr-4 text-xs text-slate-500 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="py-3 pr-4">
                            <p class="font-semibold text-slate-900">{{ $log->user->name }}</p>
                            <p class="font-mono text-[11px] text-slate-500">{{ $log->user->member_code }}</p>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">{{ $log->type->label() }}</span>
                        </td>
                        <td class="py-3 pr-4 text-xs text-slate-600">{{ $log->notes }}</td>
                        <td class="py-3 pr-4 text-right font-extrabold {{ $log->amount >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ $log->amount >= 0 ? '+' : '' }}{{ number_format($log->amount) }}
                        </td>
                        <td class="py-3 pr-4 text-right font-bold text-slate-800">{{ number_format($log->balance_after) }}</td>
                        <td class="py-3 text-xs text-slate-500">{{ $log->creator->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500">Belum ada catatan audit log.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $ledgers->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
