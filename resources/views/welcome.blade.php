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
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Hero Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-20 pb-12 flex flex-col items-center text-center">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-xs font-semibold mb-6">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Buku Tabungan Digital & Akuntansi Real-Time</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-900 dark:text-white tracking-tight max-w-4xl leading-tight">
                    Pencatatan Tabungan Nasabah Lebih <span class="bg-gradient-to-r from-emerald-500 to-teal-500 dark:from-emerald-400 dark:to-teal-300 bg-clip-text text-transparent">Mudah, Aman & Transparan</span>
                </h1>

                <p class="mt-4 text-sm sm:text-base text-zinc-600 dark:text-zinc-400 max-w-2xl leading-relaxed">
                    Solusi terpadu simpanan nasabah. Pantau mutasi setor dan tarik secara mandiri, kelola kantong target impian, serta unduh rekening koran resmi standar akuntansi kapan saja.
                </p>

                <!-- Nasabah Portal Access Card (Centered) -->
                <div class="w-full max-w-xl mt-10 text-left">
                    <div class="relative group overflow-hidden rounded-3xl bg-white dark:bg-zinc-900/90 border {{ $isNasabah ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-800' }} p-6 sm:p-8 shadow-xl dark:shadow-2xl hover:border-emerald-500/50 transition-all flex flex-col justify-between">
                        <div class="absolute top-0 right-0 w-44 h-44 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/20 transition-all"></div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="size-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                                    <x-icon-wallet class="size-6" />
                                </div>
                                @if ($isNasabah)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 text-xs font-bold border border-emerald-200 dark:border-emerald-800">
                                        <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Sesi Aktif</span>
                                    </span>
                                @endif
                            </div>

                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Portal Nasabah Mandiri</span>
                            <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tracking-tight mt-1">Akses Buku Tabungan Digital</h2>
                            <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                                Cek saldo terkini, pantau riwayat mutasi transaksi, rencanakan kantong target impian, dan unduh rekening koran mutasi resmi.
                            </p>

                            @if ($isNasabah)
                                <div class="mt-5 p-3.5 rounded-2xl bg-emerald-50/80 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/80 text-xs text-emerald-900 dark:text-emerald-200 flex items-center justify-between shadow-sm">
                                    <div class="flex items-center gap-2.5">
                                        <div class="size-8 rounded-xl bg-emerald-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                                            {{ strtoupper(substr($currentNasabah->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-sm block">{{ $currentNasabah->nama }}</span>
                                            <span class="text-[11px] text-emerald-700 dark:text-emerald-400">Sesi nasabah Anda sedang aktif</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-5 p-3.5 rounded-2xl bg-zinc-100 dark:bg-zinc-950/70 border border-zinc-200 dark:border-zinc-800/80 text-xs text-zinc-700 dark:text-zinc-300 flex items-center gap-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-emerald-600 dark:text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Login praktis tanpa password, cukup <strong>ID Nasabah</strong> & <strong>No HP</strong></span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
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
                </div>
            </section>

            <!-- Features Grid Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 border-t border-zinc-200 dark:border-zinc-800/80">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">
                        Fitur Unggulan untuk Kemudahan Anda
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                        Dirancang khusus memberikan kenyamanan, keamanan, dan transparansi pencatatan simpanan nasabah.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1 -->
                    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-6 rounded-3xl shadow-sm flex flex-col justify-between hover:border-emerald-500/40 transition-all">
                        <div>
                            <div class="size-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 border border-emerald-500/20">
                                <x-heroicon-s-banknotes class="size-6" />
                            </div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Buku Tabungan Real-Time</h3>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                                Pantau saldo dan arus transaksi masuk/keluar seketika setelah disetujui teller tanpa perlu antre cetak manual.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-6 rounded-3xl shadow-sm flex flex-col justify-between hover:border-emerald-500/40 transition-all">
                        <div>
                            <div class="size-12 rounded-2xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 border border-teal-500/20">
                                <x-heroicon-s-sparkles class="size-6" />
                            </div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Kantong Target Impian</h3>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                                Buat alokasi tabungan berencana untuk Qurban, Pendidikan, Liburan, hingga Dana Darurat dengan progress bar otomatis.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-6 rounded-3xl shadow-sm flex flex-col justify-between hover:border-emerald-500/40 transition-all">
                        <div>
                            <div class="size-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 border border-indigo-500/20">
                                <x-heroicon-s-document-text class="size-6" />
                            </div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Rekening Koran Akuntansi</h3>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                                Unduh laporan mutasi resmi berformat standar akuntansi (Debit & Kredit) yang kompatibel dengan Excel dan siap cetak.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-6 rounded-3xl shadow-sm flex flex-col justify-between hover:border-emerald-500/40 transition-all">
                        <div>
                            <div class="size-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 border border-amber-500/20">
                                <x-heroicon-s-shield-check class="size-6" />
                            </div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Aman & Terverifikasi</h3>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                                Keamanan data nasabah terjamin dengan enkripsi mutasi, pencatatan otomatis teller, dan validasi ganda identitas.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 border-t border-zinc-200 dark:border-zinc-800/80">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">
                        3 Langkah Mudah Menggunakan TabunganKu
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                        Proses pendaftaran cepat dan langsung dapat digunakan saat itu juga.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 text-center">
                        <div class="size-10 rounded-full bg-emerald-600 text-white font-black text-sm flex items-center justify-center mx-auto mb-4 shadow-md shadow-emerald-600/20">
                            1
                        </div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Buka Rekening di Teller</h3>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                            Daftarkan identitas diri dan nomor handphone Anda pada petugas teller untuk mendapatkan nomor rekening 9-digit otomatis.
                        </p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 text-center">
                        <div class="size-10 rounded-full bg-emerald-600 text-white font-black text-sm flex items-center justify-center mx-auto mb-4 shadow-md shadow-emerald-600/20">
                            2
                        </div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Masuk ke Portal Mandiri</h3>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                            Buka portal nasabah di perangkat HP atau komputer menggunakan ID Nasabah dan No Handphone yang telah didaftarkan.
                        </p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 text-center">
                        <div class="size-10 rounded-full bg-emerald-600 text-white font-black text-sm flex items-center justify-center mx-auto mb-4 shadow-md shadow-emerald-600/20">
                            3
                        </div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Pantau & Wujudkan Impian</h3>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-2 leading-relaxed">
                            Pantau riwayat mutasi transaksi, buat kantong tabungan impian, dan cetak rekening koran kapan saja sesuai kebutuhan.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-zinc-200 dark:border-zinc-800/80 bg-white dark:bg-zinc-950 py-8 text-center text-xs text-zinc-500 transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">
                <p class="font-semibold text-zinc-700 dark:text-zinc-300">TabunganKu - Solusi Buku Tabungan Digital Terpercaya</p>
                <p>&copy; {{ date('Y') }} TabunganKu. Dibangun dengan standar keamanan dan akurasi perbankan modern.</p>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>


