<div class="space-y-8 pb-12">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Pengaturan Sistem
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20">
                    Konfigurasi Global
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Kelola kredensial API Digiflazz, Tripay, WhatsApp Bot, serta saklar darurat tanpa menyentuh file .env.
            </p>
        </div>
    </div>

    <!-- ================= 1. EMERGENCY KILLSWITCH & PROVIDER ROUTING ================= -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-2xl backdrop-blur-xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200 dark:border-slate-800">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center text-white font-black text-sm shadow-md">
                ⚡
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                    Saklar Darurat & Provider Routing
                </h3>
                <p class="text-[11px] text-slate-400">Kontrol ketersediaan sistem dan alur failover supplier utama</p>
            </div>
        </div>

        @if(session()->has('success_system'))
            <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                {{ session('success_system') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
            <!-- Maintenance Mode Switch -->
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800 dark:text-white block">Mode Pemeliharaan (Maintenance)</span>
                        <span class="text-[11px] text-slate-400">Nonaktifkan pemesanan saat terjadi maintenance besar</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="maintenanceMode" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                    </label>
                </div>

                @if($maintenanceMode)
                    <div class="pt-2">
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Pesan untuk Pengguna:</label>
                        <input type="text" wire:model="maintenanceMessage"
                               class="w-full rounded-xl border border-slate-700 bg-slate-900 p-2.5 text-xs text-white">
                    </div>
                @endif
            </div>

            <!-- Provider PPOB Utama -->
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 space-y-3">
                <div>
                    <span class="font-bold text-slate-800 dark:text-white block">Jalur Supplier PPOB Aktif</span>
                    <span class="text-[11px] text-slate-400">Arahkan pengiriman transaksi ke supplier prioritas</span>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-1">
                    <button type="button" wire:click="$set('activePPOBProvider', 'digiflazz')"
                            class="py-2.5 rounded-xl border text-center font-bold text-[11px] transition {{ $activePPOBProvider === 'digiflazz' ? 'bg-indigo-600 border-indigo-500 text-white shadow-md' : 'border-slate-700 text-slate-400' }}">
                        Digiflazz (Utama)
                    </button>
                    <button type="button" wire:click="$set('activePPOBProvider', 'tokovoucher')"
                            class="py-2.5 rounded-xl border text-center font-bold text-[11px] transition {{ $activePPOBProvider === 'tokovoucher' ? 'bg-indigo-600 border-indigo-500 text-white shadow-md' : 'border-slate-700 text-slate-400' }}">
                        Tokovoucher
                    </button>
                    <button type="button" wire:click="$set('activePPOBProvider', 'vip')"
                            class="py-2.5 rounded-xl border text-center font-bold text-[11px] transition {{ $activePPOBProvider === 'vip' ? 'bg-indigo-600 border-indigo-500 text-white shadow-md' : 'border-slate-700 text-slate-400' }}">
                        VIP Reseller
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" wire:click="saveSystemSwitches"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-bold text-xs shadow-lg shadow-indigo-600/20 hover:scale-105 transition">
                Simpan Konfigurasi Saklar
            </button>
        </div>
    </div>

    <!-- ================= 2. DIGIFLAZZ PPOB API CREDENTIALS ================= -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-2xl backdrop-blur-xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200 dark:border-slate-800">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-cyan-600 to-blue-500 flex items-center justify-center text-white font-black text-sm shadow-md">
                DF
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                    Kredensial API Digiflazz
                </h3>
                <p class="text-[11px] text-slate-400">Digunakan untuk sinkronisasi harga & eksekusi top up otomatis</p>
            </div>
        </div>

        @if(session()->has('success_digiflazz'))
            <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                {{ session('success_digiflazz') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Username Digiflazz</label>
                <input type="text" wire:model="digiflazzUsername" placeholder="Username akun Digiflazz"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Production API Key (AES-256)</label>
                <input type="password" wire:model="digiflazzApiKey" placeholder="Biarkan kosong jika tidak diubah"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Webhook Secret</label>
                <input type="password" wire:model="digiflazzWebhookSecret" placeholder="Biarkan kosong jika tidak diubah"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" wire:click="saveDigiflazzSettings"
                    class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs shadow-lg shadow-cyan-600/20 hover:scale-105 transition">
                Simpan API Digiflazz
            </button>
        </div>
    </div>

    <!-- ================= 3. TRIPAY PAYMENT GATEWAY ================= -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-2xl backdrop-blur-xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200 dark:border-slate-800">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-sm shadow-md">
                TP
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                    Kredensial Tripay Payment Gateway
                </h3>
                <p class="text-[11px] text-slate-400">Gateway pemrosesan QRIS dinamis, Virtual Account, dan Minimarket</p>
            </div>
        </div>

        @if(session()->has('success_tripay'))
            <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                {{ session('success_tripay') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Merchant Code</label>
                <input type="text" wire:model="tripayMerchantCode" placeholder="Contoh: T12345"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">API Key</label>
                <input type="password" wire:model="tripayApiKey" placeholder="Biarkan kosong jika tidak diubah"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Private Key (HMAC)</label>
                <input type="password" wire:model="tripayPrivateKey" placeholder="Biarkan kosong jika tidak diubah"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" wire:click="saveTripaySettings"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/20 hover:scale-105 transition">
                Simpan API Tripay
            </button>
        </div>
    </div>

    <!-- ================= 4. WHATSAPP BOT (BAILEYS) ================= -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-2xl backdrop-blur-xl space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200 dark:border-slate-800">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-green-600 to-emerald-500 flex items-center justify-center text-white font-black text-sm shadow-md">
                WA
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                    WhatsApp Gateway Notification (Baileys Engine)
                </h3>
                <p class="text-[11px] text-slate-400">Koneksi notifikasi pesan instan ke nomor WhatsApp pelanggan</p>
            </div>
        </div>

        @if(session()->has('success_baileys'))
            <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                {{ session('success_baileys') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Microservice Endpoint URL</label>
                <input type="url" wire:model="baileysEndpoint" placeholder="http://127.0.0.1:3000"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Bearer Auth Token</label>
                <input type="password" wire:model="baileysToken" placeholder="Biarkan kosong jika tidak diubah"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white font-mono">
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" wire:click="saveBaileysSettings"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/20 hover:scale-105 transition">
                Simpan API WhatsApp
            </button>
        </div>
    </div>

</div>
