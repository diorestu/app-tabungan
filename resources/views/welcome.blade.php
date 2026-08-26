@php
    $isNasabah = Auth::guard('nasabah')->check();
    $currentNasabah = $isNasabah ? Auth::guard('nasabah')->user() : null;

    $isPetugas = Auth::guard('web')->check();
    $currentPetugas = $isPetugas ? Auth::guard('web')->user() : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme') === 'light' ? '' : (session('theme') === 'dark' ? 'dark' : '') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>TabunganKu - Sistem Pencatatan Tabungan Nasabah</title>

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
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans flex flex-col justify-between selection:bg-emerald-500 selection:text-white transition-colors duration-150">
        <!-- Top Navbar -->
        <header class="border-b border-zinc-200 dark:border-zinc-800/80 bg-white/80 dark:bg-zinc-950/70 backdrop-blur sticky top-0 z-50 transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 font-bold text-lg">
                        <x-icon-wallet class="size-6" />
                    </div>
                    <div>
                        <span class="font-extrabold text-base tracking-tight text-zinc-900 dark:text-white">TabunganKu</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 block -mt-0.5">Sistem Pencatatan Tabungan Nasabah</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <x-theme-toggle />
                    @if ($isNasabah)
                        <a href="{{ route('nasabah.dashboard') }}" class="px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-950 transition-all flex items-center gap-1.5">
                            <x-heroicon-s-home class="size-4" />
                            <span>Dashboard Saya</span>
                        </a>
                    @elseif ($isPetugas)
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-950 transition-all flex items-center gap-1.5">
                            <x-heroicon-s-squares-2x2 class="size-4" />
                            <span>Dashboard Petugas</span>
                        </a>
                    @else
                        <a href="{{ route('nasabah.login') }}" class="px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-950 transition-all">
                            Login Nasabah
                        </a>
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold rounded-xl bg-zinc-100 dark:bg-zinc-900 hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-300 dark:border-zinc-700 transition-all">
                            Login Petugas
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 flex flex-col items-center text-center">
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-900 dark:text-white tracking-tight max-w-3xl leading-tight">
                Pencatatan Tabungan Nasabah Lebih <span class="bg-gradient-to-r from-emerald-500 to-teal-500 dark:from-emerald-400 dark:to-teal-300 bg-clip-text text-transparent">Mudah & Transparan</span>
            </h1>

            <p class="mt-4 text-sm sm:text-base text-zinc-600 dark:text-zinc-400 max-w-2xl">
                Aplikasi tabungan digital dengan akses mandiri bagi nasabah cukup menggunakan <strong>ID Nasabah</strong> dan <strong>Nomor Handphone</strong>, serta panel lengkap bagi petugas untuk setor & tarik tunai.
            </p>

            <!-- Dual Portals Selection Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-4xl mt-12 text-left">
                <!-- Nasabah Portal Card -->
                <div class="relative group overflow-hidden rounded-3xl bg-white dark:bg-zinc-900/90 border {{ $isNasabah ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-800' }} p-8 shadow-xl dark:shadow-2xl hover:border-emerald-500/50 transition-all flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/20 transition-all"></div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="size-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @if ($isNasabah)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 text-xs font-bold border border-emerald-200 dark:border-emerald-800">
                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Sesi Aktif</span>
                                </span>
                            @endif
                        </div>

                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Untuk Nasabah</span>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white tracking-tight mt-1">Portal Nasabah Mandiri</h2>
                        <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 mt-2">
                            Cek saldo tabungan terkini, kelola kantong target impian, lihat riwayat setor & penarikan, serta unduh / cetak rekening koran mutasi tabungan secara langsung.
                        </p>

                        @if ($isNasabah)
                            <div class="mt-4 p-3 rounded-xl bg-emerald-50/80 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/80 text-xs text-emerald-900 dark:text-emerald-200 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-s-user-circle class="size-5 text-emerald-600 shrink-0" />
                                    <div>
                                        <span class="font-bold block">{{ $currentNasabah->nama }}</span>
                                        <span class="text-[10px] text-zinc-500 dark:text-zinc-400">ID: {{ $currentNasabah->nomor_nasabah }}</span>
                                    </div>
                                </div>
                                <span class="font-black tabular-nums text-sm">{{ $currentNasabah->formatted_saldo }}</span>
                            </div>
                        @else
                            <div class="mt-4 p-3 rounded-xl bg-zinc-100 dark:bg-zinc-950/70 border border-zinc-200 dark:border-zinc-800/80 text-xs text-zinc-700 dark:text-zinc-300 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-emerald-600 dark:text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                <span>Login hanya butuh <strong>ID Nasabah</strong> + <strong>No HP</strong></span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        @if ($isNasabah)
                            <a 
                                href="{{ route('nasabah.dashboard') }}" 
                                class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 text-center"
                            >
                                <span>Buka Dashboard Nasabah Saya</span>
                                <x-heroicon-s-arrow-right class="size-4" />
                            </a>
                        @else
                            <a 
                                href="{{ route('nasabah.login') }}" 
                                class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 text-center"
                            >
                                <span>Masuk ke Portal Nasabah</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Petugas / Admin Portal Card -->
                <div class="relative group overflow-hidden rounded-3xl bg-white dark:bg-zinc-900/90 border {{ $isPetugas ? 'border-zinc-400 dark:border-zinc-600 ring-2 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-800' }} p-8 shadow-xl dark:shadow-2xl hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-teal-500/20 transition-all"></div>

                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="size-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 flex items-center justify-center border border-zinc-300 dark:border-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @if ($isPetugas)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 text-xs font-bold border border-zinc-200 dark:border-zinc-700">
                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Sesi Petugas Aktif</span>
                                </span>
                            @endif
                        </div>

                        <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Untuk Petugas / Teller</span>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white tracking-tight mt-1">Panel Teller & Pengelola</h2>
                        <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 mt-2">
                            Pencatatan setor tunai, verifikasi tarik tunai kas, pendaftaran nasabah baru, pencetakan struk transaksi, dan rekapitulasi laporan harian.
                        </p>

                        @if ($isPetugas)
                            <div class="mt-4 p-3 rounded-xl bg-zinc-100 dark:bg-zinc-950/70 border border-zinc-200 dark:border-zinc-800/80 text-xs text-zinc-700 dark:text-zinc-300 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-s-shield-check class="size-5 text-emerald-600 shrink-0" />
                                    <div>
                                        <span class="font-bold block">{{ $currentPetugas->name }}</span>
                                        <span class="text-[10px] text-zinc-500">Role: {{ ucfirst($currentPetugas->role) }}</span>
                                    </div>
                                </div>
                                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">{{ $currentPetugas->email }}</span>
                            </div>
                        @else
                            <div class="mt-4 p-3 rounded-xl bg-zinc-100 dark:bg-zinc-950/70 border border-zinc-200 dark:border-zinc-800/80 text-xs text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-emerald-600 dark:text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                <span>Akun bawaan: <strong>admin@tabungan.test</strong> (pass: <strong>password</strong>)</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        @if ($isPetugas)
                            <a 
                                href="{{ route('admin.dashboard') }}" 
                                class="w-full py-3.5 px-4 bg-zinc-900 dark:bg-zinc-800 hover:bg-zinc-800 dark:hover:bg-zinc-700 text-white font-bold text-sm rounded-xl border border-zinc-700 transition-all flex items-center justify-center gap-2 text-center"
                            >
                                <span>Buka Panel Petugas / Admin</span>
                                <x-heroicon-s-arrow-right class="size-4" />
                            </a>
                        @else
                            <a 
                                href="{{ route('login') }}" 
                                class="w-full py-3.5 px-4 bg-zinc-900 dark:bg-zinc-800 hover:bg-zinc-800 dark:hover:bg-zinc-700 text-white font-bold text-sm rounded-xl border border-zinc-700 transition-all flex items-center justify-center gap-2 text-center"
                            >
                                <span>Masuk ke Panel Petugas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-zinc-200 dark:border-zinc-800/80 bg-white dark:bg-zinc-950 py-6 text-center text-xs text-zinc-500 transition-colors">
            <p>&copy; {{ date('Y') }} TabunganKu. Dibangun dengan Laravel 12, Livewire, dan Flux UI.</p>
        </footer>

        @fluxScripts
    </body>
</html>

