@extends('layouts.app')

@section('title', 'POS Setor Sampah — SortiPay')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">POS Setor Sampah</h1>
            <p class="text-sm text-slate-600">Pilih nasabah, timbang multi-jenis sampah, dan konfirmasi setoran poin secara real-time.</p>
        </div>
        <a href="{{ route('officer.redemptions.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
            Verifikasi Klaim Barang &rarr;
        </a>
    </div>

    <form id="pos-form" method="POST" action="{{ route('officer.deposit.store') }}" class="grid lg:grid-cols-3 gap-6 items-start">
        @csrf

        {{-- Left & Center Column: Customer Selection + Multi-item Waste Entry --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            {{-- Step 1: Select Customer --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white text-xs font-bold flex items-center justify-center">1</span>
                        Identifikasi Nasabah
                    </h2>
                    <span class="text-xs text-slate-500">Cari nama, No HP, atau scan/ketik Member Code</span>
                </div>

                <div class="relative mb-3">
                    <input id="customer-search" type="text" autocomplete="off"
                        placeholder="Ketik SRT-2026-0003, nama nasabah, atau nomor HP..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>

                <input type="hidden" name="user_id" id="selected-user-id" value="{{ old('user_id') }}" required>

                <div id="selected-customer-card" class="hidden mb-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Nasabah Terpilih</p>
                        <p id="selected-customer-name" class="text-base font-extrabold text-slate-900 mt-0.5"></p>
                        <p id="selected-customer-meta" class="text-xs text-slate-600 font-mono"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Saldo Saat Ini</p>
                        <p id="selected-customer-points" class="text-lg font-extrabold text-amber-600"></p>
                    </div>
                </div>

                <div id="customer-results" class="grid sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                    @foreach ($customers as $c)
                        <button type="button"
                            data-select-customer
                            data-id="{{ $c->id }}"
                            data-name="{{ $c->name }}"
                            data-code="{{ $c->member_code }}"
                            data-phone="{{ $c->phone }}"
                            data-points="{{ $c->points_balance }}"
                            class="text-left p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition cursor-pointer flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $c->name }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $c->member_code }} &bull; {{ $c->phone }}</p>
                            </div>
                            <span class="text-xs font-bold text-amber-600 shrink-0">{{ number_format($c->points_balance) }} pts</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Step 2: Multi-Item Waste Rows --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white text-xs font-bold flex items-center justify-center">2</span>
                        Input Item Sampah (Multi-Item)
                    </h2>
                    <button type="button" id="add-item-btn"
                        class="px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200 transition cursor-pointer">
                        + Tambah Baris Sampah
                    </button>
                </div>

                <div id="items-container" class="flex flex-col gap-3"></div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <label for="notes" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Petugas (Opsional)</label>
                    <input id="notes" name="notes" type="text" value="{{ old('notes') }}"
                        placeholder="Misal: Sampah sudah terpilah rapi dalam 2 karung..."
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>
            </div>
        </div>

        {{-- Right Column: Live Summary & Submit --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs lg:sticky lg:top-6 flex flex-col gap-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Setoran</p>
                <h3 class="text-lg font-extrabold text-slate-900">Kalkulasi Poin Real-Time</h3>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4 flex flex-col gap-2.5 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Jumlah Item</span>
                    <span id="summary-count" class="font-bold text-slate-900">1 item</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Total Berat (kg)</span>
                    <span id="summary-weight" class="font-bold text-slate-900">0.00 kg</span>
                </div>
                <div class="pt-2.5 border-t border-slate-200 flex justify-between items-baseline">
                    <span class="font-bold text-slate-900">Total Poin Masuk</span>
                    <span id="summary-points" class="text-2xl font-extrabold text-amber-600">0 Poin</span>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 leading-relaxed">
                Formula: <code class="px-1 py-0.5 rounded bg-slate-100 text-slate-800">floor(qty × tarif × kondisi)</code>. Tarif disimpan sebagai <em>snapshot</em> dan poin langsung dicatat ke buku besar (Point Ledger).
            </p>

            <button type="submit" id="submit-deposit-btn"
                class="w-full py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-base shadow-sm transition cursor-pointer">
                Konfirmasi & Simpan Setoran
            </button>
        </div>
    </form>

    {{-- Recent Deposits Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 mb-4">Setoran Terbaru di Posko</h2>
        @if ($recentDeposits->isEmpty())
            <p class="text-sm text-slate-500">Belum ada transaksi setoran.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 pr-4">No. Struk</th>
                            <th class="py-2.5 pr-4">Nasabah</th>
                            <th class="py-2.5 pr-4">Berat (kg)</th>
                            <th class="py-2.5 pr-4">Total Poin</th>
                            <th class="py-2.5 pr-4">Waktu</th>
                            <th class="py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentDeposits as $dep)
                            <tr>
                                <td class="py-3 pr-4 font-mono text-xs font-bold text-slate-800">{{ $dep->receipt_number }}</td>
                                <td class="py-3 pr-4">
                                    <p class="font-semibold text-slate-900">{{ $dep->user->name }}</p>
                                    <p class="text-xs text-slate-500 font-mono">{{ $dep->user->member_code }}</p>
                                </td>
                                <td class="py-3 pr-4 font-medium">{{ number_format((float) $dep->total_weight_kg, 2) }} kg</td>
                                <td class="py-3 pr-4 font-bold text-emerald-700">+{{ number_format($dep->total_points) }}</td>
                                <td class="py-3 pr-4 text-xs text-slate-500">{{ $dep->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('officer.deposit.receipt', $dep) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Struk</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const wasteTypes = @json($wasteTypesJson);

    const searchInput = document.getElementById('customer-search');
    const resultsContainer = document.getElementById('customer-results');
    const selectedUserIdInput = document.getElementById('selected-user-id');
    const selectedCard = document.getElementById('selected-customer-card');
    const selectedName = document.getElementById('selected-customer-name');
    const selectedMeta = document.getElementById('selected-customer-meta');
    const selectedPoints = document.getElementById('selected-customer-points');

    function selectCustomer(c) {
        selectedUserIdInput.value = c.id;
        selectedName.textContent = c.name;
        selectedMeta.textContent = `${c.member_code} • ${c.phone || '-'}`;
        selectedPoints.textContent = `${Number(c.points_balance).toLocaleString('id-ID')} Poin`;
        selectedCard.classList.remove('hidden');
    }

    function bindCustomerButtons() {
        resultsContainer.querySelectorAll('[data-select-customer]').forEach((btn) => {
            btn.addEventListener('click', () => {
                selectCustomer({
                    id: btn.dataset.id,
                    name: btn.dataset.name,
                    member_code: btn.dataset.code,
                    phone: btn.dataset.phone,
                    points_balance: btn.dataset.points,
                });
            });
        });
    }
    bindCustomerButtons();

    let searchTimer = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(async () => {
            const q = encodeURIComponent(searchInput.value.trim());
            const res = await fetch(`{{ route('officer.customers.search') }}?q=${q}`);
            const json = await res.json();
            const list = json.data || [];

            if (list.length === 0) {
                resultsContainer.innerHTML = '<p class="text-xs text-slate-500 col-span-2 py-2">Nasabah tidak ditemukan.</p>';
                return;
            }

            resultsContainer.innerHTML = list.map((c) => `
                <button type="button" data-select-customer
                    data-id="${c.id}" data-name="${c.name}" data-code="${c.member_code}"
                    data-phone="${c.phone || ''}" data-points="${c.points_balance}"
                    class="text-left p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition cursor-pointer flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate">${c.name}</p>
                        <p class="text-xs text-slate-500 font-mono">${c.member_code} &bull; ${c.phone || '-'}</p>
                    </div>
                    <span class="text-xs font-bold text-amber-600 shrink-0">${Number(c.points_balance).toLocaleString('id-ID')} pts</span>
                </button>
            `).join('');

            bindCustomerButtons();

            // Auto-select if exact member_code scanned/entered
            const exact = list.find((c) => c.member_code.toLowerCase() === searchInput.value.trim().toLowerCase());
            if (exact) {
                selectCustomer(exact);
            }
        }, 200);
    });

    // Dynamic Multi-Item Rows
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item-btn');
    const summaryCount = document.getElementById('summary-count');
    const summaryWeight = document.getElementById('summary-weight');
    const summaryPoints = document.getElementById('summary-points');
    let rowIndex = 0;

    function buildWasteOptions() {
        return wasteTypes.map((w) =>
            `<option value="${w.id}" data-rate="${w.points_per_unit}" data-unit="${w.unit}">[${w.category}] ${w.name} (${w.points_per_unit} pts/${w.unit})</option>`
        ).join('');
    }

    function recalculateTotals() {
        const rows = itemsContainer.querySelectorAll('[data-item-row]');
        let totalWeight = 0;
        let totalPoints = 0;

        rows.forEach((row) => {
            const select = row.querySelector('[data-waste-select]');
            const qtyInput = row.querySelector('[data-qty-input]');
            const condSelect = row.querySelector('[data-cond-select]');
            const unitLabel = row.querySelector('[data-unit-label]');
            const subtotalEl = row.querySelector('[data-subtotal-label]');

            const option = select.options[select.selectedIndex];
            const rate = parseFloat(option?.dataset.rate || '0');
            const unit = option?.dataset.unit || 'kg';
            const qty = Math.max(0, parseFloat(qtyInput.value || '0'));
            const mult = parseFloat(condSelect.value || '1');

            const subtotal = Math.floor(qty * rate * mult);

            unitLabel.textContent = unit;
            subtotalEl.textContent = `+${subtotal.toLocaleString('id-ID')} Poin`;

            if (unit === 'kg') {
                totalWeight += qty;
            }
            totalPoints += subtotal;
        });

        summaryCount.textContent = `${rows.length} item`;
        summaryWeight.textContent = `${totalWeight.toFixed(2)} kg`;
        summaryPoints.textContent = `${totalPoints.toLocaleString('id-ID')} Poin`;
    }

    function addRow() {
        const idx = rowIndex++;
        const row = document.createElement('div');
        row.setAttribute('data-item-row', 'true');
        row.className = 'p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 sm:grid-cols-12 gap-3 items-end';
        row.innerHTML = `
            <div class="sm:col-span-5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Sampah</label>
                <select name="items[${idx}][waste_type_id]" data-waste-select required
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-emerald-500 outline-none">
                    ${buildWasteOptions()}
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah (<span data-unit-label>kg</span>)</label>
                <input name="items[${idx}][quantity]" data-qty-input type="number" step="0.01" min="0.01" value="1.00" required
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold focus:border-emerald-500 outline-none">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kondisi</label>
                <select name="items[${idx}][quality_multiplier]" data-cond-select required
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-emerald-500 outline-none">
                    <option value="1.00">Bersih & Terpilah (100%)</option>
                    <option value="0.75">Tercampur / Basah (75%)</option>
                </select>
            </div>
            <div class="sm:col-span-2 flex items-center justify-between sm:flex-col sm:items-end gap-1">
                <span data-subtotal-label class="text-sm font-extrabold text-emerald-700">+0 Poin</span>
                <button type="button" data-remove-row class="text-xs font-semibold text-rose-600 hover:text-rose-700 cursor-pointer">Hapus</button>
            </div>
        `;

        row.querySelector('[data-waste-select]').addEventListener('change', recalculateTotals);
        row.querySelector('[data-qty-input]').addEventListener('input', recalculateTotals);
        row.querySelector('[data-cond-select]').addEventListener('change', recalculateTotals);
        row.querySelector('[data-remove-row]').addEventListener('click', () => {
            if (itemsContainer.querySelectorAll('[data-item-row]').length > 1) {
                row.remove();
                recalculateTotals();
            }
        });

        itemsContainer.appendChild(row);
        recalculateTotals();
    }

    addItemBtn.addEventListener('click', addRow);
    addRow();

    document.getElementById('pos-form').addEventListener('submit', (e) => {
        if (!selectedUserIdInput.value) {
            e.preventDefault();
            alert('Silakan pilih nasabah terlebih dahulu sebelum menyimpan setoran.');
            searchInput.focus();
        }
    });
});
</script>
@endpush
