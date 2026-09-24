<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Absolute Store') }} - {{ __('digital_ppob_store') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }

        html,
        body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            background-color: #080C14;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #080c14;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
</head>

<body class="min-h-screen bg-[#080C14] text-slate-100 antialiased selection:bg-cyan-500 selection:text-slate-950 overflow-x-hidden"
    x-data="{
        profileOpen: false,
        langMenuOpen: false
    }">

    <!-- Top Ambient Lights (Silicon Violet & Electric Cyan) -->
    <div class="fixed top-0 left-1/4 -translate-x-1/2 w-96 h-96 bg-violet-600/10 blur-[130px] pointer-events-none -z-10"></div>
    <div class="fixed top-0 right-1/4 translate-x-1/2 w-96 h-96 bg-cyan-500/10 blur-[130px] pointer-events-none -z-10"></div>

    <!-- ========================================================================= -->
    <!-- HEADER / NAVBAR ULTRA MODERN GLASSMORPHISM (SYNCED PROFILE FLYOUT)        -->
    <!-- ========================================================================= -->
    <header class="sticky top-0 z-40 border-b border-slate-800/80 bg-[#080C14]/85 backdrop-blur-2xl transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

            <!-- Brand Logo & Identity -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 p-0.5 shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-200 shrink-0">
                    <div class="h-full w-full bg-slate-950 rounded-[14px] flex items-center justify-center font-black text-white text-xs tracking-wider">
                        AS
                    </div>
                </div>
                <div>
                    <div class="text-base font-extrabold tracking-wider text-white">
                        ABSOLUTE<span class="text-cyan-400">STORE</span>
                    </div>
                    <div class="text-[9px] uppercase tracking-widest text-slate-400 font-bold flex items-center gap-1.5">
                        <span x-text="$store.i18n ? $store.i18n.t('digital_ppob_store', 'Digital & PPOB Store') : 'Digital & PPOB Store'">{{ __('digital_ppob_store') }}</span>
                        <span class="h-1 w-1 rounded-full bg-cyan-400 animate-pulse"></span>
                    </div>
                </div>
            </a>

            <!-- Center Navigation Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-6 text-xs font-bold text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-cyan-400 transition flex items-center gap-1.5 {{ request()->routeIs('home') ? 'text-cyan-400' : '' }}">
                    <span x-text="$store.i18n ? $store.i18n.t('home', 'Beranda') : 'Beranda'">{{ __('home') }}</span>
                </a>
                <a href="{{ route('order.tracking') }}" class="hover:text-cyan-400 transition flex items-center gap-1.5">
                    <span x-text="$store.i18n ? $store.i18n.t('tracking', 'Lacak Pesanan') : 'Lacak Pesanan'">{{ __('tracking') }}</span>
                </a>
                <a href="#catalog-grid" class="hover:text-cyan-400 transition flex items-center gap-1.5">
                    <span x-text="$store.i18n ? $store.i18n.t('catalog', 'Katalog Layanan') : 'Katalog Layanan'">{{ __('catalog') }}</span>
                </a>
            </nav>

            <!-- Right Actions: Language Switcher, Lacak Pesanan & Profile / Auth -->
            <div class="flex items-center gap-2.5 sm:gap-3">

                <!-- Dynamic Language Switcher (🇮🇩 ID / 🇺🇸 EN) -->
                <div class="relative" @click.outside="langMenuOpen = false">
                    <button type="button" @click="langMenuOpen = !langMenuOpen"
                            class="px-2.5 sm:px-3 py-2 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 text-xs font-bold text-slate-300 hover:text-white flex items-center gap-1.5 transition shadow-xs cursor-pointer"
                            title="Ganti Bahasa / Switch Language">
                        <span x-text="($store.i18n && $store.i18n.locale === 'en') ? '🇺🇸 EN' : '🇮🇩 ID'">
                            {{ app()->getLocale() === 'en' ? '🇺🇸 EN' : '🇮🇩 ID' }}
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Language Dropdown Menu -->
                    <div x-show="langMenuOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                         x-cloak
                         style="display: none;"
                         class="absolute right-0 top-full mt-2 w-36 rounded-xl bg-slate-900/95 border border-slate-800 p-1.5 shadow-2xl backdrop-blur-xl z-50 text-xs font-bold">
                        <button type="button"
                                @click="$store.i18n.setLocale('id'); langMenuOpen = false;"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-800 transition text-left cursor-pointer"
                                :class="($store.i18n && $store.i18n.locale === 'id') ? 'text-cyan-400 bg-slate-800/60' : 'text-slate-300'">
                            <span class="flex items-center gap-2">
                                <span>🇮🇩</span>
                                <span>Indonesia</span>
                            </span>
                            <span x-show="$store.i18n && $store.i18n.locale === 'id'" class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                        </button>
                        <button type="button"
                                @click="$store.i18n.setLocale('en'); langMenuOpen = false;"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-800 transition text-left cursor-pointer"
                                :class="($store.i18n && $store.i18n.locale === 'en') ? 'text-cyan-400 bg-slate-800/60' : 'text-slate-300'">
                            <span class="flex items-center gap-2">
                                <span>🇺🇸</span>
                                <span>English</span>
                            </span>
                            <span x-show="$store.i18n && $store.i18n.locale === 'en'" class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                        </button>
                    </div>
                </div>

                <!-- Quick Tracking Button -->
                <a href="{{ route('order.tracking') }}"
                   class="text-xs font-bold px-3 py-2 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-cyan-500/40 text-slate-300 hover:text-white transition-all duration-200 flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="hidden sm:inline" x-text="$store.i18n ? $store.i18n.t('track_order', 'Lacak Pesanan') : 'Lacak Pesanan'">{{ __('track_order') }}</span>
                </a>

                @auth
                    <!-- ================= UNIFIED PROFILE FLYOUT (REAL-TIME DB SYNC) ================= -->
                    <div class="relative shrink-0" @click.outside="profileOpen = false">
                        <button type="button" @click="profileOpen = !profileOpen"
                                class="relative p-0.5 rounded-full bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-500 shadow-md shadow-indigo-500/20 ring-2 ring-indigo-500/20 hover:ring-indigo-500/50 transition-all focus:outline-none cursor-pointer">
                            <div class="h-9 w-9 rounded-full bg-slate-950 flex items-center justify-center text-xs font-black text-white transition">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        </button>

                        <!-- FLYOUT CARD PROFIL (w-72 Ultra Modern & Presisi ke Kanan) -->
                        <div x-show="profileOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             x-cloak
                             style="display: none;"
                             class="absolute right-0 top-full mt-2.5 w-72 origin-top-right z-50 rounded-2xl bg-[#0b1120]/95 backdrop-blur-2xl p-2.5 shadow-2xl text-xs border border-slate-800 ring-1 ring-white/5">

                            <!-- Header Kartu Akun -->
                            <div class="p-3 rounded-xl bg-slate-900/70 border border-slate-800/80 mb-2">
                                <div class="flex items-center gap-2.5 mb-2">
                                    <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 to-cyan-500 flex items-center justify-center text-white font-black text-xs shadow-md shrink-0">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </div>
                                    <div class="overflow-hidden flex-1">
                                        <span class="block font-bold truncate text-slate-100">{{ Auth::user()->name }}</span>
                                        <span class="block text-[11px] font-mono text-slate-400 truncate">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-800 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" x-text="$store.i18n ? $store.i18n.t('wallet_balance', 'Saldo Wallet') : 'Saldo Wallet'">{{ __('wallet_balance') }}</span>
                                    <span class="font-mono font-black text-emerald-400 text-xs">
                                        Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="mt-2 flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ Auth::user()->isAdmin() ? 'bg-purple-500/15 text-purple-300 border border-purple-500/30' : 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30' }}">
                                        {{ Auth::user()->role }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-800 text-slate-300">
                                        {{ Auth::user()->tier }}
                                    </span>
                                </div>
                            </div>

                            <!-- Menu Pintas Cepat Akun -->
                            <div class="space-y-0.5">
                                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-800/80 transition font-medium text-slate-200">
                                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span x-text="$store.i18n ? $store.i18n.t('my_dashboard', 'Dashboard Saya') : 'Dashboard Saya'">{{ __('my_dashboard') }}</span>
                                </a>

                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-800/80 transition font-medium text-slate-200">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span x-text="$store.i18n ? $store.i18n.t('order_history', 'Riwayat Transaksi') : 'Riwayat Transaksi'">{{ __('order_history') }}</span>
                                </a>

                                <a href="{{ route('user.profile.show') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-800/80 transition font-medium text-slate-200">
                                    <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span x-text="$store.i18n ? $store.i18n.t('account_settings', 'Pengaturan Akun') : 'Pengaturan Akun'">{{ __('account_settings') }}</span>
                                </a>
                            </div>

                            <!-- Tombol Logout -->
                            <div class="pt-1 mt-1 border-t border-slate-800">
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-400 hover:bg-rose-500/10 font-bold transition text-left cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span x-text="$store.i18n ? $store.i18n.t('logout', 'Keluar Akun') : 'Keluar Akun'">{{ __('logout') }}</span>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @else
                    <!-- Guest Login & Register Buttons -->
                    <a href="{{ route('login') }}"
                       class="text-xs font-bold px-3.5 sm:px-4 py-2 text-slate-300 hover:text-white transition">
                        <span x-text="$store.i18n ? $store.i18n.t('login', 'Masuk Akun') : 'Masuk Akun'">{{ __('login') }}</span>
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-xs font-extrabold px-4 sm:px-5 py-2 rounded-xl bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 text-white shadow-lg shadow-indigo-600/25 hover:shadow-cyan-500/25 hover:scale-105 active:scale-95 transition-all duration-200">
                        <span x-text="$store.i18n ? $store.i18n.t('register', 'Daftar Akun') : 'Daftar Akun'">{{ __('register') }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Running Announcement Bar -->
    @if (isset($announcements) && $announcements->isNotEmpty())
        <div class="bg-indigo-950/50 border-b border-indigo-500/20 py-2.5 px-4 overflow-hidden relative backdrop-blur-md">
            <div class="max-w-7xl mx-auto flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-xs shrink-0">
                    <svg class="w-3 h-3 animate-pulse text-cyan-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" />
                    </svg>
                    Info Terkini
                </span>
                <div class="overflow-hidden w-full whitespace-nowrap">
                    <div class="inline-block animate-marquee text-xs font-medium text-indigo-200/90">
                        @foreach ($announcements as $ann)
                            <span class="mx-6">• {{ $ann->content }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Workspace: Interactive Livewire Storefront Catalog -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <livewire:storefront-catalog />
    </main>

    <!-- High-Converting Trust Badges Section -->
    <section class="border-t border-slate-800/80 bg-slate-950/60 py-12 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/70 hover:border-cyan-500/40 transition">
                    <div class="h-11 w-11 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-white" x-text="$store.i18n ? $store.i18n.t('instant_1_3_seconds', 'Instan 1-3 Detik') : 'Instan 1-3 Detik'">{{ __('instant_1_3_seconds') }}</h4>
                        <p class="text-[11px] text-slate-400" x-text="$store.i18n ? $store.i18n.t('instant_process_desc', 'Proses otomatis 24 jam nonstop') : 'Proses otomatis 24 jam nonstop'">{{ __('instant_process_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/70 hover:border-emerald-500/40 transition">
                    <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-white" x-text="$store.i18n ? $store.i18n.t('legal_and_secure', '100% Legal & Aman') : '100% Legal & Aman'">{{ __('legal_and_secure') }}</h4>
                        <p class="text-[11px] text-slate-400" x-text="$store.i18n ? $store.i18n.t('legal_desc', 'Jalur resmi distributor terpercaya') : 'Jalur resmi distributor terpercaya'">{{ __('legal_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/70 hover:border-indigo-500/40 transition">
                    <div class="h-11 w-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-white" x-text="$store.i18n ? $store.i18n.t('complete_payment', 'Metode Terlengkap') : 'Metode Terlengkap'">{{ __('complete_payment') }}</h4>
                        <p class="text-[11px] text-slate-400" x-text="$store.i18n ? $store.i18n.t('payment_desc', 'QRIS, VA Bank & E-Wallet') : 'QRIS, VA Bank & E-Wallet'">{{ __('payment_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/70 hover:border-violet-500/40 transition">
                    <div class="h-11 w-11 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-white" x-text="$store.i18n ? $store.i18n.t('cs_support', 'Dukungan CS 24/7') : 'Dukungan CS 24/7'">{{ __('cs_support') }}</h4>
                        <p class="text-[11px] text-slate-400" x-text="$store.i18n ? $store.i18n.t('cs_desc', 'Siap membantu via WhatsApp') : 'Siap membantu via WhatsApp'">{{ __('cs_desc') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer Modern -->
    <footer class="border-t border-slate-800/80 bg-[#06080e] py-12 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 to-cyan-400 p-0.5 flex items-center justify-center">
                        <div class="h-full w-full bg-slate-950 rounded-[10px] flex items-center justify-center font-black text-white text-xs">
                            AS
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-extrabold text-white tracking-wider">ABSOLUTE<span class="text-cyan-400">STORE</span></span>
                        <p class="text-[11px] text-slate-500">Platform Top Up Game & PPOB Otomatis Masa Kini</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 text-xs font-semibold text-slate-400">
                    <a href="{{ route('home') }}" class="hover:text-cyan-400 transition" x-text="$store.i18n ? $store.i18n.t('home', 'Beranda') : 'Beranda'">{{ __('home') }}</a>
                    <a href="{{ route('order.tracking') }}" class="hover:text-cyan-400 transition" x-text="$store.i18n ? $store.i18n.t('tracking', 'Lacak Pesanan') : 'Lacak Pesanan'">{{ __('tracking') }}</a>
                    <a href="#catalog-grid" class="hover:text-cyan-400 transition" x-text="$store.i18n ? $store.i18n.t('catalog', 'Katalog Layanan') : 'Katalog Layanan'">{{ __('catalog') }}</a>
                </div>
            </div>

            <div class="border-t border-slate-900 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Absolute Store Ecosystem. All Rights Reserved.</p>
                <p class="flex items-center gap-1.5 justify-center sm:justify-end">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sistem Server Operasional Normal (24/7)</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Global Toast Notification & WhatsApp Widget -->
    <x-toast-notification />
    <x-whatsapp-widget />

    @livewireScripts
</body>

</html>
