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

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans flex flex-col">
        <flux:sidebar sticky collapsible="mobile" class="bg-white dark:bg-zinc-950 border-r border-zinc-200 dark:border-zinc-800">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <div class="flex items-center justify-between px-2 py-3">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-600/20 font-bold text-lg">
                        <x-icon-wallet class="size-5" />
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-base tracking-tight text-zinc-900 dark:text-white">TabunganKu</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Portal Petugas & Teller</span>
                    </div>
                </div>
                <x-theme-toggle />
            </div>

            <flux:separator class="my-2" />

            <flux:navlist>
                <flux:navlist.item icon="home" href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.dashboard')">Dashboard</flux:navlist.item>
                <flux:navlist.item icon="users" href="{{ route('admin.nasabah') }}" :current="request()->routeIs('admin.nasabah*')">Data Nasabah</flux:navlist.item>
                
                <flux:navlist.group heading="Pencatatan Tabungan" class="mt-4">
                    <flux:navlist.item icon="arrow-down-tray" href="{{ route('admin.setor') }}" :current="request()->routeIs('admin.setor')">Setor Tunai</flux:navlist.item>
                    <flux:navlist.item icon="arrow-up-tray" href="{{ route('admin.tarik') }}" :current="request()->routeIs('admin.tarik')">Tarik Tunai</flux:navlist.item>
                    <flux:navlist.item icon="clock" href="{{ route('admin.transaksi') }}" :current="request()->routeIs('admin.transaksi')">Riwayat Transaksi</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist>
                <flux:navlist.item icon="arrow-right-start-on-rectangle" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Keluar (Logout)
                </flux:navlist.item>
            </flux:navlist>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            <div class="p-2 mt-2 bg-zinc-200/50 dark:bg-zinc-900/80 rounded-xl flex items-center gap-3">
                <div class="size-8 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 truncate">{{ auth()->user()->name ?? 'Petugas' }}</span>
                    <span class="text-[10px] text-zinc-500 truncate">{{ auth()->user()->email ?? 'admin@tabungan.test' }}</span>
                </div>
            </div>
        </flux:sidebar>

        <flux:header class="lg:hidden bg-white dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between px-4 py-2">
            <flux:sidebar.toggle icon="bars-3" />
            <div class="flex items-center gap-2 font-bold text-sm">
                <span class="text-emerald-600 dark:text-emerald-400">TabunganKu</span> Admin
            </div>
            <a href="{{ route('admin.setor') }}" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-2.5 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm">
                <x-heroicon-s-plus class="size-3.5" />
                <span>Transaksi</span>
            </a>
        </flux:header>

        <flux:main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm shadow-sm animate-fade-in">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1 font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0 text-rose-600 dark:text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1 font-medium">{{ session('error') }}</div>
                </div>
            @endif

            {{ $slot }}
        </flux:main>

        @fluxScripts
        @livewireScripts
    </body>
</html>

