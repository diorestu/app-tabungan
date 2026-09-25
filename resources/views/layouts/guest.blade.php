<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme') === 'light' ? '' : (session('theme') === 'dark' ? 'dark' : '') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Sistem Tabungan Nasabah' }} - TabunganKu</title>

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
        <meta property="og:title" content="{{ $title ?? 'Sistem Tabungan Nasabah' }} - TabunganKu">
        <meta property="og:description" content="Sistem buku tabungan digital dan pencatatan simpanan nasabah.">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
        <x-pwa-meta />
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans flex flex-col justify-between selection:bg-emerald-500 selection:text-white transition-colors duration-150">
        <header class="border-b border-zinc-200 dark:border-zinc-800/80 bg-white/90 dark:bg-zinc-950/80 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="TabunganKu Logo" class="size-7 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-800">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm tracking-tight text-zinc-900 dark:text-white">TabunganKu</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 text-xs">
                    <x-theme-toggle />
                    @if (Auth::guard('nasabah')->check())
                        <a href="{{ route('nasabah.dashboard') }}" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold transition-colors flex items-center gap-1.5 shadow-xs">
                            <x-heroicon-s-home class="size-3.5" />
                            <span>Dashboard Nasabah</span>
                        </a>
                    @elseif (Auth::guard('web')->check())
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold transition-colors flex items-center gap-1.5 shadow-xs">
                            <x-heroicon-s-squares-2x2 class="size-3.5" />
                            <span>Dashboard Petugas</span>
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                            Halaman Utama
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 flex flex-col justify-center py-10 px-4 sm:px-6">
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 py-5 text-center text-xs text-zinc-400 dark:text-zinc-500">
            <p>&copy; {{ date('Y') }} TabunganKu. Portal Pencatatan Tabungan Nasabah.</p>
        </footer>

        @fluxScripts
        @livewireScripts
    </body>
</html>
