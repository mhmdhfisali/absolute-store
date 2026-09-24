<div wire:poll.3s class="max-w-3xl mx-auto px-4 py-8 text-slate-200">
    <div class="rounded-3xl border border-slate-800 bg-[#0d1322] p-6 sm:p-8 shadow-2xl space-y-6">

        <!-- Header Status -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-widest text-slate-500">Invoice Tagihan</span>
                <h1 class="text-2xl font-mono font-black text-white mt-1">{{ $transaction->invoice_number }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">{{ $transaction->created_at->translatedFormat('d F Y, H:i') }}
                    WIB</p>
            </div>

            <div>
                @if ($transaction->payment_status === 'paid')
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-black text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Lunas
                    </span>
                @elseif($transaction->payment_status === 'unpaid')
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 font-black text-xs uppercase tracking-wider animate-pulse">
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                        Menunggu Pembayaran
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-xl bg-slate-800 text-slate-400 font-black text-xs uppercase">
                        {{ $transaction->payment_status }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Banner Sukses Pengiriman -->
        @if ($transaction->delivery_status === 'success')
            <div class="p-4 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 space-y-2">
                <div class="flex items-center gap-2 text-cyan-400 font-bold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Pesanan Selesai Terkirim!</span>
                </div>
                @if ($transaction->serial_number)
                    <div
                        class="p-3 rounded-xl bg-slate-950/80 border border-slate-800 text-xs font-mono text-slate-200 break-all">
                        <span class="text-slate-500 text-[10px] block font-sans">SN / Voucher Token:</span>
                        <strong class="text-cyan-400 font-bold text-sm">{{ $transaction->serial_number }}</strong>
                    </div>
                @endif
            </div>
        @elseif($transaction->delivery_status === 'processing')
            <div class="p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-400 animate-spin" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <div class="text-xs">
                    <span class="font-bold text-white block">Pembayaran Diterima!</span>
                    <span class="text-slate-400">Server sedang memproses pengisian ke ID game Anda...</span>
                </div>
            </div>
        @endif

        <!-- Rincian Item -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-[#080c15] border border-slate-800/80 space-y-1">
                <span class="text-slate-500 text-[10px] uppercase font-bold">Produk & Layanan</span>
                <div class="font-bold text-white text-sm">{{ $transaction->productItem->product->name }}</div>
                <div class="text-indigo-400 font-medium">{{ $transaction->productItem->name }}</div>
            </div>

            <div class="p-4 rounded-2xl bg-[#080c15] border border-slate-800/80 space-y-1 font-mono">
                <span class="text-slate-500 text-[10px] uppercase font-bold font-sans">Tujuan Akun</span>
                <div class="font-bold text-white text-sm">{{ $transaction->target_account }}</div>
                @if ($transaction->target_zone)
                    <div class="text-slate-400 text-xs">Server / Zone: ({{ $transaction->target_zone }})</div>
                @endif
            </div>
        </div>

        <!-- Rincian Tagihan -->
        <div class="p-4 rounded-2xl bg-[#080c15] border border-slate-800/80 space-y-2 text-xs">
            <div class="flex justify-between text-slate-400">
                <span>Harga Item</span>
                <span>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-400">
                <span>Biaya Pembayaran ({{ $transaction->paymentMethod->name }})</span>
                <span>Rp {{ number_format($transaction->fee_amount, 0, ',', '.') }}</span>
            </div>
            @if ($transaction->discount_amount > 0)
                <div class="flex justify-between text-rose-400 font-bold">
                    <span>Diskon Kupon Promo</span>
                    <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-sm font-black">
                <span class="text-white">Total Tagihan</span>
                <span class="text-emerald-400 text-base">Rp
                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Aksi & Cetak Struk Resmi -->
        <div class="pt-4 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3"
            x-data="{ copied: false }">
            <div class="flex flex-wrap items-center gap-2">
                @if ($transaction->payment_status === 'paid')
                    <a href="{{ route('order.invoice.print', $transaction->invoice_number) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Struk Resmi (Thermal / PDF)
                    </a>

                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Halo, berikut bukti transaksi Absolute Store untuk invoice #' . $transaction->invoice_number . ': ' . route('order.invoice', $transaction->invoice_number)) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 font-bold text-xs transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                        </svg>
                        Bagikan WhatsApp
                    </a>
                @endif

                <button type="button"
                    @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!copied">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="copied" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span x-text="copied ? 'Tersalin ke Clipboard!' : 'Salin Tautan'">Salin Tautan</span>
                </button>
            </div>

            <!-- Tombol Simulasi Pembayaran (Hanya tampil jika unpaid) -->
            @if ($transaction->payment_status === 'unpaid')
                <form action="{{ route('payment.simulate', $transaction->invoice_number) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-emerald-600/20 hover:bg-emerald-600 hover:text-white border border-emerald-500/30 text-emerald-400 font-bold text-xs transition">
                        Simulasi Bayar Instan
                    </button>
                </form>
            @endif
        </div>

    </div>
</div>
