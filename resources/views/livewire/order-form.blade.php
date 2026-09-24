<div class="mx-auto max-w-7xl px-2 sm:px-4 py-4"
    x-data="{
        filterCategory: 'all',
        showIdGuide: false,
        paymentTab: 'all'
    }">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ================= KOLOM KIRI (4 COLS): 3D HERO PRODUCT CARD & TRUST ================= -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
            
            <!-- Hero Product Showcase Card -->
            <div class="rounded-3xl border border-slate-800/90 bg-gradient-to-b from-[#0f1523] via-[#0c101c] to-[#080c15] p-6 shadow-2xl relative overflow-hidden backdrop-blur-2xl">
                <!-- Glowing Ambient Accent -->
                <div class="absolute -top-16 -left-16 w-44 h-44 bg-gradient-to-tr from-indigo-600/20 to-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Product Thumbnail with Badges -->
                <div class="aspect-video w-full rounded-2xl bg-slate-950 border border-slate-800 overflow-hidden relative group shadow-inner">
                    @if ($product->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                            class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="h-full w-full bg-gradient-to-tr from-indigo-950 via-slate-900 to-purple-950 flex items-center justify-center text-4xl font-black text-indigo-400/40">
                            {{ strtoupper(substr($product->name, 0, 4)) }}
                        </div>
                    @endif

                    <!-- Floating Badges on Thumbnail -->
                    <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-black/70 backdrop-blur-md border border-white/10 text-[10px] font-bold text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Official Partner</span>
                    </div>

                    <div class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-lg bg-black/70 backdrop-blur-md border border-white/10 text-[10px] font-bold text-cyan-400">
                        ⚡ 100% Legal
                    </div>
                </div>

                <!-- Product Title & Publisher Info -->
                <div class="mt-5">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-md">
                            {{ $product->category?->name ?? 'Game & Digital' }}
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">• Server H2H Aktif</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-white">{{ $product->name }}</h1>
                    <p class="mt-1 text-xs text-slate-400 leading-relaxed">
                        Layanan top up resmi dengan sistem injeksi otomatis 24 jam nonstop. Proses kilat hanya 1-5 detik setelah pembayaran terkonfirmasi.
                    </p>
                </div>

                <!-- 4 Highlights Value Prosposition Grid -->
                <div class="grid grid-cols-2 gap-2.5 mt-6 pt-5 border-t border-slate-800/80 text-[11px]">
                    <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center gap-2.5">
                        <div class="h-7 w-7 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                            ⚡
                        </div>
                        <div>
                            <span class="font-bold text-slate-200 block">1-3 Detik</span>
                            <span class="text-[9px] text-slate-500">Proses Otomatis</span>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center gap-2.5">
                        <div class="h-7 w-7 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0">
                            🛡️
                        </div>
                        <div>
                            <span class="font-bold text-slate-200 block">Anti-Banned</span>
                            <span class="text-[9px] text-slate-500">Garansi 100% Legal</span>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center gap-2.5">
                        <div class="h-7 w-7 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                            🕒
                        </div>
                        <div>
                            <span class="font-bold text-slate-200 block">24 Jam</span>
                            <span class="text-[9px] text-slate-500">Layanan Siaga</span>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center gap-2.5">
                        <div class="h-7 w-7 rounded-lg bg-violet-500/10 text-violet-400 flex items-center justify-center shrink-0">
                            ⭐
                        </div>
                        <div>
                            <span class="font-bold text-slate-200 block">4.9 / 5.0</span>
                            <span class="text-[9px] text-slate-500">Ulasan Kepuasan</span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Tutorial Accordion: Cara Menemukan User ID -->
                <div class="mt-5 pt-4 border-t border-slate-800/80">
                    <button type="button" @click="showIdGuide = !showIdGuide"
                        class="w-full flex items-center justify-between text-xs font-bold text-slate-300 hover:text-indigo-400 transition py-1">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Panduan Mencari User ID</span>
                        </span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': showIdGuide }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="showIdGuide" x-collapse class="mt-3 p-3.5 rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-400 space-y-2">
                        <p class="font-semibold text-slate-200">Langkah mudah mengecek ID:</p>
                        <ol class="list-decimal list-inside space-y-1 text-[11px] leading-relaxed">
                            <li>Buka game <strong class="text-white">{{ $product->name }}</strong> di HP Anda.</li>
                            <li>Buka menu Profil akun di pojok kiri atas layar.</li>
                            <li>Lihat angka User ID & Server/Zone ID yang tertera di bawah nickname akun Anda.</li>
                            <li>Ketikkan angka tersebut pada kolom formulir di sebelah kanan.</li>
                        </ol>
                    </div>
                </div>

            </div>

            <!-- Customer Care Help Box -->
            <div class="rounded-2xl border border-slate-800/80 bg-[#090d18] p-4 text-xs text-slate-400 flex items-center justify-between gap-3 shadow-lg">
                <div>
                    <span class="font-bold text-white block">Butuh Bantuan Transaksi?</span>
                    <span class="text-[11px] text-slate-500">Customer care kami siaga 24 jam</span>
                </div>
                <a href="https://api.whatsapp.com/send?phone=6281234567890&text=Halo%20Admin%20Absolute%20Store,%20saya%20butuh%20bantuan%20order%20{{ urlencode($product->name) }}"
                    target="_blank"
                    class="px-3 py-1.5 rounded-xl bg-emerald-600/20 hover:bg-emerald-600 text-emerald-400 hover:text-white border border-emerald-500/30 text-xs font-bold transition flex items-center gap-1.5 shrink-0">
                    <span>Chat CS</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>

        <!-- ================= KOLOM KANAN (8 COLS): LANGKAH PEMESANAN KOMPLEKS ================= -->
        <div class="lg:col-span-8 space-y-6">

            <!-- ================= STEP 1: INPUT DATA AKUN & NICKNAME SCANNER ================= -->
            <div class="rounded-3xl border border-slate-800/90 bg-gradient-to-b from-[#0f1523]/95 via-[#0c101c]/95 to-[#080c15] p-6 sm:p-7 shadow-2xl backdrop-blur-2xl relative overflow-hidden">
                <div class="flex items-center gap-3.5 mb-5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 text-white font-black text-sm shadow-lg shadow-indigo-500/30">
                        1
                    </span>
                    <div>
                        <h2 class="text-base font-black uppercase tracking-wide text-white">Masukkan Data Akun</h2>
                        <p class="text-xs text-slate-400">Pastikan User ID dan Server tujuan sudah terisi dengan benar.</p>
                    </div>
                </div>

                <!-- Quick-Select Akun Tersimpan (Jika Member Memiliki Akun Terdaftar) -->
                @if (!empty($userSavedAccounts) && count($userSavedAccounts) > 0)
                    <div class="mb-5 p-3.5 rounded-2xl bg-slate-950/80 border border-slate-800/80">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <span>Akun Favorit Tersimpan:</span>
                        </span>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($userSavedAccounts as $saved)
                                <button type="button" wire:click="applySavedAccount({{ $saved->id }})"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer {{ $userId == $saved->target_account ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 ring-2 ring-indigo-400' : 'bg-slate-900 border border-slate-800 text-slate-300 hover:border-slate-700 hover:text-white' }}">
                                    <span>{{ $saved->account_name ?: $saved->target_account }}</span>
                                    @if ($saved->nickname)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-black/40 text-cyan-300">({{ $saved->nickname }})</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Inputs Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="{{ $product->input_type === 'id_and_zone' ? '' : 'sm:col-span-2' }}">
                        <label class="text-xs font-bold text-slate-300 flex items-center justify-between mb-1.5">
                            <span>
                                @if ($product->input_type === 'id_and_zone' || $product->input_type === 'id_only')
                                    User ID Akun
                                @elseif($product->input_type === 'phone_number')
                                    Nomor Handphone
                                @elseif($product->input_type === 'meter_number')
                                    Nomor Meter / ID Pelanggan
                                @else
                                    User ID / Nomor Tujuan
                                @endif
                            </span>
                            <span class="text-[10px] text-slate-500 font-normal">Contoh: 12345678</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.600ms="userId" placeholder="Masukkan ID akun..."
                                class="w-full px-4 py-3 rounded-2xl bg-slate-950/80 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-white placeholder-slate-600 text-sm font-semibold transition" />
                        </div>
                        @error('userId')
                            <span class="text-[11px] text-rose-400 font-medium mt-1.5 block flex items-center gap-1">
                                <span>⚠️</span> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    @if ($product->input_type === 'id_and_zone')
                        <div>
                            <label class="text-xs font-bold text-slate-300 flex items-center justify-between mb-1.5">
                                <span>Server / Zone ID</span>
                                <span class="text-[10px] text-slate-500 font-normal">Contoh: 2024</span>
                            </label>
                            <input type="text" wire:model.live.debounce.600ms="zoneId" placeholder="Misal: (2024)"
                                class="w-full px-4 py-3 rounded-2xl bg-slate-950/80 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-white placeholder-slate-600 text-sm font-semibold transition" />
                            @error('zoneId')
                                <span class="text-[11px] text-rose-400 font-medium mt-1.5 block flex items-center gap-1">
                                    <span>⚠️</span> {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Indikator Feedback Cek Nickname Radar Scanner -->
                <div class="mt-4">
                    <div wire:loading wire:target="userId, zoneId"
                        class="p-3 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center gap-2.5 text-xs text-indigo-400 font-bold animate-pulse">
                        <svg class="w-4 h-4 animate-spin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Radar Sistem: Memeriksa nickname & status akun ke server game...</span>
                    </div>

                    @if (!empty($validatedUsername))
                        <div wire:loading.remove wire:target="userId, zoneId"
                            class="p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold">Nickname Terverifikasi:</span>
                                    <span class="text-emerald-400 font-black text-sm sm:text-base tracking-wide">{{ $validatedUsername }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] uppercase tracking-widest font-black text-emerald-300 bg-emerald-500/20 border border-emerald-500/30 px-2.5 py-1 rounded-lg self-start sm:self-auto">
                                Akun Valid ✓
                            </span>
                        </div>
                    @endif

                    @if (!empty($usernameError))
                        <div wire:loading.remove wire:target="userId, zoneId"
                            class="p-3 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-center gap-2.5 text-xs text-rose-400 font-semibold">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $usernameError }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ================= STEP 2: PILIH ITEM / NOMINAL MEWAH ================= -->
            <div class="rounded-3xl border border-slate-800/90 bg-gradient-to-b from-[#0f1523]/95 via-[#0c101c]/95 to-[#080c15] p-6 sm:p-7 shadow-2xl backdrop-blur-2xl relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 text-white font-black text-sm shadow-lg shadow-indigo-500/30">
                            2
                        </span>
                        <div>
                            <h2 class="text-base font-black uppercase tracking-wide text-white">Pilih Nominal Item</h2>
                            <p class="text-xs text-slate-400">Pilih paket nominal diamond, voucher, atau koin yang Anda butuhkan.</p>
                        </div>
                    </div>
                </div>

                <!-- Grid Item Nominal Kelas Dunia (Bukan card biasa) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                    @foreach ($product->items as $index => $item)
                        @php
                            $isReseller = auth()->check() && auth()->user()->isReseller() && !empty($item->reseller_price);
                            $displayPrice = $isReseller ? $item->reseller_price : $item->selling_price;
                            $isSelected = ($selectedItemId === $item->id);
                        @endphp
                        
                        <button type="button" wire:click="selectItem({{ $item->id }})"
                            class="group relative rounded-2xl p-4 text-left transition-all duration-200 cursor-pointer overflow-hidden border {{ $isSelected ? 'border-indigo-500 bg-gradient-to-br from-indigo-950/50 via-slate-900 to-indigo-900/40 shadow-xl shadow-indigo-500/20 ring-2 ring-indigo-500/40' : 'border-slate-800/90 bg-slate-950/60 hover:border-slate-700 hover:bg-slate-900/80' }}">
                            
                            <!-- Shimmer Light on Selected -->
                            @if ($isSelected)
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-indigo-500/20 rounded-full blur-xl pointer-events-none"></div>
                            @endif

                            <!-- Header Card: Icon & Tag -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="h-7 w-7 rounded-lg {{ $isSelected ? 'bg-indigo-500/30 text-cyan-300' : 'bg-slate-900 text-slate-400' }} flex items-center justify-center text-xs">
                                    💎
                                </div>
                                @if ($index === 0 || $index === 2)
                                    <span class="text-[9px] uppercase tracking-wider font-black text-amber-300 bg-amber-500/20 border border-amber-500/30 px-2 py-0.5 rounded-md">
                                        Best Seller
                                    </span>
                                @else
                                    <span class="text-[9px] uppercase tracking-wider font-bold text-slate-500">
                                        Instan
                                    </span>
                                @endif
                            </div>

                            <!-- Item Name -->
                            <div class="text-xs font-extrabold text-white group-hover:text-cyan-300 transition-colors line-clamp-2 leading-snug">
                                {{ $item->name }}
                            </div>

                            <!-- Item Price -->
                            <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex items-end justify-between">
                                <div>
                                    <span class="text-sm font-black {{ $isSelected ? 'text-cyan-400' : 'text-indigo-400' }}">
                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    </span>
                                    @if ($isReseller)
                                        <span class="block text-[9px] text-amber-400 font-bold uppercase tracking-wider">Harga Mitra VIP</span>
                                    @endif
                                </div>
                                
                                @if ($isSelected)
                                    <div class="h-5 w-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] shadow">
                                        ✓
                                    </div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- ================= STEP 3: METODE PEMBAYARAN MULTI-CHANNEL ================= -->
            <div class="rounded-3xl border border-slate-800/90 bg-gradient-to-b from-[#0f1523]/95 via-[#0c101c]/95 to-[#080c15] p-6 sm:p-7 shadow-2xl backdrop-blur-2xl relative overflow-hidden">
                <div class="flex items-center gap-3.5 mb-5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 text-white font-black text-sm shadow-lg shadow-indigo-500/30">
                        3
                    </span>
                    <div>
                        <h2 class="text-base font-black uppercase tracking-wide text-white">Metode Pembayaran</h2>
                        <p class="text-xs text-slate-400">Pilih channel pembayaran favorit dengan konfirmasi instan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($paymentMethods as $pay)
                        @php $isPaySelected = ($selectedPaymentId === $pay->id); @endphp
                        <button type="button" wire:click="selectPayment({{ $pay->id }})"
                            class="group rounded-2xl p-3.5 text-left transition-all duration-200 cursor-pointer border flex items-center justify-between {{ $isPaySelected ? 'border-indigo-500 bg-gradient-to-r from-indigo-950/40 to-slate-900 shadow-lg ring-1 ring-indigo-500' : 'border-slate-800/90 bg-slate-950/60 hover:border-slate-700 hover:bg-slate-900/80' }}">
                            
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl {{ $isPaySelected ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400' }} flex items-center justify-center font-black text-xs shrink-0">
                                    💳
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white group-hover:text-indigo-400 transition-colors">
                                        {{ $pay->name }}
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-mono uppercase">
                                        {{ $pay->code }} • Otomatis
                                    </span>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-[10px] font-bold text-emerald-400 block">
                                    {{ $pay->fee_flat > 0 ? '+Rp ' . number_format($pay->fee_flat, 0, ',', '.') : 'Bebas Admin' }}
                                </span>
                                @if ($isPaySelected)
                                    <span class="text-[9px] text-indigo-400 font-bold uppercase">Terpilih ✓</span>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- ================= STEP 4: KUPON PROMO INTERAKTIF ================= -->
            <div class="rounded-3xl border border-slate-800/90 bg-gradient-to-b from-[#0f1523]/95 via-[#0c101c]/95 to-[#080c15] p-6 sm:p-7 shadow-2xl backdrop-blur-2xl relative overflow-hidden">
                <div class="flex items-center gap-3.5 mb-5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 text-white font-black text-sm shadow-lg shadow-indigo-500/30">
                        4
                    </span>
                    <div>
                        <h2 class="text-base font-black uppercase tracking-wide text-white">Kupon Promo & Diskon (Opsional)</h2>
                        <p class="text-xs text-slate-400">Punya kode voucher? Masukkan di sini untuk potongan harga ekstra.</p>
                    </div>
                </div>

                <!-- Input Kode Kupon -->
                <div class="flex flex-col sm:flex-row gap-2.5">
                    <div class="relative flex-1">
                        <input type="text" wire:model="promoInput" placeholder="Ketik kode kupon (misal: ABSOLUTEBARU)"
                            :disabled="{{ $appliedPromo ? 'true' : 'false' }}"
                            class="w-full px-4 py-3 rounded-2xl bg-slate-950/80 border border-slate-800 focus:border-indigo-500 text-white font-mono uppercase text-sm placeholder-slate-600 disabled:opacity-60" />
                    </div>

                    @if (!$appliedPromo)
                        <button type="button" wire:click="applyPromo" wire:loading.attr="disabled"
                            class="px-6 py-3 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-indigo-600/30 active:scale-95 cursor-pointer flex items-center justify-center gap-2 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span wire:loading.remove wire:target="applyPromo">Klaim Kupon</span>
                            <span wire:loading wire:target="applyPromo">Memeriksa...</span>
                        </button>
                    @else
                        <button type="button" wire:click="removePromo"
                            class="px-5 py-3 rounded-2xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 text-xs font-bold transition cursor-pointer flex items-center justify-center gap-2 shrink-0">
                            <span>✕ Hapus Kupon</span>
                        </button>
                    @endif
                </div>

                <!-- Rekomendasi Kupon Cepat Tersedia -->
                @if (isset($activePromos) && count($activePromos) > 0 && !$appliedPromo)
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-[11px] text-slate-500 font-semibold">Kupon Tersedia:</span>
                        @foreach ($activePromos as $promo)
                            <button type="button" wire:click="setPromo('{{ $promo->code }}')"
                                class="px-2.5 py-1 rounded-lg bg-slate-900 hover:bg-indigo-600/20 border border-slate-800 hover:border-indigo-500/40 text-[11px] font-mono font-bold text-indigo-400 transition cursor-pointer">
                                {{ $promo->code }}
                            </button>
                        @endforeach
                    </div>
                @endif

                @if ($promoMessage)
                    <div class="p-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold mt-3 flex items-center gap-2">
                        <span>🎉</span>
                        <span>{{ $promoMessage }}</span>
                    </div>
                @endif

                @if ($promoError)
                    <div class="p-3 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-bold mt-3 flex items-center gap-2">
                        <span>⚠️</span>
                        <span>{{ $promoError }}</span>
                    </div>
                @endif
            </div>

            <!-- ================= STEP 5: WHATSAPP, DOMPET & FINAL CHECKOUT ================= -->
            <div class="rounded-3xl border border-slate-800/90 bg-gradient-to-b from-[#0f1523]/95 via-[#0c101c]/95 to-[#080c15] p-6 sm:p-7 shadow-2xl backdrop-blur-2xl relative overflow-hidden space-y-5">
                <div class="flex items-center gap-3.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-400 text-white font-black text-sm shadow-lg shadow-indigo-500/30">
                        5
                    </span>
                    <div>
                        <h2 class="text-base font-black uppercase tracking-wide text-white">Nomor WhatsApp & Konfirmasi Pembayaran</h2>
                        <p class="text-xs text-slate-400">Nomor ini digunakan untuk pengiriman struk resmi dan bukti SN voucher.</p>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-300 block mb-1.5">Nomor WhatsApp Aktif</label>
                    <div class="relative">
                        <input type="text" wire:model="contactNumber" placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-3 rounded-2xl bg-slate-950/80 border border-slate-800 focus:border-indigo-500 text-white text-sm font-semibold transition placeholder-slate-600" />
                    </div>
                    @error('contactNumber')
                        <span class="text-[11px] text-rose-400 font-bold mt-1.5 block">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <!-- Kotak Status Saldo Dompet (Jika Login) -->
                @auth
                    @php
                        $userBalance = (float) auth()->user()->balance;
                        $itemSelected = \App\Models\ProductItem::find($selectedItemId);
                        $itemCost = $itemSelected
                            ? (auth()->user()->isReseller() && !empty($itemSelected->reseller_price)
                                ? $itemSelected->reseller_price
                                : $itemSelected->selling_price)
                            : 0;
                        $finalBalanceCost = max(0, $itemCost - $discountValue);
                        $isBalanceEnough = $userBalance >= $finalBalanceCost;
                    @endphp

                    <div class="rounded-2xl p-4 border transition-all {{ $isBalanceEnough ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-slate-950/80 border-slate-800' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl {{ $isBalanceEnough ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-900 text-slate-400' }} flex items-center justify-center font-bold text-base shrink-0">
                                    💰
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white">Saldo Dompet Anda:</span>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ auth()->user()->isReseller() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-slate-800 text-slate-400' }}">
                                            {{ auth()->user()->tier }}
                                        </span>
                                    </div>
                                    <div class="text-base font-black {{ $isBalanceEnough ? 'text-emerald-400' : 'text-white' }} mt-0.5">
                                        Rp {{ number_format($userBalance, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            @if ($isBalanceEnough)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 bg-emerald-500/20 border border-emerald-500/30 px-3 py-1 rounded-xl">
                                    ✓ Cukup untuk Bayar Instan
                                </span>
                            @else
                                <div class="text-left sm:text-right">
                                    <span class="text-[11px] text-rose-400 font-bold block">
                                        Kurang Rp {{ number_format($finalBalanceCost - $userBalance, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('dashboard') }}" class="text-[11px] text-indigo-400 hover:underline font-semibold">
                                        Top Up Saldo di Dashboard &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endauth

                <!-- Order Calculation Summary Breakdown -->
                <div class="p-5 rounded-2xl bg-slate-950/90 border border-slate-800/80 space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-400">
                        <span>Harga Item ({{ $product->name }})</span>
                        <span class="font-bold text-slate-200">
                            Rp {{ number_format($itemCost ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    @if ($discountValue > 0)
                        <div class="flex justify-between text-rose-400 font-bold">
                            <span>Potongan Diskon Kupon ({{ $appliedPromo?->code }})</span>
                            <span>- Rp {{ number_format($discountValue, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider block">Total Tagihan Bersih:</span>
                            <div class="text-2xl sm:text-3xl font-black text-emerald-400 font-mono">
                                Rp {{ number_format($totalPay ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Action Buttons Container -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                            @auth
                                <button type="button" wire:click="payWithBalance" wire:loading.attr="disabled"
                                    class="px-5 py-3 rounded-2xl font-black text-xs transition-all flex items-center justify-center gap-2 cursor-pointer shadow-lg {{ $isBalanceEnough ? 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white shadow-emerald-500/25 active:scale-95' : 'bg-slate-900 text-slate-600 border border-slate-800 cursor-not-allowed' }}"
                                    {{ $isBalanceEnough ? '' : 'disabled' }}>
                                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span wire:loading.remove wire:target="payWithBalance">Bayar Instan Saldo</span>
                                    <span wire:loading wire:target="payWithBalance">Memproses...</span>
                                </button>
                            @endauth

                            <button wire:click="checkout" type="button" wire:loading.attr="disabled"
                                class="px-7 py-3 rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-black text-xs shadow-xl shadow-indigo-600/30 hover:shadow-cyan-500/25 transition-all duration-200 active:scale-95 disabled:opacity-50 cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span wire:loading.remove wire:target="checkout">Bayar Sekarang &rarr;</span>
                                <span wire:loading wire:target="checkout">Membuat Invoice...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Security Guarantee Seals -->
                <div class="pt-3 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-[11px] text-slate-500 border-t border-slate-800/60">
                    <span class="flex items-center gap-1.5"><span class="text-emerald-400">🛡️</span> Garansi 100% Legal</span>
                    <span class="flex items-center gap-1.5"><span class="text-cyan-400">⚡</span> Pengisian H2H Langsung</span>
                    <span class="flex items-center gap-1.5"><span class="text-indigo-400">🔒</span> Enkripsi SSL 256-Bit</span>
                </div>

            </div>

        </div>

    </div>

    <!-- ================= FLOATING MOBILE STICKY CHECKOUT BAR ================= -->
    <div class="fixed bottom-0 left-0 right-0 z-30 lg:hidden p-3 bg-[#0b0f19]/95 border-t border-slate-800 backdrop-blur-xl shadow-2xl">
        <div class="max-w-md mx-auto flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Total Tagihan:</span>
                <span class="text-lg font-black text-emerald-400 font-mono">
                    Rp {{ number_format($totalPay ?? 0, 0, ',', '.') }}
                </span>
            </div>

            <button type="button" wire:click="checkout" wire:loading.attr="disabled"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 flex items-center gap-1.5">
                <span wire:loading.remove wire:target="checkout">Bayar Sekarang &rarr;</span>
                <span wire:loading wire:target="checkout">Proses...</span>
            </button>
        </div>
    </div>

</div>
