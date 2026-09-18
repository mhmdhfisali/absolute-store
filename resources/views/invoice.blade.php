<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $transaction->invoice_number }} - {{ config('app.name', 'Absolute Store') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div
                    class="h-9 w-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center font-black text-white text-base shadow-lg shadow-indigo-600/30">
                    AS
                </div>
                <div>
                    <div class="text-sm font-black tracking-wider text-white">ABSOLUTE<span
                            class="text-indigo-400">STORE</span></div>
                    <div class="text-[9px] uppercase tracking-widest text-slate-500 font-bold">Billing Gateway</div>
                </div>
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

    <!-- Konten Polling Real-time Livewire Tracker -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10 space-y-6">
        @livewire('invoice-tracker', ['invoiceNumber' => $transaction->invoice_number])
    </main>

    <!-- Footer -->
    <footer class="mt-16 border-t border-slate-800 bg-[#070a11] py-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Absolute Store. Transaksi aman terlindungi enkripsi 256-bit.</p>
    </footer>

    @livewireScripts

    <!-- Alpine Timer Fallback Script -->
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
