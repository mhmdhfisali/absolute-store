<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Pesanan - {{ config('app.name', 'Absolute Store') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-slate-950 text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Subtle Ambient Glow -->
    <div
        class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-5xl h-72 bg-indigo-600/10 blur-[130px] pointer-events-none -z-10">
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 border-b border-slate-800 bg-slate-950/80 backdrop-blur-xl">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}">
                <x-application-mark />
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Katalog Store
                </a>
            </div>
        </div>
    </header>

    <!-- Konten Utama Tracking -->
    <main class="max-w-2xl mx-auto px-4 py-12 space-y-8">
        <!-- Hero Title -->
        <div class="text-center space-y-2">
            <span
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Layanan Publik
            </span>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Lacak Status Pesanan</h1>
            <p class="text-xs sm:text-sm text-slate-400">
                Masukkan nomor invoice transaksi Anda untuk memeriksa progres pembayaran & pengiriman.
            </p>
        </div>

        <!-- Form Pencarian Invoice -->
        <form action="{{ route('order.tracking') }}" method="GET" class="relative">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="invoice" value="{{ $searchInvoice }}"
                        placeholder="Contoh: AS-A1B2C3D4E5" required
                        class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-3 pl-11 text-sm font-mono text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-inner">
                    <svg class="absolute left-4 top-3.5 h-4 w-4 text-slate-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <button type="submit"
                    class="rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-600/25 hover:brightness-110 active:scale-95 transition">
                    Cek Transaksi
                </button>
            </div>
        </form>

        <!-- Hasil Pencarian -->
        @if (!empty($searchInvoice))
            @if ($transaction)
                <!-- Kartu Status Transaksi -->
                <div
                    class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/60 backdrop-blur-xl shadow-2xl p-6 sm:p-8 space-y-6">

                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800/80">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nomor
                                Invoice</span>
                            <h2 class="text-xl font-black font-mono text-indigo-400">{{ $transaction->invoice_number }}
                            </h2>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                {{ $transaction->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <!-- Badge Bayar -->
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $transaction->payment_status === 'paid'
                                    ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                                    : ($transaction->payment_status === 'expired'
                                        ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20'
                                        : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                <span
                                    class="h-1.5 w-1.5 rounded-full mr-1.5 {{ $transaction->payment_status === 'paid' ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                                Bayar: {{ $transaction->payment_status }}
                            </span>

                            <!-- Badge Kirim -->
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $transaction->delivery_status === 'success' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-slate-800 text-slate-300' }}">
                                Kirim: {{ $transaction->delivery_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Rincian Produk & Akun -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4 space-y-1">
                            <span class="text-slate-400 text-[11px]">Item Layanan</span>
                            <p class="font-bold text-white text-sm">{{ $transaction->productItem->product->name }}</p>
                            <p class="text-indigo-400 font-semibold">{{ $transaction->productItem->name }}</p>
                        </div>

                        <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4 space-y-1">
                            <span class="text-slate-400 text-[11px]">Tujuan / Akun</span>
                            <p class="font-mono font-bold text-white text-sm">
                                {{ $transaction->target_account }}
                                @if ($transaction->target_zone)
                                    ({{ $transaction->target_zone }})
                                @endif
                            </p>
                            <p class="text-slate-400 font-mono">{{ $transaction->contact_email_or_phone }}</p>
                        </div>
                    </div>

                    <!-- Kotak Serial Number / Token PLN -->
                    @if ($transaction->serial_number)
                        <div
                            class="rounded-2xl border border-emerald-500/30 bg-emerald-950/20 p-4 space-y-1 text-center">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">Kode Voucher /
                                Serial Number (SN)</span>
                            <p
                                class="font-mono text-base sm:text-lg font-black text-emerald-300 select-all tracking-wider">
                                {{ $transaction->serial_number }}
                            </p>
                            <span class="text-[10px] text-slate-400 block">Simpan kode ini sebagai bukti transaksi
                                Anda.</span>
                        </div>
                    @endif

                    <!-- Total & Tombol Invoice -->
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-800/80">
                        <div>
                            <span class="text-[10px] uppercase text-slate-400 block">Total Transaksi</span>
                            <span class="text-xl font-black text-emerald-400">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('order.invoice', $transaction->invoice_number) }}"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold text-center transition">
                            Buka Halaman Invoice &rarr;
                        </a>
                    </div>
                </div>
            @else
                <!-- Hasil Tidak Ditemukan -->
                <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-10 text-center space-y-3">
                    <div
                        class="h-12 w-12 mx-auto rounded-full bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Invoice Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Nomor invoice <span class="font-mono text-slate-200 font-semibold">{{ $searchInvoice }}</span>
                        tidak terdaftar di sistem. Mohon periksa kembali karakter dan formatnya.
                    </p>
                </div>
            @endif
        @endif
    </main>

    <!-- Footer -->
    <footer class="mt-20 border-t border-slate-800 bg-slate-950 py-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Absolute Store. Layanan pengecekan pesanan real-time.</p>
    </footer>
</body>

</html>
