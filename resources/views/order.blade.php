<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Top Up {{ $product->name }} - {{ config('app.name', 'Absolute Store') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,600,700|inter:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Custom scrollbar for modern gaming look */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #080c15; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }
    </style>
</head>

<body class="min-h-screen bg-[#070b14] text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white"
    x-data="{ 
        gameSwitcherOpen: false, 
        searchQuery: '',
        allGames: {{ Js::from($allProducts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'category' => $p->category?->name ?? 'Game',
            'url' => route('order.show', $p->slug),
            'thumbnail' => $p->thumbnail ? Storage::url($p->thumbnail) : null,
        ])) }}
    }"
    @keydown.escape.window="gameSwitcherOpen = false"
    @keydown.window.ctrl.k.prevent="gameSwitcherOpen = true">

    <!-- Ambient Cyberpunk Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute -top-40 left-1/4 -translate-x-1/2 w-[650px] h-[350px] bg-gradient-to-tr from-indigo-600/15 via-purple-600/10 to-transparent blur-[140px] rounded-full"></div>
        <div class="absolute top-1/3 -right-20 w-[550px] h-[350px] bg-gradient-to-bl from-cyan-500/10 via-blue-600/10 to-transparent blur-[140px] rounded-full"></div>
        <div class="absolute -bottom-20 left-1/2 -translate-x-1/2 w-[800px] h-[250px] bg-indigo-950/20 blur-[150px] rounded-full"></div>
    </div>

    <!-- ================= 1. WORLD-CLASS STOREFRONT HEADER & NAVBAR ================= -->
    <header class="sticky top-0 z-40 border-b border-slate-800/80 bg-[#070b14]/85 backdrop-blur-2xl shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-3 sm:gap-6">
            
            <!-- Brand Logo & Live Status -->
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 p-0.5 shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform">
                        <div class="h-full w-full bg-[#0b0f19] rounded-[14px] flex items-center justify-center font-black text-white text-sm tracking-wider">
                            AS
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-base font-black tracking-wider text-white">
                            ABSOLUTE<span class="text-indigo-400">STORE</span>
                        </div>
                        <div class="text-[9px] uppercase tracking-widest text-slate-500 font-bold flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="text-emerald-400">PPOB & Top Up Otomatis</span>
                        </div>
                    </div>
                </a>

                <!-- Quick Game Switcher Trigger Button -->
                <button type="button" @click="gameSwitcherOpen = true"
                    class="hidden md:inline-flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-slate-900/90 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition group cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Pilih Game Lain...</span>
                    <kbd class="hidden lg:inline-block px-1.5 py-0.5 text-[9px] font-mono bg-slate-800 border border-slate-700 rounded text-slate-400">Ctrl+K</kbd>
                </button>
            </div>

            <!-- Center Status Pill: Speed Guarantee -->
            <div class="hidden lg:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/80 border border-slate-800/80 text-[11px] font-medium text-slate-400">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-slate-300 font-bold">Server H2H Siaga</span>
                <span class="text-slate-600">•</span>
                <span class="text-indigo-400 font-semibold">Proses Kilat 1-5 Detik</span>
            </div>

            <!-- Right Actions: Tracking, Notifications, Balance, Auth -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                
                <!-- Quick Link: Lacak Pesanan -->
                <a href="{{ route('order.tracking') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 text-xs font-semibold transition">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="hidden sm:inline">Lacak Pesanan</span>
                </a>

                @auth
                    <!-- In-App Notification Center -->
                    <x-notification-center />

                    <!-- Saldo Dompet Akun Chip -->
                    <a href="{{ route('dashboard') }}"
                        class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                    </a>

                    <!-- User Dashboard Button -->
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                        <span class="sm:hidden">Akun</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Masuk</span>
                    </a>
                @endauth

            </div>
        </div>
    </header>

    <!-- ================= 2. LIVE SOCIAL PROOF TICKER ================= -->
    <div class="border-b border-slate-800/60 bg-[#090d18] py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 font-bold uppercase text-[9px] tracking-wider">
                    ⚡ Live Activity
                </span>
                <span class="text-slate-300">
                    Lebih dari <strong class="text-white">12.500+</strong> pesanan sukses diproses bulan ini dengan garansi 100% legal.
                </span>
            </div>
            <div class="hidden sm:flex items-center gap-4 text-slate-500">
                <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Anti-Minus</span>
                <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Sertifikasi SSL 256-Bit</span>
                <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> CS WhatsApp 24/7</span>
            </div>
        </div>
    </div>

    <!-- ================= 3. KONTEN UTAMA CHECKOUT ================= -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        
        <!-- Breadcrumb Navigasi Modern -->
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Beranda</span>
            </a>
            <span class="text-slate-600">/</span>
            <span class="text-slate-400">{{ $product->category?->name ?? 'Game & Digital Goods' }}</span>
            <span class="text-slate-600">/</span>
            <span class="text-indigo-400 font-bold">{{ $product->name }}</span>
        </nav>

        <!-- Livewire Order Form Component -->
        <livewire:order-form :product="$product" />

        <!-- Produk Rekomendasi Lainnya (Quick Discover) -->
        <div class="pt-10 border-t border-slate-800/80">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                        <span>Layanan Game & Tagihan Terpopuler Lainnya</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 uppercase">Favorit</span>
                    </h3>
                    <p class="text-xs text-slate-500">Pilih game favorit lainnya untuk pengisian diamond instan tanpa antri.</p>
                </div>
                <a href="{{ route('home') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition flex items-center gap-1">
                    <span>Lihat Semua Katalog</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach ($allProducts->where('id', '!=', $product->id)->take(6) as $other)
                    <a href="{{ route('order.show', $other->slug) }}"
                        class="group p-3 rounded-2xl bg-[#0d1322] border border-slate-800/80 hover:border-indigo-500/40 hover:bg-slate-900 transition-all duration-300 flex flex-col items-center text-center shadow-lg hover:shadow-indigo-500/10 hover:-translate-y-1">
                        <div class="w-full aspect-video rounded-xl bg-slate-950 border border-slate-800 overflow-hidden mb-2.5">
                            @if ($other->thumbnail)
                                <img src="{{ Storage::url($other->thumbnail) }}" alt="{{ $other->name }}"
                                    class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            @else
                                <div class="h-full w-full bg-gradient-to-tr from-indigo-950 to-purple-950 flex items-center justify-center text-xs font-black text-indigo-400/40">
                                    {{ strtoupper(substr($other->name, 0, 3)) }}
                                </div>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-slate-200 group-hover:text-cyan-400 transition-colors line-clamp-1">
                            {{ $other->name }}
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium mt-0.5">
                            {{ $other->category?->name ?? 'Instant Delivery' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Livewire Product Reviews & Ratings -->
        <div class="pt-8">
            <livewire:product-reviews :product="$product" />
        </div>

    </main>

    <!-- ================= 4. QUICK GAME SWITCHER MODAL (CTRL+K) ================= -->
    <div x-show="gameSwitcherOpen" style="display: none;"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-md p-4 sm:p-6 md:p-20 flex items-start justify-center">
        
        <div @click.outside="gameSwitcherOpen = false"
            class="w-full max-w-2xl rounded-3xl bg-[#0f1523] border border-slate-700/80 shadow-2xl overflow-hidden mt-10">
            
            <!-- Search Input Header -->
            <div class="p-4 sm:p-5 border-b border-slate-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model="searchQuery"
                    placeholder="Ketik nama game atau voucher (Mobile Legends, Free Fire, PLN, dll)..."
                    class="w-full bg-transparent text-sm text-white placeholder-slate-500 focus:outline-none"
                    x-ref="searchInput"
                    x-init="$watch('gameSwitcherOpen', value => { if(value) setTimeout(() => $refs.searchInput.focus(), 100) })" />
                <button type="button" @click="gameSwitcherOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
                    ✕
                </button>
            </div>

            <!-- List Hasil Pencarian Game -->
            <div class="max-h-96 overflow-y-auto p-4 space-y-2">
                <template x-for="game in allGames.filter(g => g.name.toLowerCase().includes(searchQuery.toLowerCase()))" :key="game.id">
                    <a :href="game.url"
                        class="flex items-center justify-between p-3 rounded-2xl bg-slate-900/60 hover:bg-indigo-600/20 border border-slate-800 hover:border-indigo-500/40 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-14 rounded-xl bg-slate-950 overflow-hidden border border-slate-800 shrink-0">
                                <template x-if="game.thumbnail">
                                    <img :src="game.thumbnail" :alt="game.name" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!game.thumbnail">
                                    <div class="h-full w-full flex items-center justify-center font-black text-slate-600 text-xs" x-text="game.name.substring(0, 3).toUpperCase()"></div>
                                </template>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors" x-text="game.name"></div>
                                <div class="text-xs text-slate-500" x-text="game.category"></div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-400 group-hover:text-cyan-400 flex items-center gap-1">
                            Buka Layanan &rarr;
                        </span>
                    </a>
                </template>
            </div>

            <div class="p-3 bg-slate-950 border-t border-slate-800 text-center text-[11px] text-slate-500">
                Tekan <kbd class="px-1.5 py-0.5 rounded bg-slate-800 text-slate-300 font-mono">ESC</kbd> untuk menutup jendela ini
            </div>
        </div>
    </div>

    <!-- ================= 5. ENTERPRISE MEGA FOOTER ================= -->
    <footer class="mt-20 border-t border-slate-800/80 bg-[#060911] pt-14 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Kolom 1: Profil Brand & Keamanan -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 to-cyan-400 p-0.5 shadow-lg">
                            <div class="h-full w-full bg-slate-950 rounded-[10px] flex items-center justify-center font-black text-white text-xs">
                                AS
                            </div>
                        </div>
                        <div class="text-sm font-black tracking-wider text-white">
                            ABSOLUTE<span class="text-indigo-400">STORE</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Platform pembelian voucher game, top up diamond, dan produk PPOB paling terpercaya dengan sistem otomatisasi 24 jam nonstop tercepat di Indonesia.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[11px] font-semibold text-slate-300">
                            🔒 256-Bit SSL Secured
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[11px] font-semibold text-slate-300">
                            ⚡ Instant 3s
                        </span>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi Cepat -->
                <div class="space-y-3">
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-300">Navigasi Utama</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-indigo-400 transition">Katalog Game & Produk</a></li>
                        <li><a href="{{ route('order.tracking') }}" class="hover:text-indigo-400 transition">Lacak Pesanan / Cek Transaksi</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-indigo-400 transition">Dashboard & Member Portal</a></li>
                        <li><a href="{{ route('home') }}#panduan" class="hover:text-indigo-400 transition">Panduan & Cara Pembelian</a></li>
                        <li><a href="{{ route('home') }}#faq" class="hover:text-indigo-400 transition">Pusat Bantuan & FAQ</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Saluran Pembayaran Resmi -->
                <div class="space-y-3">
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-300">Metode Pembayaran</h4>
                    <p class="text-xs text-slate-400">Mendukung pembayaran instan dengan verifikasi otomatis detik itu juga:</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">QRIS (Semua Bank)</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">BCA VA</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">Mandiri VA</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">BRI VA</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">BNI VA</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">DANA</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">OVO</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">ShopeePay</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">Alfamart</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-[10px] font-mono font-bold text-slate-300">Indomaret</span>
                    </div>
                </div>

                <!-- Kolom 4: Hubungi Kami & Layanan CS -->
                <div class="space-y-3">
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-300">Bantuan 24 Jam Nonstop</h4>
                    <p class="text-xs text-slate-400">Punya pertanyaan, kendala isi saldo, atau ingin upgrade ke Akun VIP Distributor?</p>
                    <div class="space-y-2">
                        <a href="https://api.whatsapp.com/send?phone=6281234567890&text=Halo%20Admin%20Absolute%20Store,%20saya%20butuh%20bantuan"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600/10 hover:bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 font-bold text-xs transition w-full justify-center">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                            </svg>
                            <span>WhatsApp CS Resmi 24 Jam</span>
                        </a>
                        <div class="text-[11px] text-slate-500 text-center">
                            Waktu Operasional: Senin - Minggu (24 Jam Aktif)
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Subfooter -->
            <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Absolute Store') }}. Seluruh Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex items-center gap-4 text-[11px]">
                    <span class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Status Sistem: Normal (0 Incident)
                    </span>
                    <span>•</span>
                    <span>WIB (UTC+7)</span>
                </div>
            </div>

        </div>
    </footer>

    <!-- Global Toast Notification & WhatsApp Widget -->
    <x-toast-notification />
    <x-whatsapp-widget />

    @livewireScripts
</body>

</html>
