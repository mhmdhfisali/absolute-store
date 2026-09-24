<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Master SKU & Varian
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-600 dark:text-cyan-400 border border-indigo-500/20">
                    Katalog Item
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Kelola seluruh item nominal produk, kode SKU provider, margin profit, dan kontrol ketersediaan stok.
            </p>
        </div>

        <button type="button" wire:click="openCreateModal"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/20 active:scale-95 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah SKU Baru</span>
        </button>
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

    <!-- Search & Filter Controls -->
    <div class="p-4 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80 group">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari SKU, nama varian, atau game..." 
                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-4 py-2.5 pl-10 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
            <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Filter Produk -->
            <select wire:model.live="selectedProduct" 
                    class="w-1/2 md:w-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500">
                <option value="all">Semua Layanan Game & PPOB</option>
                @foreach($products as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                @endforeach
            </select>

            <!-- Filter Status -->
            <select wire:model.live="statusFilter" 
                    class="w-1/2 md:w-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500">
                <option value="all">Semua Status</option>
                <option value="available">Tersedia (Aktif)</option>
                <option value="unavailable">Gangguan / Nonaktif</option>
            </select>
        </div>
    </div>

    <!-- Table SKU -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 shadow-2xl overflow-hidden backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-950/50 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-5">Kode SKU & Varian</th>
                        <th class="py-3.5 px-5">Layanan / Kategori</th>
                        <th class="py-3.5 px-5">Harga Modal</th>
                        <th class="py-3.5 px-5">Harga Jual Normal</th>
                        <th class="py-3.5 px-5">Harga Reseller</th>
                        <th class="py-3.5 px-5">Margin Profit</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($items as $item)
                        @php
                            $cost = (float) $item->original_price;
                            $sell = (float) $item->selling_price;
                            $profit = $sell - $cost;
                            $marginPercent = $cost > 0 ? round(($profit / $cost) * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- SKU Code & Name -->
                            <td class="py-3.5 px-5">
                                <div class="font-extrabold text-slate-800 dark:text-white">
                                    {{ $item->name }}
                                </div>
                                <div class="text-[10px] font-mono text-cyan-500 font-bold">
                                    {{ $item->sku_code }}
                                </div>
                            </td>

                            <!-- Product & Category -->
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-700 dark:text-slate-300">
                                    {{ $item->product?->name ?? 'N/A' }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $item->product?->category?->name ?? 'Digital' }}
                                </div>
                            </td>

                            <!-- Original Price (Modal) -->
                            <td class="py-3.5 px-5 font-mono text-slate-500">
                                Rp {{ number_format($item->original_price, 0, ',', '.') }}
                            </td>

                            <!-- Selling Price (Jual) -->
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-800 dark:text-white">
                                Rp {{ number_format($item->selling_price, 0, ',', '.') }}
                            </td>

                            <!-- Reseller Price -->
                            <td class="py-3.5 px-5 font-mono text-cyan-400 font-bold">
                                @if($item->reseller_price > 0)
                                    Rp {{ number_format($item->reseller_price, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-500 text-[10px]">-</span>
                                @endif
                            </td>

                            <!-- Margin Profit -->
                            <td class="py-3.5 px-5">
                                <span class="font-mono font-bold {{ $profit >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    Rp {{ number_format($profit, 0, ',', '.') }}
                                </span>
                                <span class="block text-[10px] text-slate-400">
                                    ({{ $marginPercent }}%)
                                </span>
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-3.5 px-5 text-center">
                                <button type="button" wire:click="toggleAvailability({{ $item->id }})"
                                        class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase transition cursor-pointer {{ $item->is_available ? 'bg-emerald-500/15 text-emerald-500 border border-emerald-500/20' : 'bg-rose-500/15 text-rose-500 border border-rose-500/20' }}">
                                    {{ $item->is_available ? 'Tersedia' : 'Gangguan' }}
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" wire:click="openEditModal({{ $item->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-indigo-500/20 text-slate-600 dark:text-slate-300 hover:text-indigo-400 transition"
                                            title="Edit SKU">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <button type="button" wire:click="deleteSku({{ $item->id }})"
                                            wire:confirm="Yakin ingin menghapus SKU {{ $item->name }}?"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-rose-500/20 text-slate-600 dark:text-slate-300 hover:text-rose-500 transition"
                                            title="Hapus SKU">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Tidak ada data SKU yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $items->links() }}
        </div>
    </div>

    <!-- ================= MODAL TAMBAH SKU ================= -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-lg rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                        Tambah Item SKU Baru
                    </h3>
                    <button type="button" wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Layanan / Game Induk</label>
                        <select wire:model="createProductId" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-bold text-slate-800 dark:text-white focus:border-cyan-500">
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                            @endforeach
                        </select>
                        @error('createProductId') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Varian / Nominal</label>
                            <input type="text" wire:model="createName" placeholder="Contoh: 86 Diamonds"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white focus:border-cyan-500">
                            @error('createName') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kode SKU Provider</label>
                            <input type="text" wire:model="createSkuCode" placeholder="Contoh: ML86"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-cyan-400 focus:border-cyan-500 uppercase">
                            @error('createSkuCode') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Modal (Rp)</label>
                            <input type="number" wire:model="createOriginalPrice" placeholder="18500"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono text-slate-800 dark:text-white focus:border-cyan-500">
                            @error('createOriginalPrice') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Jual (Rp)</label>
                            <input type="number" wire:model="createSellingPrice" placeholder="20000"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-emerald-400 focus:border-cyan-500">
                            @error('createSellingPrice') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Reseller (Rp)</label>
                            <input type="number" wire:model="createResellerPrice" placeholder="19200"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono text-cyan-400 focus:border-cyan-500">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">
                        Batal
                    </button>
                    <button type="button" wire:click="saveNewSku" class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/20">
                        Simpan SKU
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ================= MODAL EDIT SKU ================= -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-lg rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                        Edit Data SKU: {{ $editSkuCode }}
                    </h3>
                    <button type="button" wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Varian</label>
                            <input type="text" wire:model="editName"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white focus:border-cyan-500">
                            @error('editName') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kode SKU Provider</label>
                            <input type="text" wire:model="editSkuCode"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-cyan-400 focus:border-cyan-500 uppercase">
                            @error('editSkuCode') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Modal (Rp)</label>
                            <input type="number" wire:model="editOriginalPrice"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono text-slate-800 dark:text-white focus:border-cyan-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Jual (Rp)</label>
                            <input type="number" wire:model="editSellingPrice"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-emerald-400 focus:border-cyan-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Reseller (Rp)</label>
                            <input type="number" wire:model="editResellerPrice"
                                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono text-cyan-400 focus:border-cyan-500">
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-700 dark:text-slate-300">Status Ketersediaan Item</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="editIsAvailable" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">
                        Batal
                    </button>
                    <button type="button" wire:click="updateSku" class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
