<x-app-layout>
    <div class="space-y-8">

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
                <h1 class="text-2xl font-black text-white tracking-tight">Banner Promo & Announcement Bar</h1>
                <p class="text-xs text-slate-400 mt-1">Kelola gambar carousel promo banner dan teks berjalan di beranda
                    pelanggan.</p>
            </div>
        </div>

        <!-- 1. Form & List Running Announcement -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tambah Announcement -->
            <div class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-5 space-y-4 shadow-xl">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider">Teks Pengumuman Baru</h2>
                </div>
                <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Isi Pesan Running Text</label>
                        <textarea name="content" rows="3" required
                            placeholder="Contoh: Layanan Top Up Diamond MLBB dan Token PLN beroperasi normal 24 jam nonstop."
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 p-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold transition">
                        + Tambah Announcement
                    </button>
                </form>
            </div>

            <!-- List Announcements -->
            <div class="lg:col-span-2 rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-5 space-y-4 shadow-xl">
                <h2 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Teks Berjalan Aktif</h2>
                <div class="divide-y divide-slate-800/80">
                    @forelse($announcements as $ann)
                        <div class="py-3 flex items-center justify-between gap-4 text-xs">
                            <div class="flex items-center gap-3">
                                <span
                                    class="h-2 w-2 rounded-full {{ $ann->is_active ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                                <p class="text-slate-200 font-medium">{{ $ann->content }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form action="{{ route('admin.announcements.toggle', $ann->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase transition {{ $ann->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500' }}">
                                        {{ $ann->is_active ? 'Aktif' : 'Off' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2 py-1 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition">&times;</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-500">Belum ada pengumuman running text yang
                            dibuat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 2. Form & List Banner Promosi -->
        <div class="space-y-4">
            <!-- Form Tambah Banner Promo -->
            <div class="rounded-2xl border border-slate-800 bg-[#0f172a]/90 p-5 space-y-4 shadow-xl"
                x-data="{ imagePreview: null }">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-indigo-400"></span>
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider">Upload Banner Promo Baru</h2>
                </div>

                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data"
                    class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Judul Banner</label>
                        <input type="text" name="title" placeholder="Promo Spesial Diamond MLBB" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">Target Link (Opsional)</label>
                        <input type="url" name="target_url" placeholder="http://localhost:8005/order/mobile-legends"
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-slate-400 font-semibold mb-1">File Gambar (Maks 3MB)</label>
                        <input type="file" name="banner_image" accept="image/*" required
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result; }; reader.readAsDataURL(file); }"
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-2 py-1.5 text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
                    </div>

                    <!-- Preview Thumbnail Sebelum Upload -->
                    <div x-show="imagePreview"
                        class="sm:col-span-3 aspect-[21/9] sm:aspect-[24/6] rounded-xl overflow-hidden border border-slate-800 bg-slate-950 relative"
                        style="display: none;">
                        <img :src="imagePreview" alt="Preview Banner" class="w-full h-full object-cover">
                        <span
                            class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/70 text-[10px] text-white">Preview
                            Gambar</span>
                    </div>

                    <div class="sm:col-span-3 pt-1">
                        <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow-lg shadow-indigo-600/20">
                            + Upload & Terbitkan Banner
                        </button>
                    </div>
                </form>
            </div>

            <!-- Grid Card Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($banners as $banner)
                    <div
                        class="rounded-2xl border border-slate-800 bg-[#0f172a]/80 overflow-hidden shadow-xl flex flex-col justify-between">
                        <div class="relative aspect-[16/7] w-full bg-slate-950">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                class="w-full h-full object-cover">
                            <span
                                class="absolute top-2 right-2 px-2 py-0.5 rounded-md text-[9px] font-bold uppercase {{ $banner->is_active ? 'bg-emerald-500 text-black' : 'bg-slate-800 text-slate-400' }}">
                                {{ $banner->is_active ? 'Aktif' : 'Off' }}
                            </span>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <h3 class="font-bold text-white text-xs truncate">{{ $banner->title }}</h3>
                                @if ($banner->target_url)
                                    <a href="{{ $banner->target_url }}" target="_blank"
                                        class="text-[10px] text-indigo-400 hover:underline truncate block mt-0.5 font-mono">{{ $banner->target_url }}</a>
                                @endif
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-slate-800/80">
                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-[11px] font-semibold text-slate-300 hover:text-white">
                                        {{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[11px] font-semibold text-rose-400 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-xs text-slate-500">Belum ada banner promosi yang
                        diunggah.</div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
