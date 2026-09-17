<nav class="bg-[#0b1120] border-b border-slate-800/80 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Left: Logo & Nav -->
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div
                        class="h-9 w-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center font-black text-white text-base shadow-lg shadow-indigo-600/30">
                        AS
                    </div>
                    <div>
                        <div class="text-sm font-black tracking-wider text-white">ABSOLUTE<span
                                class="text-indigo-400">STORE</span></div>
                        <div class="text-[9px] uppercase tracking-widest text-slate-500 font-bold">Admin Console</div>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-1.5">
                    <a href="{{ route('dashboard') }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        Kelola Produk
                    </a>
                    <a href="{{ route('admin.payments.index') }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        Metode Bayar
                    </a>
                    <a href="{{ route('admin.promotions.index') }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('admin.promotions.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        Banner & Promo
                    </a>
                    <a href="{{ route('order.tracking') }}" target="_blank"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                        Lacak Pesanan ↗
                    </a>
                    <a href="{{ route('home') }}" target="_blank"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                        Lihat Toko ↗
                    </a>
                </div>
            </div>

            <!-- Right: Admin Status & Logout -->
            <div class="flex items-center gap-3">
                <div
                    class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-slate-300 font-medium">{{ Auth::user()->name }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit"
                        class="px-3 py-1.5 rounded-xl border border-rose-500/20 bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-400 text-xs font-bold transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
