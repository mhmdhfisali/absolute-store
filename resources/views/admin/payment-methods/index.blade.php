<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto">

        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="$el.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700 dark:hover:text-white cursor-pointer">&times;</button>
            </div>
        @endif

        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black tracking-tight">Pengaturan Payment Gateway</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola biaya admin tetap (flat), persentase
                    fee, dan saluran pembayaran aktif untuk pelanggan.</p>
            </div>
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl card text-xs font-semibold">
                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                Total: {{ $paymentMethods->count() }} Saluran Bayar
            </span>
        </div>

        <!-- Table Card -->
        <div class="card p-5 space-y-4">
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-left text-xs">
                    <thead class="table-head-theme">
                        <tr>
                            <th class="px-5 py-4">Kode & Channel</th>
                            <th class="px-5 py-4">Nama Tampilan</th>
                            <th class="px-5 py-4">Fee Flat (Rp)</th>
                            <th class="px-5 py-4">Fee Persen (%)</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($paymentMethods as $payment)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $payment->code }}</div>
                                        <span
                                            class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">{{ $payment->channel_category }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <input type="text" name="name" value="{{ $payment->name }}" required
                                            class="form-input-theme w-52 text-xs">
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="relative w-32">
                                            <span class="absolute left-2.5 top-2 text-slate-400 text-[11px]">Rp</span>
                                            <input type="number" name="fee_flat" value="{{ $payment->fee_flat }}"
                                                min="0" required
                                                class="form-input-theme pl-8 text-emerald-600 dark:text-emerald-400 font-mono font-bold text-xs">
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="relative w-28">
                                            <input type="number" step="0.01" name="fee_percent"
                                                value="{{ $payment->fee_percent }}" min="0" max="100"
                                                required
                                                class="form-input-theme text-cyan-600 dark:text-cyan-400 font-mono font-bold text-xs pr-6">
                                            <span class="absolute right-2.5 top-2 text-slate-400 text-[11px]">%</span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ $payment->is_active ? 'checked' : '' }} class="sr-only peer">
                                            <div
                                                class="w-9 h-5 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500">
                                            </div>
                                        </label>
                                    </td>

                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <button type="submit"
                                            class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow transition cursor-pointer">
                                            Simpan
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400">Belum ada metode
                                    pembayaran yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Catatan Rumus -->
            <div
                class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed">
                <span class="font-bold block mb-0.5 text-slate-700 dark:text-slate-200">Catatan Perhitungan Biaya
                    Admin:</span>
                Total biaya admin per transaksi: <span
                    class="font-mono text-indigo-600 dark:text-indigo-400 font-bold">Biaya Admin = Fee Flat + (Harga
                    Produk × Fee Persen / 100)</span>.
            </div>
        </div>

    </div>
</x-app-layout>
