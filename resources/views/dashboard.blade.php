<x-app-layout>
    <div class="space-y-8">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800/80">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Financial & Order Monitor</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">Laporan mutasi kas, status payment gateway, dan
                    pemrosesan produk digital.</p>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Tripay Webhook Ready
                </span>

                <a href="{{ route('dashboard.export') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-600 hover:brightness-110 text-white text-xs font-bold shadow-lg shadow-indigo-600/20 transition active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Rekapan (.CSV)
                </a>
            </div>
        </div>

        <!-- 5 Metric Cards (Finansial, Provider Saldo, & Mutasi) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            <!-- Card 1: Total Omset Toko -->
            <div
                class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-4 shadow-xl relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Total Omset</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl font-black text-emerald-400 tracking-tight">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Transaksi berstatus paid</p>
                </div>
            </div>

            <!-- Card 2: Saldo Deposit Digiflazz dengan Alert Saldo Rendah (< Rp 100.000) & AJAX Refresh -->
            <div class="rounded-2xl border p-4 shadow-xl relative overflow-hidden transition"
                :class="(numericBalance < 100000 && isConfigured) ? 'border-amber-500/50 bg-[#16120e]/95' :
                'border-slate-800 bg-[#0f172a]/90 hover:border-sky-500/40'"
                x-data="{
                    numericBalance: {{ $digiflazzBalance ?? 0 }},
                    balanceText: '{{ env('DIGIFLAZZ_USERNAME') ? 'Rp ' . number_format($digiflazzBalance ?? 0, 0, ',', '.') : 'Dev Off' }}',
                    isConfigured: {{ env('DIGIFLAZZ_USERNAME') ? 'true' : 'false' }},
                    loading: false,
                    async refreshBalance() {
                        this.loading = true;
                        try {
                            const res = await fetch('{{ route('admin.digiflazz.balance') }}', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await res.json();
                            this.numericBalance = data.balance || 0;
                            this.balanceText = data.formatted;
                            this.isConfigured = data.success;
                        } catch (e) {
                            console.error('Gagal mengambil saldo:', e);
                        } finally {
                            this.loading = false;
                        }
                    }
                }">

                <div class="flex items-center justify-between text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider">Saldo Digiflazz</span>

                        <!-- Badge Peringatan Saldo Rendah (< 100rb) -->
                        <template x-if="isConfigured && numericBalance < 100000">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[9px] font-black uppercase tracking-wider animate-pulse">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Saldo Menipis
                            </span>
                        </template>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <!-- Tombol Refresh AJAX -->
                        <button @click="refreshBalance()" :disabled="loading" type="button"
                            title="Sinkronkan Saldo Terkini"
                            class="h-6 w-6 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-sky-400 flex items-center justify-center transition disabled:opacity-50">
                            <svg class="w-3.5 h-3.5" :class="{ 'animate-spin text-sky-400': loading }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>

                        <div class="h-7 w-7 rounded-lg flex items-center justify-center transition"
                            :class="(numericBalance < 100000 && isConfigured) ?
                            'bg-amber-500/10 border border-amber-500/20 text-amber-400' :
                            'bg-sky-500/10 border border-sky-500/20 text-sky-400'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="text-xl font-black tracking-tight flex items-center gap-2"
                        :class="(numericBalance < 100000 && isConfigured) ? 'text-amber-400' : 'text-sky-400'">
                        <span x-text="balanceText"></span>
                        <template x-if="loading">
                            <span class="text-[10px] font-normal text-slate-400 animate-pulse">Menghubungkan...</span>
                        </template>
                    </div>

                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="h-1.5 w-1.5 rounded-full"
                            :class="!isConfigured ? 'bg-slate-600' : (numericBalance < 100000 ? 'bg-amber-400 animate-ping' :
                                'bg-sky-400 animate-pulse')"></span>
                        <p class="text-[10px] font-medium"
                            :class="(numericBalance < 100000 && isConfigured) ? 'text-amber-300/80 font-bold' : 'text-slate-500'">
                            <span x-show="!isConfigured">Mode offline</span>
                            <span x-show="isConfigured && numericBalance >= 100000">Sisa deposit aman</span>
                            <span x-show="isConfigured && numericBalance < 100000">Segera top up deposit!</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Pesanan Masuk -->
            <div
                class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-4 shadow-xl relative overflow-hidden group hover:border-indigo-500/40 transition">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Total Pesanan</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl font-black text-indigo-400 tracking-tight">
                        {{ number_format($totalOrders) }}
                    </div>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Semua invoice tercatat</p>
                </div>
            </div>

            <!-- Card 4: Order Sukses -->
            <div
                class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-4 shadow-xl relative overflow-hidden group hover:border-cyan-500/40 transition">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Order Sukses</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl font-black text-cyan-400 tracking-tight">
                        {{ number_format($successfulOrders) }}
                    </div>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Pengiriman berhasil</p>
                </div>
            </div>

            <!-- Card 5: Menunggu Bayar -->
            <div
                class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-4 shadow-xl relative overflow-hidden group hover:border-amber-500/40 transition">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Menunggu Bayar</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl font-black text-amber-400 tracking-tight">
                        {{ number_format($pendingOrders) }}
                    </div>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Dalam batas countdown</p>
                </div>
            </div>

        </div>

        <!-- Tabel Monitoring Transaksi + Filter Controls -->
        <div
            class="rounded-2xl border border-slate-800 bg-[#0f172a]/80 shadow-2xl overflow-hidden space-y-4 p-5 sm:p-6">

            <!-- Controls Bar: Filter Tabs & Search Form -->
            <div
                class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-800/80">

                <!-- Status Filter Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto scrollbar-none pb-2 lg:pb-0">
                    @php
                        $tabs = [
                            'all' => ['label' => 'Semua', 'color' => 'indigo'],
                            'paid' => ['label' => 'Paid', 'color' => 'emerald'],
                            'unpaid' => ['label' => 'Unpaid', 'color' => 'amber'],
                            'expired' => ['label' => 'Expired', 'color' => 'rose'],
                            'failed' => ['label' => 'Failed', 'color' => 'red'],
                        ];
                    @endphp

                    @foreach ($tabs as $key => $tab)
                        <a href="{{ route('dashboard', array_merge(request()->query(), ['status' => $key])) }}"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap
                           {{ $selectedStatus === $key
                               ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30'
                               : 'bg-slate-900/90 border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                            <span>{{ $tab['label'] }}</span>
                            <span
                                class="px-1.5 py-0.2 rounded-md text-[10px] {{ $selectedStatus === $key ? 'bg-indigo-800 text-indigo-200' : 'bg-slate-800 text-slate-400' }}">
                                {{ $statusCounts[$key] ?? 0 }}
                            </span>
                        </a>
                    @endforeach
                </div>

                <!-- Live Search Box -->
                <form action="{{ route('dashboard') }}" method="GET"
                    class="flex items-center gap-2 w-full lg:w-72">
                    <input type="hidden" name="status" value="{{ $selectedStatus }}">
                    <div class="relative w-full">
                        <input type="text" name="q" value="{{ $searchQuery }}"
                            placeholder="Cari invoice / user ID..."
                            class="w-full rounded-xl border border-slate-800 bg-slate-900/90 px-3.5 py-2 pl-9 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <svg class="w-3.5 h-3.5 text-slate-500 absolute left-3 top-2.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if (!empty($searchQuery))
                        <a href="{{ route('dashboard', ['status' => $selectedStatus]) }}"
                            class="px-2.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white text-xs transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-xl border border-slate-800/60">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-[#0a0f1d] border-b border-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3.5">Invoice</th>
                            <th class="px-5 py-3.5">Layanan / Item</th>
                            <th class="px-5 py-3.5">Target Akun</th>
                            <th class="px-5 py-3.5">Channel</th>
                            <th class="px-5 py-3.5">Total Bayar</th>
                            <th class="px-5 py-3.5 text-center">Status Bayar</th>
                            <th class="px-5 py-3.5 text-center">Pengiriman</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="px-5 py-4 font-mono font-bold text-indigo-400 whitespace-nowrap">
                                    {{ $trx->invoice_number }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-white">{{ $trx->productItem->product->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $trx->productItem->name }}</div>
                                </td>
                                <td class="px-5 py-4 font-mono">
                                    <span class="text-white font-medium">{{ $trx->target_account }}</span>
                                    @if ($trx->target_zone)
                                        <span class="text-slate-400 text-[11px]">({{ $trx->target_zone }})</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span
                                        class="rounded bg-slate-950 border border-slate-800 px-2 py-1 text-[10px] font-mono text-slate-300">
                                        {{ $trx->paymentMethod->code }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-bold text-emerald-400 whitespace-nowrap">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $trx->payment_status === 'paid'
                                            ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                                            : ($trx->payment_status === 'expired'
                                                ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20'
                                                : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                        {{ $trx->payment_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase
                                        {{ $trx->delivery_status === 'success' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-slate-900 border border-slate-800 text-slate-400' }}">
                                        {{ $trx->delivery_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('order.invoice', $trx->invoice_number) }}" target="_blank"
                                        class="px-3 py-1.5 rounded-lg bg-indigo-600/10 hover:bg-indigo-600 hover:text-white border border-indigo-500/30 text-indigo-400 text-xs font-bold transition">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-14 text-center text-slate-400">
                                    <div
                                        class="h-10 w-10 mx-auto rounded-xl bg-slate-800/40 flex items-center justify-center text-slate-500 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    Tidak ada data transaksi dengan kriteria pencarian ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if ($recentTransactions->hasPages())
                <div class="pt-3">
                    {{ $recentTransactions->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
