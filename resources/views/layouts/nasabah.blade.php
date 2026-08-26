<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Portal Nasabah' }} - TabunganKu</title>

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans flex flex-col selection:bg-emerald-500 selection:text-white pb-24 md:pb-8 transition-colors duration-150">
        @php
            $nasabah = Auth::guard('nasabah')->user();
        @endphp

        <!-- Top App Bar (Mobile & Desktop) -->
        <header class="bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800/80 sticky top-0 z-40 transition-colors">
            <div class="max-w-4xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-16">
                    <!-- Brand & User Greeting -->
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-sm sm:text-base text-zinc-900 dark:text-white tracking-tight">TabunganKu</span>
                                <span class="text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-500/20">Nasabah</span>
                            </div>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400 leading-tight truncate max-w-[170px] sm:max-w-none">
                                Hai, <strong class="text-zinc-800 dark:text-zinc-200">{{ $nasabah->nama ?? 'Nasabah' }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1 bg-zinc-100 dark:bg-zinc-900/70 p-1 rounded-xl border border-zinc-200 dark:border-zinc-800">
                        <a href="{{ route('nasabah.dashboard') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('nasabah.dashboard') ? 'bg-emerald-600 text-white shadow' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('nasabah.mutasi') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('nasabah.mutasi') ? 'bg-emerald-600 text-white shadow' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                            Buku Mutasi
                        </a>
                    </nav>

                    <!-- User Actions & Theme Toggle -->
                    <div class="flex items-center gap-2">
                        <x-theme-toggle />

                        <form action="{{ route('nasabah.logout') }}" method="POST" class="inline">
                            @csrf
                            <button 
                                type="submit" 
                                title="Keluar dari Portal Nasabah"
                                class="p-2 sm:px-3 sm:py-1.5 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-rose-50 hover:text-rose-600 dark:bg-zinc-900 dark:hover:bg-rose-950/70 dark:hover:text-rose-300 border border-zinc-200 hover:border-rose-300 dark:border-zinc-800 dark:hover:border-rose-800/80 transition-all text-zinc-600 dark:text-zinc-400 flex items-center gap-1.5 cursor-pointer"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M19 10a.75.75 0 0 0-.75-.75H8.704l2.473-2.47a.75.75 0 1 0-1.06-1.06l-3.75 3.75a.75.75 0 0 0 0 1.06l3.75 3.75a.75.75 0 1 0 1.06-1.06l-2.473-2.47H18.25c.414 0 .75-.336.75-.75z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-4xl w-full mx-auto px-3 sm:px-6 pt-4 pb-6">
            {{ $slot }}
        </main>

        <!-- Floating Mobile Bottom Navigation Bar (Visible only on mobile) -->
        <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-lg border-t border-zinc-200 dark:border-zinc-800/90 px-6 py-2.5 shadow-2xl transition-colors">
            <div class="flex items-center justify-around">
                <a 
                    href="{{ route('nasabah.dashboard') }}" 
                    class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('nasabah.dashboard') ? 'text-emerald-600 dark:text-emerald-400 font-bold scale-105' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200' }}"
                >
                    <div class="p-1 rounded-xl {{ request()->routeIs('nasabah.dashboard') ? 'bg-emerald-500/10' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                    <span class="text-[10px]">Beranda</span>
                </a>

                <a 
                    href="{{ route('nasabah.mutasi') }}" 
                    class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('nasabah.mutasi') ? 'text-emerald-600 dark:text-emerald-400 font-bold scale-105' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200' }}"
                >
                    <div class="p-1 rounded-xl {{ request()->routeIs('nasabah.mutasi') ? 'bg-emerald-500/10' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-[10px]">Mutasi</span>
                </a>

                <button 
                    type="button" 
                    onclick="window.print()" 
                    class="flex flex-col items-center gap-1 text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 cursor-pointer"
                >
                    <div class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                    <span class="text-[10px]">Cetak</span>
                </button>
            </div>
        </nav>

        <!-- Desktop Footer -->
        <footer class="hidden md:block border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 py-6 text-center text-xs text-zinc-500 transition-colors">
            <p>&copy; {{ date('Y') }} TabunganKu. Portal Nasabah Mandiri.</p>
        </footer>

        @fluxScripts
        @livewireScripts
    </body>
</html>


