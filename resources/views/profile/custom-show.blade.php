<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ activeTab: 'general', theme: localStorage.getItem('theme') || 'dark' }">

        <!-- Header Profil -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-800 bg-[#0f172a]/95 p-6 sm:p-8 shadow-2xl">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                <!-- Avatar Lingkaran Inisial -->
                <div class="relative group">
                    <div
                        class="h-24 w-24 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 p-1 shadow-xl shadow-indigo-600/30 flex items-center justify-center">
                        <div
                            class="h-full w-full rounded-xl bg-slate-950 flex items-center justify-center text-3xl font-black text-white">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <span
                        class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-2 border-[#0f172a] {{ $user->isAdmin() ? 'bg-purple-400' : 'bg-emerald-400' }}"
                        title="Akun Aktif"></span>
                </div>

                <!-- Bio & Identitas -->
                <div class="flex-1 text-center sm:text-left space-y-1">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 justify-center sm:justify-start">
                        <h1 class="text-2xl font-black text-white tracking-tight">{{ $user->name }}</h1>
                        <div>
                            @if ($user->role === 'superadmin')
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-500/10 border border-purple-500/30 text-purple-400">Superadmin</span>
                            @elseif($user->role === 'admin')
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-500/10 border border-sky-500/30 text-sky-400">Admin
                                    / CS</span>
                            @else
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 border border-indigo-500/30 text-indigo-400">Member
                                    Pelanggan</span>
                            @endif
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 font-mono">{{ $user->email }}</p>
                    <p class="text-[11px] text-slate-500 mt-2">
                        Member sejak {{ $user->created_at->translatedFormat('d F Y') }} • ID Pengguna: <span
                            class="font-mono text-slate-400">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </p>
                </div>

                <!-- Tombol Balik ke Dashboard -->
                <div>
                    <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                        class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                        &larr; Dashboard
                    </a>
                </div>

            </div>

            <!-- Tab Navigasi Pengaturan -->
            <div class="mt-8 pt-4 border-t border-slate-800/80 flex items-center gap-2 overflow-x-auto scrollbar-none">
                <button type="button" @click="activeTab = 'general'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap"
                    :class="activeTab === 'general' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' :
                        'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white'">
                    Informasi Profil
                </button>
                <button type="button" @click="activeTab = 'security'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap"
                    :class="activeTab === 'security' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' :
                        'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white'">
                    Keamanan & Password
                </button>
                <button type="button" @click="activeTab = 'preferences'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap"
                    :class="activeTab === 'preferences' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' :
                        'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white'">
                    Preferensi & Tema
                </button>
            </div>
        </div>

        <!-- TAB 1: INFORMASI PROFIL (NAME & EMAIL) -->
        <div x-show="activeTab === 'general'" x-transition
            class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-6">
            <div>
                <h2 class="text-base font-bold text-white">Detail Identitas Akun</h2>
                <p class="text-xs text-slate-400 mt-0.5">Perbarui nama pengguna dan alamat surel utama yang terhubung ke
                    akun Anda.</p>
            </div>

            @if (session('status_profile'))
                <div
                    class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                    {{ session('status_profile') }}
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-4 max-w-xl text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Nama
                        Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                    @error('name')
                        <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Alamat
                        Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                    @error('email')
                        <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Role Hak
                        Akses</label>
                    <input type="text" value="{{ strtoupper($user->role) }}" disabled
                        class="w-full rounded-xl border border-slate-800/60 bg-slate-900/60 px-4 py-2 text-xs font-mono text-slate-500 cursor-not-allowed">
                    <p class="text-[10px] text-slate-500 mt-1 italic">* Hak akses akun hanya dapat diubah oleh
                        Superadmin di panel manajemen pengguna.</p>
                </div>

                <div class="pt-3">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 2: KEAMANAN & PASSWORD -->
        <div x-show="activeTab === 'security'" x-transition style="display: none;"
            class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-6">
            <div>
                <h2 class="text-base font-bold text-white">Ganti Kata Sandi</h2>
                <p class="text-xs text-slate-400 mt-0.5">Pastikan kata sandi Anda menggunakan kombinasi karakter acak
                    yang kuat.</p>
            </div>

            @if (session('status_password'))
                <div
                    class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                    {{ session('status_password') }}
                </div>
            @endif

            <form action="{{ route('user.profile.update-password') }}" method="POST"
                class="space-y-4 max-w-xl text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Password
                        Saat Ini</label>
                    <input type="password" name="current_password" required
                        class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                    @error('current_password')
                        <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Password
                        Baru</label>
                    <input type="password" name="password" required
                        class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                    @error('password')
                        <span class="text-rose-400 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-slate-400 font-bold mb-1.5 uppercase tracking-wider text-[10px]">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full rounded-xl border border-slate-800 bg-[#0a0f1d] px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="pt-3">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 3: PREFERENSI & TEMA (DARK/LIGHT TOGGLE) -->
        <div x-show="activeTab === 'preferences'" x-transition style="display: none;"
            class="rounded-3xl border border-slate-800 bg-[#0f172a]/90 p-6 sm:p-8 shadow-xl space-y-6">
            <div>
                <h2 class="text-base font-bold text-white">Preferensi Tampilan & Antarmuka</h2>
                <p class="text-xs text-slate-400 mt-0.5">Sesuaikan kenyamanan visual etalase web store pada perangkat
                    Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
                <!-- Pilihan Tema Gelap (Dark Mode) -->
                <button type="button" @click="theme = 'dark'; localStorage.setItem('theme', 'dark')"
                    class="flex items-center justify-between p-4 rounded-2xl border transition text-left"
                    :class="theme === 'dark' ? 'border-indigo-500 bg-indigo-600/10 shadow-lg shadow-indigo-500/10' :
                        'border-slate-800 bg-slate-950/60 hover:border-slate-700'">
                    <div class="flex items-center gap-3">
                        <div
                            class="h-9 w-9 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-white">Dark Cyberpunk</span>
                            <span class="block text-[10px] text-slate-400">Tema gelap hemat daya (Bawaan)</span>
                        </div>
                    </div>
                    <span x-show="theme === 'dark'"
                        class="h-2.5 w-2.5 rounded-full bg-indigo-400 animate-pulse"></span>
                </button>

                <!-- Pilihan Tema Terang (Light / Day Mode) -->
                <button type="button" @click="theme = 'light'; localStorage.setItem('theme', 'light')"
                    class="flex items-center justify-between p-4 rounded-2xl border transition text-left"
                    :class="theme === 'light' ? 'border-indigo-500 bg-indigo-600/10 shadow-lg shadow-indigo-500/10' :
                        'border-slate-800 bg-slate-950/60 hover:border-slate-700'">
                    <div class="flex items-center gap-3">
                        <div
                            class="h-9 w-9 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-amber-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-white">Daylight Contrast</span>
                            <span class="block text-[10px] text-slate-400">Mode kontras tinggi siang hari</span>
                        </div>
                    </div>
                    <span x-show="theme === 'light'"
                        class="h-2.5 w-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                </button>
            </div>

            <div class="pt-4 border-t border-slate-800/60 max-w-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-bold text-white">Notifikasi Otomatis WhatsApp</span>
                        <span class="block text-[10px] text-slate-400">Menerima update invoice dan serial number via
                            gateway WhatsApp resmi toko.</span>
                    </div>
                    <span
                        class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                        Aktif
                    </span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
