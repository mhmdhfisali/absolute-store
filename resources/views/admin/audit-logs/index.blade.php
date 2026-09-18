<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ payloadModal: false, selectedPayload: null }">

        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black tracking-tight">Audit Activity Logs</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Rekam jejak seluruh aktivitas administratif,
                    perubahan role, pengeditan saldo, harga, dan proses retry transaksi.</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ $search }}"
                    placeholder="Cari aksi, IP, atau keterangan..." class="form-input-theme w-64 pl-9 text-xs">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- Tabel Log Audit (Menggunakan class .card) -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="table-head-theme">
                        <tr>
                            <th class="px-5 py-3.5">Waktu Kejadian</th>
                            <th class="px-5 py-3.5">Eksekutor</th>
                            <th class="px-5 py-3.5">Aksi / Event</th>
                            <th class="px-5 py-3.5">Keterangan Aktivitas</th>
                            <th class="px-5 py-3.5">Alamat IP</th>
                            <th class="px-5 py-3.5 text-right">Detail Payload</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td
                                    class="px-5 py-3.5 whitespace-nowrap font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ $log->created_at->format('d M Y, H:i:s') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-bold block">{{ $log->user?->name ?? 'System / Guest' }}</span>
                                    <span
                                        class="text-[10px] text-slate-400 font-mono">{{ $log->user?->email ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-mono font-bold uppercase tracking-wider
                                        {{ str_contains($log->action, 'DELETE')
                                            ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20'
                                            : (str_contains($log->action, 'UPDATE')
                                                ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20'
                                                : 'bg-indigo-500/10 text-indigo-500 border border-indigo-500/20') }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 max-w-sm truncate text-slate-600 dark:text-slate-300">
                                    {{ $log->description }}
                                </td>
                                <td
                                    class="px-5 py-3.5 whitespace-nowrap font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    @if ($log->payload)
                                        <button type="button"
                                            @click="selectedPayload = {{ json_encode($log->payload) }}; payloadModal = true"
                                            class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-[11px] transition cursor-pointer">
                                            Lihat JSON
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[11px]">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada catatan aktivitas log audit yang terekam.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="p-3 border-t border-slate-200 dark:border-slate-800">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL PAYLOAD JSON -->
        <div x-show="payloadModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="payloadModal = false" class="card w-full max-w-lg p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                        Detail Payload Data
                    </h3>
                    <button @click="payloadModal = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">&times;</button>
                </div>
                <pre class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-[11px] font-mono text-emerald-600 dark:text-emerald-400 overflow-x-auto max-h-72"
                    x-text="JSON.stringify(selectedPayload, null, 2)"></pre>
                <div class="flex justify-end">
                    <button type="button" @click="payloadModal = false"
                        class="px-4 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold text-xs transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
