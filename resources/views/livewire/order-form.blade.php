<div class="mx-auto max-w-5xl px-4 py-8 text-slate-200">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <!-- Kolom Kiri: Detail Produk -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 backdrop-blur-xl h-fit">
            <div
                class="aspect-video w-full rounded-xl bg-slate-950 border border-slate-800 overflow-hidden flex items-center justify-center">
                @if ($product->thumbnail)
                    <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                        class="h-full w-full object-cover">
                @else
                    <div
                        class="h-full w-full bg-gradient-to-tr from-indigo-900 to-purple-900 flex items-center justify-center text-3xl font-black text-indigo-400/40">
                        {{ strtoupper(substr($product->name, 0, 4)) }}
                    </div>
                @endif
            </div>
            <h1 class="mt-4 text-2xl font-black text-white tracking-tight">{{ $product->name }}</h1>
            <p class="mt-1 text-xs text-slate-400">Proses instan 1-5 detik otomatis 24 jam.</p>
        </div>

        <!-- Kolom Kanan: Langkah Pemesanan -->
        <div class="space-y-6 md:col-span-2">

            <!-- Step 1: Data Akun & Validasi Nickname -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">1</span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300">Masukkan Data Akun</h2>
                </div>

                <!-- Quick-Select Akun Tersimpan (Jika Member Memilikinya) -->
                @if (!empty($userSavedAccounts) && count($userSavedAccounts) > 0)
                    <div class="mb-4 p-3 rounded-xl bg-slate-950 border border-slate-800">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Gunakan
                            Akun Tersimpan:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($userSavedAccounts as $saved)
                                <button type="button" wire:click="applySavedAccount({{ $saved->id }})"
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $userId == $saved->target_account ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-900 border border-slate-800 text-slate-300 hover:text-white' }}">
                                    <span>{{ $saved->account_name ?: $saved->target_account }}</span>
                                    @if ($saved->nickname)
                                        <span class="text-[10px] text-indigo-300">({{ $saved->nickname }})</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="{{ $product->input_type === 'id_and_zone' ? '' : 'sm:col-span-2' }}">
                        <label class="text-xs font-semibold text-slate-400">
                            @if ($product->input_type === 'id_and_zone' || $product->input_type === 'id_only')
                                User ID
                            @elseif($product->input_type === 'phone_number')
                                Nomor Handphone
                            @elseif($product->input_type === 'meter_number')
                                Nomor Meter / ID Pelanggan
                            @else
                                User ID / Nomor Tujuan
                            @endif
                        </label>
                        <input type="text" wire:model.live.debounce.600ms="userId" placeholder="Misal: 12345678"
                            class="mt-1 w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition" />
                        @error('userId')
                            <span class="text-[11px] text-rose-500 font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($product->input_type === 'id_and_zone')
                        <div>
                            <label class="text-xs font-semibold text-slate-400">Zone ID / Server</label>
                            <input type="text" wire:model.live.debounce.600ms="zoneId" placeholder="Misal: 2024"
                                class="mt-1 w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition" />
                            @error('zoneId')
                                <span class="text-[11px] text-rose-500 font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Indikator Feedback Cek Nickname -->
                <div class="mt-3">
                    <div wire:loading wire:target="userId, zoneId"
                        class="flex items-center gap-2 text-xs text-indigo-400 font-medium animate-pulse py-1">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Memeriksa nama akun/nickname...</span>
                    </div>

                    @if (!empty($validatedUsername))
                        <div wire:loading.remove wire:target="userId, zoneId"
                            class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div
                                    class="h-5 w-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-slate-400 font-medium">Nama Akun:</span>
                                <span class="text-emerald-400 font-black">{{ $validatedUsername }}</span>
                            </div>
                            <span
                                class="text-[9px] uppercase tracking-wider font-bold text-emerald-400 bg-emerald-500/20 px-2 py-0.5 rounded-md">Terverifikasi</span>
                        </div>
                    @endif

                    @if (!empty($usernameError))
                        <div wire:loading.remove wire:target="userId, zoneId"
                            class="p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center gap-2 text-xs text-rose-400 font-semibold">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $usernameError }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Step 2: Pilih Nominal -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">2</span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300">Pilih Item / Nominal</h2>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($product->items as $item)
                        @php
                            $isReseller =
                                auth()->check() && auth()->user()->isReseller() && !empty($item->reseller_price);
                            $displayPrice = $isReseller ? $item->reseller_price : $item->selling_price;
                        @endphp
                        <button type="button" wire:click="selectItem({{ $item->id }})"
                            class="flex flex-col justify-between rounded-xl border p-3.5 text-left transition-all duration-200 {{ $selectedItemId === $item->id ? 'border-indigo-500 bg-indigo-600/10 shadow-lg shadow-indigo-500/10' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700' }}">
                            <span class="text-xs font-semibold text-white">{{ $item->name }}</span>
                            <div class="mt-2">
                                <span class="text-xs font-bold text-indigo-400">Rp
                                    {{ number_format($displayPrice, 0, ',', '.') }}</span>
                                @if ($isReseller)
                                    <span class="block text-[9px] text-amber-400 font-bold">Harga Reseller</span>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Step 3: Pilih Metode Bayar -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">3</span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300">Metode Pembayaran</h2>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach ($paymentMethods as $pay)
                        <button type="button" wire:click="selectPayment({{ $pay->id }})"
                            class="flex items-center justify-between rounded-xl border p-3 transition-all duration-200 {{ $selectedPaymentId === $pay->id ? 'border-indigo-500 bg-indigo-600/10' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700' }}">
                            <span class="text-xs font-medium text-white">{{ $pay->name }}</span>
                            <span
                                class="rounded bg-slate-800 px-2 py-0.5 text-[10px] text-slate-400 uppercase font-mono">{{ $pay->code }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Step 4: Kode Promo Diskon -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">4</span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300">Kupon Promo / Diskon
                        (Opsional)</h2>
                </div>

                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="text" wire:model="promoInput" placeholder="Ketik kode (misal: ABSOLUTEHEMAT)"
                            :disabled="{{ $appliedPromo ? 'true' : 'false' }}"
                            class="w-full uppercase rounded-xl border border-slate-800 bg-slate-950 px-4 py-2.5 text-sm font-mono text-white placeholder-slate-600 focus:border-indigo-500 focus:outline-none transition disabled:opacity-60" />
                    </div>

                    @if (!$appliedPromo)
                        <button type="button" wire:click="applyPromo" wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-md">
                            Gunakan
                        </button>
                    @else
                        <button type="button" wire:click="removePromo"
                            class="px-4 py-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 text-xs font-bold transition">
                            Hapus
                        </button>
                    @endif
                </div>

                @if ($promoMessage)
                    <p class="text-xs text-emerald-400 font-medium mt-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $promoMessage }}
                    </p>
                @endif

                @if ($promoError)
                    <p class="text-xs text-rose-400 font-medium mt-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $promoError }}
                    </p>
                @endif
            </div>

            <!-- Step 5: Kontak, Saldo Member & Eksekusi Pembayaran -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md space-y-4">
                <div class="flex items-center gap-3">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">5</span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300">Nomor WhatsApp & Konfirmasi
                        Pembayaran</h2>
                </div>

                <div>
                    <label class="text-xs text-slate-400 mb-1 block">Nomor WhatsApp atau Email (Bukti
                        Transaksi)</label>
                    <input type="text" wire:model="contactNumber"
                        placeholder="Nomor WhatsApp untuk bukti invoice (08xx)"
                        class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition" />
                    @error('contactNumber')
                        <span class="text-[11px] text-rose-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- KOTAK INDIKATOR SALDO MEMBER -->
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

                    <div
                        class="rounded-2xl border p-4 transition-all {{ $isBalanceEnough ? 'border-emerald-500/30 bg-emerald-950/15' : 'border-slate-800 bg-slate-950' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 rounded-xl {{ $isBalanceEnough ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800 text-slate-400' }} flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white">Saldo Dompet Akun</span>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wider {{ auth()->user()->isReseller() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-slate-800 text-slate-400' }}">
                                            {{ auth()->user()->tier }}
                                        </span>
                                    </div>
                                    <div
                                        class="text-sm font-black mt-0.5 {{ $isBalanceEnough ? 'text-emerald-400' : 'text-slate-300' }}">
                                        Rp {{ number_format($userBalance, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            @if ($isBalanceEnough)
                                <span
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20 self-start sm:self-auto">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Cukup untuk Bayar Instan (Bebas Admin)
                                </span>
                            @else
                                <div class="text-right">
                                    <span class="text-[10px] text-rose-400 font-bold block">Kurang Rp
                                        {{ number_format($finalBalanceCost - $userBalance, 0, ',', '.') }}</span>
                                    <a href="{{ route('dashboard') }}"
                                        class="text-[10px] text-indigo-400 hover:underline font-semibold">Isi Deposit di
                                        Dashboard &rarr;</a>
                                </div>
                            @endif
                        </div>

                        @error('balance')
                            <span class="text-[11px] text-rose-400 font-bold mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>
                @endauth

                <!-- Rincian Biaya & Diskon -->
                <div class="border-t border-slate-800 pt-4 space-y-2 text-xs">
                    @if ($discountValue > 0)
                        <div class="flex items-center justify-between text-slate-400">
                            <span>Potongan Diskon Kupon ({{ $appliedPromo?->code }})</span>
                            <span class="text-rose-400 font-bold">- Rp
                                {{ number_format($discountValue, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                        <div>
                            <span class="block text-[11px] text-slate-400 uppercase tracking-wider">Total
                                Tagihan</span>
                            <span class="text-2xl font-black text-emerald-400">Rp
                                {{ number_format($totalPay, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            <!-- Tombol Bayar Instan Pakai Saldo (Jika Login) -->
                            @auth
                                <button type="button" wire:click="payWithBalance" wire:loading.attr="disabled"
                                    class="px-5 py-3 rounded-xl font-bold text-xs transition flex items-center justify-center gap-2 {{ $isBalanceEnough ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 active:scale-95' : 'bg-slate-800 text-slate-500 cursor-not-allowed' }}"
                                    {{ $isBalanceEnough ? '' : 'disabled' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span wire:loading.remove wire:target="payWithBalance">Potong Saldo Akun</span>
                                    <span wire:loading wire:target="payWithBalance">Memproses Saldo...</span>
                                </button>
                            @endauth

                            <!-- Tombol Checkout Reguler (QRIS / VA Gateway) -->
                            <button wire:click="checkout" type="button" wire:loading.attr="disabled"
                                class="rounded-xl bg-gradient-to-r from-indigo-600 to-pink-600 px-6 py-3 text-xs font-bold text-white shadow-lg shadow-indigo-500/25 transition-all duration-200 hover:brightness-110 active:scale-95 disabled:opacity-50">
                                <span wire:loading.remove wire:target="checkout">Bayar via Gateway</span>
                                <span wire:loading wire:target="checkout">Membuat Invoice...</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
