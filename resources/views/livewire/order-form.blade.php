<div class="mx-auto max-w-5xl px-4 py-8 text-slate-200">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <!-- Kolom Kiri: Detail Produk -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 backdrop-blur-xl h-fit">
            <div
                class="h-40 w-full rounded-xl bg-gradient-to-tr from-indigo-900 to-purple-900 flex items-center justify-center text-4xl font-black text-indigo-400/30">
                AS STORE
            </div>
            <h1 class="mt-4 text-2xl font-black text-white">{{ $product->name }}</h1>
            <p class="mt-1 text-xs text-slate-400">Proses instan 1-5 detik otomatis 24 jam.</p>
        </div>

        <!-- Kolom Kanan: 4 Langkah Pemesanan -->
        <div class="space-y-6 md:col-span-2">
            <!-- Step 1: Data Akun -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">1</span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300">Masukkan Data Akun</h2>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs text-slate-400">User ID / Nomor Tujuan</label>
                        <input type="text" wire:model="userId" placeholder="Misal: 12345678"
                            class="mt-1 w-full rounded-xl border-slate-800 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        @error('userId')
                            <span class="text-[11px] text-rose-500">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($product->input_type === 'id_and_zone')
                        <div>
                            <label class="text-xs text-slate-400">Zone ID / Server</label>
                            <input type="text" wire:model="zoneId" placeholder="(1234)"
                                class="mt-1 w-full rounded-xl border-slate-800 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
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
                        <button type="button" wire:click="selectItem({{ $item->id }})"
                            class="flex flex-col justify-between rounded-xl border p-3.5 text-left transition-all duration-200 {{ $selectedItemId === $item->id ? 'border-indigo-500 bg-indigo-600/10 shadow-lg shadow-indigo-500/10' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700' }}">
                            <span class="text-xs font-semibold text-white">{{ $item->name }}</span>
                            <span class="mt-2 text-xs font-bold text-indigo-400">Rp
                                {{ number_format($item->selling_price, 0, ',', '.') }}</span>
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

            <!-- Step 4: Kontak & Checkout Button -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">4</span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300">Nomor WhatsApp & Bayar</h2>
                </div>
                <input type="text" wire:model="contactNumber"
                    placeholder="Nomor WhatsApp untuk bukti transaksi (08xx)"
                    class="w-full rounded-xl border-slate-800 bg-slate-950 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />

                <div class="mt-6 flex items-center justify-between border-t border-slate-800 pt-4">
                    <div>
                        <span class="block text-[11px] text-slate-400 uppercase tracking-wider">Total Pembayaran</span>
                        <span class="text-xl font-black text-emerald-400">Rp
                            {{ number_format($totalPay, 0, ',', '.') }}</span>
                    </div>
                    <button wire:click="checkout" type="button"
                        class="rounded-xl bg-gradient-to-r from-indigo-600 to-pink-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition-all duration-200 hover:brightness-110 active:scale-95">
                        Beli Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
