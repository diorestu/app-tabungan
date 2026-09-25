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
        <title>TabunganKu - Sistem Pencatatan Tabungan Nasabah Digital</title>

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
        <meta property="og:title" content="TabunganKu - Sistem Pencatatan Tabungan Nasabah Digital">
        <meta property="og:description" content="Solusi terpadu buku tabungan digital, mutasi real-time, dan rekening koran standar akuntansi.">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="TabunganKu - Sistem Pencatatan Tabungan Nasabah Digital">
        <meta name="twitter:description" content="Solusi terpadu buku tabungan digital, mutasi real-time, dan rekening koran standar akuntansi.">
        <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        <x-pwa-meta />
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased font-sans flex flex-col justify-between selection:bg-emerald-500 selection:text-white transition-colors duration-150">
        <!-- Top Minimal Navbar -->
        <header class="border-b border-zinc-200 dark:border-zinc-800/80 bg-white/90 dark:bg-zinc-950/80 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="TabunganKu Logo" class="size-8 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-800">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-base tracking-tight text-zinc-900 dark:text-white">TabunganKu</span>
                        <span class="hidden sm:inline-block text-[11px] text-zinc-400 dark:text-zinc-500 font-medium">/ Digital Passbook</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    <x-theme-toggle />
                    @if ($isNasabah)
                        <a href="{{ route('nasabah.dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white transition-colors inline-flex items-center gap-1.5 shadow-xs">
                            <x-heroicon-s-home class="size-3.5" />
                            <span>Dashboard Saya</span>
                        </a>
                    @elseif ($isPetugas)
                        <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white transition-colors inline-flex items-center gap-1.5 shadow-xs">
                            <x-heroicon-s-squares-2x2 class="size-3.5" />
                            <span>Dashboard Petugas</span>
                        </a>
                    @else
                        <a href="{{ route('nasabah.login') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white transition-colors shadow-xs">
                            Login Nasabah
                        </a>
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-medium rounded-lg text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 transition-colors">
                            Petugas
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Hero Section -->
            <section class="max-w-4xl mx-auto px-4 sm:px-6 pt-14 sm:pt-20 pb-12 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-medium mb-6">
                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                    <span>Buku Tabungan Digital & Pencatatan Transaksi Real-Time</span>
                </div>

                <h1 class="text-3xl sm:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight leading-tight max-w-3xl mx-auto">
                    Pencatatan tabungan nasabah yang sederhana, jelas, dan transparan.
                </h1>

                <p class="mt-4 text-sm sm:text-base text-zinc-500 dark:text-zinc-400 max-w-xl mx-auto leading-relaxed">
                    Akses mutasi saldo seketika, rencanakan kantong target impian, dan cetak laporan rekening koran resmi standar akuntansi perbankan.
                </p>

                <!-- Dual Portals Entry Cards (Nasabah & Petugas) -->
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4 text-left max-w-2xl mx-auto">
                    <!-- Card 1: Portal Nasabah Mandiri -->
                    <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border {{ $isNasabah ? 'border-emerald-500/80 ring-1 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-800' }} shadow-xs hover:border-zinc-300 dark:hover:border-zinc-700 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="size-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200/60 dark:border-emerald-800/60">
                                    <x-heroicon-o-wallet class="size-5" />
                                </div>
                                @if ($isNasabah)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-[10px] font-semibold border border-emerald-200 dark:border-emerald-800">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Sesi Aktif</span>
                                    </span>
                                @else
                                    <span class="text-[10px] font-mono text-zinc-400 dark:text-zinc-500 uppercase">Nasabah</span>
                                @endif
                            </div>

                            <h2 class="text-base font-bold text-zinc-900 dark:text-white">Portal Nasabah Mandiri</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1.5 leading-relaxed">
                                Cek saldo terkini, riwayat mutasi, kantong impian, dan rekening koran mandiri tanpa antre.
                            </p>

                            @if ($isNasabah)
                                <div class="mt-4 p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-xs">
                                    <div class="text-[11px] text-zinc-500">Masuk sebagai:</div>
                                    <div class="font-semibold text-zinc-900 dark:text-white truncate">{{ $currentNasabah->nama }}</div>
                                </div>
                            @else
                                <div class="mt-4 text-[11px] text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                                    <x-heroicon-s-check-circle class="size-3.5 text-emerald-600 shrink-0" />
                                    <span>Cukup ID Nasabah & No Handphone</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-5">
                            @if ($isNasabah)
                                <a 
                                    href="{{ route('nasabah.dashboard') }}" 
                                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                >
                                    <span>Buka Dashboard Nasabah Saya</span>
                                    <x-heroicon-s-arrow-right class="size-3.5" />
                                </a>
                            @else
                                <a 
                                    href="{{ route('nasabah.login') }}" 
                                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                >
                                    <span>Masuk ke Portal Nasabah</span>
                                    <x-heroicon-s-arrow-right class="size-3.5" />
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Card 2: Portal Petugas / Teller -->
                    <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border {{ $isPetugas ? 'border-emerald-500/80 ring-1 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-800' }} shadow-xs hover:border-zinc-300 dark:hover:border-zinc-700 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="size-9 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center border border-zinc-200 dark:border-zinc-700">
                                    <x-heroicon-o-building-library class="size-5" />
                                </div>
                                @if ($isPetugas)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-[10px] font-semibold border border-emerald-200 dark:border-emerald-800">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Petugas Aktif</span>
                                    </span>
                                @else
                                    <span class="text-[10px] font-mono text-zinc-400 dark:text-zinc-500 uppercase">Teller / Admin</span>
                                @endif
                            </div>

                            <h2 class="text-base font-bold text-zinc-900 dark:text-white">Panel Petugas & Teller</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1.5 leading-relaxed">
                                Pencatatan setoran & penarikan kas, registrasi rekening nasabah, tutup kas, dan audit log.
                            </p>

                            @if ($isPetugas)
                                <div class="mt-4 p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-xs">
                                    <div class="text-[11px] text-zinc-500">Masuk sebagai:</div>
                                    <div class="font-semibold text-zinc-900 dark:text-white truncate">{{ $currentPetugas->name }}</div>
                                </div>
                            @else
                                <div class="mt-4 text-[11px] text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                                    <x-heroicon-s-lock-closed class="size-3.5 text-zinc-400 shrink-0" />
                                    <span>Akses terautentikasi email & password</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-5">
                            @if ($isPetugas)
                                <a 
                                    href="{{ route('admin.dashboard') }}" 
                                    class="w-full py-2.5 px-4 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-semibold text-xs rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                >
                                    <span>Buka Dashboard Petugas</span>
                                    <x-heroicon-s-arrow-right class="size-3.5" />
                                </a>
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="w-full py-2.5 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700 font-semibold text-xs rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                >
                                    <span>Masuk sebagai Petugas</span>
                                    <x-heroicon-s-arrow-right class="size-3.5" />
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Grid Section (Clean & Antislop) -->
            <section class="max-w-5xl mx-auto px-4 sm:px-6 py-12 border-t border-zinc-200 dark:border-zinc-800/80">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-5 rounded-xl bg-white dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800">
                        <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mb-3">
                            <x-heroicon-o-banknotes class="size-4" />
                        </div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Mutasi Real-Time</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Saldo langsung terupdate setiap setoran atau penarikan berhasil dicatat teller.
                        </p>
                    </div>

                    <div class="p-5 rounded-xl bg-white dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800">
                        <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mb-3">
                            <x-heroicon-o-sparkles class="size-4" />
                        </div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Kantong Target</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Pisahkan simpanan untuk rencana qurban, pendidikan, atau keperluan darurat.
                        </p>
                    </div>

                    <div class="p-5 rounded-xl bg-white dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800">
                        <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mb-3">
                            <x-heroicon-o-document-text class="size-4" />
                        </div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Rekening Koran</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Ekspor mutasi debit-kredit berformat CSV standar akuntansi dan cetak kapan saja.
                        </p>
                    </div>

                    <div class="p-5 rounded-xl bg-white dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800">
                        <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mb-3">
                            <x-heroicon-o-shield-check class="size-4" />
                        </div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Verifikasi Digital</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Validasi keaslian struk dan buku tabungan via QR code terenkripsi SHA-256.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Minimal Footer -->
        <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 py-6 text-center text-xs text-zinc-400 dark:text-zinc-500">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>&copy; {{ date('Y') }} TabunganKu. Sistem Pencatatan Tabungan Nasabah Digital.</span>
                <span class="font-mono text-[11px]">Clean &bull; Accurate &bull; Secure</span>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
