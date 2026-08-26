<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Sistem Tabungan Nasabah' }} - TabunganKu</title>

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans flex flex-col justify-between selection:bg-emerald-500 selection:text-white transition-colors duration-150">
        <header class="border-b border-zinc-200 dark:border-zinc-800/80 bg-white/80 dark:bg-zinc-950/70 backdrop-blur sticky top-0 z-50 transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <div class="size-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-600/30 font-bold text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                            <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-base tracking-tight text-zinc-900 dark:text-white">TabunganKu</span>
                        <span class="hidden sm:inline-block text-xs text-zinc-500 dark:text-zinc-400 ml-2 border-l border-zinc-300 dark:border-zinc-700 pl-2">Sistem Buku Tabungan Digital</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 sm:gap-3 text-xs font-medium">
                    <x-theme-toggle />
                    <a href="{{ route('nasabah.login') }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold transition-colors shadow-sm">
                        Login Nasabah
                    </a>
                    <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-900 hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-colors border border-zinc-200 dark:border-zinc-700">
                        Login Petugas
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1 flex flex-col justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-200 dark:border-zinc-800/80 bg-white dark:bg-zinc-950 py-6 text-center text-xs text-zinc-500 transition-colors">
            <p>&copy; {{ date('Y') }} TabunganKu - Sistem Pencatatan Tabungan Nasabah. Dibangun dengan Laravel 12, Livewire, dan Flux UI.</p>
        </footer>

        @fluxScripts
        @livewireScripts
    </body>
</html>

