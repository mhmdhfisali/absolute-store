<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ modalDetailOpen: false, modalData: null, retryingInvoice: null }">

        {{-- Alerts --}}
        @if (session('success'))
            <div
                class="p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="$el.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700 dark:hover:text-white text-base leading-none">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="$el.parentElement.remove()"
                    class="text-rose-500 hover:text-rose-700 dark:hover:text-white text-base leading-none">&times;</button>
            </div>
        @endif

        <!-- 5 METRIC CARDS RINGKAS & ELEGAN -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">

            <!-- Card 1: Omset -->
            <div
                class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/80 p-4 shadow-md dark:shadow-xl relative overflow-hidden transition-colors duration-200">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Omset Lunas</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="mt-2.5 text-lg sm:text-xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight truncate">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 block">Total dana
                    masuk</span>
            </div>

            <!-- Card 2: Saldo Digiflazz AJAX -->
            <div class="rounded-2xl border p-4 shadow-md dark:shadow-xl transition-all relative duration-200"
                :class="(numericBalance < 100000 && isConfigured) ? 'border-amber-500/50 bg-amber-50/50 dark:bg-[#16120e]' :
                'border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/80'"
                x-data="{
                    numericBalance: {{ $digiflazzBalance ?? 0 }},
                    balanceText: '{{ env('DIGIFLAZZ_USERNAME') ? 'Rp ' . number_format($digiflazzBalance ?? 0, 0, ',', '.') : 'Dev Off' }}',
                    isConfigured: {{ env('DIGIFLAZZ_USERNAME') ? 'true' : 'false' }},
                    loading: false,
                    async refreshBalance() {
                        this.loading = true;
                        try {
                            const res = await fetch('{{ route('admin.digiflazz.balance') }}');
                            const data = await res.json();
                            this.numericBalance = data.balance || 0;
                            this.balanceText = data.formatted;
                            this.isConfigured = data.success;
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.loading = false;
                        }
                    }
                }">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Saldo Provider</span>
                    <button @click="refreshBalance()" :disabled="loading" type="button" title="Refresh Saldo"
                        class="h-6 w-6 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 flex items-center justify-center transition cursor-pointer">
                        <svg class="w-3 h-3" :class="{ 'animate-spin text-sky-500': loading }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
                <div class="mt-2.5 text-lg sm:text-xl font-black tracking-tight truncate"
                    :class="(numericBalance < 100000 && isConfigured) ? 'text-amber-600 dark:text-amber-400' :
                    'text-sky-600 dark:text-sky-400'"
                    x-text="balanceText"></div>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 block truncate"
                    x-text="!isConfigured ? 'Mode dev testing' : (numericBalance < 100000 ? 'Segera isi deposit!' : 'Deposit aman')"></span>
            </div>

            <!-- Card 3: Total Orders -->
            <div
                class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/80 p-4 shadow-md dark:shadow-xl transition-colors duration-200">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Total Order</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5 text-lg sm:text-xl font-black text-indigo-600 dark:text-indigo-400 tracking-tight">
                    {{ number_format($totalOrders) }}
                </div>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 block">Semua
                    invoice</span>
            </div>

            <!-- Card 4: Sukses -->
            <div
                class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/80 p-4 shadow-md dark:shadow-xl transition-colors duration-200">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Terkirim</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5 text-lg sm:text-xl font-black text-cyan-600 dark:text-cyan-400 tracking-tight">
                    {{ number_format($successfulOrders) }}
                </div>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 block">Item masuk ke
                    user</span>
            </div>

            <!-- Card 5: Menunggu -->
            <div
                class="col-span-2 lg:col-span-1 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/80 p-4 shadow-md dark:shadow-xl transition-colors duration-200">
                <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Unpaid</span>
                    <div
                        class="h-7 w-7 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5 text-lg sm:text-xl font-black text-amber-600 dark:text-amber-400 tracking-tight">
                    {{ number_format($pendingOrders) }}
                </div>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 block">Menunggu
                    bayar</span>
            </div>

        </div>

        <!-- SEKSI GRAFIK PENJUALAN 7 HARI (APEXCHARTS) -->
        @if (isset($chartLabels) && isset($chartRevenues))
            <div
                class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/90 p-5 shadow-lg dark:shadow-xl transition-colors duration-200">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800/80">
                    <div>
                        <h2
                            class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            Tren Penjualan & Omset 7 Hari Terakhir
                        </h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">Analisis pertumbuhan pendapatan harian transaksi
                            berstatus lunas (Paid).</p>
                    </div>
                    <span
                        class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 self-start sm:self-auto">
                        Live Real-time
                    </span>
                </div>

                <div id="revenueChart" class="w-full h-64"></div>
            </div>
        @endif

        <!-- BAR FILTER & SEARCH -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">

            <!-- Status Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                @foreach (['all' => 'Semua', 'paid' => 'Paid', 'unpaid' => 'Unpaid', 'expired' => 'Expired', 'failed' => 'Gagal'] as $key => $label)
                    <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['status' => $key, 'page' => 1])) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ $selectedStatus === $key ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        <span>{{ $label }}</span>
                        <span
                            class="px-1.5 py-0.2 rounded-md text-[10px] {{ $selectedStatus === $key ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }}">
                            {{ $statusCounts[$key] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Search & Export -->
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="relative flex-1 sm:w-64">
                    <input type="hidden" name="status" value="{{ $selectedStatus }}">
                    <input type="text" name="q" value="{{ $searchQuery }}"
                        placeholder="Cari invoice, ID akun..."
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#090e1b] px-3.5 py-2 pl-9 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 absolute left-3 top-2.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>

                <a href="{{ route('dashboard.export') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-emerald-500/40 text-emerald-600 dark:text-emerald-400 font-bold text-xs transition shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        <!-- TABEL DATA MUTASI TRANSAKSI -->
        <div
            class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/90 shadow-xl dark:shadow-2xl overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 dark:bg-[#090e1b] border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3.5">Invoice</th>
                            <th class="px-5 py-3.5">Item & Layanan</th>
                            <th class="px-5 py-3.5">Akun Tujuan</th>
                            <th class="px-5 py-3.5">Total Tagihan</th>
                            <th class="px-5 py-3.5 text-center">Bayar</th>
                            <th class="px-5 py-3.5 text-center">Kirim</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <!-- Invoice -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span
                                        class="block font-mono font-bold text-slate-900 dark:text-white">{{ $trx->invoice_number }}</span>
                                    <span
                                        class="block text-[10px] text-slate-400 dark:text-slate-500">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                                </td>

                                <!-- Layanan -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span
                                        class="block font-bold text-slate-800 dark:text-slate-200">{{ $trx->productItem?->product?->name ?? 'Layanan Dihapus' }}</span>
                                    <span
                                        class="block text-[11px] text-slate-500 dark:text-slate-400">{{ $trx->productItem?->name ?? '-' }}</span>
                                </td>

                                <!-- Akun Tujuan -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="block font-mono font-bold text-slate-800 dark:text-slate-200">
                                        {{ $trx->target_account }}{{ $trx->target_zone ? " ({$trx->target_zone})" : '' }}
                                    </span>
                                    <span
                                        class="block text-[10px] text-slate-400 dark:text-slate-500 font-mono">{{ $trx->contact_email_or_phone }}</span>
                                </td>

                                <!-- Total Nominal -->
                                <td
                                    class="px-5 py-3.5 whitespace-nowrap font-bold text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    <span
                                        class="block text-[9px] font-normal text-slate-400 dark:text-slate-500 font-mono">{{ $trx->paymentMethod?->code ?? 'QRIS' }}</span>
                                </td>

                                <!-- Status Bayar -->
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                    @if ($trx->payment_status === 'paid')
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400">Paid</span>
                                    @elseif($trx->payment_status === 'unpaid')
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400">Unpaid</span>
                                    @elseif($trx->payment_status === 'expired')
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-500">Expired</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400">Failed</span>
                                    @endif
                                </td>

                                <!-- Status Pengiriman -->
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                    @if ($trx->delivery_status === 'success')
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-cyan-500/10 border border-cyan-500/20 text-cyan-600 dark:text-cyan-400">Terkirim</span>
                                        @if ($trx->serial_number)
                                            <span
                                                class="block text-[9px] font-mono text-slate-500 dark:text-slate-400 mt-1 max-w-[130px] truncate mx-auto"
                                                title="{{ $trx->serial_number }}">
                                                {{ $trx->serial_number }}
                                            </span>
                                        @endif
                                    @elseif($trx->delivery_status === 'processing')
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 animate-pulse">Diproses</span>
                                    @elseif($trx->delivery_status === 'failed')
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400">Gagal</span>
                                        @if ($trx->provider_response)
                                            <button type="button"
                                                @click="modalData = {{ json_encode($trx->provider_response) }}; modalDetailOpen = true"
                                                class="block text-[9px] text-rose-500 hover:underline mx-auto mt-0.5 cursor-pointer">
                                                Log Error
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-1">
                                    @if ($trx->payment_status === 'paid' && $trx->delivery_status !== 'success')
                                        <form action="{{ route('admin.transactions.retry', $trx->invoice_number) }}"
                                            method="POST" class="inline-block"
                                            @submit="retryingInvoice = '{{ $trx->invoice_number }}'"
                                            onsubmit="return confirm('Kirim ulang order {{ $trx->invoice_number }} ke Digiflazz?')">
                                            @csrf
                                            <button type="submit"
                                                :disabled="retryingInvoice === '{{ $trx->invoice_number }}'"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-600 dark:text-amber-400 font-bold text-[11px] transition cursor-pointer">
                                                <svg class="w-3 h-3"
                                                    :class="{ 'animate-spin': retryingInvoice === '{{ $trx->invoice_number }}' }"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                <span>Retry</span>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('order.invoice', $trx->invoice_number) }}" target="_blank"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-[11px] transition">
                                        Invoice ↗
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500 font-medium">
                                    Tidak ada data transaksi yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($recentTransactions->hasPages())
                <div class="p-3 border-t border-slate-100 dark:border-slate-800">
                    {{ $recentTransactions->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL LOG ERROR PROVIDER -->
        <div x-show="modalDetailOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="modalDetailOpen = false"
                class="w-full max-w-lg rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222] p-6 space-y-4 shadow-2xl text-slate-800 dark:text-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div
                        class="flex items-center gap-2 text-rose-500 dark:text-rose-400 font-bold text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Respon Teknis Provider</span>
                    </div>
                    <button @click="modalDetailOpen = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
                </div>
                <pre class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-[11px] font-mono text-slate-700 dark:text-slate-300 overflow-x-auto max-h-64"
                    x-text="JSON.stringify(modalData, null, 2)"></pre>
                <div class="flex justify-end">
                    <button type="button" @click="modalDetailOpen = false"
                        class="px-4 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-white font-bold text-xs transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    @if (isset($chartLabels) && isset($chartRevenues))
        <!-- Script ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isDarkMode = document.documentElement.classList.contains('dark');

                const options = {
                    series: [{
                            name: 'Omset Lunas (Rp)',
                            data: @json($chartRevenues)
                        },
                        {
                            name: 'Transaksi Sukses',
                            data: @json($chartOrderCounts ?? [])
                        }
                    ],
                    chart: {
                        type: 'area',
                        height: 250,
                        toolbar: {
                            show: false
                        },
                        background: 'transparent'
                    },
                    colors: ['#6366f1', '#06b6d4'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: [2.5, 2]
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    xaxis: {
                        categories: @json($chartLabels),
                        labels: {
                            style: {
                                colors: '#94a3b8',
                                fontSize: '11px',
                                fontFamily: 'Plus Jakarta Sans'
                            }
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: [{
                            labels: {
                                formatter: function(val) {
                                    return 'Rp ' + (val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val);
                                },
                                style: {
                                    colors: '#94a3b8',
                                    fontSize: '11px'
                                }
                            }
                        },
                        {
                            opposite: true,
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0) + ' trx';
                                },
                                style: {
                                    colors: '#94a3b8',
                                    fontSize: '11px'
                                }
                            }
                        }
                    ],
                    grid: {
                        borderColor: isDarkMode ? '#1e293b' : '#f1f5f9',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: isDarkMode ? 'dark' : 'light',
                        y: {
                            formatter: function(val, opts) {
                                if (opts.seriesIndex === 0) {
                                    return 'Rp ' + Number(val).toLocaleString('id-ID');
                                }
                                return val + ' Transaksi';
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
                chart.render();
            });
        </script>
    @endif
</x-app-layout>
