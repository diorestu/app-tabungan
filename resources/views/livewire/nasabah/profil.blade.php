<div class="space-y-4 sm:space-y-6" x-data="{ copied: false }">
    <!-- Header Page -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-4 sm:p-5 rounded-2xl flex items-center justify-between shadow-sm transition-colors">
        <div class="flex items-center gap-3.5">
            <div class="size-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-lg shadow-sm">
                {{ strtoupper(substr($nasabah->nama, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-black text-zinc-900 dark:text-white tracking-tight leading-tight">
                    {{ $nasabah->nama }}
                </h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">ID:</span>
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 tracking-wider tabular-nums">{{ $nasabah->nomor_nasabah }}</span>
                    <button 
                        type="button" 
                        @click="navigator.clipboard.writeText('{{ $nasabah->nomor_nasabah }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 p-0.5 cursor-pointer"
                        title="Salin ID Nasabah"
                    >
                        <x-heroicon-s-clipboard-document class="size-3.5" />
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center">
            @if ($nasabah->status === 'aktif')
                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Aktif</span>
                </span>
            @else
                <span class="px-3 py-1 rounded-full bg-rose-100 dark:bg-rose-950/70 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <span class="size-2 rounded-full bg-rose-500"></span>
                    <span>Dibekukan</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Copied Alert -->
    <div 
        x-show="copied" 
        x-transition
        class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs text-center font-medium shadow-sm"
        style="display: none;"
    >
        ✓ ID Nasabah <strong>{{ $nasabah->nomor_nasabah }}</strong> berhasil disalin!
    </div>

    <!-- 1. Informasi Lengkap Rekening Tabungan -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-6 shadow-sm transition-colors space-y-4">
        <div class="flex items-center gap-2.5 pb-3 border-b border-zinc-100 dark:border-zinc-800/80">
            <div class="size-8 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <x-heroicon-s-identification class="size-4" />
            </div>
            <div>
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Data Rekening & Identitas Diri</h2>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Informasi identitas nasabah yang terdaftar pada sistem</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <!-- ID Rekening -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 block mb-1">Nomor Rekening / ID Nasabah</span>
                <span class="text-sm font-black text-emerald-700 dark:text-emerald-400 tabular-nums">{{ $nasabah->nomor_nasabah }}</span>
            </div>

            <!-- Nama Lengkap -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 block mb-1">Nama Lengkap</span>
                <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $nasabah->nama }}</span>
            </div>

            <!-- Nomor HP -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 block mb-1">Nomor Handphone Terdaftar</span>
                <span class="text-sm font-bold text-zinc-900 dark:text-white tabular-nums">{{ $nasabah->no_hp }}</span>
            </div>

            <!-- NIK -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 block mb-1">Nomor Induk Kependudukan (NIK)</span>
                <span class="text-sm font-bold text-zinc-900 dark:text-white tabular-nums">{{ $nasabah->nik ?: '-' }}</span>
            </div>

            <!-- Wilayah Registrasi -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 block mb-1">Wilayah / Zona Pendaftaran</span>
                <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $nasabah->wilayah_nama }} (Kode: {{ substr($nasabah->nomor_nasabah, 0, 1) }})</span>
            </div>

            <!-- Tanggal Buka Rekening -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 block mb-1">Tanggal Pembukaan Rekening</span>
                <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $nasabah->created_at->format('d F Y, H:i') }} WIB</span>
            </div>

            <!-- Alamat Domisili -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80 sm:col-span-2">
                <span class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 block mb-1">Alamat Domisili</span>
                <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $nasabah->alamat ?: 'Belum diisi' }}</span>
            </div>
        </div>
    </div>

    <!-- 2. Ringkasan Finansial Rekening -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-6 shadow-sm transition-colors space-y-4">
        <div class="flex items-center gap-2.5 pb-3 border-b border-zinc-100 dark:border-zinc-800/80">
            <div class="size-8 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                <x-icon-wallet class="size-4" />
            </div>
            <div>
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Ringkasan Tabungan</h2>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Ikhtisar saldo dan rekap mutasi rekening</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[10px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400 font-semibold block">Saldo Utama</span>
                <span class="text-base sm:text-lg font-black text-emerald-600 dark:text-emerald-400 tabular-nums block mt-1">
                    {{ $nasabah->formatted_saldo }}
                </span>
            </div>

            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[10px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400 font-semibold block">Total Masuk</span>
                <span class="text-base sm:text-lg font-black text-emerald-600 dark:text-emerald-400 tabular-nums block mt-1">
                    Rp {{ number_format($totalSetor, 0, ',', '.') }}
                </span>
            </div>

            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[10px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400 font-semibold block">Total Keluar</span>
                <span class="text-base sm:text-lg font-black text-amber-600 dark:text-amber-400 tabular-nums block mt-1">
                    Rp {{ number_format($totalTarik, 0, ',', '.') }}
                </span>
            </div>

            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-100 dark:border-zinc-800/80">
                <span class="text-[10px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400 font-semibold block">Total Transaksi</span>
                <span class="text-base sm:text-lg font-black text-zinc-900 dark:text-white tabular-nums block mt-1">
                    {{ $countTransaksi }} <span class="text-xs font-normal text-zinc-500">kali</span>
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Informasi Lembaga Tabungan -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-6 shadow-sm transition-colors space-y-3">
        <div class="flex items-center gap-2.5 pb-3 border-b border-zinc-100 dark:border-zinc-800/80">
            <div class="size-8 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <x-heroicon-s-building-library class="size-4" />
            </div>
            <div>
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white">{{ $lembaga['nama'] }}</h2>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ $lembaga['slogan'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-zinc-600 dark:text-zinc-400">
            <div class="flex items-center gap-2">
                <x-heroicon-s-map-pin class="size-4 text-zinc-400 shrink-0" />
                <span class="truncate">{{ $lembaga['alamat'] }}</span>
            </div>
            <div class="flex items-center gap-2">
                <x-heroicon-s-phone class="size-4 text-zinc-400 shrink-0" />
                <span>{{ $lembaga['telepon'] }}</span>
            </div>
            <div class="flex items-center gap-2">
                <x-heroicon-s-envelope class="size-4 text-zinc-400 shrink-0" />
                <span>{{ $lembaga['email'] }}</span>
            </div>
        </div>
    </div>

    <!-- Logout Action Card -->
    <div class="p-4 rounded-2xl bg-rose-50/50 dark:bg-rose-950/20 border border-rose-200/80 dark:border-rose-900/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-xs font-bold text-rose-900 dark:text-rose-200">Sesi Portal Nasabah</h3>
            <p class="text-[11px] text-rose-700/80 dark:text-rose-400">Keluar dari akun Anda untuk mengamankan data transaksi.</p>
        </div>
        <form action="{{ route('nasabah.logout') }}" method="POST">
            @csrf
            <button 
                type="submit" 
                class="w-full sm:w-auto px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-rose-950/20 flex items-center justify-center gap-2 cursor-pointer active:scale-95"
            >
                <x-heroicon-o-arrow-right-start-on-rectangle class="size-4" />
                <span>Keluar Akun (Logout)</span>
            </button>
        </form>
    </div>
</div>
