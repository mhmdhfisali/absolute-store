<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Absolute Store') }} - Console</title>

    <!-- Skrip Inisialisasi Tema Instan Sebelum Halaman Render -->
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
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- CSS PENGUNCI TEMA TERANG & GELAP (MUTLAK & PASTI BERUBAH) -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        /* ATURAN SAAT MODE GELAP (html.dark) */
        html.dark body {
            background-color: #080c15 !important;
            color: #f8fafc !important;
        }

        html.dark aside {
            background-color: #0d1322 !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }

        html.dark header {
            background-color: #0d1322 !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }

        html.dark .card {
            background-color: #0d1322 !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }

        html.dark .form-input-theme {
            background-color: #090e1b !important;
            border-color: #1e293b !important;
            color: #ffffff !important;
        }

        html.dark .table-head-theme {
            background-color: #090e1b !important;
            border-color: #1e293b !important;
            color: #94a3b8 !important;
        }

        html.dark table tbody tr {
            border-color: #1e293b !important;
        }

        html.dark table tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.4) !important;
        }

        /* ATURAN SAAT MODE TERANG (html:not(.dark)) */
        html:not(.dark) body {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }

        html:not(.dark) aside {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #0f172a !important;
        }

        html:not(.dark) header {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #0f172a !important;
        }

        html:not(.dark) .card {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #0f172a !important;
        }

        html:not(.dark) .form-input-theme {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        html:not(.dark) .table-head-theme {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #64748b !important;
        }

        html:not(.dark) table tbody tr {
            border-color: #f1f5f9 !important;
        }

        html:not(.dark) table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }

        html.dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
</head>

<body class="antialiased transition-colors duration-200" x-data="{
    sidebarOpen: (window.innerWidth >= 1024),
    isDark: document.documentElement.classList.contains('dark'),
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },
    toggleTheme() {
        this.isDark = !this.isDark;
        if (this.isDark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    }
}">

    <div class="min-h-screen flex relative transition-colors duration-200">

        <!-- ================= SIDEBAR NAVIGASI ================= -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-full opacity-0"
            class="w-64 border-r flex flex-col justify-between shrink-0 sticky top-0 h-screen z-40 shadow-xl transition-colors duration-200">

            <div>
                <!-- Brand Logo & Tutup Mobile -->
                <div class="h-16 flex items-center justify-between px-5 border-b">
                    <a href="{{ Auth::user()?->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                        class="flex items-center gap-3">
                        <div
                            class="h-9 w-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center font-black text-white text-sm shadow-md shrink-0">
                            AS
                        </div>
                        <div>
                            <div class="text-sm font-extrabold tracking-wider">ABSOLUTE<span
                                    class="text-indigo-600 dark:text-indigo-400">STORE</span></div>
                            <div
                                class="text-[9px] uppercase tracking-widest text-slate-400 dark:text-slate-500 font-bold">
                                {{ Auth::user()?->isAdmin() ? 'Control Panel' : 'Member Portal' }}
                            </div>
                        </div>
                    </a>

                    <button type="button" @click="toggleSidebar()"
                        class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Menu Item Links -->
                <div class="px-3 py-5 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)]">

                    @if (Auth::user()?->isAdmin())
                        <!-- Menu Utama -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Utama</span>
                            <div class="space-y-1">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                            </div>
                        </div>

                        <!-- Menu Manajemen -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Manajemen</span>
                            <div class="space-y-1">
                                <a href="{{ route('admin.products.index') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span>Katalog & SKU</span>
                                </a>

                                <a href="{{ route('admin.promocodes.index') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.promocodes.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span>Kode Promo</span>
                                </a>

                                <a href="{{ route('admin.payments.index') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <span>Metode Bayar</span>
                                </a>

                                <a href="{{ route('admin.promotions.index') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.promotions.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                    <span>Banner & Info</span>
                                </a>
                            </div>
                        </div>

                        <!-- Menu Superadmin -->
                        @if (Auth::user()?->isSuperAdmin())
                            <div>
                                <span
                                    class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Akses
                                    & Sistem</span>
                                <div class="space-y-1">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>Kelola Pengguna</span>
                                    </a>

                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        <span>Audit Logs</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Menu Member -->
                        <div>
                            <span
                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Member</span>
                            <div class="space-y-1">
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span>Dashboard & Pesanan</span>
                                </a>
                                <a href="{{ route('home') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800/60 transition">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span>Katalog Top Up</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Pintasan Web Publik -->
                    <div>
                        <span
                            class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 block mb-2">Toko</span>
                        <div class="space-y-1">
                            <a href="{{ route('order.tracking') }}" target="_blank"
                                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800/40 transition">
                                <span class="flex items-center gap-3">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Lacak Pesanan
                                </span>
                                <span class="text-[10px] text-slate-400">↗</span>
                            </a>
                            <a href="{{ route('home') }}" target="_blank"
                                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800/40 transition">
                                <span class="flex items-center gap-3">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Buka Toko
                                </span>
                                <span class="text-[10px] text-slate-400">↗</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Sidebar -->
            <div class="p-3 border-t">
                <div class="flex items-center gap-2.5">
                    <div
                        class="h-8 w-8 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center text-white font-bold text-xs shadow-md shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden flex-1">
                        <span class="block text-xs font-bold truncate">{{ Auth::user()->name }}</span>
                        <span
                            class="block text-[9px] uppercase font-mono font-bold text-purple-600 dark:text-purple-400 truncate">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ================= KONTEN UTAMA KANAN ================= -->
        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-200">

            <!-- Header Top Bar -->
            <header
                class="h-16 border-b px-4 sm:px-6 flex items-center justify-between sticky top-0 z-40 transition-colors duration-200">

                <!-- Kiri: Toggle Sidebar & Judul -->
                <div class="flex items-center gap-3">
                    <button type="button" @click="toggleSidebar()"
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer"
                        title="Buka/Tutup Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </button>

                    <h1 class="text-xs font-black tracking-wider uppercase">
                        @if (request()->routeIs('admin.dashboard'))
                            Dashboard Ringkasan
                        @elseif(request()->routeIs('admin.products.*'))
                            Katalog Produk
                        @elseif(request()->routeIs('admin.promocodes.*'))
                            Kupon Promo
                        @elseif(request()->routeIs('admin.payments.*'))
                            Metode Pembayaran
                        @elseif(request()->routeIs('admin.users.*'))
                            Pengguna & Role
                        @elseif(request()->routeIs('admin.audit-logs.*'))
                            Audit Activity Logs
                        @elseif(request()->routeIs('user.profile.*'))
                            Pengaturan Akun
                        @else
                            Area Pengguna
                        @endif
                    </h1>
                </div>

                <!-- Kanan: Toggle Mode & Avatar Profil -->
                <div class="flex items-center gap-3">

                    <!-- Quick Toggle Tema (Matahari / Bulan) -->
                    <button type="button" @click="toggleTheme()"
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:text-amber-500 transition cursor-pointer"
                        :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'">
                        <svg x-show="isDark" class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="!isDark" style="display: none;" class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <!-- Avatar Dropdown Wrapper -->
                    <div class="relative shrink-0" x-data="{ userMenuOpen: false }" @click.away="userMenuOpen = false">
                        <button type="button" @click="userMenuOpen = !userMenuOpen"
                            class="h-10 w-10 rounded-full ring-2 ring-indigo-500/30 hover:ring-indigo-500 bg-gradient-to-tr from-indigo-600 to-cyan-500 p-0.5 transition shadow-md flex items-center justify-center focus:outline-none cursor-pointer">
                            <div
                                class="h-full w-full rounded-full bg-white dark:bg-slate-900 flex items-center justify-center text-xs font-black transition">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        </button>

                        <!-- Flyout Dropdown -->
                        <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-1 scale-95" style="display: none;"
                            class="absolute right-0 top-full mt-2 w-64 rounded-2xl card p-2 shadow-2xl z-50 text-xs origin-top-right">

                            <!-- Info Akun -->
                            <div class="p-3 border-b border-slate-100 dark:border-slate-800 mb-1">
                                <span class="block font-bold truncate">{{ Auth::user()->name }}</span>
                                <span
                                    class="block text-[11px] font-mono text-slate-400 truncate">{{ Auth::user()->email }}</span>

                                <div class="mt-2 flex items-center gap-1.5">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ Auth::user()->isAdmin() ? 'bg-purple-500/10 text-purple-600 dark:text-purple-300 border border-purple-500/20' : 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 border border-indigo-500/20' }}">
                                        {{ Auth::user()->role }}
                                    </span>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                        {{ Auth::user()->tier ?? 'Member' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Menu Link Akun -->
                            <div class="space-y-0.5">
                                <a href="{{ route('user.profile.show') }}"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition font-medium">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>

                                <a href="{{ route('user.profile.show') }}"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition font-medium">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Pengaturan & Sandi</span>
                                </a>

                                <button type="button" @click="toggleTheme()"
                                    class="w-full flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition font-medium cursor-pointer">
                                    <span class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>Ganti Tema</span>
                                    </span>
                                    <span class="text-[10px] font-bold uppercase font-mono text-slate-400"
                                        x-text="isDark ? 'Dark' : 'Light'"></span>
                                </button>
                            </div>

                            <!-- Tombol Keluar -->
                            <div class="pt-1 mt-1 border-t border-slate-100 dark:border-slate-800">
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 font-bold transition text-left cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

    </div>

    @livewireScripts
</body>

</html>
