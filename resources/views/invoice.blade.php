<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $transaction->invoice_number }} - {{ config('app.name', 'Absolute Store') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#0b0f19] text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white"
    x-data="invoiceHandler('{{ $transaction->created_at->addMinutes(15)->toIso8601String() }}', '{{ $transaction->payment_status }}')">

    <!-- Ambient Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div
            class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-tr from-indigo-600/20 via-purple-600/20 to-pink-500/20 blur-[130px] rounded-full">
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 border-b border-slate-800/80 bg-[#0b0f19]/80 backdrop-blur-xl">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}">
                <x-application-mark />
            </a>

            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </header>

    <!-- Konten Invoice -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10 space-y-6">

        <!-- Status Header Card -->
        <div
            class="relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl text-center space-y-3">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                {{ $transaction->payment_status === 'paid'
                    ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                    : ($transaction->payment_status === 'expired'
                        ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20'
                        : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                <span
                    class="h-2 w-2 rounded-full {{ $transaction->payment_status === 'paid' ? 'bg-emerald-400' : ($transaction->payment_status === 'expired' ? 'bg-rose-400' : 'bg-amber-400 animate-ping') }}"></span>
                Status: {{ strtoupper($transaction->payment_status) }}
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Invoice
                #{{ $transaction->invoice_number }}</h1>
            <p class="text-xs text-slate-400">Selesaikan pembayaran sebelum batas waktu berakhir agar pesanan diproses
                otomatis.</p>

            @if ($transaction->payment_status === 'unpaid')
                <!-- Countdown Timer -->
                <div class="pt-2 flex justify-center items-center gap-3 font-mono text-sm font-bold">
                    <span class="text-slate-400 font-sans text-xs">Sisa Waktu:</span>
                    <div
                        class="flex items-center gap-1.5 bg-slate-950/80 px-4 py-1.5 rounded-xl border border-slate-800 text-rose-400">
                        <span x-text="countdown.minutes">15</span>:<span x-text="countdown.seconds">00</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom Kiri: Barcode QRIS / Instruksi -->
            <div
                class="rounded-3xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md flex flex-col items-center justify-between text-center">
                @if ($transaction->payment_status === 'paid')
                    <div class="my-auto space-y-3">
                        <div
                            class="h-20 w-20 mx-auto rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white">Pembayaran Sukses</h2>
                        <p class="text-xs text-slate-400">Pesanan telah diteruskan ke sistem. Cek SN / token di rincian
                            order.</p>
                    </div>
                @elseif($transaction->payment_status === 'expired')
                    <div class="my-auto space-y-3">
                        <div
                            class="h-20 w-20 mx-auto rounded-full bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white">Invoice Kedaluwarsa</h2>
                        <p class="text-xs text-slate-400">Batas waktu telah habis. Silakan buat pesanan baru.</p>
                    </div>
                @else
                    <div class="space-y-4 w-full flex flex-col items-center">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Scan QRIS untuk
                            Bayar</span>

                        <!-- QR Code Generator -->
                        <div
                            class="p-3 bg-white rounded-2xl shadow-xl shadow-black/40 inline-block border-2 border-slate-700">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode('00020101021226590014ID.LINKAJA.WWW01189360091100213038840215' . $transaction->invoice_number) }}"
                                alt="QRIS Barcode" class="h-44 w-44 object-contain">
                        </div>

                        <p class="text-[11px] text-slate-400 max-w-xs">
                            Mendukung BCA, Mandiri, BRI, BNI, Dana, GoPay, OVO, ShopeePay, dan seluruh perbankan
                            berstandar QRIS.
                        </p>
                    </div>

                    <!-- Tombol Simulasi Pembayaran (Khusus Dev) -->
                    <div class="mt-4 pt-4 border-t border-slate-800/80 w-full">
                        <form action="{{ route('payment.simulate', $transaction->invoice_number) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500 hover:text-white border border-emerald-500/30 text-emerald-400 text-xs font-bold transition">
                                Simulasi Bayar Berhasil (Dev Mode)
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Kolom Kanan: Rincian Tagihan -->
            <div
                class="rounded-3xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md flex flex-col justify-between space-y-4">
                <div>
                    <h2
                        class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-3 border-b border-slate-800">
                        Rincian Pesanan
                    </h2>

                    <dl class="divide-y divide-slate-800/60 text-xs">
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-400">Layanan</dt>
                            <dd class="font-bold text-white text-right">{{ $transaction->productItem->product->name }}
                            </dd>
                        </div>
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-400">Item / SKU</dt>
                            <dd class="font-semibold text-indigo-400 text-right">{{ $transaction->productItem->name }}
                            </dd>
                        </div>
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-400">User ID / Tujuan</dt>
                            <dd class="font-mono font-bold text-white text-right">
                                {{ $transaction->target_account }}
                                @if ($transaction->target_zone)
                                    ({{ $transaction->target_zone }})
                                @endif
                            </dd>
                        </div>
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-400">Metode Bayar</dt>
                            <dd class="font-semibold text-slate-300 text-right">{{ $transaction->paymentMethod->name }}
                            </dd>
                        </div>
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-400">Biaya Admin</dt>
                            <dd class="text-slate-300 text-right">Rp
                                {{ number_format($transaction->fee_amount, 0, ',', '.') }}</dd>
                        </div>
                        @if ($transaction->serial_number)
                            <div class="py-2.5 flex justify-between bg-emerald-950/20 px-2 rounded-lg mt-1">
                                <dt class="text-emerald-400 font-bold">SN / Kode Voucher</dt>
                                <dd class="font-mono font-bold text-emerald-300 text-right select-all">
                                    {{ $transaction->serial_number }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Total Bayar Box -->
                <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                    <span class="block text-[10px] uppercase tracking-wider text-slate-500">Total Yang Harus
                        Dibayar</span>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-2xl font-black text-emerald-400">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                        <button
                            @click="navigator.clipboard.writeText('{{ $transaction->total_amount }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            x-data="{ copied: false }"
                            class="px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-900 text-[11px] font-semibold text-slate-300 hover:text-white transition">
                            <span x-show="!copied">Salin</span>
                            <span x-show="copied" class="text-emerald-400" style="display:none;">Tersalin!</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-16 border-t border-slate-800 bg-[#070a11] py-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Absolute Store. Transaksi aman terlindungi enkripsi 256-bit.</p>
    </footer>

    <!-- Alpine Handler Script -->
    <script>
        function invoiceHandler(expiryTime, initialStatus) {
            return {
                status: initialStatus,
                countdown: {
                    minutes: '00',
                    seconds: '00'
                },
                init() {
                    if (this.status !== 'unpaid') return;

                    const end = new Date(expiryTime).getTime();

                    const interval = setInterval(() => {
                        const now = new Date().getTime();
                        const distance = end - now;

                        if (distance <= 0) {
                            clearInterval(interval);
                            this.countdown = {
                                minutes: '00',
                                seconds: '00'
                            };
                            location.reload();
                            return;
                        }

                        const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const s = Math.floor((distance % (1000 * 60)) / 1000);

                        this.countdown.minutes = m < 10 ? '0' + m : m;
                        this.countdown.seconds = s < 10 ? '0' + s : s;
                    }, 1000);
                }
            }
        }
    </script>
</body>

</html>
