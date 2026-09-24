@php
    $user = Auth::user();
    $unreadCount = $user ? $user->unreadNotificationsCount() : 0;
    $notifications = $user ? $user->userNotifications()->latest()->take(8)->get() : collect();
@endphp

<div x-data="{
        open: false,
        unreadCount: {{ $unreadCount }},
        markAllAsRead() {
            fetch('{{ route('user.notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => {
                this.unreadCount = 0;
            });
        },
        markAsRead(id, url) {
            fetch('/user/notifications/' + id + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => {
                if (this.unreadCount > 0) this.unreadCount--;
                if (url) window.location.href = url;
            });
        }
    }"
    class="relative">

    <!-- Bell Icon Button -->
    <button type="button" @click="open = !open"
            class="relative p-2 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition cursor-pointer"
            title="Pusat Notifikasi">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Red Glowing Unread Badge -->
        <span x-show="unreadCount > 0"
              class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-black text-white shadow-lg shadow-rose-500/50"
              x-text="unreadCount > 9 ? '9+' : unreadCount"
              x-cloak>
        </span>
    </button>

    <!-- Notification Dropdown Panel -->
    <div x-show="open"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
         x-cloak
         class="absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c101d] shadow-2xl z-50 overflow-hidden">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Pusat Notifikasi</h4>
                <span x-show="unreadCount > 0" class="px-1.5 py-0.2 rounded-md bg-rose-500/10 text-rose-500 text-[10px] font-bold" x-text="unreadCount + ' Baru'"></span>
            </div>
            <button type="button" @click="markAllAsRead"
                    class="text-[10px] font-bold text-indigo-600 dark:text-cyan-400 hover:underline cursor-pointer">
                Tandai Dibaca
            </button>
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($notifications as $notif)
                <div @click="markAsRead({{ $notif->id }}, '{{ $notif->action_url }}')"
                     class="p-3.5 hover:bg-slate-50 dark:hover:bg-slate-900/60 transition cursor-pointer flex items-start gap-3 {{ ! $notif->is_read ? 'bg-indigo-50/50 dark:bg-indigo-950/20' : '' }}">
                    
                    <!-- Icon based on type -->
                    <div class="shrink-0 p-2 rounded-xl mt-0.5
                        {{ $notif->type === 'order' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                        {{ $notif->type === 'wallet' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : '' }}
                        {{ $notif->type === 'affiliate' ? 'bg-violet-500/10 text-violet-400 border border-violet-500/20' : '' }}
                        {{ $notif->type === 'system' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : '' }}
                        {{ $notif->type === 'promo' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : '' }}
                    ">
                        @if($notif->type === 'order')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        @elseif($notif->type === 'wallet')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        @elseif($notif->type === 'affiliate')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h5 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $notif->title }}</h5>
                            <span class="text-[10px] text-slate-400 font-mono shrink-0">{{ $notif->created_at->diffForHumans(null, true) }}</span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-300 mt-0.5 line-clamp-2 leading-relaxed">{{ $notif->message }}</p>
                    </div>

                    @if(! $notif->is_read)
                        <span class="h-2 w-2 rounded-full bg-cyan-400 mt-2 shrink-0"></span>
                    @endif
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-xs">
                    <svg class="w-8 h-8 mx-auto text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    Belum ada notifikasi baru.
                </div>
            @endforelse
        </div>

        @if(count($notifications) > 0)
            <div class="p-2.5 text-center bg-slate-50 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard') }}" class="text-[11px] font-bold text-indigo-600 dark:text-cyan-400 hover:underline">
                    Lihat Dasbor Akun
                </a>
            </div>
        @endif
    </div>
</div>
