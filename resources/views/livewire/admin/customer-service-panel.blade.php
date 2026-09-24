<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Dispute & Layanan CS
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                    Pusat Resolusi
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Pusat investigasi transaksi kendala, penyelesaian manual, auto-refund, dan respon cepat WhatsApp.
            </p>
        </div>
    </div>

    <!-- Alert Flash Notifications -->
    @if(session()->has('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session()->has('info'))
        <div class="p-4 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="p-4 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80 group">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari invoice, ID akun, telepon..." 
                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-4 py-2.5 pl-10 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
            <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <select wire:model.live="statusFilter" 
                    class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500">
                <option value="all">Semua Status Transaksi</option>
                <option value="failed">❌ Delivery Gagal (Failed)</option>
                <option value="processing">⏳ Sedang Proses (Processing)</option>
                <option value="unpaid">💳 Belum Bayar (Unpaid)</option>
            </select>
        </div>
    </div>

    <!-- Table Transaksi CS -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 shadow-2xl overflow-hidden backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-950/50 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-5">Invoice / Ref</th>
                        <th class="py-3.5 px-5">Target Akun</th>
                        <th class="py-3.5 px-5">Item & Total</th>
                        <th class="py-3.5 px-5">Status Bayar & Kirim</th>
                        <th class="py-3.5 px-5">Serial Number (SN)</th>
                        <th class="py-3.5 px-5 text-right">Tindakan Cepat (CS)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($transactions as $tx)
                        @php
                            $phoneRaw = $tx->contact_email_or_phone ?? '';
                            $phoneClean = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $phoneRaw));
                            $waText = urlencode("Halo {$tx->target_account}, CS Absolute Store di sini terkait pesanan {$tx->invoice_number}. Apakah ada kendala yang bisa kami bantu?");
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- Invoice & User -->
                            <td class="py-3.5 px-5">
                                <div class="font-mono font-bold text-slate-800 dark:text-white">
                                    {{ $tx->invoice_number }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $tx->user?->name ?? 'Tamu (Guest)' }}
                                </div>
                            </td>

                            <!-- Target Akun -->
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-700 dark:text-slate-200">
                                    {{ $tx->target_account }} {{ $tx->target_zone ? "({$tx->target_zone})" : '' }}
                                </div>
                                <div class="text-[10px] font-mono text-slate-400">
                                    {{ $tx->contact_email_or_phone ?? '-' }}
                                </div>
                            </td>

                            <!-- Item & Nominal -->
                            <td class="py-3.5 px-5">
                                <div class="font-medium text-slate-800 dark:text-white truncate max-w-xs">
                                    {{ $tx->item?->name ?? $tx->productItem?->name ?? 'Top Up' }}
                                </div>
                                <div class="font-mono font-bold text-indigo-600 dark:text-cyan-400">
                                    Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                </div>
                            </td>

                            <!-- Status Bayar & Delivery -->
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $tx->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-500' : 'bg-amber-500/15 text-amber-500' }}">
                                        {{ $tx->payment_status }}
                                    </span>
                                </div>
                                <div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $tx->delivery_status === 'success' ? 'bg-emerald-500/15 text-emerald-500' : ($tx->delivery_status === 'failed' ? 'bg-rose-500/15 text-rose-500' : 'bg-cyan-500/15 text-cyan-400') }}">
                                        {{ $tx->delivery_status }}
                                    </span>
                                </div>
                            </td>

                            <!-- Serial Number -->
                            <td class="py-3.5 px-5 font-mono text-[11px] text-slate-400 max-w-xs truncate">
                                {{ $tx->serial_number ?: '-' }}
                            </td>

                            <!-- Tombol Tindakan CS -->
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">

                                    <!-- Re-check API Status -->
                                    <button type="button" wire:click="recheckStatus({{ $tx->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-cyan-500/20 text-slate-600 dark:text-slate-300 hover:text-cyan-400 transition"
                                            title="Re-Check Status ke Provider Digiflazz">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>

                                    <!-- Force Complete -->
                                    <button type="button" wire:click="openCompleteModal({{ $tx->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-500/20 text-slate-600 dark:text-slate-300 hover:text-emerald-400 transition"
                                            title="Selesaikan Pesanan Manual (Force Complete)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>

                                    <!-- Force Refund -->
                                    <button type="button" wire:click="openRefundModal({{ $tx->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-rose-500/20 text-slate-600 dark:text-slate-300 hover:text-rose-400 transition"
                                            title="Kembalikan Dana ke Dompet Pelanggan (Force Refund)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                    </button>

                                    <!-- WhatsApp Deep-Link -->
                                    @if($phoneClean)
                                        <a href="https://wa.me/{{ $phoneClean }}?text={{ $waText }}" target="_blank"
                                           class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-500/20 text-emerald-500 transition"
                                           title="Chat WhatsApp Pelanggan">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                                            </svg>
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Tidak ada data transaksi bermasalah saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- ================= MODAL FORCE COMPLETE ================= -->
    @if($showCompleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-md rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-black text-emerald-500 uppercase tracking-wider">
                        Selesaikan Pesanan Manual
                    </h3>
                    <button type="button" wire:click="$set('showCompleteModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Serial Number (SN) / Kode Voucher</label>
                        <input type="text" wire:model="manualSerialNumber" placeholder="Masukkan SN transaksi resmi..."
                               class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-slate-800 dark:text-white focus:border-emerald-500">
                        @error('manualSerialNumber') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Penyelesaian Admin</label>
                        <input type="text" wire:model="completeAdminNote" placeholder="Contoh: SN diperoleh dari CS Digiflazz via tiket manual"
                               class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white focus:border-emerald-500">
                        @error('completeAdminNote') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('showCompleteModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">
                        Batal
                    </button>
                    <button type="button" wire:click="executeForceComplete" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg">
                        Konfirmasi Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ================= MODAL FORCE REFUND ================= -->
    @if($showRefundModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-md rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h3 class="text-sm font-black text-rose-500 uppercase tracking-wider">
                            Refund Dana Transaksi
                        </h3>
                        <p class="text-[11px] text-slate-400">Invoice: <span class="text-white font-mono font-bold">{{ $refundInvoice }}</span></p>
                    </div>
                    <button type="button" wire:click="$set('showRefundModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-3 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-xs space-y-1">
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Penerima Refund:</span>
                        <strong class="text-white">{{ $refundTargetUser }}</strong>
                    </div>
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Nominal Refund:</span>
                        <strong class="text-rose-400 font-mono">Rp {{ number_format($refundAmount, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alasan Pengembalian Dana</label>
                    <textarea wire:model="refundReason" rows="3" placeholder="Masukkan alasan refund..."
                              class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-xs text-slate-800 dark:text-white focus:border-rose-500"></textarea>
                    @error('refundReason') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('showRefundModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">
                        Batal
                    </button>
                    <button type="button" wire:click="executeForceRefund" class="px-5 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-lg">
                        Proses Refund Instan
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
