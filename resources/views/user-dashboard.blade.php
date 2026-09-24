<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4" 
        x-data="{ 
            addAccountModal: false, 
            depositModal: false, 
            selectedInputType: 'id_and_zone',
            depositAmount: 50000,
            copiedToast: false,
            toastMsg: '',
            copyToClipboard(text, label = 'Teks') {
                navigator.clipboard.writeText(text);
                this.toastMsg = label + ' berhasil disalin!';
                this.copiedToast = true;
                setTimeout(() => this.copiedToast = false, 2500);
            }
        }">

        <!-- Toast Feedback Notification -->
        <div x-show="copiedToast" 
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            x-cloak
            class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-2xl bg-emerald-600 text-white text-xs font-bold shadow-2xl flex items-center gap-2 border border-emerald-400/40 backdrop-blur-xl">
            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span x-text="toastMsg"></span>
        </div>

        {{-- Alerts --}}
        @if (session('success_account'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-xs font-bold flex items-center justify-between shadow-lg backdrop-blur-xl">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success_account') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-300 text-base leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        @if (session('success_deposit'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-xs font-bold flex items-center justify-between shadow-lg backdrop-blur-xl">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success_deposit') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-300 text-base leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        @if (session('success_wishlist'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-xs font-bold flex items-center justify-between shadow-lg backdrop-blur-xl">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success_wishlist') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-300 text-base leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        @if (session('success_tier'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-xs font-bold flex items-center justify-between shadow-lg backdrop-blur-xl">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success_tier') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-300 text-base leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        @if (session('error_tier'))
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs font-bold flex items-center justify-between shadow-lg backdrop-blur-xl">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error_tier') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-300 text-base leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        @if (session('info_tier'))
            <div class="p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-bold flex items-center justify-between shadow-lg backdrop-blur-xl">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('info_tier') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-indigo-400 hover:text-indigo-200 text-base leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        <!-- ================= 1. QUICK WALLET & BALANCE CARD ================= -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-6 sm:p-8 shadow-2xl transition-all duration-300">
            <!-- Glow Ambient Accents -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-violet-600/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-violet-500/10 border border-violet-500/20 text-violet-600 dark:text-cyan-400">
                            Member Central Portal
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ Auth::user()->isReseller() ? 'bg-amber-500/10 border border-amber-500/30 text-amber-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                            Tier: {{ strtoupper(Auth::user()->tier) }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mt-2.5">
                        Selamat Datang, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Akses saldo dompet instan, pelacakan otomatis invoice digital, dan akun game favorit Anda.
                    </p>

                    <!-- Saldo Display -->
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Saldo Dompet:</span>
                        <span class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 flex-wrap">
                    <button type="button" @click="depositModal = true"
                        class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-black shadow-lg shadow-emerald-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Top Up Saldo Sekarang
                    </button>

                    <button type="button" @click="addAccountModal = true"
                        class="px-4 py-3 rounded-2xl bg-slate-100 dark:bg-slate-900/90 hover:bg-slate-200 dark:hover:bg-slate-800 border border-slate-200 dark:border-[#1E293B] text-slate-800 dark:text-white text-xs font-bold transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        + Simpan ID Game
                    </button>

                    <a href="{{ route('home') }}"
                        class="px-4 py-3 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition flex items-center gap-1.5">
                        <span>Etalase Top Up</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= QUICK STATS GRID ================= -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] shadow-lg">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Transaksi</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white font-mono mt-1">
                    {{ number_format($myTransactions->total() ?? count($myTransactions)) }}
                </div>
                <span class="text-[10px] text-slate-500 mt-1 block">Pesanan digital dibuat</span>
            </div>

            <div class="p-5 rounded-2xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] shadow-lg">
                <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-500">Item Sukses Terkirim</span>
                <div class="text-2xl font-black text-cyan-500 font-mono mt-1">
                    {{ number_format($myTransactions->where('delivery_status', 'success')->count()) }}
                </div>
                <span class="text-[10px] text-slate-500 mt-1 block">Terkirim otomatis</span>
            </div>

            <div class="p-5 rounded-2xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] shadow-lg">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Menunggu Pembayaran</span>
                <div class="text-2xl font-black text-amber-500 font-mono mt-1">
                    {{ number_format($myTransactions->where('payment_status', 'unpaid')->count()) }}
                </div>
                <span class="text-[10px] text-slate-500 mt-1 block">Invoice aktif</span>
            </div>

            <div class="p-5 rounded-2xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] shadow-lg">
                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-500">Akun Game Tersimpan</span>
                <div class="text-2xl font-black text-indigo-500 font-mono mt-1">
                    {{ count($savedAccounts) }}
                </div>
                <span class="text-[10px] text-slate-500 mt-1 block">Siap top-up 1-klik</span>
            </div>
        </div>

        <!-- ================= 2. DAFTAR AKUN GAME TERSIMPAN ================= -->
        <div class="rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#1E293B] pb-4">
                <div>
                    <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-indigo-500/10 text-indigo-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </span>
                        Daftar Akun Game Tersimpan (Saved Accounts)
                    </h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">ID Game favorit Anda tersimpan aman untuk checkout instan tanpa mengetik ulang.</p>
                </div>
                <button type="button" @click="addAccountModal = true" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-bold cursor-pointer">
                    + Tambah Akun Baru
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                @forelse($savedAccounts as $acc)
                    <div class="rounded-2xl border border-slate-200 dark:border-[#1E293B] bg-slate-50/60 dark:bg-slate-900/40 p-4 flex flex-col justify-between hover:border-indigo-500/50 transition duration-200">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-black text-indigo-600 dark:text-cyan-400 truncate">
                                    {{ $acc->product?->name ?? 'Game' }}
                                </span>
                                <form action="{{ route('user.saved-accounts.destroy', $acc->id) }}" method="POST" onsubmit="return confirm('Hapus ID akun game ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 p-1 transition cursor-pointer" title="Hapus Akun">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <span class="block text-sm font-bold text-slate-800 dark:text-white mt-1">
                                {{ $acc->account_name ?: 'Akun Game' }}
                            </span>

                            <div class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-mono">
                                ID: <strong class="text-slate-900 dark:text-slate-200">{{ $acc->target_account }}</strong>
                                @if ($acc->target_zone)
                                    <span class="text-slate-500">({{ $acc->target_zone }})</span>
                                @endif
                            </div>

                            @if ($acc->nickname)
                                <span class="text-emerald-500 text-[11px] font-bold block mt-1">IGN: {{ $acc->nickname }}</span>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-200 dark:border-[#1E293B]">
                            <a href="{{ route('order.show', ['slug' => $acc->product?->slug ?? '', 'saved_id' => $acc->target_account, 'saved_zone' => $acc->target_zone]) }}"
                                class="w-full py-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-bold text-xs transition shadow-md flex items-center justify-center gap-1.5">
                                <span>Top Up Cepat</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-500 text-xs">
                        <svg class="w-8 h-8 mx-auto text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Belum ada ID akun game tersimpan. Simpan ID game Anda untuk mempercepat transaksi berikutnya!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= 3. RIWAYAT PESANAN BERSTATUS DINAMIS ================= -->
        <div class="rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] shadow-xl overflow-hidden space-y-4">
            <div class="p-6 pb-0 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-cyan-500/10 text-cyan-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        Riwayat Pesanan Top Up & Status Pengiriman
                    </h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pantau status pembayaran, waktu pengiriman, dan salin voucher / Serial Number Anda.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-y border-slate-100 dark:border-[#1E293B] bg-slate-50/70 dark:bg-slate-900/50 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                            <th class="px-5 py-3.5">Invoice & Waktu</th>
                            <th class="px-5 py-3.5">Game / Item Layanan</th>
                            <th class="px-5 py-3.5">Akun Tujuan</th>
                            <th class="px-5 py-3.5">Total Tagihan</th>
                            <th class="px-5 py-3.5 text-center">Status Bayar</th>
                            <th class="px-5 py-3.5 text-center">Status Pengiriman</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1E293B]/60 text-slate-700 dark:text-slate-300">
                        @forelse($myTransactions as $trx)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition">
                                <!-- Invoice -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $trx->invoice_number }}</span>
                                        <button type="button" @click="copyToClipboard('{{ $trx->invoice_number }}', 'Nomor Invoice')" class="text-slate-400 hover:text-cyan-400 p-0.5 transition" title="Salin Invoice">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="block text-[10px] text-slate-400">{{ $trx->created_at->format('d M Y, H:i') }} WIB</span>
                                </td>

                                <!-- Game & Item -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $trx->productItem?->product?->name ?? 'Produk' }}</div>
                                    <div class="text-[11px] text-indigo-500 dark:text-indigo-400 font-medium">{{ $trx->productItem?->name ?? '-' }}</div>
                                </td>

                                <!-- Tujuan -->
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono">
                                    <span class="font-bold text-slate-900 dark:text-slate-200">{{ $trx->target_account }}</span>
                                    @if ($trx->target_zone)
                                        <span class="text-slate-400">({{ $trx->target_zone }})</span>
                                    @endif
                                </td>

                                <!-- Total Tagihan -->
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    <span class="block text-[9px] font-sans font-normal text-slate-400">via {{ $trx->paymentMethod?->name ?? 'Saldompet' }}</span>
                                </td>

                                <!-- Status Bayar -->
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if ($trx->payment_status === 'paid')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 border border-emerald-500/20 text-emerald-500">
                                            Lunas
                                        </span>
                                    @elseif($trx->payment_status === 'unpaid')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/10 border border-amber-500/20 text-amber-500">
                                            Menunggu
                                        </span>
                                    @elseif($trx->payment_status === 'expired')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-400">
                                            Kadaluarsa
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/10 border border-rose-500/20 text-rose-500">
                                            Gagal
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Pengiriman & 1-CLICK COPY SN / VOUCHER -->
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if ($trx->delivery_status === 'success')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-cyan-500/10 border border-cyan-500/20 text-cyan-400">
                                            Terkirim
                                        </span>
                                        @if ($trx->serial_number)
                                            <div class="mt-1.5 flex items-center justify-center gap-1">
                                                <span class="font-mono text-[10px] text-cyan-300 bg-cyan-950/50 px-2 py-0.5 rounded-md border border-cyan-800/50 max-w-[130px] truncate" title="{{ $trx->serial_number }}">
                                                    {{ $trx->serial_number }}
                                                </span>
                                                <button type="button" @click="copyToClipboard('{{ $trx->serial_number }}', 'Serial Number / Voucher')" class="p-1 rounded-md bg-slate-800 hover:bg-cyan-600 text-slate-300 hover:text-white transition cursor-pointer" title="Salin Serial Number">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    @elseif($trx->delivery_status === 'processing')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 animate-pulse">
                                            Diproses
                                        </span>
                                    @elseif($trx->delivery_status === 'failed')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/10 border border-rose-500/20 text-rose-400">
                                            Gagal
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-400">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                                    <a href="{{ route('order.invoice', $trx->invoice_number) }}" target="_blank"
                                        class="px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition">
                                        Invoice ↗
                                    </a>
                                    @if($trx->payment_status === 'paid')
                                        <a href="{{ route('order.invoice.print', $trx->invoice_number) }}" target="_blank" title="Cetak Struk Thermal / PDF"
                                            class="px-2.5 py-1.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-500 dark:text-indigo-400 font-bold text-xs transition">
                                            Struk 🖨️
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                                    Belum ada riwayat pesanan yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($myTransactions->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-[#1E293B]">
                    {{ $myTransactions->links() }}
                </div>
            @endif
        </div>

        <!-- ================= 4. TRACKER DEPOSIT SALDO ================= -->
        <div class="rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] shadow-xl overflow-hidden space-y-4">
            <div class="p-6 pb-0 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-emerald-500/10 text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        Tracker Riwayat Mutasi Deposit
                    </h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Catatan transparansi penambahan saldo dompet ke akun Absolute Store Anda.</p>
                </div>
                <button type="button" @click="depositModal = true" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-bold cursor-pointer">
                    + Top Up Saldo
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-y border-slate-100 dark:border-[#1E293B] bg-slate-50/70 dark:bg-slate-900/50 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                            <th class="px-5 py-3.5">Nomor Deposit</th>
                            <th class="px-5 py-3.5">Metode Gateway</th>
                            <th class="px-5 py-3.5">Nominal Masuk</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Tanggal & Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1E293B]/60 text-slate-700 dark:text-slate-300">
                        @forelse($myDeposits ?? [] as $depo)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">
                                    {{ $depo->deposit_number }}
                                </td>
                                <td class="px-5 py-3.5 font-medium">
                                    {{ $depo->paymentMethod?->name ?? 'Gateway' }}
                                </td>
                                <td class="px-5 py-3.5 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    + Rp {{ number_format($depo->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 border border-emerald-500/20 text-emerald-500">
                                        {{ $depo->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right text-slate-400 font-mono text-[11px]">
                                    {{ $depo->created_at->format('d M Y, H:i') }} WIB
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-500">
                                    Belum ada transaksi deposit saldo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= 5. WISHLIST & PRODUK FAVORIT (1-CLICK RE-ORDER) ================= -->
        <div class="rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-5 sm:p-7 shadow-2xl transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-[#1E293B]">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="p-1.5 rounded-xl bg-rose-500/10 text-rose-500 border border-rose-500/20">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </span>
                        Wishlist & Produk Favorit Saya
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Daftar game dan produk favorit Anda untuk top up kilat 1-klik tanpa cari ulang.
                    </p>
                </div>
                <a href="{{ route('home') }}#catalog-grid"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-500 hover:text-rose-400 transition">
                    <span>Eksplor Katalog</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="mt-6">
                @if($myWishlists->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($myWishlists as $wishlist)
                            @php
                                $itemMinPrice = $wishlist->product->items->min('selling_price') ?? 0;
                            @endphp
                            <div class="group relative rounded-2xl border border-slate-200 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-900/60 p-3.5 hover:border-rose-500/50 hover:shadow-xl transition-all duration-200 flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="aspect-video w-full rounded-xl bg-slate-900 border border-slate-800 overflow-hidden relative flex items-center justify-center">
                                        @if($wishlist->product->thumbnail_url)
                                            <img src="{{ $wishlist->product->thumbnail_url }}" alt="{{ $wishlist->product->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <div class="p-2 text-center text-xs font-bold text-slate-400">
                                                {{ $wishlist->product->name }}
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('user.wishlist.destroy', $wishlist->id) }}" class="absolute top-2 right-2 z-10">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus dari Wishlist"
                                                    class="h-7 w-7 rounded-lg bg-slate-950/80 text-rose-400 hover:bg-rose-500 hover:text-white transition flex items-center justify-center backdrop-blur-md cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div>
                                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            {{ $wishlist->product->category?->name ?? 'Game' }}
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-rose-400 transition truncate">
                                            {{ $wishlist->product->name }}
                                        </h4>
                                        <div class="text-xs font-extrabold text-cyan-500 font-mono mt-1">
                                            @if($itemMinPrice > 0)
                                                Mulai Rp {{ number_format($itemMinPrice, 0, ',', '.') }}
                                            @else
                                                Tersedia
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800">
                                    <a href="{{ route('order.show', $wishlist->product->slug) }}"
                                       class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-bold text-xs shadow-md shadow-rose-500/20 active:scale-95 transition">
                                        <span>Order Sekarang</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center space-y-3 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-6">
                        <div class="h-12 w-12 mx-auto rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Belum Ada Wishlist Tersimpan</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Klik ikon hati (❤️) pada card katalog produk favorit Anda untuk menyimpannya di sini.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('home') }}#catalog-grid"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-white hover:bg-slate-700 text-xs font-bold transition">
                                Jelajahi Katalog Sekarang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ================= 6. PROGRAM AFILIASI & REFERRAL REWARDS ================= -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-5 sm:p-7 shadow-2xl transition-all duration-300">
            <!-- Glow ambient -->
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100 dark:border-[#1E293B]">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-[10px] font-black uppercase tracking-wider mb-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                        Program Afiliasi Berjenjang
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        Komisi Referral & Passive Income
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Bagikan tautan afiliasi Anda. Dapatkan komisi instan 0.5% dari total belanja setiap downline yang langsung cair ke saldo dompet Anda.
                    </p>
                </div>
            </div>

            <!-- Referral Link & Code Box -->
            <div class="relative z-10 mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/80 p-4 sm:p-5 space-y-3">
                    <label class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Tautan Afiliasi Unik Anda
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ $referralLink }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 text-xs font-mono font-bold text-indigo-600 dark:text-cyan-400 select-all focus:outline-none" />
                        <button type="button" @click="copyToClipboard('{{ $referralLink }}', 'Link Referral')"
                                class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Salin Link</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 pt-1 text-xs text-slate-500">
                        <span>Kode Referral: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $referralCode }}</strong></span>
                        <span>•</span>
                        <button type="button" @click="copyToClipboard('{{ $referralCode }}', 'Kode Referral')" class="text-indigo-500 hover:underline cursor-pointer font-semibold">Salin Kode Saja</button>
                    </div>
                </div>

                <!-- Fast Stats -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 p-4 flex flex-col justify-between">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Downline</div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white font-mono mt-2">
                            {{ number_format($totalDownlines) }}
                        </div>
                        <div class="text-[10px] text-slate-500 mt-1">User Terdaftar</div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 p-4 flex flex-col justify-between">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Komisi</div>
                        <div class="text-xl sm:text-2xl font-black text-emerald-500 font-mono mt-2 truncate">
                            Rp {{ number_format($totalAffiliateEarnings, 0, ',', '.') }}
                        </div>
                        <div class="text-[10px] text-emerald-400 mt-1 font-semibold">Otomatis Cair ke Saldo</div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Komisi Afiliasi Table -->
            <div class="relative z-10 mt-6 space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Riwayat Komisi Terakhir
                </h4>

                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-[#1E293B]">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-[#1E293B]">
                            <tr>
                                <th class="px-5 py-3">No. Invoice</th>
                                <th class="px-5 py-3">Pembeli</th>
                                <th class="px-5 py-3 text-right">Nilai Belanja</th>
                                <th class="px-5 py-3 text-right">Komisi (0.5%)</th>
                                <th class="px-5 py-3 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#1E293B]">
                            @forelse($recentAffiliateEarnings as $earning)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                    <td class="px-5 py-3.5 font-mono text-indigo-500 dark:text-cyan-400 font-bold">
                                        {{ $earning->transaction?->invoice_number ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">
                                        {{ $earning->buyer?->name ?? 'Member' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono text-slate-600 dark:text-slate-400">
                                        Rp {{ number_format($earning->transaction?->total_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono font-black text-emerald-500">
                                        +Rp {{ number_format($earning->commission_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono text-[11px] text-slate-400">
                                        {{ $earning->created_at->format('d M Y, H:i') }} WIB
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-slate-500">
                                        Belum ada komisi referral yang diperoleh. Bagikan tautan afiliasi Anda untuk mulai mengumpulkan komisi!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= 7. BUKU KAS & MUTASI SALDO DOMPET ================= -->
        <div class="rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-5 sm:p-7 shadow-2xl transition-all duration-300"
             x-data="{ mutationFilter: 'all' }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-[#1E293B]">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="p-1.5 rounded-xl bg-cyan-500/10 text-cyan-500 border border-cyan-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </span>
                        Buku Kas & Riwayat Mutasi Saldo
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Transparansi arus kas masuk (deposit, komisi referral) dan keluar (pembelian pesanan) secara akurat.
                    </p>
                </div>

                <!-- Mutation Filter Pills -->
                <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-900 p-1 rounded-2xl border border-slate-200 dark:border-slate-800">
                    <button type="button" @click="mutationFilter = 'all'"
                            class="px-3 py-1 rounded-xl text-xs font-bold transition cursor-pointer"
                            :class="mutationFilter === 'all' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-300'">
                        Semua
                    </button>
                    <button type="button" @click="mutationFilter = 'credit'"
                            class="px-3 py-1 rounded-xl text-xs font-bold transition cursor-pointer"
                            :class="mutationFilter === 'credit' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:text-emerald-500'">
                        Pemasukan (+)
                    </button>
                    <button type="button" @click="mutationFilter = 'debit'"
                            class="px-3 py-1 rounded-xl text-xs font-bold transition cursor-pointer"
                            :class="mutationFilter === 'debit' ? 'bg-rose-500 text-white shadow-sm' : 'text-slate-500 hover:text-rose-500'">
                        Pengeluaran (-)
                    </button>
                </div>
            </div>

            <!-- Mutations Table -->
            <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200 dark:border-[#1E293B]">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-[#1E293B]">
                        <tr>
                            <th class="px-5 py-3">Waktu</th>
                            <th class="px-5 py-3">No. Referensi</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Keterangan</th>
                            <th class="px-5 py-3 text-right">Nominal Mutasi</th>
                            <th class="px-5 py-3 text-right">Sisa Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1E293B]">
                        @forelse($walletMutations as $mutasi)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition"
                                x-show="mutationFilter === 'all' || mutationFilter === '{{ $mutasi->type }}'">
                                <td class="px-5 py-3.5 text-slate-400 font-mono text-[11px]">
                                    {{ $mutasi->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-indigo-600 dark:text-cyan-400 font-bold">
                                    {{ $mutasi->reference_id }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $mutasi->category === 'deposit' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : '' }}
                                        {{ $mutasi->category === 'order' ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20' : '' }}
                                        {{ $mutasi->category === 'referral_commission' ? 'bg-violet-500/10 text-violet-500 border border-violet-500/20' : '' }}">
                                        {{ str_replace('_', ' ', $mutasi->category) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">
                                    {{ $mutasi->description }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-black {{ $mutasi->type === 'credit' ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $mutasi->type === 'credit' ? '+' : '-' }}Rp {{ number_format($mutasi->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono text-slate-600 dark:text-slate-300 font-semibold">
                                    Rp {{ number_format($mutasi->balance_after, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                                    Belum ada catatan mutasi saldo dompet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= 8. PROGRAM KEMITRAAN & UPGRADE TIER RESELLER / VIP ================= -->
        <div class="rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-5 sm:p-7 shadow-2xl transition-all duration-300">
            <div class="pb-5 border-b border-slate-100 dark:border-[#1E293B]">
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-500 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider mb-1">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    Program Kemitraan Toko & Grosir
                </div>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                    Tingkat Akun & Keuntungan Reseller / VIP
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Nikmati margin keuntungan maksimal untuk dijual kembali dengan meng-upgrade akun ke tingkatan mitra resmi.
                </p>
            </div>

            <!-- Tier Cards Grid -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Tier 1: Member Biasa -->
                <div class="rounded-2xl border p-5 flex flex-col justify-between transition-all {{ Auth::user()->tier === 'member' ? 'border-cyan-500 bg-cyan-500/5 shadow-lg shadow-cyan-500/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40' }}">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-400">Tier Reguler</span>
                            @if(Auth::user()->tier === 'member')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-cyan-500 text-slate-950">Aktif Saat Ini</span>
                            @endif
                        </div>
                        <h4 class="text-xl font-black text-slate-900 dark:text-white">Member Retail</h4>
                        <p class="text-xs text-slate-500">Harga eceran resmi standar katalog Absolute Store.</p>
                        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-200 dark:border-slate-800">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Transaksi Otomatis 24 Jam</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Lacak Pesanan Instan</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-400">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Harga Diskon Grosir</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tier 2: Reseller Resmi -->
                <div class="rounded-2xl border p-5 flex flex-col justify-between transition-all {{ Auth::user()->tier === 'reseller' ? 'border-amber-500 bg-amber-500/5 shadow-lg shadow-amber-500/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40' }}">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black uppercase tracking-wider text-amber-500">Tier Kemitraan</span>
                            @if(Auth::user()->tier === 'reseller')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-500 text-slate-950">Aktif Saat Ini</span>
                            @endif
                        </div>
                        <h4 class="text-xl font-black text-slate-900 dark:text-white">Mitra Reseller</h4>
                        <p class="text-xs text-slate-500">Akses harga grosir khusus untuk dijual kembali dengan margin tebal.</p>
                        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-200 dark:border-slate-800">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Harga Grosir SKU Reseller</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Syarat Saldo: Rp 250.000</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Antrean Server Prioritas</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                        @if(Auth::user()->tier === 'member')
                            <form action="{{ route('user.tier.upgrade') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tier" value="reseller">
                                <button type="submit"
                                        class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition active:scale-95 shadow-md shadow-amber-500/20 cursor-pointer">
                                    Aktifkan Tier Reseller
                                </button>
                            </form>
                        @elseif(Auth::user()->tier === 'reseller')
                            <button disabled class="w-full py-2 rounded-xl bg-slate-800 text-slate-400 font-bold text-xs">
                                Tingkat Aktif
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Tier 3: VIP Enterprise -->
                <div class="rounded-2xl border p-5 flex flex-col justify-between transition-all {{ Auth::user()->tier === 'vip' ? 'border-violet-500 bg-violet-500/5 shadow-lg shadow-violet-500/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40' }}">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black uppercase tracking-wider text-violet-400">Tier Tertinggi</span>
                            @if(Auth::user()->tier === 'vip')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-violet-500 text-white">Aktif Saat Ini</span>
                            @endif
                        </div>
                        <h4 class="text-xl font-black text-slate-900 dark:text-white">VIP Enterprise</h4>
                        <p class="text-xs text-slate-500">Harga termurah se-Indonesia, fast-lane API, dan dedicated account manager.</p>
                        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-200 dark:border-slate-800">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Harga Termurah Level Supplier</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Syarat Saldo: Rp 1.000.000</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Dukungan VIP CS Khusus</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                        @if(Auth::user()->tier !== 'vip')
                            <form action="{{ route('user.tier.upgrade') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tier" value="vip">
                                <button type="submit"
                                        class="w-full py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-black text-xs transition active:scale-95 shadow-md shadow-indigo-500/20 cursor-pointer">
                                    Aktifkan Tier VIP
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full py-2 rounded-xl bg-slate-800 text-slate-400 font-bold text-xs">
                                Tingkat Tertinggi Aktif
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= MODAL TOP UP SALDO DOMPET ================= -->
        <div x-show="depositModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
            style="display: none;">
            <div @click.away="depositModal = false"
                class="w-full max-w-md rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-6 space-y-4 shadow-2xl text-slate-800 dark:text-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-[#1E293B]">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-emerald-500/10 text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </span>
                        Top Up Saldo Dompet Instan
                    </h3>
                    <button @click="depositModal = false" class="text-slate-400 hover:text-white text-lg">&times;</button>
                </div>

                <form action="{{ route('user.deposit.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Pilih / Masukkan Nominal (Rp)</label>
                        <div class="grid grid-cols-3 gap-2 mb-2.5">
                            @foreach([10000, 25000, 50000, 100000, 250000, 500000] as $chip)
                                <button type="button" @click="depositAmount = {{ $chip }}"
                                    class="py-2 px-1 rounded-xl text-[11px] font-mono font-bold border transition text-center"
                                    :class="depositAmount == {{ $chip }} ? 'border-emerald-500 bg-emerald-500/10 text-emerald-400' : 'border-slate-200 dark:border-[#1E293B] bg-slate-50 dark:bg-slate-900 text-slate-300'">
                                    Rp {{ number_format($chip, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                        <input type="number" name="amount" x-model="depositAmount" placeholder="Minimal 10000" min="10000" step="1000" required
                            class="w-full rounded-xl border border-slate-200 dark:border-[#1E293B] bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 text-slate-900 dark:text-white font-mono font-bold focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Metode Pembayaran</label>
                        <select name="payment_method_id" required
                            class="w-full rounded-xl border border-slate-200 dark:border-[#1E293B] bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none">
                            @foreach ($paymentMethods ?? [] as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }} (Biaya: Rp {{ number_format($pm->fee_flat, 0, ',', '.') }} + {{ $pm->fee_percent }}%)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-[#1E293B]">
                        <button type="button" @click="depositModal = false"
                            class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold transition shadow-lg shadow-emerald-600/30 cursor-pointer">
                            Bayar & Tambah Saldo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL SIMPAN ID GAME ================= -->
        <div x-show="addAccountModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
            style="display: none;">
            <div @click.away="addAccountModal = false"
                class="w-full max-w-md rounded-3xl border border-slate-200 dark:border-[#1E293B] bg-white dark:bg-[#080C14] p-6 space-y-4 shadow-2xl text-slate-800 dark:text-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-[#1E293B]">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-indigo-500/10 text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </span>
                        Simpan ID Game Favorit
                    </h3>
                    <button @click="addAccountModal = false" class="text-slate-400 hover:text-white text-lg">&times;</button>
                </div>

                <form action="{{ route('user.saved-accounts.store') }}" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Pilih Game / Layanan</label>
                        <select name="product_id" required
                            @change="selectedInputType = $event.target.selectedOptions[0].getAttribute('data-input-type')"
                            class="w-full rounded-xl border border-slate-200 dark:border-[#1E293B] bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none">
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" data-input-type="{{ $p->input_type }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Label / Nama Akun (Opsional)</label>
                        <input type="text" name="account_name" placeholder="Misal: Akun Utama / Smurf"
                            class="w-full rounded-xl border border-slate-200 dark:border-[#1E293B] bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div :class="selectedInputType === 'id_and_zone' ? '' : 'sm:col-span-2'">
                            <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">User ID</label>
                            <input type="text" name="target_account" placeholder="12345678" required
                                class="w-full rounded-xl border border-slate-200 dark:border-[#1E293B] bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none font-mono">
                        </div>

                        <div x-show="selectedInputType === 'id_and_zone'">
                            <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Zone / Server ID</label>
                            <input type="text" name="target_zone" placeholder="2024"
                                class="w-full rounded-xl border border-slate-200 dark:border-[#1E293B] bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none font-mono">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-[#1E293B]">
                        <button type="button" @click="addAccountModal = false"
                            class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-bold transition shadow-lg shadow-indigo-600/30 cursor-pointer">
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
