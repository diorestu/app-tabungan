<div class="space-y-4" x-data="{ copied: false }">
    <!-- Header Page -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 rounded-xl flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 flex items-center justify-center font-bold text-sm border border-zinc-200 dark:border-zinc-700">
                {{ strtoupper(substr($nasabah->nama, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white tracking-tight leading-tight">
                    {{ $nasabah->nama }}
                </h1>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="text-xs text-zinc-400">ID:</span>
                    <span class="text-xs font-mono font-semibold text-zinc-700 dark:text-zinc-300 tabular-nums">{{ $nasabah->nomor_nasabah }}</span>
                    <button 
                        type="button" 
                        @click="navigator.clipboard.writeText('{{ $nasabah->nomor_nasabah }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-0.5 cursor-pointer"
                        title="Salin ID"
                    >
                        <x-heroicon-s-clipboard-document class="size-3" />
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center">
            @if ($nasabah->status === 'aktif')
                <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold flex items-center gap-1.5">
                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                    <span>Aktif</span>
                </span>
            @else
                <span class="px-2.5 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs font-semibold flex items-center gap-1.5">
                    <span class="size-1.5 rounded-full bg-rose-500"></span>
                    <span>Dibekukan</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Copied Alert -->
    <div 
        x-show="copied" 
        x-transition
        class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs text-center font-medium shadow-xs"
        style="display: none;"
    >
        ID Nasabah <strong>{{ $nasabah->nomor_nasabah }}</strong> berhasil disalin.
    </div>

    <!-- 1. Informasi Lengkap Rekening Tabungan -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs space-y-3">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white">
            Data Rekening & Identitas
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
            <!-- ID Rekening -->
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[11px] text-zinc-400 block mb-0.5">Nomor Rekening / ID Nasabah</span>
                <span class="text-xs font-mono font-bold text-zinc-900 dark:text-white tabular-nums">{{ $nasabah->nomor_nasabah }}</span>
            </div>

            <!-- Nama Lengkap -->
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[11px] text-zinc-400 block mb-0.5">Nama Lengkap</span>
                <span class="text-xs font-semibold text-zinc-900 dark:text-white">{{ $nasabah->nama }}</span>
            </div>

            <!-- Nomor HP -->
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[11px] text-zinc-400 block mb-0.5">Nomor Handphone</span>
                <span class="text-xs font-mono font-semibold text-zinc-900 dark:text-white tabular-nums">{{ $nasabah->no_hp }}</span>
            </div>

            <!-- NIK -->
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[11px] text-zinc-400 block mb-0.5">Nomor Induk Kependudukan (NIK)</span>
                <span class="text-xs font-mono font-semibold text-zinc-900 dark:text-white tabular-nums">{{ $nasabah->nik ?: '-' }}</span>
            </div>

            <!-- Wilayah Registrasi -->
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[11px] text-zinc-400 block mb-0.5">Wilayah / Zona Pendaftaran</span>
                <span class="text-xs font-semibold text-zinc-900 dark:text-white">{{ $nasabah->wilayah_nama }}</span>
            </div>

            <!-- Tanggal Buka Rekening -->
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[11px] text-zinc-400 block mb-0.5">Tanggal Pembukaan Rekening</span>
                <span class="text-xs font-semibold text-zinc-900 dark:text-white">{{ $nasabah->created_at->format('d F Y, H:i') }} WIB</span>
            </div>

            <!-- Alamat Domisili -->
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 sm:col-span-2">
                <span class="text-[11px] text-zinc-400 block mb-0.5">Alamat Domisili</span>
                <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $nasabah->alamat ?: 'Belum diisi' }}</span>
            </div>
        </div>
    </div>

    <!-- 2. Ringkasan Finansial Rekening -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs space-y-3">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white">
            Ringkasan Tabungan
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[10px] uppercase text-zinc-400 font-medium block">Saldo Utama</span>
                <span class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white tabular-nums block mt-1">
                    {{ $nasabah->formatted_saldo }}
                </span>
            </div>

            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[10px] uppercase text-zinc-400 font-medium block">Total Masuk</span>
                <span class="text-sm sm:text-base font-bold text-emerald-600 dark:text-emerald-400 tabular-nums block mt-1">
                    Rp {{ number_format($totalSetor, 0, ',', '.') }}
                </span>
            </div>

            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[10px] uppercase text-zinc-400 font-medium block">Total Keluar</span>
                <span class="text-sm sm:text-base font-bold text-rose-600 dark:text-rose-400 tabular-nums block mt-1">
                    Rp {{ number_format($totalTarik, 0, ',', '.') }}
                </span>
            </div>

            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800">
                <span class="text-[10px] uppercase text-zinc-400 font-medium block">Total Transaksi</span>
                <span class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white tabular-nums block mt-1">
                    {{ $countTransaksi }} <span class="text-xs font-normal text-zinc-400">kali</span>
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Informasi Lembaga Tabungan -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs space-y-2">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white">
            {{ $lembaga['nama'] }}
        </h2>
        <p class="text-[11px] text-zinc-500">{{ $lembaga['slogan'] }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2 text-xs text-zinc-600 dark:text-zinc-400 border-t border-zinc-100 dark:border-zinc-800">
            <div class="flex items-center gap-1.5">
                <x-heroicon-s-map-pin class="size-3.5 text-zinc-400 shrink-0" />
                <span class="truncate">{{ $lembaga['alamat'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <x-heroicon-s-phone class="size-3.5 text-zinc-400 shrink-0" />
                <span>{{ $lembaga['telepon'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <x-heroicon-s-envelope class="size-3.5 text-zinc-400 shrink-0" />
                <span>{{ $lembaga['email'] }}</span>
            </div>
        </div>
    </div>

    <!-- Logout Action Card -->
    <div class="p-4 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-200">Sesi Portal Nasabah</h3>
            <p class="text-[11px] text-zinc-500">Keluar dari akun Anda untuk mengamankan data transaksi.</p>
        </div>
        <form action="{{ route('nasabah.logout') }}" method="POST">
            @csrf
            <button 
                type="submit" 
                class="w-full sm:w-auto px-3.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5 cursor-pointer shadow-xs"
            >
                <x-heroicon-o-arrow-right-start-on-rectangle class="size-4" />
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>
</div>
