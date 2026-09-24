<div class="space-y-6">

    <!-- Header Section & Reconciliation Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Ledger Keuangan & Rekonsiliasi
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                    Buku Besar Kas
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Audit perputaran arus kas, mutasi wallet pengguna, dan integritas saldo supplier real-time.
            </p>
        </div>

        <!-- Tombol Rekonsiliasi Supplier -->
        <button type="button" wire:click="runReconciliation" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/20 active:scale-95 transition cursor-pointer">
            <svg class="w-4 h-4 animate-spin" wire:loading fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <svg class="w-4 h-4" wire:loading.remove fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Audit Saldo Vendor Realtime</span>
        </button>
    </div>

    <!-- Alert Rekonsiliasi -->
    @if($reconciliationMessage)
        <div class="p-4 rounded-2xl text-xs font-bold flex items-center gap-3 {{ $reconciliationStatus === 'healthy' ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : ($reconciliationStatus === 'warning' ? 'bg-amber-500/10 border border-amber-500/30 text-amber-400' : 'bg-rose-500/10 border border-rose-500/30 text-rose-400') }}">
            <span class="text-base">{{ $reconciliationStatus === 'healthy' ? '✔' : '⚠' }}</span>
            <span>{{ $reconciliationMessage }}</span>
        </div>
    @endif

    <!-- Metric Cards: Finansial Periode -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Card 1: Total Dana Masuk -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold mb-2">
                <span>Total Dana Masuk (Credit)</span>
                <span class="p-1.5 rounded-xl bg-emerald-500/10 text-emerald-500 font-bold">Inflow</span>
            </div>
            <div class="text-2xl font-black font-mono text-emerald-600 dark:text-emerald-400">
                Rp {{ number_format($creditSum, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Deposit & penyesuaian credit</div>
        </div>

        <!-- Card 2: Total Dana Keluar -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold mb-2">
                <span>Total Dana Keluar (Debit)</span>
                <span class="p-1.5 rounded-xl bg-rose-500/10 text-rose-500 font-bold">Outflow</span>
            </div>
            <div class="text-2xl font-black font-mono text-rose-600 dark:text-rose-400">
                Rp {{ number_format($debitSum, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Belanja produk & penarikan</div>
        </div>

        <!-- Card 3: Omset Pesanan Sukses -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold mb-2">
                <span>Gross Omzet Penjualan</span>
                <span class="p-1.5 rounded-xl bg-indigo-500/10 text-indigo-400 font-bold">Orders</span>
            </div>
            <div class="text-2xl font-black font-mono text-indigo-600 dark:text-cyan-400">
                Rp {{ number_format($successfulOrdersTotal, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Total nilai pesanan delivered</div>
        </div>
    </div>

    <!-- Filter Bar: Search, Type, Periode -->
    <div class="p-4 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative w-full md:w-80 group">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari ID referensi, nama atau email..." 
                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-4 py-2.5 pl-10 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
            <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Filter Tipe -->
            <select wire:model.live="typeFilter" 
                    class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500">
                <option value="all">Semua Tipe (Credit & Debit)</option>
                <option value="credit">Dana Masuk (+ Credit)</option>
                <option value="debit">Dana Keluar (- Debit)</option>
            </select>

            <!-- Filter Periode -->
            <select wire:model.live="period" 
                    class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500">
                <option value="today">Hari Ini</option>
                <option value="week">Minggu Ini</option>
                <option value="month">Bulan Ini</option>
                <option value="all">Semua Waktu</option>
            </select>
        </div>
    </div>

    <!-- Mutasi Ledger Table -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 shadow-2xl overflow-hidden backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-950/50 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-5">ID Referensi</th>
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-5">Tipe & Kategori</th>
                        <th class="py-3.5 px-5">Perubahan Mutasi</th>
                        <th class="py-3.5 px-5">Saldo Akhir</th>
                        <th class="py-3.5 px-5">Keterangan</th>
                        <th class="py-3.5 px-5 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($mutations as $m)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ $m->reference_id }}
                            </td>
                            <td class="py-3.5 px-5 font-bold text-slate-800 dark:text-white">
                                {{ $m->user?->name ?? 'System/Deleted' }}
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase {{ $m->type === 'credit' ? 'bg-emerald-500/15 text-emerald-500' : 'bg-rose-500/15 text-rose-500' }}">
                                    {{ $m->type }}
                                </span>
                                <span class="text-[10px] text-slate-400 ml-1">({{ $m->category }})</span>
                            </td>
                            <td class="py-3.5 px-5 font-mono font-bold {{ $m->type === 'credit' ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $m->type === 'credit' ? '+' : '-' }} Rp {{ number_format($m->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-700 dark:text-slate-200">
                                Rp {{ number_format($m->balance_after, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-5 text-slate-400 max-w-xs truncate">
                                {{ $m->description ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-right font-mono text-[11px] text-slate-400">
                                {{ $m->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                Belum ada riwayat mutasi transaksi untuk kriteria pencarian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $mutations->links() }}
        </div>
    </div>

</div>
