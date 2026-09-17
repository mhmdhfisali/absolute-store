<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order {{ $product->name }} - {{ config('app.name', 'Absolute Store') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="min-h-screen bg-[#0b0f19] text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Ambient Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div
            class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-tr from-indigo-600/20 via-purple-600/20 to-pink-500/20 blur-[130px] rounded-full">
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 border-b border-slate-800/80 bg-[#0b0f19]/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}">
                <x-application-mark />
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Katalog
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="text-xs font-semibold px-4 py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-700 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-xs font-semibold text-slate-300 hover:text-white transition">Masuk</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Konten Utama Order -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Breadcrumb Navigasi -->
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-slate-200 transition">Beranda</a>
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-400">{{ $product->category->name }}</span>
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-indigo-400 font-semibold">{{ $product->name }}</span>
        </nav>

        <!-- Livewire Order Form Component -->
        <livewire:order-form :product="$product" />
    </main>

    <!-- Footer -->
    <footer class="mt-20 border-t border-slate-800 bg-[#070a11] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} Absolute Store. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
s
