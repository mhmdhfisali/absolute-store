<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Absolute Store') }} - Console</title>

    <!-- Skrip Inisialisasi Tema Instan Sebelum Halaman Render (Mencegah Layar Berkedip / Anti-Flicker) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- ULTRA MODERN GLOBAL STYLES, GLASSMORPHISM & SHIMMER -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        html,
        body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transition: background-color 0.25s cubic-bezier(0.4, 0, 0.2, 1), color 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Obsidian Dark Mode (#080C14 / #0F172A) */
        html.dark body {
            background-color: #080c14 !important;
            color: #f8fafc !important;
        }

        html.dark aside,
        html.dark header,
        html.dark .card {
            background-color: rgba(15, 23, 42, 0.75) !important;
            border-color: rgba(30, 41, 59, 0.8) !important;
            color: #f8fafc !important;
        }

        html.dark .form-input-theme,
        html.dark .table-head-theme {
            background-color: rgba(8, 12, 20, 0.85) !important;
            border-color: #1e293b !important;
            color: #ffffff !important;
        }

        html.dark .table-head-theme {
            color: #94a3b8 !important;
        }

        html.dark table tbody tr {
            border-color: #1e293b !important;
        }

        html.dark table tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.45) !important;
        }

        /* Slate Light Mode (#F8FAFC / #F1F5F9) */
        html:not(.dark) body {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }

        html:not(.dark) aside,
        html:not(.dark) header,
        html:not(.dark) .card {
            background-color: rgba(255, 255, 255, 0.85) !important;
            border-color: rgba(226, 232, 240, 0.8) !important;
            color: #0f172a !important;
        }

        html:not(.dark) .form-input-theme,
        html:not(.dark) .table-head-theme {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        html:not(.dark) .table-head-theme {
            color: #64748b !important;
        }

        html:not(.dark) table tbody tr {
            border-color: #f1f5f9 !important;
        }

        html:not(.dark) table tbody tr:hover {
            background-color: #f1f5f9 !important;
        }

        /* Shimmer Loading Animation */
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            position: relative;
            overflow: hidden;
        }

        .animate-shimmer::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            transform: translateX(-100%);
            background-image: linear-gradient(90deg,
                    rgba(255, 255, 255, 0) 0,
                    rgba(255, 255, 255, 0.08) 20%,
                    rgba(255, 255, 255, 0.18) 60%,
                    rgba(255, 255, 255, 0));
            animation: shimmer 2s infinite;
            content: '';
        }

        /* Custom Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 9999px;
        }

        html.dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
</head>

<body
    class="antialiased transition-colors duration-200 overflow-x-hidden max-w-full selection:bg-indigo-500 selection:text-white"
    x-data="{
        sidebarOpen: (window.innerWidth >= 1024),
        isDark: document.documentElement.classList.contains('dark'),
        userMenuOpen: false,
        soundEnabled: true,
        toggleSidebar() {
            this.playSoftClick();
            this.sidebarOpen = !this.sidebarOpen;
        },
        toggleTheme() {
            this.playSoftClick();
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        },
        playSoftClick() {
            if (!this.soundEnabled) return;
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(650, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(850, ctx.currentTime + 0.03);
                gain.gain.setValueAtTime(0.04, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.03);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.03);
            } catch (e) {}
        },
        playSuccessChime() {
            if (!this.soundEnabled) return;
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const notes = [523.25, 659.25, 783.99, 1046.50];
                notes.forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + (idx * 0.07));
                    gain.gain.setValueAtTime(0.05, ctx.currentTime + (idx * 0.07));
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + (idx * 0.07) + 0.28);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(ctx.currentTime + (idx * 0.07));
                    osc.stop(ctx.currentTime + (idx * 0.07) + 0.28);
                });
            } catch (e) {}
        }
    }" @play-click.window="playSoftClick()" @play-chime.window="playSuccessChime()">

    <!-- Isolated Layout Container with Full Screen Bounds -->
    <div
        class="h-screen flex relative transition-colors duration-200 overflow-hidden w-full bg-slate-50 dark:bg-[#080c14]">

        <!-- Mobile Backdrop Overlay -->
        <div x-show="sidebarOpen" @click="toggleSidebar()"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
            class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-30 lg:hidden" style="display: none;"></div>

        <!-- ================= ULTRA MODERN STICKY SIDEBAR ================= -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0"
            class="w-64 shrink-0 h-screen sticky top-0 z-40 border-r flex flex-col justify-between shadow-2xl transition-all duration-300 fixed lg:sticky bg-white/95 dark:bg-[#080c14]/95 backdrop-blur-2xl border-slate-200/80 dark:border-slate-800/80 overflow-y-auto">

            <div>
                <!-- Brand Logo & Header -->
                <div
                    class="h-16 flex items-center justify-between px-5 border-b border-slate-200/80 dark:border-slate-800/80 shrink-0">
                    <a href="{{ Auth::user()?->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                        @click="playSoftClick()" class="flex items-center gap-3 group">
                        <div
                            class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 p-0.5 shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-200 shrink-0">
                            <div
                                class="h-full w-full bg-slate-950 rounded-[10px] flex items-center justify-center font-black text-white text-xs tracking-wider">
                                AS
                            </div>
                        </div>
                        <div>
                            <div
                                class="text-sm font-extrabold tracking-wider bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                                ABSOLUTE<span class="text-indigo-600 dark:text-cyan-400">STORE</span>
                            </div>
                            <div
                                class="text-[9px] uppercase tracking-widest text-slate-400 dark:text-slate-500 font-bold flex items-center gap-1.5">
                                <span>{{ Auth::user()?->isAdmin() ? 'Command Center' : 'Member Portal' }}</span>
                                <span class="h-1 w-1 rounded-full bg-cyan-500"></span>
                            </div>
                        </div>
                    </a>

                    <button type="button" @click="toggleSidebar()"
                        class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-white p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Live Supplier Status Indicator Pill -->
                <div class="px-4 pt-4 pb-2">
                    <div
                        class="px-3 py-1.5 rounded-xl border border-emerald-500/20 bg-emerald-500/5 dark:bg-emerald-950/20 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span
                                class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">PPOB
                                Live Sync</span>
                        </div>
                        <span class="text-[9px] font-mono font-bold text-slate-400 dark:text-slate-500">Digiflazz</span>
                    </div>
                </div>

                <!-- Navigation Links (Completely De-duplicated & Clearly Categorized) -->
                <div class="px-3 py-3 space-y-6">
                    @if (Auth::user()?->isAdmin())
                        <!-- Section 1: Utama -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Utama</span>
                            <div class="space-y-1">
                                <a href="{{ route('admin.dashboard') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                            </div>
                        </div>

                        <!-- Section 2: Produk & Stok -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Produk
                                & Stok</span>
                            <div class="space-y-1">
                                <a href="{{ route('admin.products.index') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.products.*') || request()->routeIs('barang.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-violet-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span>Katalog & Produk</span>
                                </a>

                                <a href="{{ route('admin.sku-management') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.sku-management') || request()->routeIs('sku.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span>Master SKU & Varian</span>
                                </a>

                                <a href="{{ route('admin.supplier-management') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.supplier-management') || request()->routeIs('supplier.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span>Supplier & Vendor PPOB</span>
                                </a>
                            </div>
                        </div>

                        <!-- Section 3: Keuangan & Transaksi -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Keuangan</span>
                            <div class="space-y-1">
                                <a href="{{ route('admin.financial-ledger') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.financial-ledger') || request()->routeIs('mutasi.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Ledger Keuangan & Rekonsiliasi</span>
                                </a>

                                <a href="{{ route('admin.customer-service') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.customer-service') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>Dispute & Layanan CS</span>
                                </a>

                                <a href="{{ route('admin.payments.index') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.payments.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <span>Metode Pembayaran</span>
                                </a>

                                <a href="{{ route('admin.promocodes.index') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.promocodes.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-pink-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span>Kupon Promo & Diskon</span>
                                </a>
                            </div>
                        </div>

                        <!-- Section 4: Pengguna -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Pengguna</span>
                            <div class="space-y-1">
                                <a href="{{ route('admin.user-management') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.user-management') || request()->routeIs('pelanggan.*') || request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>Manajemen Pengguna (RBAC)</span>
                                </a>

                                <a href="{{ route('admin.reviews.index') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.reviews.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-amber-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    <span>Moderasi Ulasan</span>
                                </a>
                            </div>
                        </div>

                        <!-- Section 5: Sistem -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Sistem</span>
                            <div class="space-y-1">
                                <a href="{{ route('admin.diagnostics.index') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.diagnostics.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                    <span>Diagnostik Sistem & API</span>
                                </a>

                                <a href="{{ route('admin.audit-logs-viewer') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.audit-logs-viewer') || request()->routeIs('admin.audit-logs.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>Audit Log & Keamanan</span>
                                </a>

                                <a href="{{ route('admin.system-settings') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.system-settings') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Pengaturan Sistem</span>
                                </a>

                                <a href="{{ route('admin.promotions.index') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.promotions.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-amber-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                    <span>Banner & Pengumuman</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Member Portal Area -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Member
                                Portal</span>
                            <div class="space-y-1">
                                <a href="{{ route('dashboard') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300' }}">
                                    <svg class="w-4 h-4 shrink-0 text-violet-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span>Dashboard & Pesanan</span>
                                </a>
                                <a href="{{ route('home') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300 transition">
                                    <svg class="w-4 h-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span>Katalog Top Up</span>
                                </a>
                                <a href="{{ route('user.profile.show') }}" @click="playSoftClick()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800/60 text-slate-600 dark:text-slate-300 transition {{ request()->routeIs('user.profile.*') ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/25' : '' }}">
                                    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Pengaturan Akun</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Sidebar: User Mini Info -->
            <div
                class="p-3.5 border-t border-slate-200/80 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/40 shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="relative p-0.5 rounded-xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 shrink-0 shadow-md">
                        <div
                            class="h-8 w-8 rounded-[10px] bg-slate-950 flex items-center justify-center text-white font-black text-xs">
                            {{ strtoupper(substr(Auth::user()?->name ?? 'User', 0, 2)) }}
                        </div>
                    </div>
                    <div class="overflow-hidden flex-1">
                        <span
                            class="block text-xs font-bold truncate text-slate-800 dark:text-slate-200">{{ Auth::user()?->name ?? 'Guest' }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            <span
                                class="block text-[10px] font-mono font-bold text-indigo-600 dark:text-indigo-400 truncate">
                                Rp {{ number_format(Auth::user()?->balance ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ================= KONTEN UTAMA KANAN (INDEPENDENT SCROLL) ================= -->
        <div
            class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto transition-colors duration-200 relative overflow-x-hidden">

            <!-- Header Top Bar (Glassmorphism + Dynamic Accent) -->
            <header
                class="h-16 border-b border-slate-200/70 dark:border-slate-800/70 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 transition-colors duration-200 w-full bg-white/85 dark:bg-[#080c14]/85 backdrop-blur-2xl shadow-xs dark:shadow-none shrink-0">

                <!-- Kiri: Toggle Sidebar & Judul -->
                <div class="flex items-center gap-3.5">
                    <button type="button" @click="toggleSidebar()"
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition cursor-pointer shadow-xs"
                        title="Buka / Tutup Navigasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-2">
                        <h1 class="text-xs font-black tracking-wider uppercase text-slate-800 dark:text-slate-200">
                            Absolute Store Console
                        </h1>
                    </div>
                </div>

                <!-- Kanan: Shortcut Storefront, Audio Synthesizer, Theme Mode, dan Profile Dropdown -->
                <div class="flex items-center gap-3">

                    <!-- Shortcut to Storefront -->
                    <a href="{{ route('home') }}" @click="playSoftClick()"
                        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500/50 text-xs font-bold text-slate-700 dark:text-slate-300 transition group shadow-xs">
                        <svg class="w-3.5 h-3.5 text-indigo-500 group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Storefront</span>
                    </a>

                    <!-- Language Switcher in Header (🇮🇩 ID / 🇺🇸 EN) -->
                    <div class="relative" x-data="{ langOpen: false }" @click.outside="langOpen = false">
                        <button type="button" @click="langOpen = !langOpen"
                            class="px-2.5 sm:px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/70 hover:text-cyan-400 text-xs font-bold text-slate-700 dark:text-slate-300 transition cursor-pointer shadow-xs flex items-center gap-1.5"
                            title="Ganti Bahasa / Switch Language">
                            <span x-text="($store.i18n && $store.i18n.locale === 'en') ? '🇺🇸 EN' : '🇮🇩 ID'">
                                {{ app()->getLocale() === 'en' ? '🇺🇸 EN' : '🇮🇩 ID' }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="langOpen" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-1 scale-95" x-cloak style="display: none;"
                            class="absolute right-0 top-full mt-2 w-36 rounded-xl bg-white dark:bg-[#0b1120] border border-slate-200 dark:border-slate-800 p-1.5 shadow-2xl backdrop-blur-xl z-50 text-xs font-bold">
                            <button type="button" @click="$store.i18n.setLocale('id'); langOpen = false;"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition text-left cursor-pointer"
                                :class="($store.i18n && $store.i18n.locale === 'id') ?
                                'text-indigo-600 dark:text-cyan-400 bg-slate-50 dark:bg-slate-800/60' :
                                'text-slate-600 dark:text-slate-300'">
                                <span class="flex items-center gap-2">
                                    <span>🇮🇩</span>
                                    <span>Indonesia</span>
                                </span>
                                <span x-show="$store.i18n && $store.i18n.locale === 'id'"
                                    class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                            </button>
                            <button type="button" @click="$store.i18n.setLocale('en'); langOpen = false;"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition text-left cursor-pointer"
                                :class="($store.i18n && $store.i18n.locale === 'en') ?
                                'text-indigo-600 dark:text-cyan-400 bg-slate-50 dark:bg-slate-800/60' :
                                'text-slate-600 dark:text-slate-300'">
                                <span class="flex items-center gap-2">
                                    <span>🇺🇸</span>
                                    <span>English</span>
                                </span>
                                <span x-show="$store.i18n && $store.i18n.locale === 'en'"
                                    class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Toggle Audio Synthesizer -->
                    <button type="button" @click="soundEnabled = !soundEnabled; if(soundEnabled) playSoftClick();"
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/70 hover:text-indigo-500 transition cursor-pointer shadow-xs"
                        :title="soundEnabled ? 'Audio Feedback Aktif' : 'Audio Feedback Senyap'">
                        <svg x-show="soundEnabled" class="w-4 h-4 text-indigo-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                        </svg>
                        <svg x-show="!soundEnabled" style="display: none;" class="w-4 h-4 text-slate-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                        </svg>
                    </button>

                    <!-- Toggle Light / Dark Mode -->
                    <button type="button" @click="toggleTheme()"
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/70 hover:text-amber-500 transition cursor-pointer shadow-xs"
                        :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'">
                        <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="!isDark" style="display: none;" class="w-4 h-4 text-amber-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <!-- In-App Notification Center -->
                    <x-notification-center />

                    <!-- Avatar Profile Dropdown Wrapper (Right-Aligned Anti-Overflow Flyout) -->
                    <div class="relative shrink-0" @click.outside="userMenuOpen = false">
                        <button type="button" @click="playSoftClick(); userMenuOpen = !userMenuOpen"
                            class="relative p-0.5 rounded-full bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-500 shadow-md shadow-indigo-500/20 ring-2 ring-indigo-500/20 hover:ring-indigo-500/50 transition-all focus:outline-none cursor-pointer">
                            <div
                                class="h-9 w-9 rounded-full bg-slate-950 flex items-center justify-center text-xs font-black text-white transition">
                                {{ strtoupper(substr(Auth::user()?->name ?? 'U', 0, 2)) }}
                            </div>
                        </button>

                        <!-- FLYOUT CARD PROFIL (w-72 Ultra Modern & Positioned to Prevent Overflow) -->
                        <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-2 scale-95" x-cloak style="display: none;"
                            class="absolute right-0 top-full mt-2.5 w-72 origin-top-right z-50 rounded-2xl bg-white/95 dark:bg-[#0b1120]/95 backdrop-blur-2xl p-2.5 shadow-2xl text-xs border border-slate-200/80 dark:border-slate-800/90 ring-1 ring-black/5 dark:ring-white/5">

                            <!-- Header Kartu Akun -->
                            <div
                                class="p-3 rounded-xl bg-slate-50/70 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800/80 mb-2">
                                <div class="flex items-center gap-2.5 mb-2">
                                    <div
                                        class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-md shrink-0">
                                        {{ strtoupper(substr(Auth::user()?->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div class="overflow-hidden flex-1">
                                        <span
                                            class="block font-bold truncate text-slate-800 dark:text-slate-100">{{ Auth::user()?->name ?? 'Guest User' }}</span>
                                        <span
                                            class="block text-[11px] font-mono text-slate-400 truncate">{{ Auth::user()?->email ?? 'guest@absolutestore.id' }}</span>
                                    </div>
                                </div>

                                <div
                                    class="pt-2 border-t border-slate-200/60 dark:border-slate-800/80 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Saldo
                                        Wallet</span>
                                    <span class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-xs">
                                        Rp {{ number_format(Auth::user()?->balance ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="mt-2 flex items-center gap-1.5">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ Auth::user()?->isAdmin() ? 'bg-purple-500/15 text-purple-600 dark:text-purple-300 border border-purple-500/30' : 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-300 border border-indigo-500/30' }}">
                                        {{ Auth::user()?->role ?? 'Guest' }}
                                    </span>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-200/70 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        {{ Auth::user()?->tier ?? 'Reguler' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Menu Link Akun -->
                            <div class="space-y-0.5">
                                <a href="{{ route('user.profile.show') }}" @click="playSoftClick()"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/60 transition font-medium text-slate-700 dark:text-slate-200">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Pengaturan Akun & Profil</span>
                                </a>

                                <button type="button" @click="toggleTheme()"
                                    class="w-full flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/60 transition font-medium text-slate-700 dark:text-slate-200 cursor-pointer">
                                    <span class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>Ganti Mode Tema</span>
                                    </span>
                                    <span class="text-[10px] font-bold uppercase font-mono text-slate-400"
                                        x-text="isDark ? 'Dark' : 'Light'"></span>
                                </button>
                            </div>

                            <!-- Tombol Logout -->
                            <div class="pt-1 mt-1 border-t border-slate-100 dark:border-slate-800/80">
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 font-bold transition text-left cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Keluar Akun</span>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Workspace Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>

    </div>

    <!-- Global Toast Notification & WhatsApp Widget -->
    <x-toast-notification />
    <x-whatsapp-widget />

    @livewireScripts
</body>

</html>
