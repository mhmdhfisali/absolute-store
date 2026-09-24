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

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Banner & Pengumuman
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                        Promosi Etalase
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola gambar banner carousel dan teks pengumuman berjalan di halaman depan.
                </p>
            </div>
        </div>

        <!-- Running Announcement -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Tambah Announcement -->
            <div class="card p-5 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                    Teks Pengumuman Baru
                </h2>
                <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Isi Pesan Running
                            Text</label>
                        <textarea name="content" rows="3" required
                            placeholder="Contoh: Layanan Top Up Diamond MLBB beroperasi normal 24 jam nonstop." class="form-input-theme"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold transition cursor-pointer">
                        + Tambah Announcement
                    </button>
                </form>
            </div>

            <!-- List Announcements -->
            <div class="lg:col-span-2 card p-5 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-wider">Daftar Teks Berjalan Aktif</h2>
                <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse($announcements as $ann)
                        <div class="py-3 flex items-center justify-between gap-4 text-xs">
                            <div class="flex items-center gap-3">
                                <span
                                    class="h-2 w-2 rounded-full {{ $ann->is_active ? 'bg-emerald-400' : 'bg-slate-400' }}"></span>
                                <p class="font-medium">{{ $ann->content }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form action="{{ route('admin.announcements.toggle', $ann->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase transition cursor-pointer {{ $ann->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                                        {{ $ann->is_active ? 'Aktif' : 'Off' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2 py-1 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition cursor-pointer">&times;</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada pengumuman running text.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Banner Promosi -->
        <div class="space-y-4">
            <!-- Form Upload Banner -->
            <div class="card p-5 space-y-4" x-data="{ imagePreview: null }">
                <h2 class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    Upload Banner Promo Baru
                </h2>

                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data"
                    class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Judul Banner</label>
                        <input type="text" name="title" placeholder="Promo Spesial MLBB" required
                            class="form-input-theme">
                    </div>
                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">Target Link
                            (Opsional)</label>
                        <input type="url" name="target_url" placeholder="http://localhost:8005/order/mobile-legends"
                            class="form-input-theme font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-500 dark:text-slate-400 font-semibold mb-1">File Gambar (Maks
                            3MB)</label>
                        <input type="file" name="banner_image" accept="image/*" required
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result; }; reader.readAsDataURL(file); }"
                            class="form-input-theme py-1 text-xs file:mr-2 file:py-0.5 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-600 file:text-white cursor-pointer">
                    </div>

                    <div x-show="imagePreview"
                        class="sm:col-span-3 aspect-[21/9] sm:aspect-[24/6] rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 relative"
                        style="display: none;">
                        <img :src="imagePreview" alt="Preview Banner" class="w-full h-full object-cover">
                    </div>

                    <div class="sm:col-span-3 pt-1">
                        <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow cursor-pointer">
                            + Upload & Terbitkan Banner
                        </button>
                    </div>
                </form>
            </div>

            <!-- List Grid Card Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($banners as $banner)
                    <div class="card overflow-hidden flex flex-col justify-between">
                        <div class="relative aspect-[16/7] w-full bg-slate-100 dark:bg-slate-950">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                class="w-full h-full object-cover">
                            <span
                                class="absolute top-2 right-2 px-2 py-0.5 rounded-md text-[9px] font-bold uppercase {{ $banner->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-400' }}">
                                {{ $banner->is_active ? 'Aktif' : 'Off' }}
                            </span>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <h3 class="font-bold text-xs truncate">{{ $banner->title }}</h3>
                                @if ($banner->target_url)
                                    <a href="{{ $banner->target_url }}" target="_blank"
                                        class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline truncate block mt-0.5 font-mono">{{ $banner->target_url }}</a>
                                @endif
                            </div>
                            <div
                                class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-[11px] font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white cursor-pointer">
                                        {{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[11px] font-semibold text-rose-500 hover:underline cursor-pointer">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-xs text-slate-400">Belum ada banner promosi yang
                        diunggah.</div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
