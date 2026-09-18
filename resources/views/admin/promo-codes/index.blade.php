<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ openModal: false, promoType: 'flat' }">

        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="$el.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700 dark:hover:text-white cursor-pointer">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs font-bold flex items-center justify-between">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button @click="$el.parentElement.remove()" class="text-rose-500 cursor-pointer">&times;</button>
            </div>
        @endif

        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black tracking-tight">Kupon Promo & Diskon</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola kode voucher belanja, batas pemakaian,
                    diskon persen/flat, dan masa aktif kupon.</p>
            </div>
            <button @click="openModal = true"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Kupon Baru
            </button>
        </div>

        <!-- Table Card -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="table-head-theme">
                        <tr>
                            <th class="px-6 py-4">Kode Kupon</th>
                            <th class="px-6 py-4">Besaran Diskon</th>
                            <th class="px-6 py-4">Min. Belanja</th>
                            <th class="px-6 py-4 text-center">Pemakaian / Kuota</th>
                            <th class="px-6 py-4">Masa Berlaku</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($promoCodes as $promo)
                            @php
                                $isExpired = $promo->valid_until && now()->gt($promo->valid_until);
                                $isExhausted =
                                    $promo->usage_limit !== null && $promo->used_count >= $promo->usage_limit;
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 font-mono font-black text-xs tracking-wider">
                                        {{ $promo->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($promo->type === 'percentage')
                                        <span
                                            class="font-bold text-amber-500 text-xs">{{ (int) $promo->discount_amount }}%</span>
                                        @if ($promo->max_discount)
                                            <span class="block text-[10px] text-slate-400">Maks. Rp
                                                {{ number_format($promo->max_discount, 0, ',', '.') }}</span>
                                        @endif
                                    @else
                                        <span class="font-bold text-emerald-600 dark:text-emerald-400 text-xs">Rp
                                            {{ number_format($promo->discount_amount, 0, ',', '.') }}</span>
                                        <span class="block text-[10px] text-slate-400">Potongan Flat</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-600 dark:text-slate-300">
                                    {{ $promo->min_transaction > 0 ? 'Rp ' . number_format($promo->min_transaction, 0, ',', '.') : 'Tanpa Minimum' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="font-bold">{{ $promo->used_count }}</span>
                                    <span class="text-slate-400">/
                                        {{ $promo->usage_limit ? $promo->usage_limit . 'x' : '∞' }}</span>
                                    @if ($isExhausted)
                                        <span class="block text-[9px] font-bold text-rose-500">Kuota Habis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($promo->valid_until)
                                        <span
                                            class="block font-medium {{ $isExpired ? 'text-rose-500 line-through' : '' }}">
                                            {{ $promo->valid_until->format('d M Y, H:i') }}
                                        </span>
                                        @if ($isExpired)
                                            <span class="text-[9px] font-bold text-rose-500">Kedaluwarsa</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 italic">Selamanya</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <form action="{{ route('admin.promocodes.toggle', $promo->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition cursor-pointer {{ $promo->is_active && !$isExpired && !$isExhausted ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
                                            {{ $promo->is_active && !$isExpired && !$isExhausted ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <form action="{{ route('admin.promocodes.destroy', $promo->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus kode promo {{ $promo->code }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 font-bold text-xs transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada kupon diskon.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($promoCodes->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $promoCodes->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Tambah Kupon -->
        <div x-show="openModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="openModal = false" class="card w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-sm font-bold uppercase tracking-wider">Buat Kode Promo Baru</h2>
                    <button @click="openModal = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('admin.promocodes.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Kode Promo /
                            Kupon</label>
                        <input type="text" name="code" placeholder="Misal: ABSOLUTEMERDEKA" required
                            class="form-input-theme uppercase font-mono">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Tipe
                                Diskon</label>
                            <select name="type" x-model="promoType" required class="form-input-theme">
                                <option value="flat">Potongan Flat (Rp)</option>
                                <option value="percentage">Persentase (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1"
                                x-text="promoType === 'percentage' ? 'Nilai Persen (%)' : 'Nominal Diskon (Rp)'"></label>
                            <input type="number" name="discount_amount" placeholder="5000 / 10" min="1" required
                                class="form-input-theme font-mono">
                        </div>
                    </div>

                    <div x-show="promoType === 'percentage'" style="display: none;">
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Maksimal Potongan
                            (Rp) (Opsional)</label>
                        <input type="number" name="max_discount" placeholder="Misal: 10000" min="0"
                            class="form-input-theme font-mono">
                    </div>

                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Syarat Minimal
                            Belanja (Rp)</label>
                        <input type="number" name="min_transaction" placeholder="0 jika tanpa minimum"
                            min="0" value="0" class="form-input-theme font-mono">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Batas Kuota
                                Pemakaian</label>
                            <input type="number" name="usage_limit" placeholder="Kosongkan jika unlimited"
                                min="1" class="form-input-theme">
                        </div>
                        <div>
                            <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Berlaku
                                Sampai</label>
                            <input type="datetime-local" name="valid_until" class="form-input-theme">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="openModal = false"
                            class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow cursor-pointer">Terbitkan
                            Kupon</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
