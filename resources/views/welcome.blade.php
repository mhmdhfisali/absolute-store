<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Absolute Store') }} - Top Up Game & Produk Digital Terlengkap</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white"
    x-data="{ activeCategory: 'all', searchQuery: '' }">

    <!-- Subtle Top Glow -->
    <div
        class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-72 bg-indigo-600/10 blur-[120px] pointer-events-none -z-10">
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 border-b border-slate-800 bg-slate-950/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}">
                <x-application-mark />
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('order.tracking') }}"
                    class="text-xs font-semibold px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white transition">
                    Lacak Pesanan
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="text-xs font-semibold px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-xs font-semibold px-4 py-2.5 text-slate-400 hover:text-white transition">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="text-xs font-bold px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/20 transition">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Running Announcement Bar -->
    @if (isset($announcements) && $announcements->isNotEmpty())
        <div class="bg-indigo-950/70 border-b border-indigo-500/20 py-2.5 px-4 overflow-hidden relative">
            <div class="max-w-7xl mx-auto flex items-center gap-3">
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-indigo-500 text-white shrink-0">
                    <svg class="w-3 h-3 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" />
                    </svg>
                    Info
                </span>
                <div class="overflow-hidden w-full whitespace-nowrap">
                    <div class="inline-block animate-marquee text-xs font-medium text-indigo-200">
                        @foreach ($announcements as $ann)
                            <span class="mx-6">• {{ $ann->content }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        <!-- Banner Slider (Jika Tersedia) -->
        @if (isset($banners) && $banners->isNotEmpty())
            <div class="relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/60 shadow-2xl"
                x-data="{ current: 0, total: {{ $banners->count() }} }" x-init="setInterval(() => { current = (current + 1) % total }, 5000)">
                <div class="flex transition-transform duration-700 ease-out"
                    :style="'transform: translateX(-' + (current * 100) + '%)'">
                    @foreach ($banners as $ban)
                        <div class="min-w-full aspect-[21/9] sm:aspect-[24/8] relative overflow-hidden bg-slate-950">
                            @if ($ban->target_url)
                                <a href="{{ $ban->target_url }}">
                            @endif
                            <img src="{{ $ban->image_url }}" alt="{{ $ban->title }}"
                                class="w-full h-full object-cover">
                            @if ($ban->target_url)
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Hero Promo Default Jika Belum Ada Banner -->
            <section
                class="relative overflow-hidden rounded-3xl border border-slate-800 bg-gradient-to-br from-indigo-950/50 via-slate-900 to-slate-950 p-8 sm:p-12 shadow-2xl">
                <div class="relative z-10 max-w-2xl space-y-4">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-indigo-500/30 bg-indigo-500/10 px-3.5 py-1 text-xs font-medium text-indigo-400">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Layanan Otomatis 24/7
                    </span>
                    <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Pusat Top Up Game & <span class="text-indigo-400">Layanan Digital</span> Tercepat
                    </h1>
                    <p class="text-sm sm:text-base text-slate-400 leading-relaxed">
                        Tersedia Diamond Game, Pulsa & Data, Token Listrik PLN, Saldo E-Wallet, Voucher Streaming,
                        hingga Game PC & Console dengan sistem pembayaran instan QRIS dan Virtual Account.
                    </p>
                </div>
            </section>
        @endif

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">
            <!-- Hero Promo Banner -->
            <section
                class="relative overflow-hidden rounded-3xl border border-slate-800 bg-gradient-to-br from-indigo-950/50 via-slate-900 to-slate-950 p-8 sm:p-12 shadow-2xl">
                <div class="relative z-10 max-w-2xl space-y-4">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-indigo-500/30 bg-indigo-500/10 px-3.5 py-1 text-xs font-medium text-indigo-400">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Layanan Otomatis 24/7
                    </span>
                    <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Pusat Top Up Game & <span class="text-indigo-400">Layanan Digital</span> Tercepat
                    </h1>
                    <p class="text-sm sm:text-base text-slate-400 leading-relaxed">
                        Tersedia Diamond Game, Pulsa & Data, Token Listrik PLN, Saldo E-Wallet, Voucher Streaming,
                        hingga
                        Game PC & Console dengan sistem pembayaran instan QRIS dan Virtual Account.
                    </p>
                </div>
            </section>

            <!-- Search Bar & Filter Kategori -->
            <section class="space-y-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-80">
                        <input type="text" x-model="searchQuery" placeholder="Cari game, pulsa, token, voucher..."
                            class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-2.5 pl-10 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- Tabs Filter Kategori -->
                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
                        <button @click="activeCategory = 'all'"
                            :class="activeCategory === 'all' ?
                                'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25 border-indigo-500' :
                                'bg-slate-900 text-slate-400 border-slate-800 hover:border-slate-700'"
                            class="px-4 py-2 rounded-xl border text-xs font-semibold whitespace-nowrap transition">
                            Semua Layanan
                        </button>
                        @foreach ($categories as $category)
                            <button @click="activeCategory = '{{ $category->slug }}'"
                                :class="activeCategory === '{{ $category->slug }}' ?
                                    'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25 border-indigo-500' :
                                    'bg-slate-900 text-slate-400 border-slate-800 hover:border-slate-700'"
                                class="px-4 py-2 rounded-xl border text-xs font-semibold whitespace-nowrap transition">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Grid Produk / Katalog -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @forelse($products as $product)
                        <div x-show="(activeCategory === 'all' || activeCategory === '{{ $product->category?->slug }}') && ('{{ strtolower($product->name) }}'.includes(searchQuery.toLowerCase()))"
                            x-transition
                            class="group flex flex-col justify-between rounded-2xl border border-slate-800 bg-slate-900/60 p-3.5 hover:border-indigo-500/50 hover:bg-slate-900 transition duration-200">
                            <a href="{{ route('order.show', $product->slug) }}" class="space-y-3 block">
                                <div
                                    class="aspect-video w-full overflow-hidden rounded-xl bg-slate-950 border border-slate-800/80 relative flex items-center justify-center">
                                    @if ($product->thumbnail_url)
                                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                                            class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <span
                                            class="font-extrabold text-sm text-indigo-400 tracking-wide">{{ $product->name }}</span>
                                    @endif

                                    @if ($product->category)
                                        <span
                                            class="absolute bottom-2 left-2 rounded-md bg-slate-950/90 px-1.5 py-0.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider border border-slate-800">
                                            {{ $product->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h3
                                        class="text-xs font-bold text-slate-200 group-hover:text-indigo-400 transition truncate">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-[10px] text-slate-500 mt-0.5">
                                        Mulai dari Rp
                                        {{ number_format($product->items->min('selling_price') ?? 10000, 0, ',', '.') }}
                                    </p>
                                </div>
                            </a>

                            <div class="mt-3 pt-2.5 border-t border-slate-800/60">
                                <a href="{{ route('order.show', $product->slug) }}"
                                    class="block w-full py-2 text-center text-xs font-bold text-indigo-400 bg-indigo-500/10 hover:bg-indigo-600 hover:text-white rounded-lg transition">
                                    Pilih Layanan
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <p class="text-sm text-slate-500 font-medium">Belum ada layanan atau game yang tersedia saat
                                ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="mt-20 border-t border-slate-800 bg-slate-950 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Absolute Store (AS). Solusi Top Up Game & Produk Digital Terpercaya.</p>
            </div>
        </footer>
</body>

</html>
