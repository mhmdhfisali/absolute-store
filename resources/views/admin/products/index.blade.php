<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ openModal: false, syncing: false }">

        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="$el.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700 dark:hover:text-white cursor-pointer">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button @click="$el.parentElement.remove()"
                    class="text-rose-500 hover:text-rose-700 dark:hover:text-white cursor-pointer">&times;</button>
            </div>
        @endif

        <!-- Header Actions -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Katalog & Produk
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-violet-500/10 text-violet-600 dark:text-cyan-400 border border-violet-500/20">
                        Manajemen Master
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola daftar layanan game, PPOB, sinkronisasi SKU provider, serta margin harga jual.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Sync Digiflazz -->
                <form action="{{ route('admin.digiflazz.sync') }}" method="POST" @submit="syncing = true"
                    onsubmit="return confirm('Mulai sinkronisasi harga modal & status SKU dari Digiflazz sekarang?')">
                    @csrf
                    <input type="hidden" name="margin_flat" value="1500">
                    <button type="submit" :disabled="syncing"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl card font-bold text-xs hover:border-sky-500 text-sky-600 dark:text-sky-400 transition cursor-pointer">
                        <svg class="w-4 h-4" :class="{ 'animate-spin': syncing }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span x-text="syncing ? 'Menyinkronkan...' : 'Sync Harga Digiflazz'"></span>
                    </button>
                </form>

                <!-- Tambah Produk -->
                <button @click="openModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk
                </button>
            </div>
        </div>

        <!-- Tabel Produk (Card) -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="table-head-theme">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Tipe Input</th>
                            <th class="px-6 py-4 text-center">Jumlah SKU</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($products as $prod)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="px-6 py-4 font-bold whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if ($prod->thumbnail)
                                            <img src="{{ Storage::url($prod->thumbnail) }}" alt="{{ $prod->name }}"
                                                class="w-9 h-9 rounded-lg object-cover border border-slate-200 dark:border-slate-800 shrink-0 bg-slate-100 dark:bg-slate-950">
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 flex items-center justify-center text-[10px] font-black text-indigo-500 shrink-0">
                                                {{ strtoupper(substr($prod->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="block font-bold">{{ $prod->name }}</span>
                                            <span
                                                class="block text-[10px] font-mono text-slate-400">/order/{{ $prod->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-semibold text-[11px]">
                                        {{ $prod->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ $prod->input_type }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold">{{ $prod->items->count() }}</span> Varian
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.products.toggle', $prod->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition cursor-pointer {{ $prod->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                                            {{ $prod->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.products.edit', $prod->id) }}"
                                        class="px-3 py-1.5 rounded-lg bg-indigo-600/10 hover:bg-indigo-600 hover:text-white text-indigo-600 dark:text-indigo-400 font-bold transition">
                                        Atur Harga & SKU &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($products->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Tambah Produk -->
        <div x-show="openModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="openModal = false" class="card w-full max-w-md p-6 space-y-4" x-data="{ preview: null }">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-sm font-bold uppercase tracking-wider">Tambah Layanan Baru</h2>
                    <button @click="openModal = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Kategori</label>
                        <select name="category_id" required class="form-input-theme">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Nama Layanan</label>
                        <input type="text" name="name" placeholder="Misal: Mobile Legends" required
                            class="form-input-theme">
                    </div>

                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Tipe Input
                            Formulir</label>
                        <select name="input_type" required class="form-input-theme">
                            <option value="id_and_zone">User ID + Zone ID</option>
                            <option value="id_only">User ID Saja</option>
                            <option value="phone_number">Nomor HP</option>
                            <option value="meter_number">Nomor Meter / ID Pelanggan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Thumbnail
                            Logo</label>
                        <input type="file" name="thumbnail" accept="image/*"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }"
                            class="form-input-theme py-1 text-xs file:mr-2 file:py-0.5 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-600 file:text-white cursor-pointer">
                    </div>

                    <div x-show="preview"
                        class="h-20 w-20 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 mx-auto"
                        style="display: none;">
                        <img :src="preview" alt="Preview" class="h-full w-full object-cover">
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="openModal = false"
                            class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow cursor-pointer">Simpan
                            Layanan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
