<div class="card p-6 space-y-6">
    <!-- Header Reviews & Rating Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 p-0.5 shadow-md shadow-amber-500/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-100">
                    Ulasan & Penilaian Pelanggan
                </h3>
                <p class="text-[11px] text-slate-400">Pengalaman nyata dari pembeli terverifikasi</p>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-900/80 px-4 py-2 rounded-2xl border border-slate-200 dark:border-slate-800 self-start sm:self-auto">
            <div class="text-xl font-black text-amber-500 flex items-center gap-1">
                <span>{{ number_format($avgRating, 1) }}</span>
                <span class="text-xs text-slate-400 font-normal">/ 5.0</span>
            </div>
            <div class="h-6 w-px bg-slate-200 dark:bg-slate-800"></div>
            <div class="text-[11px] text-slate-400 font-bold">
                <span class="text-slate-700 dark:text-slate-200 font-black">{{ $totalReviews }}</span> Ulasan
            </div>
        </div>
    </div>

    <!-- Review Form for Verified Buyers -->
    @if($canReview)
        <form wire:submit="submitReview" class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-500/20 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Tulis Ulasan Pesanan Anda
                </span>

                <!-- Star Rating Selector -->
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" wire:click="$set('rating', {{ $i }})"
                                class="text-lg transition-transform hover:scale-125 focus:outline-none cursor-pointer">
                            <span class="{{ $i <= $rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600' }}">★</span>
                        </button>
                    @endfor
                </div>
            </div>

            <textarea wire:model="comment" rows="2"
                      placeholder="Bagikan pengalaman transaksi Anda (misal: Proses cepat 1 detik langsung masuk, recommended!)..."
                      class="form-input-theme text-xs w-full resize-none"></textarea>

            @error('comment')
                <span class="text-[11px] text-rose-500 font-medium block">{{ $message }}</span>
            @enderror

            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled"
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-bold shadow-md hover:shadow-indigo-500/20 transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span wire:loading.remove wire:target="submitReview">Kirim Ulasan</span>
                    <span wire:loading wire:target="submitReview">Mengirim...</span>
                </button>
            </div>
        </form>
    @endif

    <!-- Reviews List -->
    <div class="space-y-3">
        @forelse($reviews as $rev)
            <div class="p-3.5 rounded-xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/40 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-lg bg-gradient-to-tr from-violet-600 to-cyan-500 flex items-center justify-center text-white text-[10px] font-black shrink-0">
                            {{ strtoupper(substr($rev->user->name ?? 'Pembeli', 0, 2)) }}
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                {{ $rev->user->name ?? 'Pembeli Terverifikasi' }}
                            </span>
                            <span class="block text-[10px] text-slate-400">
                                {{ $rev->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Stars Display -->
                    <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                        @for($s = 1; $s <= 5; $s++)
                            <span>{{ $s <= $rev->rating ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    "{{ $rev->comment }}"
                </p>
            </div>
        @empty
            <div class="text-center py-6 text-slate-400 text-xs">
                <p>Belum ada ulasan untuk layanan ini. Jadilah pembeli pertama yang memberikan ulasan!</p>
            </div>
        @endforelse

        <div class="pt-2">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
