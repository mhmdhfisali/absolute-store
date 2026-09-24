<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Supplier & Vendor PPOB
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20">
                    Direct API Sync
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Monitor saldo deposit vendor, sinkronisasi massal katalog harga SKU, dan uji inquiry API.
            </p>
        </div>

        <button type="button" wire:click="checkBalance" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-900 border border-slate-800 hover:border-cyan-500/40 text-cyan-400 hover:text-white text-xs font-bold transition active:scale-95 cursor-pointer">
            <svg class="w-4 h-4" :class="{ 'animate-spin': {{ $balanceStatus === 'loading' ? 'true' : 'false' }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>Refresh Saldo Vendor</span>
        </button>
    </div>

    <!-- Alert Flash -->
    @if(session()->has('success_sync'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success_sync') }}</span>
        </div>
    @endif

    @if(session()->has('error_sync'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error_sync') }}</span>
        </div>
    @endif

    <!-- Cards: Saldo & Status Vendor -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Card 1: Saldo Digiflazz -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold mb-2">
                <span>Saldo Deposit Digiflazz</span>
                <span class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full {{ $balanceStatus === 'success' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    <span class="text-[10px] text-emerald-400">Live API</span>
                </span>
            </div>

            <div class="text-2xl font-black font-mono text-slate-800 dark:text-cyan-400">
                @if($balanceStatus === 'loading')
                    <span class="text-slate-400 text-base font-sans">Memeriksa saldo...</span>
                @elseif($balanceStatus === 'success')
                    Rp {{ number_format($balance, 0, ',', '.') }}
                @else
                    <span class="text-rose-400 text-sm font-sans">Gagal terhubung</span>
                @endif
            </div>

            @if($balanceError)
                <p class="text-[10px] text-rose-500 mt-1 truncate">{{ $balanceError }}</p>
            @else
                <p class="text-[10px] text-slate-400 mt-1">Digunakan untuk eksekusi otomatis 24 jam</p>
            @endif
        </div>

        <!-- Card 2: Total SKU Terdaftar -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold mb-2">
                <span>Total SKU di Sistem</span>
                <span class="p-1 rounded-lg bg-indigo-500/10 text-indigo-400 font-bold">Catalog</span>
            </div>
            <div class="text-2xl font-black font-mono text-slate-800 dark:text-white">
                {{ number_format($totalSkus, 0, ',', '.') }} <span class="text-xs font-sans text-slate-400 font-normal">Varian</span>
            </div>
            <p class="text-[11px] text-emerald-500 mt-1">{{ $availableSkus }} item berstatus aktif</p>
        </div>

        <!-- Card 3: Status Jalur Integrasi -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold mb-2">
                <span>Gateway Supplier</span>
                <span class="p-1 rounded-lg bg-emerald-500/10 text-emerald-400 font-bold">Multi-Vendor</span>
            </div>
            <div class="space-y-1 pt-1 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Digiflazz API:</span>
                    <strong class="text-emerald-400">ONLINE</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Tokovoucher (Backup):</span>
                    <strong class="text-cyan-400">STANDBY</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Form Sinkronisasi Massal & Pricing Margin -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-2xl backdrop-blur-xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200 dark:border-slate-800">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-md">
                ⚡
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                    Sinkronisasi Massal Pricelist Digiflazz
                </h3>
                <p class="text-[11px] text-slate-400">Ambil daftar harga modal terbaru dari Digiflazz dan sesuaikan margin keuntungan otomatis</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Margin Keuntungan Flat (Rp)</label>
                <input type="number" wire:model="marginFlat" placeholder="1500"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-slate-800 dark:text-white focus:border-cyan-500">
                <span class="text-[10px] text-slate-400">Contoh: Rp 1.500 per transaksi di atas harga modal vendor</span>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Margin Keuntungan Persen (%)</label>
                <input type="number" wire:model="marginPercent" placeholder="3"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-slate-800 dark:text-white focus:border-cyan-500">
                <span class="text-[10px] text-slate-400">Contoh: Tambah 3% dari modal untuk produk nominal tinggi</span>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <div class="text-[11px] text-slate-400">
                Perhatian: Proses sinkronisasi massal akan memperbarui harga jual produk aktif.
            </div>

            <button type="button" wire:click="runSync" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/20 active:scale-95 transition cursor-pointer">
                <svg class="w-4 h-4 animate-spin" wire:loading fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span wire:loading.remove>Mulai Sinkronisasi Sekarang</span>
                <span wire:loading>Menyinkronkan dari API...</span>
            </button>
        </div>
    </div>

    <!-- Section 3: Live Inquiry Test SKU -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-2xl backdrop-blur-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                    Uji Inquiry SKU Digiflazz
                </h3>
                <p class="text-[11px] text-slate-400">Cek harga modal dan status real-time dari provider untuk kode buyer SKU tertentu</p>
            </div>
        </div>

        <div class="flex gap-3">
            <input type="text" wire:model="lookupSku" placeholder="Masukkan kode buyer SKU (misal: ML86, FF140, PLN20)..."
                   class="flex-1 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2.5 text-xs text-slate-800 dark:text-white font-mono uppercase focus:border-cyan-500">
            <button type="button" wire:click="testInquiry" wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-2xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition">
                Cek SKU
            </button>
        </div>

        @if($lookupResult)
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 font-mono text-xs text-emerald-400 overflow-x-auto">
                <pre>{{ json_encode($lookupResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        @endif
    </div>

</div>
