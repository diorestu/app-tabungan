<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme') === 'light' ? '' : (session('theme') === 'dark' ? 'dark' : '') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Portal Nasabah' }} - TabunganKu</title>

        <script>
            (function() {
                const sessionTheme = @json(session('theme'));
                const localTheme = localStorage.getItem('flux.appearance') || localStorage.getItem('theme');
                const theme = sessionTheme || localTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    localStorage.setItem('flux.appearance', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    localStorage.setItem('flux.appearance', 'light');
                }

                if (!sessionTheme && localTheme) {
                    fetch('{{ route('theme.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ theme: theme })
                    }).catch(() => {});
                }
            })();
        </script>

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        
        <!-- Open Graph / Meta -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $title ?? 'Portal Nasabah' }} - TabunganKu">
        <meta property="og:description" content="Akses buku tabungan digital, pantau saldo mutasi, dan kantong impian nasabah.">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
        <x-pwa-meta />
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans flex flex-col selection:bg-emerald-500 selection:text-white pb-20 md:pb-8 transition-colors duration-150">
        @php
            $nasabah = Auth::guard('nasabah')->user();
        @endphp

        <!-- Top App Bar (Mobile & Desktop) -->
        <header class="bg-white/90 dark:bg-zinc-950/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800/80 sticky top-0 z-40">
            <div class="max-w-4xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-14">
                    <!-- Brand & User Greeting -->
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="TabunganKu Logo" class="size-7 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-800">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-bold text-sm text-zinc-900 dark:text-white tracking-tight">TabunganKu</span>
                                <span class="hidden sm:inline text-xs text-zinc-400">/</span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400 truncate max-w-[150px] sm:max-w-none">
                                    {{ $nasabah->nama ?? 'Nasabah' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1 bg-zinc-100 dark:bg-zinc-900 p-1 rounded-lg border border-zinc-200/80 dark:border-zinc-800 text-xs font-medium">
                        <a href="{{ route('nasabah.dashboard') }}" class="px-3 py-1 rounded-md transition-colors {{ request()->routeIs('nasabah.dashboard') ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white shadow-xs font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('nasabah.mutasi') }}" class="px-3 py-1 rounded-md transition-colors {{ request()->routeIs('nasabah.mutasi') ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white shadow-xs font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                            Mutasi
                        </a>
                        <a href="{{ route('nasabah.target') }}" class="px-3 py-1 rounded-md transition-colors {{ request()->routeIs('nasabah.target') ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white shadow-xs font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                            Target Impian
                        </a>
                        <a href="{{ route('nasabah.profil') }}" class="px-3 py-1 rounded-md transition-colors {{ request()->routeIs('nasabah.profil') ? 'bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white shadow-xs font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                            Profil Rekening
                        </a>
                    </nav>

                    <!-- User Actions & Theme Toggle -->
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <x-theme-toggle />

                        <form action="{{ route('nasabah.logout') }}" method="POST" class="inline">
                            @csrf
                            <button 
                                type="submit" 
                                title="Keluar dari Portal Nasabah"
                                class="px-2.5 py-1 text-xs font-medium rounded-lg text-zinc-600 hover:text-rose-600 dark:text-zinc-400 dark:hover:text-rose-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors flex items-center gap-1 cursor-pointer"
                            >
                                <x-heroicon-o-arrow-right-start-on-rectangle class="size-4" />
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 pt-5 pb-6">
            {{ $slot }}
        </main>

        <!-- Floating Mobile Bottom Navigation Bar (Visible only on mobile) -->
        <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-md border-t border-zinc-200 dark:border-zinc-800 px-3 py-1.5 shadow-lg">
            <div class="flex items-center justify-around">
                <a 
                    href="{{ route('nasabah.dashboard') }}" 
                    class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition-colors {{ request()->routeIs('nasabah.dashboard') ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                >
                    <x-heroicon-s-home class="size-5" />
                    <span class="text-[10px]">Beranda</span>
                </a>

                <a 
                    href="{{ route('nasabah.mutasi') }}" 
                    class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition-colors {{ request()->routeIs('nasabah.mutasi') ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                >
                    <x-heroicon-s-document-text class="size-5" />
                    <span class="text-[10px]">Mutasi</span>
                </a>

                <a 
                    href="{{ route('nasabah.target') }}" 
                    class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition-colors {{ request()->routeIs('nasabah.target') ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                >
                    <x-heroicon-s-sparkles class="size-5" />
                    <span class="text-[10px]">Target</span>
                </a>

                <a 
                    href="{{ route('nasabah.profil') }}" 
                    class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg transition-colors {{ request()->routeIs('nasabah.profil') ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                >
                    <x-heroicon-s-user class="size-5" />
                    <span class="text-[10px]">Profil</span>
                </a>
            </div>
        </nav>

        <!-- Desktop Footer -->
        <footer class="hidden md:block border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 py-5 text-center text-xs text-zinc-400 dark:text-zinc-500">
            <p>&copy; {{ date('Y') }} TabunganKu. Portal Nasabah Mandiri.</p>
        </footer>

        @fluxScripts
        @livewireScripts
    </body>
</html>
