<div class="space-y-8">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-emerald-50 via-white to-zinc-50 dark:from-emerald-950/70 dark:via-zinc-900 dark:to-zinc-950 border border-emerald-500/20 p-6 rounded-3xl shadow-sm dark:shadow-xl transition-colors">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Dashboard Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Ringkasan keuangan dan aktivitas mutasi tabungan nasabah hari ini</p>
        </div>

        <!-- Quick CTA buttons -->
        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.setor') }}" 
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2 cursor-pointer active:scale-95"
            >
                <x-heroicon-s-arrow-down-tray class="size-4" />
                <span>Setor Tunai</span>
            </a>
            <a 
                href="{{ route('admin.tarik') }}" 
                class="px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-amber-600/30 transition-all flex items-center gap-2 cursor-pointer active:scale-95"
            >
                <x-heroicon-s-arrow-up-tray class="size-4" />
                <span>Tarik Tunai</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Dana Kas Tabungan -->
        <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm relative overflow-hidden flex flex-col justify-between transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Total Kas Tabungan</span>
                <div class="size-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <x-heroicon-o-banknotes class="size-5" />
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-zinc-900 dark:text-white font-mono tracking-tight">
                    Rp {{ number_format($totalKas, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1 block">Saldo keseluruhan nasabah</span>
            </div>
        </div>

        <!-- Total Nasabah -->
        <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm relative overflow-hidden flex flex-col justify-between transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Jumlah Nasabah</span>
                <div class="size-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <x-heroicon-o-users class="size-5" />
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-zinc-900 dark:text-white font-mono tracking-tight">
                    {{ $totalNasabah }} <span class="text-sm font-normal text-zinc-500 dark:text-zinc-400">Orang</span>
                </h3>
                <span class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 block font-medium">{{ $totalNasabahAktif }} nasabah aktif</span>
            </div>
        </div>

        <!-- Setoran Hari Ini -->
        <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm relative overflow-hidden flex flex-col justify-between transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Setoran Hari Ini</span>
                <div class="size-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <x-heroicon-o-arrow-down-tray class="size-5" />
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">
                    Rp {{ number_format($setorHariIni, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1 block">Total setor: Rp {{ number_format($totalSetorAll, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Penarikan Hari Ini -->
        <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm relative overflow-hidden flex flex-col justify-between transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Penarikan Hari Ini</span>
                <div class="size-10 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <x-heroicon-o-arrow-up-tray class="size-5" />
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono tracking-tight">
                    Rp {{ number_format($tarikHariIni, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1 block">Total tarik: Rp {{ number_format($totalTarikAll, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a 
            href="{{ route('admin.nasabah') }}" 
            class="p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-500/50 shadow-sm transition-all flex items-center gap-3.5 group cursor-pointer"
        >
            <div class="size-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 group-hover:bg-emerald-600 text-zinc-700 dark:text-zinc-300 group-hover:text-white flex items-center justify-center transition-colors">
                <x-heroicon-o-users class="size-5" />
            </div>
            <div>
                <span class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 block transition-colors">Data Nasabah</span>
                <span class="text-[10px] text-zinc-500">Kelola buku nasabah</span>
            </div>
        </a>

        <a 
            href="{{ route('admin.setor') }}" 
            class="p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-500/50 shadow-sm transition-all flex items-center gap-3.5 group cursor-pointer"
        >
            <div class="size-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 group-hover:bg-emerald-600 text-zinc-700 dark:text-zinc-300 group-hover:text-white flex items-center justify-center transition-colors">
                <x-heroicon-o-arrow-down-tray class="size-5" />
            </div>
            <div>
                <span class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 block transition-colors">Setor Tunai</span>
                <span class="text-[10px] text-zinc-500">Catat deposit baru</span>
            </div>
        </a>

        <a 
            href="{{ route('admin.tarik') }}" 
            class="p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-amber-500/50 shadow-sm transition-all flex items-center gap-3.5 group cursor-pointer"
        >
            <div class="size-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 group-hover:bg-amber-600 text-zinc-700 dark:text-zinc-300 group-hover:text-white flex items-center justify-center transition-colors">
                <x-heroicon-o-arrow-up-tray class="size-5" />
            </div>
            <div>
                <span class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 block transition-colors">Tarik Tunai</span>
                <span class="text-[10px] text-zinc-500">Proses penarikan kas</span>
            </div>
        </a>

        <a 
            href="{{ route('admin.transaksi') }}" 
            class="p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-blue-500/50 shadow-sm transition-all flex items-center gap-3.5 group cursor-pointer"
        >
            <div class="size-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 group-hover:bg-blue-600 text-zinc-700 dark:text-zinc-300 group-hover:text-white flex items-center justify-center transition-colors">
                <x-heroicon-o-document-text class="size-5" />
            </div>
            <div>
                <span class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 block transition-colors">Buku Transaksi</span>
                <span class="text-[10px] text-zinc-500">Laporan & Rekapitulasi</span>
            </div>
        </a>
    </div>

    <!-- Two-column Content: Recent Transactions (left 2/3) + Top Savers (right 1/3) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-zinc-900 dark:text-white tracking-tight">Transaksi Terbaru</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Pencatatan setor & tarik paling mutakhir</p>
                </div>
                <a href="{{ route('admin.transaksi') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                    <span>Semua Transaksi</span>
                    <x-heroicon-s-chevron-right class="size-3.5" />
                </a>
            </div>

            @if ($recentTransactions->isEmpty())
                <div class="text-center py-10 text-zinc-400 dark:text-zinc-500 text-xs">
                    Belum ada transaksi tabungan yang tercatat.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold">
                                <th class="pb-2.5 px-3">Kode & Waktu</th>
                                <th class="pb-2.5 px-3">Nasabah</th>
                                <th class="pb-2.5 px-3">Jenis</th>
                                <th class="pb-2.5 px-3 text-right">Nominal</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/60">
                            @foreach ($recentTransactions as $trx)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                    <td class="py-3 px-3">
                                        <span class="font-mono font-bold text-zinc-900 dark:text-zinc-200 block">{{ $trx->kode_transaksi }}</span>
                                        <span class="text-[10px] text-zinc-400 dark:text-zinc-500">{{ $trx->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="font-semibold text-zinc-900 dark:text-white block">{{ $trx->nasabah->nama ?? 'Nasabah Dihapus' }}</span>
                                        <span class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400">{{ $trx->nasabah->nomor_nasabah ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-3">
                                        @if ($trx->jenis_transaksi === 'setor')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                SETOR
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                                TARIK
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-right font-mono font-bold whitespace-nowrap {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                                    </td>
                                    <td class="py-3 px-3 text-right font-mono font-semibold text-zinc-700 dark:text-zinc-300 whitespace-nowrap">
                                        {{ $trx->formatted_saldo_akhir }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Top Savers -->
        <div class="lg:col-span-1 bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between transition-colors">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900 dark:text-white tracking-tight">Saldo Terbesar</h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Nasabah dengan tabungan tertinggi</p>
                    </div>
                    <a href="{{ route('admin.nasabah') }}" class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach ($topNasabahs as $index => $item)
                        <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800/80 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="size-6 rounded-full bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center text-xs font-bold font-mono">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-xs font-semibold text-zinc-900 dark:text-white">{{ $item->nama }}</p>
                                    <p class="text-[10px] font-mono text-zinc-500">{{ $item->nomor_nasabah }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold font-mono text-emerald-600 dark:text-emerald-400">{{ $item->formatted_saldo }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Fast Customer ID Lookup tip -->
            <div class="mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-800 text-[11px] text-zinc-500 dark:text-zinc-400">
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">Tips Petugas:</span> Nasabah dapat login mandiri ke portal mereka cukup dengan <strong>ID Nasabah</strong> & <strong>Nomor Handphone</strong>.
            </div>
        </div>
    </div>
</div>


