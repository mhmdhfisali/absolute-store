<div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Diagnostik Sistem & API
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20">
                    Live Engine
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Pantau latensi koneksi gateway Tripay, ketersediaan API Digiflazz, dan tes inquiry username akun game secara live.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" wire:click="runHealthChecks"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 dark:bg-slate-800 text-slate-200 hover:text-white border border-slate-700 text-xs font-bold transition active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Refresh Ping Status</span>
            </button>
            <button type="button" wire:click="clearSystemCache" wire:confirm="Bersihkan cache aplikasi sekarang?"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-600/10 text-rose-500 hover:bg-rose-600 hover:text-white border border-rose-500/30 text-xs font-bold transition active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Flush Cache</span>
            </button>
        </div>
    </div>

    <!-- 1. Infrastructure Latency & Health Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($systemStatus as $key => $status)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14] p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $status['name'] }}</span>
                    @if($status['ok'])
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                    @else
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                        </span>
                    @endif
                </div>

                <div class="flex items-baseline justify-between pt-2">
                    <span class="text-2xl font-black font-mono {{ $status['ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $status['latency'] }} <span class="text-xs font-normal text-slate-400">ms</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $status['ok'] ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                        {{ $status['ok'] ? 'Connected' : 'Offline' }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- 2. Interactive Account Inquiry Tester Sandbox -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14] p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                Live Simulator
            </div>
            <h3 class="text-lg font-black text-slate-900 dark:text-white">
                Simulator Pengecekan Akun Game & PPOB (Account Inquiry Sandbox)
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Uji coba validasi nama pengguna (nickname) untuk Mobile Legends, Free Fire, Genshin Impact, PLN, dll secara real-time tanpa membuat orderan.
            </p>
        </div>

        <form wire:submit="testAccountInquiry" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pilih Produk</label>
                <select wire:model="selectedProductId"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-900 dark:text-white font-bold focus:outline-none focus:border-indigo-500">
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->input_type }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">User ID / No. Tujuan</label>
                <input type="text" wire:model="targetAccount" placeholder="Contoh: 12345678"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-900 dark:text-white font-mono focus:outline-none focus:border-indigo-500" />
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Zone ID (Jika ada)</label>
                <div class="flex items-center gap-2">
                    <input type="text" wire:model="targetZone" placeholder="Contoh: 2024"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-900 dark:text-white font-mono focus:outline-none focus:border-indigo-500" />
                    
                    <button type="submit" wire:loading.attr="disabled"
                            class="shrink-0 px-4 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md transition active:scale-95 cursor-pointer">
                        <span wire:loading.remove>Cek Akun</span>
                        <span wire:loading>Memeriksa...</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Result Card -->
        @if($inquiryResult)
            <div class="mt-4 p-5 rounded-2xl border transition-all {{ $inquiryResult['success'] ? 'bg-emerald-500/5 border-emerald-500/30' : 'bg-rose-500/5 border-rose-500/30' }}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $inquiryResult['success'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                            {{ $inquiryResult['success'] ? 'Inquiry Valid' : 'Inquiry Gagal' }}
                        </span>
                        <span class="text-xs font-mono text-slate-400">{{ $inquiryResult['timestamp'] }}</span>
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Hasil Nama Pengguna (Nickname)</div>
                        <div class="text-lg sm:text-xl font-black {{ $inquiryResult['success'] ? 'text-emerald-400' : 'text-rose-400' }} mt-0.5">
                            {{ $inquiryResult['nickname'] ?? 'Gagal memverifikasi akun' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
