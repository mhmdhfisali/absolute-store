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

        <!-- Tombol Simulasi Pembayaran (Hanya tampil jika unpaid) -->
        @if ($transaction->payment_status === 'unpaid')
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-[11px] text-slate-500 italic">
                    * Halaman ini otomatis mendeteksi ketika pembayaran sudah masuk.
                </span>
                <form action="{{ route('payment.simulate', $transaction->invoice_number) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded-xl bg-emerald-600/20 hover:bg-emerald-600 hover:text-white border border-emerald-500/30 text-emerald-400 font-bold text-xs transition">
                        Simulasi Bayar Instan
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
