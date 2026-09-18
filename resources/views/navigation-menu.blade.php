<nav class="bg-[#0b1120] border-b border-slate-800/80 sticky top-0 z-50" x-data="{ mobileMenuOpen: false, userDropdownOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Kiri: Logo & Navigasi Utama -->
            <div class="flex items-center gap-8">
                <a href="{{ Auth::user()?->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                    class="flex items-center gap-3 group">
                    <div
                        class="h-9 w-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center font-black text-white text-base shadow-lg shadow-indigo-600/30">
                        AS
                    </div>
                    <div>
                        <div class="text-sm font-black tracking-wider text-white">ABSOLUTE<span
                                class="text-indigo-400">STORE</span></div>
                        <div class="text-[9px] uppercase tracking-widest text-slate-500 font-bold">
                            {{ Auth::user()?->isAdmin() ? 'Admin Console' : 'Member Portal' }}
                        </div>
                    </div>
                </a>

                <!-- Desktop Menu Links -->
                <div class="hidden md:flex items-center gap-1.5">
                    @if (Auth::user()?->isAdmin())
                        {{-- MENU KHUSUS ADMIN & SUPERADMIN --}}
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            Kelola Produk
                        </a>
                        <a href="{{ route('admin.promocodes.index') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.promocodes.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            Kode Promo
                        </a>
                        <a href="{{ route('admin.payments.index') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            Metode Bayar
                        </a>
                        <a href="{{ route('admin.promotions.index') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.promotions.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            Banner & Promo
                        </a>

                        @if (Auth::user()?->isSuperAdmin())
                            <a href="{{ route('admin.users.index') }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                                Pengguna
                            </a>
                        @endif

                        <a href="{{ route('order.tracking') }}" target="_blank"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                            Lacak ↗
                        </a>
                        <a href="{{ route('home') }}" target="_blank"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                            Lihat Toko ↗
                        </a>
                    @else
                        {{-- MENU KHUSUS USER / MEMBER BIASA (HANYA YANG PENTING) --}}
                        <a href="{{ route('dashboard') }}"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            Pesanan Saya
                        </a>
                        <a href="{{ route('home') }}"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                            Katalog Top Up
                        </a>
                        <a href="{{ route('order.tracking') }}"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                            Lacak Pesanan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Kanan: Profil & Dropdown Akun -->
            <div class="hidden sm:flex items-center gap-3 relative">
                <div class="relative" @click.away="userDropdownOpen = false">
                    <button type="button" @click="userDropdownOpen = !userDropdownOpen"
                        class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs transition">
                        <div
                            class="h-6 w-6 rounded-lg bg-indigo-600 text-white font-bold flex items-center justify-center text-[10px]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="text-left">
                            <span class="block text-slate-200 font-bold leading-tight">{{ Auth::user()->name }}</span>
                            <span
                                class="block text-[9px] uppercase tracking-wider font-bold {{ Auth::user()?->isAdmin() ? 'text-purple-400' : 'text-slate-500' }}">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition" :class="{ 'rotate-180': userDropdownOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Konten Dropdown Profil -->
                    <div x-show="userDropdownOpen" style="display: none;"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-800 bg-[#0a0f1d] shadow-2xl p-2 space-y-1 z-50">

                        <div class="px-3 py-2 border-b border-slate-800/80">
                            <span class="block text-xs font-bold text-white truncate">{{ Auth::user()->name }}</span>
                            <span
                                class="block text-[10px] text-slate-400 truncate font-mono">{{ Auth::user()->email }}</span>
                        </div>

                        <a href="{{ route('user.profile.show') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Pengaturan Profil & Sandi
                        </a>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tombol Mobile Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 hover:text-white focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="mobileMenuOpen" style="display: none;"
        class="sm:hidden border-t border-slate-800 bg-[#080d19] px-4 pt-3 pb-4 space-y-1">

        @if (Auth::user()?->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
                class="block px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="block px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Kelola Produk
            </a>
            <a href="{{ route('admin.promocodes.index') }}"
                class="block px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.promocodes.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Kode Promo
            </a>
            <a href="{{ route('admin.payments.index') }}"
                class="block px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Metode Bayar
            </a>
            <a href="{{ route('admin.promotions.index') }}"
                class="block px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.promotions.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Banner & Promo
            </a>

            @if (Auth::user()?->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}"
                    class="block px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Kelola Pengguna & Role
                </a>
            @endif
        @else
            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                Pesanan Saya
            </a>
            <a href="{{ route('home') }}"
                class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white">
                Katalog Top Up
            </a>
            <a href="{{ route('order.tracking') }}"
                class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white">
                Lacak Pesanan
            </a>
        @endif

        <a href="{{ route('user.profile.show') }}"
            class="block px-3 py-2 rounded-lg text-xs font-bold text-indigo-400 hover:bg-slate-800">
            Pengaturan Akun & Profil
        </a>

        <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span
                    class="h-2 w-2 rounded-full {{ Auth::user()?->isAdmin() ? 'bg-purple-400' : 'bg-emerald-400' }}"></span>
                <span>{{ Auth::user()?->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit" class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-400 text-xs font-bold">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
