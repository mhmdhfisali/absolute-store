<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ editModalOpen: false, currentUser: {} }">

        @if (session('success'))
            <div
                class="p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-white">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-bold flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="$el.parentElement.remove()" class="text-rose-500 hover:text-white">&times;</button>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">Manajemen Pengguna & Role</h1>
                <p class="text-xs text-slate-400 mt-1">Superadmin dapat mengubah hak akses, mengatur tier reseller, dan
                    menyesuaikan saldo dompet akun pengguna.</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau email..."
                    class="w-64 rounded-xl border border-slate-800 bg-[#0a0f1d] px-3.5 py-2 pl-9 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                <svg class="w-3.5 h-3.5 text-slate-500 absolute left-3 top-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- Tabel Pengguna -->
        <div class="rounded-2xl border border-slate-800 bg-[#0c1222]/90 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-[#090e1b] border-b border-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3.5">Pengguna</th>
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5 text-center">Role</th>
                            <th class="px-5 py-3.5 text-center">Tier Akun</th>
                            <th class="px-5 py-3.5">Saldo Dompet</th>
                            <th class="px-5 py-3.5">Terdaftar</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="px-5 py-3.5 font-bold text-white whitespace-nowrap">
                                    {{ $u->name }}
                                    @if ($u->id === Auth::id())
                                        <span
                                            class="ml-1 text-[9px] px-1.5 py-0.5 rounded bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">Anda</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono text-slate-300">
                                    {{ $u->email }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if ($u->role === 'superadmin')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-purple-500/10 border border-purple-500/30 text-purple-400">Superadmin</span>
                                    @elseif($u->role === 'admin')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-sky-500/10 border border-sky-500/30 text-sky-400">Admin
                                            / CS</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-slate-800 text-slate-400">Member</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $u->tier === 'reseller' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : ($u->tier === 'vip' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-slate-800 text-slate-500') }}">
                                        {{ $u->tier }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-bold text-emerald-400">
                                    Rp {{ number_format($u->balance, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-400">
                                    {{ $u->created_at->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                    <!-- Tombol Edit Lengkap (Modal) -->
                                    <button type="button"
                                        @click="currentUser = { id: {{ $u->id }}, name: '{{ addslashes($u->name) }}', email: '{{ $u->email }}', role: '{{ $u->role }}', tier: '{{ $u->tier }}', balance: {{ (int) $u->balance }} }; editModalOpen = true"
                                        class="px-2.5 py-1 rounded-lg bg-indigo-600/10 hover:bg-indigo-600 hover:text-white border border-indigo-500/30 text-indigo-400 font-bold text-xs transition">
                                        Edit / Kelola
                                    </button>

                                    @if ($u->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Hapus akun pengguna {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 font-bold text-xs transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    Tidak ada pengguna yang sesuai dengan pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-3 border-t border-slate-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL EDIT PENGGUNA LENGKAP -->
        <div x-show="editModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="editModalOpen = false"
                class="w-full max-w-md rounded-2xl border border-slate-800 bg-[#0d1322] p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Edit Data & Akses Pengguna</h3>
                    <button @click="editModalOpen = false"
                        class="text-slate-400 hover:text-white text-lg">&times;</button>
                </div>

                <form :action="'{{ url('admin/users') }}/' + currentUser.id + '/update-full'" method="POST"
                    class="space-y-3 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="currentUser.name" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Role Akun</label>
                            <select name="role" x-model="currentUser.role" required
                                class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white focus:border-indigo-500 focus:outline-none">
                                <option value="user">Member (User Biasa)</option>
                                <option value="admin">Admin / CS</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Tier Membership</label>
                            <select name="tier" x-model="currentUser.tier" required
                                class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-white focus:border-indigo-500 focus:outline-none">
                                <option value="member">Member Reguler</option>
                                <option value="reseller">Reseller (Harga Diskon)</option>
                                <option value="vip">VIP Member</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Saldo Dompet Akun (Rp)</label>
                        <input type="number" name="balance" x-model="currentUser.balance" min="0" required
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 font-mono font-bold text-emerald-400 focus:border-indigo-500 focus:outline-none">
                        <span class="text-[10px] text-slate-500 mt-1 block">Superadmin dapat menambah atau mengurangi
                            saldo user di sini.</span>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-800">
                        <button type="button" @click="editModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-bold transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow-lg shadow-indigo-600/30">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
