<x-app-layout>
    <div class="space-y-6" x-data="{ openModal: false }">

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
                <h1 class="text-2xl font-black text-white tracking-tight">Katalog & Manajemen Produk</h1>
                <p class="text-xs text-slate-400 mt-1">Kelola daftar layanan game, PPOB, serta margin harga jual.</p>
            </div>
            <button @click="openModal = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk
            </button>
        </div>

        <!-- Table Card -->
        <div class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-[#0a0f1d] border-b border-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Tipe Input</th>
                            <th class="px-6 py-4 text-center">Jumlah SKU</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($products as $prod)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="px-6 py-4 font-bold text-white whitespace-nowrap">
                                    {{ $prod->name }}
                                    <span
                                        class="block text-[10px] font-mono text-slate-500">/order/{{ $prod->slug }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-indigo-400 font-semibold text-[11px]">
                                        {{ $prod->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-[11px] text-slate-400">
                                    {{ $prod->input_type }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-white">{{ $prod->items->count() }}</span> Varian
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.products.toggle', $prod->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition {{ $prod->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500' }}">
                                            {{ $prod->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                    <a href="{{ route('admin.products.edit', $prod->id) }}"
                                        class="px-3 py-1.5 rounded-lg bg-indigo-600/10 hover:bg-indigo-600 hover:text-white border border-indigo-500/30 text-indigo-400 font-bold transition">
                                        Atur Harga & SKU &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada data produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($products->hasPages())
                <div class="p-4 border-t border-slate-800">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Tambah Produk -->
        <div x-show="openModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="openModal = false"
                class="w-full max-w-md rounded-2xl border border-slate-800 bg-[#0f172a] p-6 space-y-4 shadow-2xl"
                x-data="{ preview: null }">
                <h2 class="text-base font-bold text-white">Tambah Layanan Baru</h2>
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Kategori</label>
                        <select name="category_id" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Nama Layanan</label>
                        <input type="text" name="name" placeholder="Misal: Mobile Legends" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Tipe Input Formulir</label>
                        <select name="input_type" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white">
                            <option value="id_and_zone">User ID + Zone ID (Mobile Legends/Genshin)</option>
                            <option value="id_only">User ID Saja (Free Fire/Valorant)</option>
                            <option value="phone_number">Nomor Handphone (Pulsa/Data/E-Wallet)</option>
                            <option value="meter_number">Nomor Meter / ID Pelanggan (PLN)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Thumbnail Logo (Maks 2MB)</label>
                        <input type="file" name="thumbnail" accept="image/*"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }"
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-2 py-1.5 text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
                    </div>
                    <div x-show="preview"
                        class="h-20 w-20 rounded-xl overflow-hidden border border-slate-800 bg-slate-950 mx-auto"
                        style="display: none;">
                        <img :src="preview" alt="Preview" class="h-full w-full object-cover">
                    </div>
                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="openModal = false"
                            class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
