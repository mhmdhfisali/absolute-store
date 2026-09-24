<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Manajemen Pengguna (RBAC)
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-600 dark:text-cyan-400 border border-indigo-500/20">
                    Kontrol Akses
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Kelola hak akses role, tier diskon, suspensi akun, dan penyesuaian saldo ledger secara real-time.
            </p>
        </div>
    </div>

    <!-- Alert Flash Message -->
    @if(session()->has('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80 group">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari nama atau email pengguna..." 
                   class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-4 py-2.5 pl-10 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
            <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Filter Role -->
            <select wire:model.live="roleFilter" 
                    class="w-1/2 md:w-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                <option value="all">Semua Role</option>
                <option value="member">Member</option>
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
            </select>

            <!-- Filter Tier -->
            <select wire:model.live="tierFilter" 
                    class="w-1/2 md:w-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 px-3 py-2.5 text-xs text-slate-700 dark:text-slate-200 font-bold focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                <option value="all">Semua Tier</option>
                <option value="member">Member (Regular)</option>
                <option value="reseller">Reseller</option>
                <option value="vip">VIP Platinum</option>
            </select>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 shadow-2xl overflow-hidden backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-950/50 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-5">Role Akses</th>
                        <th class="py-3.5 px-5">Tier Harga</th>
                        <th class="py-3.5 px-5">Saldo Wallet</th>
                        <th class="py-3.5 px-5">Status Akun</th>
                        <th class="py-3.5 px-5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- Info User -->
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-500 p-0.5 shrink-0">
                                        <div class="h-full w-full bg-slate-950 rounded-[10px] flex items-center justify-center font-black text-white text-xs">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-slate-800 dark:text-white truncate flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="px-1.5 py-0.2 rounded text-[9px] bg-indigo-500/20 text-indigo-400 font-bold">You</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] font-mono text-slate-400 truncate">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role Badge -->
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $user->role === 'superadmin' ? 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30' : ($user->role === 'admin' ? 'bg-purple-500/15 text-purple-600 dark:text-purple-400 border border-purple-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400') }}">
                                    {{ $user->role ?? 'member' }}
                                </span>
                            </td>

                            <!-- Tier Badge -->
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $user->tier === 'vip' ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30' : ($user->tier === 'reseller' ? 'bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400') }}">
                                    {{ $user->tier ?? 'member' }}
                                </span>
                            </td>

                            <!-- Saldo -->
                            <td class="py-3.5 px-5">
                                <div class="font-mono font-bold text-xs text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($user->balance, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $user->wallet_transactions_count }} mutasi
                                </div>
                            </td>

                            <!-- Status Banned -->
                            <td class="py-3.5 px-5">
                                @if($user->is_banned)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-rose-500/20 text-rose-500 border border-rose-500/30"
                                          title="{{ $user->ban_reason }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Active
                                    </span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Edit Role/Tier Button -->
                                    <button type="button" wire:click="openRoleModal({{ $user->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-indigo-500/20 text-slate-600 dark:text-slate-300 hover:text-indigo-400 transition"
                                            title="Ubah Role & Tier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </button>

                                    <!-- Adjust Balance Button -->
                                    <button type="button" wire:click="openBalanceModal({{ $user->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-500/20 text-slate-600 dark:text-slate-300 hover:text-emerald-400 transition"
                                            title="Penyesuaian Saldo Ledger">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>

                                    <!-- Ban/Suspend Button -->
                                    <button type="button" wire:click="openBanModal({{ $user->id }})"
                                            class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-rose-500/20 text-slate-600 dark:text-slate-300 hover:text-rose-400 transition"
                                            title="{{ $user->is_banned ? 'Buka Blokir' : 'Tangguhkan / Suspend' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Tidak ada data pengguna yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $users->links() }}
        </div>
    </div>

    <!-- ================= MODAL UBAH ROLE & TIER ================= -->
    @if($showRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-md rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                        Ubah Hak Akses & Tier
                    </h3>
                    <button type="button" wire:click="$set('showRoleModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Role Akses Console</label>
                        <select wire:model="editRole" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-xs text-slate-800 dark:text-white font-bold">
                            <option value="member">Member (Pengguna Biasa)</option>
                            <option value="admin">Admin (Akses Console Standar)</option>
                            <option value="superadmin">Superadmin (Akses Penuh & Konfigurasi)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tier Harga Produk</label>
                        <select wire:model="editTier" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-xs text-slate-800 dark:text-white font-bold">
                            <option value="member">Member (Harga Publik)</option>
                            <option value="reseller">Reseller (Harga Khusus Reseller)</option>
                            <option value="vip">VIP Platinum (Margin Terendah)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('showRoleModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">
                        Batal
                    </button>
                    <button type="button" wire:click="saveRoleAndTier" class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/20">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ================= MODAL PENYESUAIAN SALDO MANUAL ================= -->
    @if($showBalanceModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-md rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">
                            Penyesuaian Saldo Manual
                        </h3>
                        <p class="text-[11px] text-slate-400">Target: <span class="text-white font-bold">{{ $balanceUserName }}</span></p>
                    </div>
                    <button type="button" wire:click="$set('showBalanceModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-3 rounded-2xl bg-slate-100 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-bold">Saldo Saat Ini:</span>
                    <span class="font-mono font-black text-emerald-500">Rp {{ number_format($currentBalance, 0, ',', '.') }}</span>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Operasi</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" wire:click="$set('balanceAdjustmentType', 'credit')"
                                    class="py-2.5 rounded-xl border font-bold text-center transition {{ $balanceAdjustmentType === 'credit' ? 'bg-emerald-500/20 border-emerald-500 text-emerald-400' : 'border-slate-700 text-slate-400' }}">
                                + Tambah Saldo (Credit)
                            </button>
                            <button type="button" wire:click="$set('balanceAdjustmentType', 'debit')"
                                    class="py-2.5 rounded-xl border font-bold text-center transition {{ $balanceAdjustmentType === 'debit' ? 'bg-rose-500/20 border-rose-500 text-rose-400' : 'border-slate-700 text-slate-400' }}">
                                - Potong Saldo (Debit)
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nominal (Rp)</label>
                        <input type="number" wire:model="adjustmentAmount" placeholder="Contoh: 50000"
                               class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 font-mono font-bold text-slate-800 dark:text-white focus:border-cyan-500">
                        @error('adjustmentAmount') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Alasan Penyesuaian</label>
                        <input type="text" wire:model="adjustmentNote" placeholder="Contoh: Kompensasi gangguan PPOB / Topup Offline"
                               class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-slate-800 dark:text-white focus:border-cyan-500">
                        @error('adjustmentNote') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('showBalanceModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">
                        Batal
                    </button>
                    <button type="button" wire:click="applyBalanceAdjustment" class="px-5 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-lg shadow-indigo-600/20">
                        Eksekusi Ledger Saldo
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ================= MODAL BAN / SUSPEND ================= -->
    @if($showBanModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="w-full max-w-md rounded-3xl bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-black text-rose-500 uppercase tracking-wider">
                        {{ $isCurrentlyBanned ? 'Buka Blokir Akun' : 'Tangguhkan / Suspend Akun' }}
                    </h3>
                    <button type="button" wire:click="$set('showBanModal', false)" class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <p class="text-xs text-slate-400 leading-relaxed">
                    @if($isCurrentlyBanned)
                        Akun <strong class="text-white">{{ $banUserName }}</strong> saat ini sedang ditangguhkan. Klik tombol di bawah untuk mencabut status blokir.
                    @else
                        Anda akan menangguhkan akun <strong class="text-white">{{ $banUserName }}</strong>. Pengguna tidak akan dapat login atau melakukan pemesanan.
                    @endif
                </p>

                @if(!$isCurrentlyBanned)
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alasan Pemblokiran</label>
                        <textarea wire:model="banReason" rows="3" placeholder="Masukkan alasan pemblokiran..."
                                  class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-3 text-xs text-slate-800 dark:text-white focus:border-rose-500"></textarea>
                        @error('banReason') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('showBanModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">
                        Batal
                    </button>
                    <button type="button" wire:click="toggleBanStatus" 
                            class="px-5 py-2 rounded-xl text-xs font-bold {{ $isCurrentlyBanned ? 'bg-emerald-600 hover:bg-emerald-500 text-white' : 'bg-rose-600 hover:bg-rose-500 text-white' }} shadow-lg">
                        {{ $isCurrentlyBanned ? 'Cabut Blokir Akun' : 'Konfirmasi Suspend' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
