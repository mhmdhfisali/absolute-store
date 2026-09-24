<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Audit Log & Keamanan
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                    Security Log
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Pencatatan rekam jejak aktivitas sensitif admin, perubahan data pengguna, dan investigasi forensik sistem.
            </p>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="p-4 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80 group">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari deskripsi, IP, user, atau aksi..." 
                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-4 py-2.5 pl-10 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
            <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <select wire:model.live="actionFilter" 
                    class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500">
                <option value="all">Semua Kategori Aksi</option>
                @foreach($distinctActions as $act)
                    <option value="{{ $act }}">{{ $act }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table Audit Logs -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 shadow-2xl overflow-hidden backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-950/50 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-5">Waktu Kejadian</th>
                        <th class="py-3.5 px-5">Eksekutor</th>
                        <th class="py-3.5 px-5">Kategori Aksi</th>
                        <th class="py-3.5 px-5">Deskripsi Aktivitas</th>
                        <th class="py-3.5 px-5">IP Address</th>
                        <th class="py-3.5 px-5 text-right">Detail Payload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-5 font-mono text-[11px] text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="py-3.5 px-5 font-bold text-slate-800 dark:text-white">
                                {{ $log->user?->name ?? 'System / Anonymous' }}
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 max-w-md">
                                {{ $log->description }}
                            </td>
                            <td class="py-3.5 px-5 font-mono text-[11px] text-slate-400">
                                {{ $log->ip_address ?: '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                @if(!empty($log->payload))
                                    <button type="button" wire:click="viewPayload({{ $log->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-purple-500/20 text-slate-600 dark:text-slate-300 hover:text-purple-400 transition"
                                            title="Inspeksi JSON Payload">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-slate-500 text-[10px]">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Belum ada catatan log aktivitas yang terekam.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- ================= MODAL DETAIL PAYLOAD JSON ================= -->
    @if($showPayloadModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-xl rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-xs font-black text-purple-400 uppercase tracking-wider">
                        Inspeksi Payload JSON: {{ $selectedLogAction }}
                    </h3>
                    <button type="button" wire:click="$set('showPayloadModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 font-mono text-xs text-emerald-400 overflow-x-auto max-h-96">
                    <pre>{{ json_encode($selectedPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" wire:click="$set('showPayloadModal', false)" class="px-5 py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-white">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
