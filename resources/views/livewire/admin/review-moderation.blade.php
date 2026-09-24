<div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Moderasi Ulasan
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                    Social Proof
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Pantau feedback pembeli, verifikasi ulasan bintang, dan bersihkan komentar spam untuk menjaga reputasi toko.
            </p>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14] p-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Ulasan</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white font-mono mt-1">{{ number_format($totalReviews) }}</h3>
            </div>
            <div class="p-3 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14] p-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Menunggu Moderasi</p>
                <h3 class="text-2xl font-black text-amber-500 font-mono mt-1">{{ number_format($pendingReviews) }}</h3>
            </div>
            <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14] p-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Ulasan Disetujui</p>
                <h3 class="text-2xl font-black text-emerald-500 font-mono mt-1">{{ number_format($approvedReviews) }}</h3>
            </div>
            <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14] p-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rata-Rata Rating</p>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="text-2xl font-black text-amber-400 font-mono">{{ number_format($averageRating, 1) }}</span>
                    <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
            </div>
            <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filters & Bulk Actions Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14]">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari ulasan, pembeli, produk..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-indigo-500" />
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Status Filter -->
            <select wire:model.live="statusFilter"
                    class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="all">Semua Status</option>
                <option value="pending">Menunggu Persetujuan</option>
                <option value="approved">Sudah Disetujui</option>
            </select>

            <!-- Rating Filter -->
            <select wire:model.live="ratingFilter"
                    class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="all">Semua Rating</option>
                <option value="5">⭐⭐⭐⭐⭐ (5 Bintang)</option>
                <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                <option value="3">⭐⭐⭐ (3 Bintang)</option>
                <option value="2">⭐⭐ (2 Bintang)</option>
                <option value="1">⭐ (1 Bintang)</option>
            </select>
        </div>

        <!-- Bulk Action Buttons -->
        @if(count($selectedReviews) > 0)
            <div class="flex items-center gap-2 shrink-0 animate-fadeIn">
                <span class="text-xs font-bold text-slate-400">
                    Terpilih: {{ count($selectedReviews) }}
                </span>
                <button type="button" wire:click="bulkApprove"
                        class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition active:scale-95 cursor-pointer">
                    Setujui Terpilih
                </button>
                <button type="button" wire:click="bulkDelete" wire:confirm="Yakin ingin menghapus ulasan terpilih?"
                        class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow transition active:scale-95 cursor-pointer">
                    Hapus Terpilih
                </button>
            </div>
        @endif
    </div>

    <!-- Reviews Table List -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#080C14] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-4 w-8">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded border-slate-700 text-indigo-600 focus:ring-indigo-500" />
                        </th>
                        <th class="px-4 py-3">Pembeli</th>
                        <th class="px-4 py-3">Produk / Layanan</th>
                        <th class="px-4 py-3">Rating</th>
                        <th class="px-4 py-3">Ulasan / Testimoni</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse($reviews as $rev)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">
                            <td class="p-4">
                                <input type="checkbox" wire:model.live="selectedReviews" value="{{ (string) $rev->id }}" class="rounded border-slate-700 text-indigo-600 focus:ring-indigo-500" />
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $rev->user?->name ?? 'Guest / Anonim' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $rev->user?->email }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-indigo-600 dark:text-cyan-400">{{ $rev->product?->name ?? 'Produk Dihapus' }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-slate-700 fill-none stroke-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-1 text-[11px] font-mono font-bold text-slate-400">({{ $rev->rating }})</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <p class="text-xs text-slate-700 dark:text-slate-300 line-clamp-2">{{ $rev->comment ?: 'Tidak ada teks komentar.' }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($rev->is_approved)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                        Tayang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-400 font-mono text-[11px]">
                                {{ $rev->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3.5 text-right space-x-1">
                                @if(! $rev->is_approved)
                                    <button type="button" wire:click="approveReview({{ $rev->id }})"
                                            title="Setujui Ulasan"
                                            class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @else
                                    <button type="button" wire:click="rejectReview({{ $rev->id }})"
                                            title="Sembunyikan Ulasan"
                                            class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400 hover:bg-amber-500 hover:text-white transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                @endif

                                <button type="button" wire:click="deleteReview({{ $rev->id }})" wire:confirm="Hapus permanen ulasan ini?"
                                        title="Hapus Ulasan"
                                        class="p-1.5 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-500">
                                Tidak ada ulasan pembeli yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
