<div x-data="{
        toasts: [],
        playChime(type) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                
                const now = ctx.currentTime;
                if (type === 'error') {
                    osc.frequency.setValueAtTime(320, now);
                    osc.frequency.exponentialRampToValueAtTime(240, now + 0.2);
                } else if (type === 'warning') {
                    osc.frequency.setValueAtTime(440, now);
                    osc.frequency.exponentialRampToValueAtTime(554, now + 0.15);
                } else {
                    // Soft high chime (C6 -> G6)
                    osc.frequency.setValueAtTime(587.33, now);
                    osc.frequency.exponentialRampToValueAtTime(880, now + 0.18);
                }

                gain.gain.setValueAtTime(0.06, now);
                gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.35);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(now);
                osc.stop(now + 0.35);
            } catch (e) {
                // AudioContext not allowed or disabled
            }
        },
        addToast(detail) {
            const id = Date.now() + Math.random();
            const toast = {
                id: id,
                type: detail.type || 'success',
                title: detail.title || (detail.type === 'error' ? 'Gagal' : (detail.type === 'warning' ? 'Perhatian' : 'Berhasil')),
                message: detail.message || '',
            };
            this.toasts.push(toast);
            this.playChime(toast.type);

            setTimeout(() => {
                this.removeToast(id);
            }, 4000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-init="
        window.addEventListener('show-toast', event => {
            const detail = event.detail[0] || event.detail;
            addToast(detail);
        });
    "
    class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="pointer-events-auto w-full rounded-2xl p-4 shadow-2xl backdrop-blur-2xl border transition-all duration-300 flex items-start gap-3"
             :class="{
                 'bg-slate-900/95 border-emerald-500/40 text-slate-100 shadow-emerald-500/10': toast.type === 'success',
                 'bg-slate-900/95 border-rose-500/40 text-slate-100 shadow-rose-500/10': toast.type === 'error',
                 'bg-slate-900/95 border-amber-500/40 text-slate-100 shadow-amber-500/10': toast.type === 'warning',
                 'bg-slate-900/95 border-cyan-500/40 text-slate-100 shadow-cyan-500/10': toast.type === 'info'
             }">
            
            <!-- Type Icon -->
            <div class="shrink-0 p-2 rounded-xl"
                 :class="{
                     'bg-emerald-500/20 text-emerald-400': toast.type === 'success',
                     'bg-rose-500/20 text-rose-400': toast.type === 'error',
                     'bg-amber-500/20 text-amber-400': toast.type === 'warning',
                     'bg-cyan-500/20 text-cyan-400': toast.type === 'info'
                 }">
                <template x-if="toast.type === 'success'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>

            <!-- Message Body -->
            <div class="flex-1 min-w-0 pr-1">
                <h5 class="text-xs font-bold text-white tracking-wide" x-text="toast.title"></h5>
                <p class="text-[11px] text-slate-300 leading-relaxed mt-0.5" x-text="toast.message"></p>
            </div>

            <!-- Close Button -->
            <button @click="removeToast(toast.id)" 
                    class="shrink-0 text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-white/5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
