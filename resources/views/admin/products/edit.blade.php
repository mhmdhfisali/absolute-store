<x-app-layout>
    <div class="space-y-8">

        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-800">
            <div>
                <a href="{{ route('admin.products.index') }}"
                    class="text-xs font-semibold text-indigo-400 hover:text-white">&larr; Kembali ke Daftar Produk</a>
                <h1 class="text-2xl font-black text-white tracking-tight mt-1">{{ $product->name }}</h1>
            </div>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                onsubmit="return confirm('Hapus produk beserta seluruh SKU-nya?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-3.5 py-2 rounded-xl border border-rose-500/30 bg-rose-500/10 text-rose-400 text-xs font-bold hover:bg-rose-500 hover:text-white transition">
                    Hapus Produk
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Info Produk -->
            <div class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-5 space-y-4" x-data="{ preview: '{{ $product->thumbnail_url }}' }">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Detail Layanan</h2>
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-3 text-xs">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-4 pb-2 border-b border-slate-800">
                        <div
                            class="h-16 w-16 rounded-xl border border-slate-800 bg-slate-950 overflow-hidden flex items-center justify-center shrink-0">
                            <template x-if="preview">
                                <img :src="preview" alt="Thumbnail" class="h-full w-full object-cover">
                            </template>
                            <template x-if="!preview">
                                <span class="text-xs font-bold text-slate-600">No Image</span>
                            </template>
                        </div>
                        <div class="w-full">
                            <label class="block text-slate-400 font-semibold mb-1">Ganti Thumbnail</label>
                            <input type="file" name="thumbnail" accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }"
                                class="w-full rounded-xl border border-slate-800 bg-slate-950 px-2 py-1 text-slate-400 file:mr-2 file:py-0.5 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Kategori</label>
                        <select name="category_id" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Tipe Input</label>
                        <select name="input_type" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white">
                            <option value="id_and_zone" {{ $product->input_type == 'id_and_zone' ? 'selected' : '' }}>
                                User ID + Zone ID</option>
                            <option value="id_only" {{ $product->input_type == 'id_only' ? 'selected' : '' }}>User ID
                                Saja</option>
                            <option value="phone_number"
                                {{ $product->input_type == 'phone_number' ? 'selected' : '' }}>No HP</option>
                            <option value="meter_number"
                                {{ $product->input_type == 'meter_number' ? 'selected' : '' }}>No Meteran Listrik
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ $product->is_active ? 'checked' : '' }}
                            class="rounded border-slate-800 bg-slate-950 text-indigo-600 focus:ring-0">
                        <label for="is_active" class="text-slate-300 font-semibold">Tampilkan di Katalog Depan</label>
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Form Tambah Item SKU -->
            <div class="lg:col-span-2 rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-5 space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Tambah Item / Varian Nominal</h2>
                <form action="{{ route('admin.items.store', $product->id) }}" method="POST"
                    class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                    @csrf
                    <div class="sm:col-span-2">
                        <label class="block text-slate-400 font-semibold mb-1">Nama Item</label>
                        <input type="text" name="name" placeholder="Contoh: 86 Diamonds" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-slate-400 font-semibold mb-1">Kode SKU / Provider</label>
                        <input type="text" name="sku_code" placeholder="MLBB-86" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white font-mono uppercase">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-slate-400 font-semibold mb-1">Harga Modal (Rp)</label>
                        <input type="number" name="original_price" placeholder="19000" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white font-mono">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-slate-400 font-semibold mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="selling_price" placeholder="21500" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-emerald-400 font-mono font-bold">
                    </div>
                    <div class="sm:col-span-4 pt-1">
                        <button type="submit"
                            class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition">
                            + Simpan SKU Item
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar SKU & Manajemen Margin -->
        <div class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 shadow-2xl overflow-hidden space-y-3 p-5">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">Daftar SKU & Margin Keuntungan</h2>
            <div class="overflow-x-auto rounded-xl border border-slate-800">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-[#0a0f1d] border-b border-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Nama Varian</th>
                            <th class="px-4 py-3">Kode SKU</th>
                            <th class="px-4 py-3">Harga Modal</th>
                            <th class="px-4 py-3">Harga Jual</th>
                            <th class="px-4 py-3">Margin (Profit)</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($product->items as $item)
                            <tr class="hover:bg-slate-800/30">
                                <form action="{{ route('admin.items.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td class="px-4 py-3">
                                        <input type="text" name="name" value="{{ $item->name }}"
                                            class="rounded-lg border border-slate-800 bg-slate-950 px-2 py-1 text-xs text-white w-48">
                                    </td>
                                    <td class="px-4 py-3 font-mono">
                                        <input type="text" name="sku_code" value="{{ $item->sku_code }}"
                                            class="rounded-lg border border-slate-800 bg-slate-950 px-2 py-1 text-xs text-indigo-400 uppercase w-32">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="original_price"
                                            value="{{ $item->original_price }}"
                                            class="rounded-lg border border-slate-800 bg-slate-950 px-2 py-1 text-xs text-white w-28">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="selling_price"
                                            value="{{ $item->selling_price }}"
                                            class="rounded-lg border border-slate-800 bg-slate-950 px-2 py-1 text-xs text-emerald-400 font-bold w-28">
                                    </td>
                                    <td class="px-4 py-3 font-mono font-bold text-cyan-400">
                                        +Rp
                                        {{ number_format($item->selling_price - $item->original_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" name="is_available" value="1"
                                            {{ $item->is_available ? 'checked' : '' }}
                                            class="rounded border-slate-800 bg-slate-950 text-indigo-600">
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                        <button type="submit"
                                            class="px-2.5 py-1 rounded bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-[11px]">
                                            Simpan
                                        </button>
                                </form>
                                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Hapus SKU ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2.5 py-1 rounded bg-rose-500/20 hover:bg-rose-500 text-rose-400 hover:text-white font-bold text-[11px]">
                                        &times;
                                    </button>
                                </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada item/SKU
                                    pada produk ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
