<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" 
        x-data="{ 
            activeTab: new URLSearchParams(window.location.search).get('tab') || 'general',
            showLogoutSessionsModal: false,
            showAddAccountModal: false,
            showDeleteAccountModal: false,
            showRecoveryCodes: false,
            showNewTokenModal: false,
            copiedReferral: false,
            copiedToken: false,
            newPassword: '',
            get passwordStrength() {
                let score = 0;
                if (this.newPassword.length >= 8) score++;
                if (/[A-Z]/.test(this.newPassword)) score++;
                if (/[0-9]/.test(this.newPassword)) score++;
                if (/[^A-Za-z0-9]/.test(this.newPassword)) score++;
                return score;
            },
            get passwordStrengthText() {
                if (this.newPassword.length === 0) return '';
                if (this.passwordStrength <= 1) return 'Sangat Lemah';
                if (this.passwordStrength === 2) return 'Cukup';
                if (this.passwordStrength === 3) return 'Kuat';
                return 'Sangat Aman';
            },
            get passwordStrengthColor() {
                if (this.passwordStrength <= 1) return 'bg-rose-500';
                if (this.passwordStrength === 2) return 'bg-amber-500';
                if (this.passwordStrength === 3) return 'bg-sky-500';
                return 'bg-emerald-500';
            },
            copyReferral(url) {
                navigator.clipboard.writeText(url);
                this.copiedReferral = true;
                setTimeout(() => this.copiedReferral = false, 2500);
            },
            copyToken(token) {
                navigator.clipboard.writeText(token);
                this.copiedToken = true;
                setTimeout(() => this.copiedToken = false, 2500);
            }
        }">

        <!-- Breadcrumb Navigasi -->
        <div class="flex items-center justify-between text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-indigo-400 transition">Beranda</a>
                <span class="text-slate-600">/</span>
                <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="hover:text-indigo-400 transition">Dashboard</a>
                <span class="text-slate-600">/</span>
                <span class="text-slate-200 font-semibold">Pengaturan Akun & Profil</span>
            </div>
            <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" 
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-medium text-xs transition border border-slate-700/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- HERO BANNER PROFIL CYBERPUNK LUXURY -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-800/80 bg-gradient-to-br from-[#0f172a] via-[#0d1424] to-[#0a0f1d] p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
            <!-- Background Glow Ornaments -->
            <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-indigo-600/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-purple-600/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-6">
                <div class="flex flex-col lg:flex-row items-center lg:items-start justify-between gap-6">
                    
                    <!-- Avatar & Info Pengguna -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                        <!-- Avatar Foto / Inisial -->
                        <div class="relative group">
                            <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 p-1 shadow-xl shadow-indigo-500/20">
                                @if ($user->profile_photo_path)
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                                        class="h-full w-full rounded-xl object-cover bg-slate-950">
                                @else
                                    <div class="h-full w-full rounded-xl bg-slate-950 flex items-center justify-center text-3xl font-black text-white bg-gradient-to-b from-slate-900 to-slate-950">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <!-- Status Indicator Pin -->
                            <span class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-2 border-[#0f172a] bg-emerald-400 ring-2 ring-emerald-500/20" title="Akun Terverifikasi"></span>
                        </div>

                        <!-- Identitas & Badges -->
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $user->name }}</h1>
                                
                                <!-- Role Badge -->
                                @if ($user->role === 'superadmin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-purple-500/10 border border-purple-500/30 text-purple-400 shadow-sm shadow-purple-500/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                        Superadmin Root
                                    </span>
                                @elseif($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-sky-500/10 border border-sky-500/30 text-sky-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                                        Admin CS
                                    </span>
                                @elseif($user->tier === 'vip')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-500/10 border border-amber-500/30 text-amber-400">
                                        👑 VIP Enterprise
                                    </span>
                                @elseif($user->tier === 'reseller')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                                        💼 Reseller Partner
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-indigo-500/10 border border-indigo-500/30 text-indigo-400">
                                        🎮 Member Pelanggan
                                    </span>
                                @endif

                                @if ($user->hasEnabledTwoFactorAuthentication())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400" title="2FA Dilindungi">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        2FA Aktif
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-4 gap-y-1 text-xs text-slate-400">
                                <span class="font-mono text-slate-300 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $user->email }}
                                </span>
                                @if($user->phone)
                                    <span class="font-mono text-slate-300 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.102-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564c.174.086.289.13.332.202.043.072.043.419-.101.824z"/>
                                        </svg>
                                        {{ $user->phone }}
                                    </span>
                                @endif
                                <span class="text-slate-500">
                                    ID: <span class="font-mono text-slate-400">#AS-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </span>
                                <span class="text-slate-500">
                                    Gabung: {{ $user->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            @if($user->bio)
                                <p class="text-xs text-slate-400 italic bg-slate-900/50 px-3 py-1.5 rounded-xl border border-slate-800/60 max-w-xl">
                                    "{{ $user->bio }}"
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Ringkasan Statistik Finansial & Game (Right side) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full lg:w-auto">
                        <!-- Saldo Dompet -->
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 flex flex-col justify-between">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Saldo Dompet</span>
                            <div class="mt-1">
                                <span class="text-base sm:text-lg font-black text-emerald-400 font-mono">
                                    Rp {{ number_format($user->balance, 0, ',', '.') }}
                                </span>
                            </div>
                            <a href="{{ route('dashboard') }}" class="mt-2 text-[10px] font-bold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                                + Top Up Saldo &rarr;
                            </a>
                        </div>

                        <!-- Total Belanja -->
                        <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 flex flex-col justify-between">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Total Belanja</span>
                            <div class="mt-1">
                                <span class="text-base sm:text-lg font-black text-white font-mono">
                                    Rp {{ number_format($totalSpent, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="mt-2 text-[10px] text-slate-500">
                                {{ $totalOrders }} transaksi sukses
                            </span>
                        </div>

                        <!-- Kode Referral -->
                        <div class="col-span-2 sm:col-span-1 p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 flex flex-col justify-between">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Kode Afiliasi</span>
                            <div class="mt-1 flex items-center justify-between gap-1">
                                <span class="text-sm font-black text-amber-400 font-mono tracking-wider">
                                    {{ $user->referral_code }}
                                </span>
                                <button type="button" @click="copyReferral('{{ route('referral.link', $user->referral_code) }}')"
                                    class="p-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[10px] transition"
                                    title="Salin Link Afiliasi">
                                    <svg x-show="!copiedReferral" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <svg x-show="copiedReferral" class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="mt-2 text-[10px] text-emerald-400 font-mono">
                                Komisi: Rp {{ number_format($referralEarnings, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tier Progression Progress Bar -->
                <div class="pt-4 border-t border-slate-800/60">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 font-medium">Status Akun:</span>
                            <span class="font-bold text-white uppercase">{{ $user->tier }}</span>
                            <span class="text-slate-600">&rarr;</span>
                            <span class="font-bold text-indigo-400">Target Berikutnya: {{ $nextTier['name'] }}</span>
                        </div>
                        <div class="text-slate-400 text-[11px]">
                            <span>Rp {{ number_format($nextTier['current_spent'], 0, ',', '.') }} / Rp {{ number_format($nextTier['target_spent'], 0, ',', '.') }}</span>
                            <span class="font-bold text-emerald-400 ml-1">({{ $nextTier['percent'] }}%)</span>
                        </div>
                    </div>
                    <!-- Progress Track -->
                    <div class="mt-2 w-full bg-slate-900 rounded-full h-2 overflow-hidden p-0.5 border border-slate-800">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-400 transition-all duration-500" 
                            style="width: {{ $nextTier['percent'] }}%"></div>
                    </div>
                    <p class="mt-1.5 text-[10px] text-slate-500 flex items-center gap-1">
                        <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Keuntungan Tier: {{ $nextTier['benefits'] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- TAB NAVIGATION SYSTEM -->
        <div class="border-b border-slate-800 overflow-x-auto scrollbar-none">
            <div class="flex items-center gap-2 min-w-max pb-3">
                
                <!-- Tab 1: Identitas Profil -->
                <button type="button" @click="activeTab = 'general'"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2"
                    :class="activeTab === 'general' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-900/60 border border-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-800/60'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Profil
                </button>

                <!-- Tab 2: Keamanan & 2FA -->
                <button type="button" @click="activeTab = 'security'"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2"
                    :class="activeTab === 'security' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-900/60 border border-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-800/60'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Keamanan & 2FA
                    @if ($user->hasEnabledTwoFactorAuthentication())
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    @endif
                </button>

                <!-- Tab 3: Akun Game Favorit -->
                <button type="button" @click="activeTab = 'saved_accounts'"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2"
                    :class="activeTab === 'saved_accounts' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-900/60 border border-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-800/60'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Akun Game Tersimpan
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-800 text-slate-300 font-mono">{{ $savedAccounts->count() }}</span>
                </button>

                <!-- Tab 4: Sesi & Perangkat Aktif -->
                <button type="button" @click="activeTab = 'sessions'"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2"
                    :class="activeTab === 'sessions' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-900/60 border border-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-800/60'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Sesi Perangkat
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-800 text-slate-300 font-mono">{{ $sessions->count() }}</span>
                </button>

                <!-- Tab 5: Notifikasi & Audio -->
                <button type="button" @click="activeTab = 'notifications'"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2"
                    :class="activeTab === 'notifications' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-900/60 border border-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-800/60'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Notifikasi & Suara
                </button>

                <!-- Tab 6: API Reseller & Webhook -->
                <button type="button" @click="activeTab = 'api'"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2"
                    :class="activeTab === 'api' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-900/60 border border-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-800/60'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    API H2H & Webhook
                    @if($tokens->count() > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-emerald-500/20 text-emerald-400 font-mono">{{ $tokens->count() }}</span>
                    @endif
                </button>

                <!-- Tab 7: Zona Privasi & Akun -->
                <button type="button" @click="activeTab = 'danger'"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2"
                    :class="activeTab === 'danger' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-slate-900/60 border border-slate-800/80 text-slate-400 hover:text-rose-400 hover:bg-slate-800/60'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Zona Akun & Privasi
                </button>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: INFORMASI PROFIL LENGKAP -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'general'" x-transition class="space-y-6">
            @if (session('status_profile'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('status_profile') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Foto Profil Uploader -->
                <div class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 shadow-xl flex flex-col items-center text-center justify-between">
                    <div class="space-y-4 w-full flex flex-col items-center">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Foto Profil Avatar</h3>
                        <div class="relative group">
                            <div class="h-32 w-32 rounded-3xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 p-1 shadow-xl shadow-indigo-500/20">
                                @if ($user->profile_photo_path)
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-full w-full rounded-2xl object-cover bg-slate-950">
                                @else
                                    <div class="h-full w-full rounded-2xl bg-slate-950 flex items-center justify-center text-4xl font-black text-white bg-gradient-to-b from-slate-900 to-slate-950">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-slate-400">Format yang didukung: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                    </div>

                    <div class="pt-6 w-full space-y-2">
                        @if ($user->profile_photo_path)
                            <form action="{{ route('user.profile.delete-avatar') }}" method="POST" onsubmit="return confirm('Hapus foto profil dan kembalikan ke avatar inisial?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 font-bold text-xs transition">
                                    Hapus Foto Profil
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Form Informasi Dasar Akun -->
                <div class="lg:col-span-2 rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-6">
                    <div>
                        <h2 class="text-base font-bold text-white">Detail Identitas & Kontak</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Perbarui data kontak agar otomatis terisi saat checkout pesanan dan menerima invoice WhatsApp.</p>
                    </div>

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('name')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Alamat Email -->
                            <div>
                                <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('email')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nomor WhatsApp -->
                            <div>
                                <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Nomor WhatsApp (Untuk Notifikasi Invoice)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2.5 text-sm text-slate-500 font-mono">🇮🇩</span>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                                        class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] pl-11 pr-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                </div>
                                @error('phone')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Discord / Gamer Nickname -->
                            <div>
                                <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Discord Tag / Gamer Nickname</label>
                                <input type="text" name="discord_tag" value="{{ old('discord_tag', $user->discord_tag) }}" placeholder="Contoh: gamer#1337"
                                    class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                @error('discord_tag')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Upload Avatar Baru -->
                        <div>
                            <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Ganti Foto Avatar (Opsional)</label>
                            <input type="file" name="photo" accept="image/png, image/jpeg, image/webp"
                                class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer bg-slate-950 border border-slate-800 rounded-xl">
                            @error('photo')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bio Singkat -->
                        <div>
                            <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Bio Singkat / Gamer Status</label>
                            <textarea name="bio" rows="2" placeholder="Tuliskan slogan gamer atau bio singkat Anda..."
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: KEAMANAN & TWO-FACTOR AUTH (2FA) -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'security'" x-transition style="display: none;" class="space-y-6">
            @if (session('status_password'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('status_password') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form Ganti Password -->
                <div class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-6">
                    <div>
                        <h2 class="text-base font-bold text-white">Perbarui Kata Sandi</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Gunakan kombinasi minimal 8 karakter dengan huruf kapital, angka, dan simbol.</p>
                    </div>

                    <form action="{{ route('user.profile.update-password') }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" required placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                            @error('current_password')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Kata Sandi Baru</label>
                            <input type="password" name="password" x-model="newPassword" required placeholder="Minimal 8 karakter"
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                            
                            <!-- Real-time Password Strength Meter -->
                            <div x-show="newPassword.length > 0" class="mt-2 space-y-1">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="text-slate-400">Kekuatan Sandi:</span>
                                    <span class="font-bold font-mono" :class="passwordStrength >= 3 ? 'text-emerald-400' : (passwordStrength == 2 ? 'text-amber-400' : 'text-rose-400')" x-text="passwordStrengthText"></span>
                                </div>
                                <div class="w-full bg-slate-900 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full transition-all duration-300 rounded-full" :class="passwordStrengthColor" :style="'width: ' + (passwordStrength * 25) + '%'"></div>
                                </div>
                            </div>
                            @error('password')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                                Simpan Password Baru
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Two-Factor Authentication (2FA) Card -->
                <div class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-bold text-white">Two-Factor Authentication (2FA)</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Amankan akun Anda dengan verifikasi kode OTP dari Google Authenticator.</p>
                            </div>
                            @if ($user->hasEnabledTwoFactorAuthentication())
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-800 border border-slate-700 text-slate-400">
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                        <!-- Info Deskripsi 2FA -->
                        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 space-y-2 text-xs text-slate-300">
                            <p class="leading-relaxed">
                                Saat 2FA aktif, Anda akan diminta memasukkan token keamanan 6 digit setiap kali login atau melakukan mutasi saldo bernilai tinggi.
                            </p>
                        </div>

                        <!-- 2FA Aktif: Tampilkan Recovery Codes & Form Disable -->
                        @if ($user->hasEnabledTwoFactorAuthentication())
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-emerald-500/5 border border-emerald-500/20 text-xs text-emerald-300 space-y-2">
                                    <div class="font-bold flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Two-Factor Authentication Aktif & Melindungi Akun
                                    </div>
                                    <p class="text-[11px] text-slate-400">
                                        Simpan kode pemulihan (recovery codes) di tempat aman jika sewaktu-waktu ponsel Anda hilang.
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 pt-2">
                                    <button type="button" @click="showRecoveryCodes = !showRecoveryCodes"
                                        class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition">
                                        <span x-text="showRecoveryCodes ? 'Sembunyikan Recovery Codes' : 'Lihat Recovery Codes'"></span>
                                    </button>

                                    <!-- Form Nonaktifkan 2FA -->
                                    <form action="{{ route('two-factor.disable') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan 2FA? Keamanan akun akan berkurang.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 text-xs font-bold transition">
                                            Nonaktifkan 2FA
                                        </button>
                                    </form>
                                </div>

                                <!-- Box Recovery Codes -->
                                <div x-show="showRecoveryCodes" x-transition class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Kode Pemulihan Cadangan (Emergency Codes):</span>
                                    <div class="grid grid-cols-2 gap-2 text-xs font-mono text-slate-300">
                                        @foreach ((array) $user->recoveryCodes() as $code)
                                            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-center">{{ $code }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        
                        <!-- 2FA Sedang Diaktifkan (Butuh Konfirmasi OTP) -->
                        @elseif ($user->two_factor_secret && !$user->two_factor_confirmed_at)
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs space-y-2">
                                    <p class="font-bold">Pindai Kode QR berikut dengan aplikasi Google Authenticator:</p>
                                    <div class="flex justify-center p-3 bg-white rounded-xl w-max mx-auto">
                                        {!! $user->twoFactorQrCodeSvg() !!}
                                    </div>
                                </div>

                                <!-- Form Konfirmasi OTP 6 Digit -->
                                <form action="{{ route('two-factor.confirm') }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-slate-400 font-bold mb-1 text-[10px] uppercase tracking-wider">Masukkan 6 Digit OTP</label>
                                        <input type="text" name="code" required placeholder="Contoh: 123456" maxlength="6"
                                            class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2 text-center text-lg font-mono tracking-widest text-white focus:border-indigo-500 focus:outline-none">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="submit" class="flex-1 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition">
                                            Konfirmasi & Selesaikan 2FA
                                        </button>
                                    </div>
                                </form>
                            </div>

                        <!-- 2FA Belum Aktif: Tampilkan Tombol Aktifkan -->
                        @else
                            <form action="{{ route('two-factor.enable') }}" method="POST" class="pt-2">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    Mulai Aktifkan Two-Factor Authentication
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: AKUN GAME FAVORIT TERSIMPAN -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'saved_accounts'" x-transition style="display: none;" class="space-y-6">
            @if (session('success_account'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success_account') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white">Preset & Akun Game Favorit</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola User ID & Zone ID akun game Anda untuk top up kilat dalam 1-klik tanpa input ulang.</p>
                </div>
                <button type="button" @click="showAddAccountModal = true"
                    class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Akun Game Baru
                </button>
            </div>

            <!-- List Akun Tersimpan Grid -->
            @if ($savedAccounts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($savedAccounts as $acc)
                        <div class="p-5 rounded-3xl border border-slate-800 bg-[#0f172a]/90 shadow-xl relative group flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 border border-indigo-500/20 text-indigo-400">
                                        {{ $acc->product->name ?? 'Game Product' }}
                                    </span>
                                    <form action="{{ route('user.saved-accounts.destroy', $acc->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini dari preset tersimpan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-500 hover:text-rose-400 transition p-1" title="Hapus Akun">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <div>
                                    <h4 class="text-sm font-black text-white">{{ $acc->account_name }}</h4>
                                    @if ($acc->nickname)
                                        <div class="text-xs text-emerald-400 font-bold flex items-center gap-1 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $acc->nickname }}
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3 rounded-2xl bg-slate-950/80 border border-slate-800/80 text-xs font-mono space-y-1">
                                    <div class="flex justify-between text-slate-400">
                                        <span>Target ID:</span>
                                        <span class="text-white font-bold">{{ $acc->target_account }}</span>
                                    </div>
                                    @if ($acc->target_zone)
                                        <div class="flex justify-between text-slate-400">
                                            <span>Zone ID:</span>
                                            <span class="text-white font-bold">({{ $acc->target_zone }})</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol Top Up Instan -->
                            @if ($acc->product)
                                <a href="{{ route('order.show', $acc->product->slug) }}?target={{ urlencode($acc->target_account) }}&zone={{ urlencode($acc->target_zone) }}" 
                                    class="w-full py-2 rounded-xl bg-slate-800 hover:bg-indigo-600 text-slate-200 hover:text-white text-xs font-bold text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                                    <span>Top Up Akun Ini</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="p-12 text-center rounded-3xl border border-dashed border-slate-800 bg-[#0f172a]/50 space-y-4">
                    <div class="h-16 w-16 mx-auto rounded-2xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div class="max-w-md mx-auto space-y-1">
                        <h3 class="text-base font-bold text-white">Belum Ada Akun Game Tersimpan</h3>
                        <p class="text-xs text-slate-400">Simpan User ID Mobile Legends, Free Fire, atau game favorit Anda lainnya untuk mempermudah transaksi cepat.</p>
                    </div>
                    <button type="button" @click="showAddAccountModal = true"
                        class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition">
                        + Tambah Akun Pertama
                    </button>
                </div>
            @endif

            <!-- MODAL TAMBAH AKUN GAME -->
            <div x-show="showAddAccountModal" x-transition style="display: none;" 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                <div @click.away="showAddAccountModal = false" class="max-w-md w-full rounded-3xl border border-slate-800 bg-[#0f172a] p-6 sm:p-8 shadow-2xl space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-white">Tambah Preset Akun Game</h3>
                        <button type="button" @click="showAddAccountModal = false" class="text-slate-400 hover:text-white text-lg font-bold">&times;</button>
                    </div>

                    <form action="{{ route('user.saved-accounts.store') }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block text-slate-400 font-bold mb-1 uppercase tracking-wider text-[10px]">Pilih Game / Layanan</label>
                            <select name="product_id" required class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                                <option value="">-- Pilih Game --</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-slate-400 font-bold mb-1 uppercase tracking-wider text-[10px]">Label Akun (Misal: Akun Utama)</label>
                            <input type="text" name="account_name" placeholder="Contoh: Akun ML Utama"
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-400 font-bold mb-1 uppercase tracking-wider text-[10px]">User ID / Nomor</label>
                                <input type="text" name="target_account" required placeholder="Contoh: 12345678"
                                    class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none font-mono">
                            </div>
                            <div>
                                <label class="block text-slate-400 font-bold mb-1 uppercase tracking-wider text-[10px]">Zone ID (Opsional)</label>
                                <input type="text" name="target_zone" placeholder="Contoh: 2134"
                                    class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none font-mono">
                            </div>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-2">
                            <button type="button" @click="showAddAccountModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-indigo-600/30">
                                Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: SESI & PERANGKAT AKTIF -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'sessions'" x-transition style="display: none;" class="space-y-6">
            @if (session('status_sessions'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('status_sessions') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white">Sesi Login & Perangkat Aktif</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola dan pantau peramban serta perangkat yang sedang mengakses akun Anda.</p>
                </div>
                <button type="button" @click="showLogoutSessionsModal = true"
                    class="px-4 py-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 font-bold text-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout Perangkat Lain
                </button>
            </div>

            <!-- List Sessions Grid -->
            <div class="space-y-3">
                @foreach ($sessions as $s)
                    <div class="p-4 sm:p-5 rounded-2xl border border-slate-800 bg-[#0f172a]/90 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="h-11 w-11 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400">
                                @if ($s->agent['is_desktop'])
                                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-white">{{ $s->agent['platform'] }} - {{ $s->agent['browser'] }}</span>
                                    @if ($s->is_current_device)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                                            Perangkat Ini
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-400 font-mono flex items-center gap-2">
                                    <span>IP: {{ $s->ip_address }}</span>
                                    <span>•</span>
                                    <span>{{ $s->is_current_device ? 'Aktif Sekarang' : 'Terakhir aktif: ' . $s->last_active }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            @if ($s->is_current_device)
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 animate-pulse block" title="Sesi Terbuka"></span>
                            @else
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-600 block" title="Sesi Tersimpan"></span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- MODAL LOGOUT PERANGKAT LAIN -->
            <div x-show="showLogoutSessionsModal" x-transition style="display: none;" 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                <div @click.away="showLogoutSessionsModal = false" class="max-w-md w-full rounded-3xl border border-slate-800 bg-[#0f172a] p-6 sm:p-8 shadow-2xl space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-white">Keluar dari Sesi Lain</h3>
                        <button type="button" @click="showLogoutSessionsModal = false" class="text-slate-400 hover:text-white text-lg font-bold">&times;</button>
                    </div>

                    <p class="text-xs text-slate-400 leading-relaxed">
                        Masukkan kata sandi akun Anda untuk mengonfirmasi pemutusan seluruh sesi login di perangkat atau peramban lain.
                    </p>

                    <form action="{{ route('user.profile.logout-other-sessions') }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block text-slate-400 font-bold mb-1 uppercase tracking-wider text-[10px]">Kata Sandi Anda</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                            @error('password')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button type="button" @click="showLogoutSessionsModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition shadow-lg shadow-rose-600/30">
                                Putuskan Sesi Lain
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: NOTIFIKASI & SUARA -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'notifications'" x-transition style="display: none;" class="space-y-6">
            @if (session('status_notifications'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('status_notifications') }}
                </div>
            @endif

            <div class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-6">
                <div>
                    <h2 class="text-base font-bold text-white">Preferensi Notifikasi Transaksi</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Tentukan bagaimana Anda ingin menerima pembaruan status faktur, bukti pembayaran, dan voucher pesanan.</p>
                </div>

                @php
                    $prefs = $user->notification_preferences ?? [
                        'whatsapp_orders' => true,
                        'email_receipts' => true,
                        'promo_alerts' => true,
                        'security_alerts' => true,
                    ];
                @endphp

                <form action="{{ route('user.profile.update-notifications') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-3">
                        <!-- Toggle WhatsApp -->
                        <label class="p-4 rounded-2xl border border-slate-800/80 bg-slate-950/60 flex items-center justify-between cursor-pointer hover:border-slate-700 transition">
                            <div class="flex items-center gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.102-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564c.174.086.289.13.332.202.043.072.043.419-.101.824z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-white">Notifikasi Faktur & Voucher via WhatsApp</span>
                                    <span class="block text-[11px] text-slate-400">Kirimkan rincian invoice dan nomor serial game langsung ke WhatsApp nomor terdaftar.</span>
                                </div>
                            </div>
                            <input type="checkbox" name="whatsapp_orders" value="1" {{ !empty($prefs['whatsapp_orders']) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Email -->
                        <label class="p-4 rounded-2xl border border-slate-800/80 bg-slate-950/60 flex items-center justify-between cursor-pointer hover:border-slate-700 transition">
                            <div class="flex items-center gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-white">Bukti Pembayaran Elektronik (E-Receipt Email)</span>
                                    <span class="block text-[11px] text-slate-400">Kirimkan file invoice PDF dan bukti transaksi berhasil ke alamat email Anda.</span>
                                </div>
                            </div>
                            <input type="checkbox" name="email_receipts" value="1" {{ !empty($prefs['email_receipts']) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Promo -->
                        <label class="p-4 rounded-2xl border border-slate-800/80 bg-slate-950/60 flex items-center justify-between cursor-pointer hover:border-slate-700 transition">
                            <div class="flex items-center gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-white">Pemberitahuan Diskon & Flash Sale</span>
                                    <span class="block text-[11px] text-slate-400">Dapatkan penawaran voucher promo spesial member dan event top up mingguan.</span>
                                </div>
                            </div>
                            <input type="checkbox" name="promo_alerts" value="1" {{ !empty($prefs['promo_alerts']) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Security -->
                        <label class="p-4 rounded-2xl border border-slate-800/80 bg-slate-950/60 flex items-center justify-between cursor-pointer hover:border-slate-700 transition">
                            <div class="flex items-center gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-white">Peringatan Keamanan Login Akun</span>
                                    <span class="block text-[11px] text-slate-400">Menerima email darurat jika terdeteksi aktivitas login dari perangkat atau IP baru.</span>
                                </div>
                            </div>
                            <input type="checkbox" name="security_alerts" value="1" {{ !empty($prefs['security_alerts']) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                        </label>
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                            Simpan Preferensi Notifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 6: API H2H RESELLER & WEBHOOK -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'api'" x-transition style="display: none;" class="space-y-6">
            @if (session('status_tokens') || session('status_webhook'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('status_tokens') ?? session('status_webhook') }}
                </div>
            @endif

            <!-- Modal / Box Token Baru Jika Dibuat -->
            @if (session('new_plain_token'))
                <div class="p-6 rounded-3xl border border-emerald-500/40 bg-emerald-500/10 shadow-2xl space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-300 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            API Token Berhasil Dibuat!
                        </span>
                        <span class="text-[11px] text-rose-400 font-bold">Salin sekarang, token tidak akan ditampilkan kembali!</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ session('new_plain_token') }}" 
                            class="w-full rounded-xl border border-emerald-500/30 bg-slate-950 px-4 py-2.5 text-xs text-emerald-400 font-mono select-all">
                        <button type="button" @click="copyToken('{{ session('new_plain_token') }}')"
                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition flex items-center gap-1.5 whitespace-nowrap">
                            <span x-text="copiedToken ? 'Tersalin!' : 'Salin Token'"></span>
                        </button>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sanctum API Personal Access Tokens -->
                <div class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-5">
                    <div>
                        <h2 class="text-base font-bold text-white">Personal Access Token (API H2H)</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Gunakan API token untuk mengintegrasikan bot transaksi WhatsApp, Telegram, atau aplikasi web store Anda sendiri.</p>
                    </div>

                    <!-- Form Buat Token Baru -->
                    <form action="{{ route('user.profile.tokens.store') }}" method="POST" class="space-y-3 text-xs">
                        @csrf
                        <div>
                            <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Nama Perangkat / Integrasi Token</label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="token_name" required placeholder="Misal: Bot WhatsApp Store"
                                    class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs whitespace-nowrap transition shadow-lg shadow-indigo-600/30">
                                    + Buat Token
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- List Token Aktif -->
                    <div class="space-y-2 pt-2 border-t border-slate-800/60">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-2">Daftar Token Aktif:</span>
                        @forelse ($tokens as $token)
                            <div class="p-3 rounded-2xl border border-slate-800/80 bg-slate-950/60 flex items-center justify-between gap-3 text-xs">
                                <div>
                                    <span class="font-bold text-white block">{{ $token->name }}</span>
                                    <span class="text-[10px] text-slate-500 font-mono">
                                        Dibuat: {{ $token->created_at->translatedFormat('d M Y') }} • 
                                        Terakhir: {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Belum digunakan' }}
                                    </span>
                                </div>
                                <form action="{{ route('user.profile.tokens.destroy', $token->id) }}" method="POST" onsubmit="return confirm('Cabut token ini? Aplikasi yang menggunakan token ini tidak dapat melakukan transaksi lagi.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-xs transition" title="Cabut Token">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 italic">Belum ada API token yang aktif.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Webhook Callback URL -->
                <div class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-base font-bold text-white">Webhook Callback Reseller</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Sistem kami akan mengirimkan HTTP POST event setiap kali pesanan Anda berubah status menjadi <code class="text-emerald-400">PAID</code> atau <code class="text-rose-400">FAILED</code>.</p>
                        </div>

                        <form action="{{ route('user.profile.update-webhook') }}" method="POST" class="space-y-3 text-xs">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Endpoint URL Callback Webhook</label>
                                <input type="url" name="webhook_url" value="{{ old('webhook_url', $user->webhook_url) }}" placeholder="https://domainanda.com/api/callback"
                                    class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-xs text-white focus:border-indigo-500 focus:outline-none font-mono">
                                @error('webhook_url')
                                    <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-lg shadow-indigo-600/30">
                                Simpan Webhook URL
                            </button>
                        </form>
                    </div>

                    <!-- Quick Code Example -->
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2 text-xs">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Contoh Request Header:</span>
                        <pre class="text-[11px] font-mono text-indigo-300 overflow-x-auto">curl -H "Authorization: Bearer &lt;TOKEN&gt;" \
     -H "Accept: application/json" \
     {{ config('app.url') }}/api/v1/profile</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 7: ZONA AKUN & PRIVASI DATA -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'danger'" x-transition style="display: none;" class="space-y-6">
            <!-- Ekspor Data Pribadi (GDPR & Data Freedom) -->
            <div class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-white">Unduh Riwayat & Data Akun (GDPR)</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Ekspor seluruh arsip transaksi, mutasi saldo dompet, dan preset akun game dalam berkas JSON terenkripsi.</p>
                    </div>
                    <a href="{{ route('user.profile.export-data') }}" 
                        class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-xs transition flex items-center gap-2 border border-slate-700">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Data Saya (.JSON)
                    </a>
                </div>
            </div>

            <!-- Zona Bahaya: Hapus Akun -->
            <div class="rounded-3xl border border-rose-900/50 bg-rose-950/20 p-6 sm:p-8 shadow-xl space-y-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h2 class="text-base font-bold text-rose-400">Nonaktifkan atau Hapus Akun Permanen</h2>
                        <p class="text-xs text-rose-300/70 leading-relaxed max-w-xl">
                            Tindakan ini tidak dapat dibatalkan. Seluruh saldo tersisa, riwayat belanja, voucher, dan akses API akan dihapus secara permanen dari server Absolute Store.
                        </p>
                    </div>
                    <button type="button" @click="showDeleteAccountModal = true"
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition shadow-lg shadow-rose-600/30 whitespace-nowrap">
                        Hapus Akun Saya
                    </button>
                </div>
            </div>

            <!-- MODAL HAPUS AKUN DENGAN VERIFIKASI FRASA -->
            <div x-show="showDeleteAccountModal" x-transition style="display: none;" 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                <div @click.away="showDeleteAccountModal = false" class="max-w-md w-full rounded-3xl border border-rose-900/80 bg-[#0f172a] p-6 sm:p-8 shadow-2xl space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-rose-400">Konfirmasi Hapus Akun</h3>
                        <button type="button" @click="showDeleteAccountModal = false" class="text-slate-400 hover:text-white text-lg font-bold">&times;</button>
                    </div>

                    <p class="text-xs text-slate-300 leading-relaxed">
                        Ketik frasa <strong class="text-rose-400 font-mono">HAPUS AKUN SAYA</strong> dan masukkan kata sandi akun Anda untuk melanjutkan proses penghapusan.
                    </p>

                    <form action="{{ route('user.profile.delete-account') }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label class="block text-slate-400 font-bold mb-1 uppercase tracking-wider text-[10px]">Ketik Frasa Konfirmasi</label>
                            <input type="text" name="confirmation" required placeholder="HAPUS AKUN SAYA"
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-rose-300 focus:border-rose-500 focus:outline-none font-mono">
                            @error('confirmation')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-slate-400 font-bold mb-1 uppercase tracking-wider text-[10px]">Kata Sandi Anda</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                            @error('password')
                                <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button type="button" @click="showDeleteAccountModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition shadow-lg shadow-rose-600/30">
                                Ya, Hapus Akun Permanen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

