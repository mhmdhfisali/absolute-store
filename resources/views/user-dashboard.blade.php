<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4" x-data="{ addAccountModal: false, depositModal: false, selectedInputType: 'id_and_zone' }">

        @if (session('success_account'))
            <div
                class="p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success_account') }}</span>
                </div>
                <button @click="$el.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700 dark:hover:text-white">&times;</button>
            </div>
        @endif

        @if (session('success_deposit'))
            <div
                class="p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success_deposit') }}</span>
                </div>
                <button @click="$el.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700 dark:hover:text-white">&times;</button>
            </div>
        @endif

        <!-- Banner Selamat Datang & Pintasan Saldo -->
        <div
            class="relative overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0d1322] p-6 sm:p-8 shadow-xl dark:shadow-2xl transition-colors duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                            Member Area
                        </span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ Auth::user()->isReseller() ? 'bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                            Tier: {{ strtoupper(Auth::user()->tier) }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mt-2">Halo,
                        {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Saldo dompet Anda: <strong
                            class="text-emerald-600 dark:text-emerald-400 font-mono text-sm">Rp
                            {{ number_format(Auth::user()->balance, 0, ',', '.') }}</strong></p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap">
                    <button type="button" @click="depositModal = true"
                        class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/25 transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Top Up Saldo
                    </button>
                    <button type="button" @click="addAccountModal = true"
                        class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white text-xs font-bold transition cursor-pointer">
                        + Simpan ID Game
                    </button>
                </div>
            </div>
        </div>

        <!-- Akun Game Favorit -->
        <div
            class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/90 p-5 shadow-lg dark:shadow-xl space-y-4 transition-colors duration-200">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h2
                    class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Akun Game Tersimpan
                </h2>
                <button type="button" @click="addAccountModal = true"
                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-bold cursor-pointer">+
                    Tambah</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @forelse($savedAccounts as $acc)
                    <div
                        class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-[#090e1b] p-3.5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs font-bold text-indigo-600 dark:text-indigo-400 truncate">{{ $acc->product->name }}</span>
                                <form action="{{ route('user.saved-accounts.destroy', $acc->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-slate-400 hover:text-rose-500 cursor-pointer">&times;</button>
                                </form>
                            </div>
                            <span
                                class="block text-sm font-bold text-slate-800 dark:text-white mt-1">{{ $acc->account_name ?: 'Akun Game' }}</span>
                            <div class="mt-1 text-xs text-slate-600 dark:text-slate-400 font-mono">
                                ID: <strong
                                    class="text-slate-900 dark:text-slate-200">{{ $acc->target_account }}</strong>
                                @if ($acc->target_zone)
                                    <span>({{ $acc->target_zone }})</span>
                                @endif
                            </div>
                            @if ($acc->nickname)
                                <span
                                    class="text-emerald-600 dark:text-emerald-400 text-[11px] font-bold block mt-1">{{ $acc->nickname }}</span>
                            @endif
                        </div>
                        <div class="mt-3 pt-2 border-t border-slate-200 dark:border-slate-800">
                            <a href="{{ route('order.show', ['slug' => $acc->product->slug, 'saved_id' => $acc->target_account, 'saved_zone' => $acc->target_zone]) }}"
                                class="block w-full text-center py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow">
                                Top Up Cepat &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-4 text-center text-slate-500 text-xs">Belum ada akun game yang
                        disimpan.</div>
                @endforelse
            </div>
        </div>

        <!-- RIWAYAT MUTASI SALDO DOMPET (DEPOSIT TRACKER) -->
        <div
            class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/90 shadow-lg dark:shadow-xl overflow-hidden transition-colors duration-200">
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h2
                    class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Riwayat Deposit Saldo Dompet
                </h2>
                <button type="button" @click="depositModal = true"
                    class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-bold cursor-pointer">+
                    Isi Saldo</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 dark:bg-[#090e1b] border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3">No. Deposit</th>
                            <th class="px-5 py-3">Metode</th>
                            <th class="px-5 py-3">Nominal Masuk</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                        @forelse($myDeposits ?? [] as $depo)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition">
                                <td class="px-5 py-3 font-mono font-bold text-slate-900 dark:text-white">
                                    {{ $depo->deposit_number }}</td>
                                <td class="px-5 py-3">{{ $depo->paymentMethod->name }}</td>
                                <td class="px-5 py-3 font-bold text-emerald-600 dark:text-emerald-400">+ Rp
                                    {{ number_format($depo->amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                                        {{ $depo->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $depo->created_at->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-500">Belum ada mutasi
                                    deposit saldo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Riwayat Transaksi Pesanan -->
        <div
            class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1222]/90 shadow-lg dark:shadow-xl overflow-hidden transition-colors duration-200">
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Riwayat Pesanan
                    Top Up</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 dark:bg-[#090e1b] border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3">Invoice</th>
                            <th class="px-5 py-3">Layanan & Item</th>
                            <th class="px-5 py-3">Tujuan</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3 text-center">Status Bayar</th>
                            <th class="px-5 py-3 text-center">Status Kirim</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                        @forelse($myTransactions as $trx)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition">
                                <td
                                    class="px-5 py-3 whitespace-nowrap font-mono font-bold text-slate-900 dark:text-white">
                                    {{ $trx->invoice_number }}</td>
                                <td class="px-5 py-3 whitespace-nowrap">{{ $trx->productItem?->name ?? '-' }}</td>
                                <td class="px-5 py-3 whitespace-nowrap font-mono">{{ $trx->target_account }}</td>
                                <td
                                    class="px-5 py-3 whitespace-nowrap font-bold text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $trx->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                                        {{ $trx->payment_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $trx->delivery_status === 'success' ? 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }}">
                                        {{ $trx->delivery_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('order.invoice', $trx->invoice_number) }}" target="_blank"
                                        class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs">
                                        Invoice ↗
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-500">Belum ada riwayat
                                    pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($myTransactions->hasPages())
                <div class="p-3 border-t border-slate-100 dark:border-slate-800">
                    {{ $myTransactions->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL FORM DEPOSIT SALDO -->
        <div x-show="depositModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="depositModal = false"
                class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0d1322] p-6 space-y-4 shadow-2xl text-slate-800 dark:text-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Top Up Saldo
                        Dompet</h3>
                    <button @click="depositModal = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
                </div>

                <form action="{{ route('user.deposit.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Nominal Deposit
                            (Rp)</label>
                        <input type="number" name="amount" placeholder="Minimal 10000" min="10000"
                            step="1000" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-slate-900 dark:text-white font-mono font-bold focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Metode
                            Pembayaran</label>
                        <select name="payment_method_id" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none">
                            @foreach ($paymentMethods ?? [] as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="depositModal = false"
                            class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition shadow-lg shadow-emerald-600/30 cursor-pointer">Bayar
                            & Tambah Saldo</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL SIMPAN ID GAME -->
        <div x-show="addAccountModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="addAccountModal = false"
                class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0d1322] p-6 space-y-4 shadow-2xl text-slate-800 dark:text-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Simpan ID
                        Game Favorit</h3>
                    <button @click="addAccountModal = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
                </div>

                <form action="{{ route('user.saved-accounts.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Pilih Layanan</label>
                        <select name="product_id" required
                            @change="selectedInputType = $event.target.selectedOptions[0].getAttribute('data-input-type')"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none">
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" data-input-type="{{ $p->input_type }}">
                                    {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Label Akun
                            (Opsional)</label>
                        <input type="text" name="account_name" placeholder="Misal: Akun Utama / Smurf"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div :class="selectedInputType === 'id_and_zone' ? '' : 'sm:col-span-2'">
                            <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">User ID</label>
                            <input type="text" name="target_account" placeholder="12345678" required
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none">
                        </div>

                        <div x-show="selectedInputType === 'id_and_zone'">
                            <label class="block text-slate-600 dark:text-slate-400 font-bold mb-1">Zone ID</label>
                            <input type="text" name="target_zone" placeholder="(2024)"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="addAccountModal = false"
                            class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold cursor-pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
