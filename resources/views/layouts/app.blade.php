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

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans">
        <flux:sidebar sticky collapsible="mobile" class="bg-white dark:bg-zinc-950 border-r border-zinc-200 dark:border-zinc-800 lg:sticky lg:top-0 lg:h-dvh lg:overflow-y-auto">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <div class="flex items-center justify-between px-2 py-2.5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="TabunganKu Logo" class="size-7 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-800">
                    <div class="flex flex-col">
                        <span class="font-bold text-sm tracking-tight text-zinc-900 dark:text-white leading-tight">TabunganKu</span>
                        <span class="text-[10px] text-zinc-400 font-medium">Panel Petugas</span>
                    </div>
                </a>
                <x-theme-toggle />
            </div>

            <flux:separator class="my-2" />

            <flux:navlist>
                <flux:navlist.item icon="home" href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.dashboard')">Dashboard</flux:navlist.item>
                <flux:navlist.item icon="users" href="{{ route('admin.nasabah') }}" :current="request()->routeIs('admin.nasabah*')">Data Nasabah</flux:navlist.item>
                
                <flux:navlist.group heading="Pencatatan Tabungan" class="mt-3">
                    <flux:navlist.item icon="arrow-down-tray" href="{{ route('admin.setor') }}" :current="request()->routeIs('admin.setor')">Setor Tunai</flux:navlist.item>
                    <flux:navlist.item icon="arrow-up-tray" href="{{ route('admin.tarik') }}" :current="request()->routeIs('admin.tarik')">Tarik Tunai</flux:navlist.item>
                    <flux:navlist.item icon="clock" href="{{ route('admin.transaksi') }}" :current="request()->routeIs('admin.transaksi')">Riwayat Transaksi</flux:navlist.item>
                    <flux:navlist.item icon="book-open" href="{{ route('admin.cetak-buku') }}" :current="request()->routeIs('admin.cetak-buku')">Cetak Buku Tabungan</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Kasir & Settlement" class="mt-3">
                    <flux:navlist.item icon="banknotes" href="{{ route('admin.tutup-kas') }}" :current="request()->routeIs('admin.tutup-kas')">Tutup Kas Harian</flux:navlist.item>
                    <flux:navlist.item icon="calculator" href="{{ route('admin.bagi-hasil') }}" :current="request()->routeIs('admin.bagi-hasil')">Bagi Hasil & Admin</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Sistem & Konfigurasi" class="mt-3">
                    <flux:navlist.item icon="shield-check" href="{{ route('admin.audit-log') }}" :current="request()->routeIs('admin.audit-log')">Audit Trail & Log</flux:navlist.item>
                    <flux:navlist.item icon="cog-6-tooth" href="{{ route('admin.pengaturan') }}" :current="request()->routeIs('admin.pengaturan')">Pengaturan</flux:navlist.item>
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

            <div class="p-2.5 mt-2 bg-zinc-100 dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-xl flex items-center gap-2.5">
                <div class="size-7 rounded-lg bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 truncate leading-tight">{{ auth()->user()->name ?? 'Petugas' }}</span>
                    <span class="text-[10px] text-zinc-400 truncate">{{ auth()->user()->email ?? 'admin@tabungan.test' }}</span>
                </div>
            </div>
        </flux:sidebar>

        <flux:header class="lg:hidden bg-white/90 dark:bg-zinc-950/90 backdrop-blur border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between px-4 py-2.5">
            <flux:sidebar.toggle icon="bars-3" />
            <div class="flex items-center gap-2 font-bold text-sm tracking-tight">
                <span class="text-emerald-600 dark:text-emerald-400">TabunganKu</span>
                <span class="text-xs text-zinc-400 font-normal">/ Admin</span>
            </div>
            <a href="{{ route('admin.setor') }}" class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-xs transition-colors">
                <x-heroicon-s-plus class="size-3.5" />
                <span>Transaksi</span>
            </a>
        </flux:header>

        <flux:main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
            @if (session('success'))
                <div class="mb-5 flex items-center gap-2.5 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1 font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 flex items-center gap-2.5 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-rose-600 dark:text-rose-400" viewBox="0 0 20 20" fill="currentColor">
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
