<div class="space-y-12 pb-16">

    <!-- ========================================================================= -->
    <!-- 1. HERO PROMO CAROUSEL & DYNAMIC BANNER SLIDER                           -->
    <!-- ========================================================================= -->
    <section class="relative" 
        x-data="{
            current: 0,
            total: {{ count($banners) > 0 ? count($banners) : 3 }},
            timer: null,
            init() {
                this.startAutoPlay();
            },
            startAutoPlay() {
                this.timer = setInterval(() => {
                    this.next();
                }, 4500);
            },
            stopAutoPlay() {
                if (this.timer) clearInterval(this.timer);
            },
            next() {
                this.current = (this.current + 1) % this.total;
            },
            prev() {
                this.current = (this.current - 1 + this.total) % this.total;
            },
            goTo(index) {
                this.current = index;
            }
        }"
        @mouseenter="stopAutoPlay()" 
        @mouseleave="startAutoPlay()">

        <!-- Slider Main Wrapper -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-2xl shadow-2xl shadow-indigo-950/30 group">

            <!-- Slides Track -->
            <div class="flex transition-transform duration-700 cubic-bezier(0.4, 0, 0.2, 1) w-full"
                 :style="'transform: translateX(-' + (current * 100) + '%)'">

                @if(count($banners) > 0)
                    @foreach($banners as $index => $banner)
                        <div class="min-w-full aspect-[21/9] sm:aspect-[24/8] lg:aspect-[26/8] relative overflow-hidden flex items-center justify-between p-6 sm:p-10 lg:p-14 select-none">
                            <!-- Background Image with Dark Vignette Gradient -->
                            <div class="absolute inset-0 z-0">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/70 to-transparent"></div>
                                <div class="absolute inset-0 bg-radial-at-t from-indigo-500/10 via-transparent to-black/60"></div>
                            </div>

                            <!-- Content Overlay -->
                            <div class="relative z-10 max-w-xl space-y-3 sm:space-y-4">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 backdrop-blur-md">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                                    </span>
                                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-cyan-400">Promo Eksklusif</span>
                                </div>

                                <h2 class="text-xl sm:text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight drop-shadow-md">
                                    {{ $banner->title }}
                                </h2>

                                <p class="text-xs sm:text-sm text-slate-300 line-clamp-2 leading-relaxed">
                                    Top up instan otomatis 24 jam dengan jaminan harga termurah se-Indonesia dan bonus loyalty point setiap transaksi.
                                </p>

                                <div class="pt-2">
                                    <a href="{{ $banner->target_url ?: '#catalog-grid' }}"
                                       class="relative inline-flex items-center gap-2.5 px-5 sm:px-6 py-2.5 sm:py-3 rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-indigo-600/30 hover:shadow-cyan-500/30 hover:scale-105 active:scale-95 transition-all duration-200 group/btn">
                                        <span>Sikat Promo Sekarang</span>
                                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- 3 Curated Modern Glassmorphism Fallback Banners -->
                    <!-- Slide 1: MLBB & Free Fire Flash Promo -->
                    <div class="min-w-full aspect-[21/9] sm:aspect-[24/8] lg:aspect-[26/8] relative overflow-hidden flex items-center justify-between p-6 sm:p-10 lg:p-14 select-none bg-gradient-to-br from-[#0c0f24] via-[#090d1a] to-[#04060d]">
                        <div class="absolute right-0 top-0 bottom-0 w-1/2 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-violet-600/20 via-cyan-500/10 to-transparent pointer-events-none"></div>
                        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 max-w-xl space-y-3 sm:space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 backdrop-blur-md">
                                <span class="h-2 w-2 rounded-full bg-violet-400 animate-pulse"></span>
                                <span class="text-[11px] font-black uppercase tracking-wider text-violet-300">Mega Diskon Top Up</span>
                            </div>

                            <h2 class="text-xl sm:text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight">
                                Top Up Diamond Game <br class="hidden sm:inline" />
                                <span class="bg-gradient-to-r from-violet-400 via-indigo-300 to-cyan-400 bg-clip-text text-transparent">Hemat Hingga 35%</span>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-300 max-w-md leading-relaxed">
                                Nikmati diskon kilat untuk Mobile Legends, Free Fire, Valorant, & Genshin Impact. Proses instan 1 detik masuk akun!
                            </p>

                            <div class="pt-2">
                                <a href="#catalog-grid"
                                   class="relative inline-flex items-center gap-2.5 px-5 sm:px-6 py-2.5 sm:py-3 rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-indigo-600/30 hover:shadow-cyan-500/30 hover:scale-105 active:scale-95 transition-all duration-200 group/btn">
                                    <span>Sikat Promo Sekarang</span>
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="hidden md:flex items-center justify-center pr-8">
                            <div class="relative w-48 h-48 lg:w-56 lg:h-56 rounded-3xl bg-gradient-to-tr from-violet-600/30 to-cyan-400/20 p-1 border border-white/10 shadow-2xl backdrop-blur-xl flex items-center justify-center transform rotate-3 hover:rotate-0 transition duration-500">
                                <div class="text-center space-y-2">
                                    <div class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">
                                        35%
                                    </div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        OFF ALL GAMES
                                    </div>
                                    <div class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold inline-block">
                                        Instant Delivery
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: PPOB & Token Listrik -->
                    <div class="min-w-full aspect-[21/9] sm:aspect-[24/8] lg:aspect-[26/8] relative overflow-hidden flex items-center justify-between p-6 sm:p-10 lg:p-14 select-none bg-gradient-to-br from-[#08151f] via-[#060e17] to-[#04060d]">
                        <div class="absolute right-0 top-0 bottom-0 w-1/2 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-cyan-600/20 via-emerald-500/10 to-transparent pointer-events-none"></div>

                        <div class="relative z-10 max-w-xl space-y-3 sm:space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/15 border border-cyan-500/30 backdrop-blur-md">
                                <span class="h-2 w-2 rounded-full bg-cyan-400 animate-pulse"></span>
                                <span class="text-[11px] font-black uppercase tracking-wider text-cyan-300">PPOB Otomatis 24 Jam</span>
                            </div>

                            <h2 class="text-xl sm:text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight">
                                Token Listrik PLN & Pulsa <br class="hidden sm:inline" />
                                <span class="bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Biaya Admin Rp 0 Tanpa Syarat</span>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-300 max-w-md leading-relaxed">
                                Bayar tagihan dan beli token listrik kapan pun dengan verifikasi instan via API Digiflazz terpercaya.
                            </p>

                            <div class="pt-2">
                                <a href="#catalog-grid"
                                   class="relative inline-flex items-center gap-2.5 px-5 sm:px-6 py-2.5 sm:py-3 rounded-2xl bg-gradient-to-r from-cyan-500 via-teal-600 to-emerald-500 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-cyan-600/30 hover:scale-105 active:scale-95 transition-all duration-200 group/btn">
                                    <span>Beli Token Sekarang</span>
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="hidden md:flex items-center justify-center pr-8">
                            <div class="relative w-48 h-48 lg:w-56 lg:h-56 rounded-3xl bg-gradient-to-tr from-cyan-500/30 to-emerald-500/20 p-1 border border-white/10 shadow-2xl backdrop-blur-xl flex items-center justify-center transform -rotate-3 hover:rotate-0 transition duration-500">
                                <div class="text-center space-y-2">
                                    <div class="text-3xl lg:text-4xl font-black text-white">
                                        ADMIN <span class="text-cyan-400">Rp 0</span>
                                    </div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        PLN • TELKOM • PDAM
                                    </div>
                                    <div class="px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] font-bold inline-block">
                                        Auto SN Masuk SMS/WA
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3: QRIS & E-Wallet Instant -->
                    <div class="min-w-full aspect-[21/9] sm:aspect-[24/8] lg:aspect-[26/8] relative overflow-hidden flex items-center justify-between p-6 sm:p-10 lg:p-14 select-none bg-gradient-to-br from-[#1b0d26] via-[#100717] to-[#04060d]">
                        <div class="absolute right-0 top-0 bottom-0 w-1/2 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-fuchsia-600/20 via-pink-500/10 to-transparent pointer-events-none"></div>

                        <div class="relative z-10 max-w-xl space-y-3 sm:space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-fuchsia-500/15 border border-fuchsia-500/30 backdrop-blur-md">
                                <span class="h-2 w-2 rounded-full bg-fuchsia-400 animate-pulse"></span>
                                <span class="text-[11px] font-black uppercase tracking-wider text-fuchsia-300">Gateway Tripay Resmi</span>
                            </div>

                            <h2 class="text-xl sm:text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight">
                                Bayar Cepat Serba QRIS <br class="hidden sm:inline" />
                                <span class="bg-gradient-to-r from-pink-400 via-rose-300 to-amber-300 bg-clip-text text-transparent">BCA, Mandiri, DANA, GoPay & ShopeePay</span>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-300 max-w-md leading-relaxed">
                                Transaksi anti ribet tanpa konfirmasi manual. Scan QRIS dinamis langsung aktif dan terverifikasi otomatis.
                            </p>

                            <div class="pt-2">
                                <a href="#catalog-grid"
                                   class="relative inline-flex items-center gap-2.5 px-5 sm:px-6 py-2.5 sm:py-3 rounded-2xl bg-gradient-to-r from-fuchsia-600 via-pink-600 to-rose-500 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-pink-600/30 hover:scale-105 active:scale-95 transition-all duration-200 group/btn">
                                    <span>Jelajahi Katalog</span>
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="hidden md:flex items-center justify-center pr-8">
                            <div class="relative w-48 h-48 lg:w-56 lg:h-56 rounded-3xl bg-gradient-to-tr from-fuchsia-600/30 to-pink-500/20 p-1 border border-white/10 shadow-2xl backdrop-blur-xl flex items-center justify-center transform rotate-2 hover:rotate-0 transition duration-500">
                                <div class="text-center space-y-2">
                                    <div class="text-2xl lg:text-3xl font-black text-white tracking-wider">
                                        QRIS <span class="text-pink-400">INSTAN</span>
                                    </div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        DANA • OVO • GOPAY • SHOPEE
                                    </div>
                                    <div class="px-3 py-1 rounded-full bg-pink-500/20 text-pink-300 text-[10px] font-bold inline-block">
                                        Verifikasi < 5 Detik
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Arrow Navigation (Hover visible) -->
            <button type="button" @click="prev()"
                    class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 h-10 w-10 sm:h-12 sm:w-12 rounded-2xl bg-slate-950/70 border border-white/10 backdrop-blur-xl text-white flex items-center justify-center opacity-0 group-hover:opacity-100 hover:scale-110 hover:bg-slate-900/90 transition-all duration-300 shadow-xl focus:outline-none z-20 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button type="button" @click="next()"
                    class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 h-10 w-10 sm:h-12 sm:w-12 rounded-2xl bg-slate-950/70 border border-white/10 backdrop-blur-xl text-white flex items-center justify-center opacity-0 group-hover:opacity-100 hover:scale-110 hover:bg-slate-900/90 transition-all duration-300 shadow-xl focus:outline-none z-20 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Dot Pagination Indicators -->
            <div class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20 bg-slate-950/50 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/10">
                <template x-for="i in total" :key="i">
                    <button type="button" @click="goTo(i - 1)"
                            :class="current === (i - 1) ? 'w-7 sm:w-9 bg-gradient-to-r from-cyan-400 to-indigo-500 shadow-md shadow-cyan-500/50' : 'w-2 bg-slate-600 hover:bg-slate-400'"
                            class="h-2 rounded-full transition-all duration-300 focus:outline-none cursor-pointer"></button>
                </template>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. QUICK CATEGORY & ICON TABS (FILTER CEPAT INTERAKTIF)                   -->
    <!-- ========================================================================= -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="p-1.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-black text-white uppercase tracking-wider">
                        Kategori Layanan
                    </h3>
                    <p class="text-[11px] text-slate-400">Pilih kategori untuk memfilter katalog secara instan</p>
                </div>
            </div>

            @if($selectedCategory !== 'all' || $search !== '')
                <button type="button" wire:click="clearFilters"
                        class="text-[11px] font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reset Filter</span>
                </button>
            @endif
        </div>

        <!-- Horizontal Scrollable Tabs with Modern Icons -->
        <div class="flex items-center gap-2.5 overflow-x-auto pb-3 pt-1 scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">

            <!-- Tab: Semua Layanan -->
            <button type="button" wire:click="selectCategory('all')"
                    class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all duration-300 {{ $selectedCategory === 'all' ? 'bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 text-white border-cyan-400/50 shadow-lg shadow-indigo-600/30 scale-105' : 'bg-slate-900/80 border-slate-800 hover:border-slate-700 text-slate-400 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Semua Layanan</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $selectedCategory === 'all' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                    {{ $products->count() }}
                </span>
            </button>

            @foreach($categories as $category)
                @php
                    $catSlug = strtolower($category->slug);
                @endphp
                <button type="button" wire:click="selectCategory('{{ $category->slug }}')"
                        class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all duration-300 {{ $selectedCategory === $category->slug ? 'bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 text-white border-cyan-400/50 shadow-lg shadow-indigo-600/30 scale-105' : 'bg-slate-900/80 border-slate-800 hover:border-slate-700 text-slate-400 hover:text-white' }}">

                    <!-- Ikon Kategori Kontekstual -->
                    @if(str_contains($catSlug, 'game') || str_contains($catSlug, 'mobile') || str_contains($catSlug, 'free-fire'))
                        <svg class="w-4 h-4 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    @elseif(str_contains($catSlug, 'pulsa') || str_contains($catSlug, 'data') || str_contains($catSlug, 'paket'))
                        <svg class="w-4 h-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    @elseif(str_contains($catSlug, 'pln') || str_contains($catSlug, 'listrik'))
                        <svg class="w-4 h-4 shrink-0 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    @elseif(str_contains($catSlug, 'voucher'))
                        <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    @elseif(str_contains($catSlug, 'wallet') || str_contains($catSlug, 'emoney') || str_contains($catSlug, 'dana'))
                        <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    @endif

                    <span>{{ $category->name }}</span>
                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $selectedCategory === $category->slug ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        {{ $category->products_count }}
                    </span>
                </button>
            @endforeach
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. SECTION FLASH SALE & COUNTDOWN TIMER BERDENYUT                         -->
    <!-- ========================================================================= -->
    @if(count($flashSaleItems) > 0)
        <section class="relative overflow-hidden rounded-3xl border border-rose-500/30 bg-gradient-to-b from-rose-950/20 via-slate-900/90 to-slate-950 p-5 sm:p-7 shadow-2xl shadow-rose-950/20 backdrop-blur-2xl"
                 x-data="{
                    hours: '03',
                    minutes: '42',
                    seconds: '18',
                    totalSeconds: 3 * 3600 + 42 * 60 + 18,
                    init() {
                        setInterval(() => {
                            if (this.totalSeconds > 0) {
                                this.totalSeconds--;
                                const h = Math.floor(this.totalSeconds / 3600);
                                const m = Math.floor((this.totalSeconds % 3600) / 60);
                                const s = this.totalSeconds % 60;
                                this.hours = String(h).padStart(2, '0');
                                this.minutes = String(m).padStart(2, '0');
                                this.seconds = String(s).padStart(2, '0');
                            }
                        }, 1000);
                    }
                 }">

            <!-- Ambient Glow Effect -->
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Flash Sale Header -->
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-800/80">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-rose-600 to-amber-500 p-0.5 shadow-lg shadow-rose-600/30 flex items-center justify-center shrink-0">
                        <span class="text-xl animate-bounce">🔥</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-black uppercase tracking-wider text-white">
                                Flash Sale Kilat
                            </h2>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-500 text-white animate-pulse">
                                Live Now
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">Harga terendah hari ini • Kuota terbatas & auto-reset</p>
                    </div>
                </div>

                <!-- High Precision Countdown Timer -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mr-1">Berakhir Dalam:</span>
                    <div class="flex items-center gap-1 font-mono font-black text-xs sm:text-sm">
                        <div class="h-8 px-2 rounded-xl bg-slate-950 border border-rose-500/40 text-rose-400 flex items-center justify-center shadow-inner" x-text="hours">03</div>
                        <span class="text-rose-500 font-bold animate-pulse">:</span>
                        <div class="h-8 px-2 rounded-xl bg-slate-950 border border-rose-500/40 text-rose-400 flex items-center justify-center shadow-inner" x-text="minutes">42</div>
                        <span class="text-rose-500 font-bold animate-pulse">:</span>
                        <div class="h-8 px-2 rounded-xl bg-slate-950 border border-rose-500/40 text-rose-400 flex items-center justify-center shadow-inner" x-text="seconds">18</div>
                    </div>
                </div>
            </div>

            <!-- Flash Sale Items Grid -->
            <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($flashSaleItems as $flash)
                    <div class="group relative flex flex-col justify-between rounded-2xl border border-slate-800 bg-slate-950/70 p-3.5 hover:border-rose-500/50 hover:bg-slate-900/90 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-rose-500/10">

                        <!-- Floating Discount Badge -->
                        <div class="absolute top-2.5 right-2.5 z-20 px-2 py-0.5 rounded-full bg-gradient-to-r from-rose-600 to-amber-500 text-white font-black text-[10px] tracking-wider shadow-md shadow-rose-600/30">
                            -{{ $flash['discount_percent'] }}%
                        </div>

                        <!-- Product Thumbnail & Details -->
                        <div class="space-y-3">
                            <div class="aspect-video w-full overflow-hidden rounded-xl bg-slate-900 border border-slate-800 relative flex items-center justify-center">
                                @if($flash['thumbnail'])
                                    <img src="{{ $flash['thumbnail'] }}" alt="{{ $flash['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <span class="text-xs font-black text-rose-400">{{ $flash['product_name'] }}</span>
                                @endif
                                <span class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 rounded-md bg-slate-950/90 border border-slate-800 text-[9px] font-bold text-slate-300 uppercase tracking-wider">
                                    {{ $flash['category'] }}
                                </span>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-white group-hover:text-rose-400 transition truncate">
                                    {{ $flash['name'] }}
                                </h4>
                                <p class="text-[10px] text-slate-400 truncate">
                                    {{ $flash['product_name'] }}
                                </p>

                                <div class="mt-2 space-y-0.5">
                                    <div class="text-[11px] font-mono text-slate-500 line-through">
                                        Rp {{ number_format($flash['original_price'], 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm font-black font-mono text-amber-400 drop-shadow-sm">
                                        Rp {{ number_format($flash['selling_price'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quota Sold Progress Bar & CTA -->
                        <div class="mt-3.5 pt-3 border-t border-slate-800/80 space-y-2.5">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[10px] font-bold">
                                    <span class="text-rose-400">Terjual {{ $flash['sold_percentage'] }}%</span>
                                    <span class="text-slate-400">Sisa {{ $flash['stock_left'] }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-rose-500 to-amber-400 rounded-full transition-all duration-500"
                                         style="width: {{ $flash['sold_percentage'] }}%"></div>
                                </div>
                            </div>

                            <a href="{{ route('order.show', $flash['product_slug']) }}"
                               class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl bg-rose-600/20 hover:bg-rose-600 border border-rose-500/30 hover:border-rose-500 text-rose-300 hover:text-white text-xs font-bold transition-all duration-200 shadow-sm active:scale-95 group/btn">
                                <span>Beli Cepat</span>
                                <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ========================================================================= -->
    <!-- 4. INSTANT SEARCH BAR & PRODUCT GRID (PPOB GRID SYSTEM)                   -->
    <!-- ========================================================================= -->
    <section id="catalog-grid" class="space-y-6">

        <!-- Search Bar & Stats Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 rounded-3xl bg-slate-900/60 border border-slate-800/80 backdrop-blur-xl">

            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-cyan-500 to-indigo-600 p-0.5 shadow-lg shadow-cyan-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-black uppercase tracking-wider text-white">
                        Katalog Produk & Game
                    </h3>
                    <p class="text-[11px] text-slate-400">
                        Menampilkan <span class="font-bold text-cyan-400">{{ $products->count() }}</span> produk digital aktif
                    </p>
                </div>
            </div>

            <!-- Glow Instant Search Bar -->
            <div class="relative w-full md:w-80 lg:w-96 group">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari Mobile Legends, Free Fire, PLN..." 
                       class="w-full rounded-2xl border border-slate-800 bg-slate-950/80 px-4 py-3 pl-11 pr-10 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-200 shadow-inner">

                <!-- Left Glowing Icon -->
                <div class="absolute left-3.5 top-3.5 pointer-events-none text-slate-500 group-focus-within:text-cyan-400 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Clear Search Button -->
                @if($search !== '')
                    <button type="button" wire:click="$set('search', '')"
                            class="absolute right-3.5 top-3 text-slate-400 hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

        </div>

        <!-- Responsive Products Grid (2 cols mobile, up to 6 on wide screens) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-5">
            @forelse($products as $product)
                @php
                    $minPrice = $product->items->min('selling_price') ?? 0;
                @endphp
                <div class="group flex flex-col justify-between rounded-2xl border border-slate-800/80 bg-[#0c101d]/80 hover:bg-[#0f1527] p-3 sm:p-3.5 hover:border-cyan-500/60 hover:shadow-2xl hover:shadow-cyan-500/20 hover:-translate-y-2 transition-all duration-300 backdrop-blur-xl relative overflow-hidden">

                    <!-- Wishlist Favorite Button (Floating Top-Left) -->
                    @php
                        $isWishlisted = in_array($product->id, $userWishlistProductIds ?? []);
                    @endphp
                    <button type="button"
                        wire:click.stop="toggleWishlist({{ $product->id }})"
                        class="absolute top-5 left-5 z-20 h-7 w-7 rounded-lg backdrop-blur-md transition-all flex items-center justify-center cursor-pointer shadow-md {{ $isWishlisted ? 'bg-rose-500 text-white shadow-rose-500/40' : 'bg-slate-950/70 text-slate-400 hover:text-rose-400 hover:bg-slate-900 border border-white/10' }}"
                        title="{{ $isWishlisted ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}">
                        <svg class="w-3.5 h-3.5 {{ $isWishlisted ? 'fill-current' : 'fill-none stroke-current' }}" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>

                    <a href="{{ route('order.show', $product->slug) }}" class="space-y-3 block">

                        <!-- Aspect Video Thumbnail with Micro Glow & Hover Zoom -->
                        <div class="aspect-video w-full overflow-hidden rounded-xl bg-slate-950 border border-slate-800/80 relative flex items-center justify-center">
                            @if($product->thumbnail_url)
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                                     class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-gradient-to-tr from-violet-950/40 to-slate-900 p-2 text-center">
                                    <span class="font-black text-xs sm:text-sm text-cyan-400 tracking-wide line-clamp-2">
                                        {{ $product->name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Active Pulse Indicator Pill -->
                            <span class="absolute top-1.5 right-1.5 flex h-2 w-2" title="Layanan Aktif 24 Jam">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>

                            <!-- Category Badge -->
                            @if($product->category)
                                <span class="absolute bottom-1.5 left-1.5 rounded-md bg-slate-950/90 backdrop-blur-md px-1.5 py-0.5 text-[8px] sm:text-[9px] font-black text-slate-300 uppercase tracking-wider border border-slate-800">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Title & Details -->
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white group-hover:text-cyan-400 transition-colors duration-200 truncate">
                                {{ $product->name }}
                            </h4>

                            <!-- Rating & Reviews Badge -->
                            <div class="flex items-center gap-1.5 mt-1">
                                <div class="flex items-center text-amber-400">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-[10px] font-extrabold text-amber-400 ml-1 font-mono">
                                        {{ number_format($product->average_rating, 1) }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-slate-500 font-mono">
                                    ({{ $product->reviews_count }})
                                </span>
                                <span class="text-[10px] text-slate-600">•</span>
                                <span class="text-[10px] text-slate-400 truncate">
                                    {{ $product->provider_code ?: 'Top Up Otomatis' }}
                                </span>
                            </div>

                            <!-- Price Range -->
                            <div class="mt-2.5">
                                <div class="text-[9px] uppercase tracking-wider text-slate-400 font-bold" x-text="$store.i18n ? $store.i18n.t('starting_from', 'Mulai dari') : 'Mulai dari'">{{ __('starting_from') }}</div>
                                <div class="text-xs sm:text-sm font-black font-mono text-cyan-400 drop-shadow-sm">
                                    @if($minPrice > 0)
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                    @else
                                        <span x-text="$store.i18n ? $store.i18n.t('available', 'Tersedia') : 'Tersedia'">{{ __('available') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </a>

                    <!-- Bottom Quick Buy Action Button (Micro Glow & Sliding Transition) -->
                    <div class="mt-3.5 pt-2.5 border-t border-slate-800/70">
                        <a href="{{ route('order.show', $product->slug) }}"
                           class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl bg-gradient-to-r from-cyan-500/10 via-indigo-500/10 to-violet-500/10 hover:from-cyan-500 hover:via-indigo-600 hover:to-violet-600 border border-cyan-500/30 hover:border-cyan-400 text-cyan-400 hover:text-white text-xs font-extrabold transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-cyan-500/25 active:scale-95 group/cta">
                            <span x-text="$store.i18n ? $store.i18n.t('quick_buy', 'Beli Cepat') : 'Beli Cepat'">{{ __('quick_buy') }}</span>
                            <svg class="w-3.5 h-3.5 group-hover/cta:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center space-y-3 rounded-3xl border border-slate-800 bg-slate-900/40 p-8">
                    <div class="h-12 w-12 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">Produk tidak ditemukan</h4>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Tidak ada layanan yang cocok dengan kata kunci "<span class="text-cyan-400">{{ $search }}</span>". Coba gunakan kata kunci lain atau reset filter.
                    </p>
                    <button type="button" wire:click="clearFilters"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-indigo-600/20">
                        Reset Pencarian
                    </button>
                </div>
            @endforelse
        </div>

    </section>

</div>
