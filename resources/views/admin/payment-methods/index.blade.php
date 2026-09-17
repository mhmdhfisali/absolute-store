<x-app-layout>
    <div class="space-y-6">

        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-white">&times;</button>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">Pengaturan Payment Gateway</h1>
                <p class="text-xs text-slate-400 mt-1">Kelola biaya admin tetap (flat), persentase fee, dan channel
                    pembayaran aktif untuk pelanggan.</p>
            </div>
            <span
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 text-xs font-semibold">
                <span class="h-2 w-2 rounded-full bg-indigo-400"></span>
                Total: {{ $paymentMethods->count() }} Saluran Bayar
            </span>
        </div>

        <!-- Table Card -->
        <div class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 shadow-2xl overflow-hidden p-5 space-y-4">
            <div class="overflow-x-auto rounded-xl border border-slate-800">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-[#0a0f1d] border-b border-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-4">Kode & Channel</th>
                            <th class="px-5 py-4">Nama Tampilan</th>
                            <th class="px-5 py-4">Fee Flat (Rp)</th>
                            <th class="px-5 py-4">Fee Persen (%)</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($paymentMethods as $payment)
                            <tr class="hover:bg-slate-800/30 transition">
                                <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-mono font-bold text-indigo-400">{{ $payment->code }}</div>
                                        <span
                                            class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold">{{ $payment->channel_category }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <input type="text" name="name" value="{{ $payment->name }}" required
                                            class="w-56 rounded-xl border border-slate-800 bg-slate-950 px-3 py-1.5 text-xs text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="relative w-32">
                                            <span class="absolute left-2.5 top-1.5 text-slate-500 text-[11px]">Rp</span>
                                            <input type="number" name="fee_flat" value="{{ $payment->fee_flat }}"
                                                min="0" required
                                                class="w-full rounded-xl border border-slate-800 bg-slate-950 pl-8 pr-3 py-1.5 text-xs text-emerald-400 font-mono font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="relative w-28">
                                            <input type="number" step="0.01" name="fee_percent"
                                                value="{{ $payment->fee_percent }}" min="0" max="100"
                                                required
                                                class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-1.5 text-xs text-cyan-400 font-mono font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                            <span class="absolute right-2.5 top-1.5 text-slate-500 text-[11px]">%</span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ $payment->is_active ? 'checked' : '' }} class="sr-only peer">
                                            <div
                                                class="w-9 h-5 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500">
                                            </div>
                                        </label>
                                    </td>

                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <button type="submit"
                                            class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                                            Simpan
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">Belum ada metode
                                    pembayaran yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Petunjuk Rumus Perhitungan Biaya -->
            <div
                class="p-4 rounded-xl border border-slate-800 bg-slate-950/60 text-slate-400 text-[11px] leading-relaxed">
                <span class="text-white font-bold block mb-0.5">Catatan Perhitungan Biaya Admin:</span>
                Total biaya admin per transaksi dihitung dengan rumus: <span
                    class="font-mono text-indigo-400 font-bold">Biaya Admin = Fee Flat + (Harga Produk × Fee Persen /
                    100)</span>. Jika dinonaktifkan (toggle off), opsi pembayaran tidak akan muncul pada form pemesanan
                pelanggan.
            </div>
        </div>

    </div>
</x-app-layout>
