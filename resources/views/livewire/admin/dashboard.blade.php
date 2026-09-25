<div class="space-y-5">
    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 rounded-xl shadow-xs">
        <div>
            <h1 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tracking-tight">Dashboard Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Ringkasan keuangan dan aktivitas mutasi tabungan nasabah hari ini</p>
        </div>

        <!-- Quick CTA buttons -->
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('admin.setor') }}" 
                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer"
            >
                <x-heroicon-s-arrow-down-tray class="size-3.5" />
                <span>Setor Tunai</span>
            </a>
            <a 
                href="{{ route('admin.tarik') }}" 
                class="px-3.5 py-1.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 text-xs font-semibold rounded-lg shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer"
            >
                <x-heroicon-s-arrow-up-tray class="size-3.5" />
                <span>Tarik Tunai</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total Dana Kas Tabungan -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
            <div class="flex items-center justify-between text-zinc-400">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Total Kas Tabungan</span>
                <x-heroicon-o-banknotes class="size-4" />
            </div>
            <div class="mt-2">
                <h3 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tabular-nums tracking-tight">
                    Rp {{ number_format($totalKas, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-zinc-400 block mt-0.5">Saldo keseluruhan nasabah</span>
            </div>
        </div>

        <!-- Total Nasabah -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
            <div class="flex items-center justify-between text-zinc-400">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Jumlah Nasabah</span>
                <x-heroicon-o-users class="size-4" />
            </div>
            <div class="mt-2">
                <h3 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tabular-nums tracking-tight">
                    {{ $totalNasabah }} <span class="text-xs font-normal text-zinc-400">Orang</span>
                </h3>
                <span class="text-[11px] text-emerald-600 dark:text-emerald-400 block mt-0.5 font-medium">{{ $totalNasabahAktif }} nasabah aktif</span>
            </div>
        </div>

        <!-- Setoran Hari Ini -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
            <div class="flex items-center justify-between text-zinc-400">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Setoran Hari Ini</span>
                <x-heroicon-o-arrow-down-tray class="size-4 text-emerald-500" />
            </div>
            <div class="mt-2">
                <h3 class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400 tabular-nums tracking-tight">
                    Rp {{ number_format($setorHariIni, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-zinc-400 block mt-0.5">Total setor: Rp {{ number_format($totalSetorAll, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Penarikan Hari Ini -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
            <div class="flex items-center justify-between text-zinc-400">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Penarikan Hari Ini</span>
                <x-heroicon-o-arrow-up-tray class="size-4 text-rose-500" />
            </div>
            <div class="mt-2">
                <h3 class="text-xl sm:text-2xl font-bold text-rose-600 dark:text-rose-400 tabular-nums tracking-tight">
                    Rp {{ number_format($tarikHariIni, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-zinc-400 block mt-0.5">Total tarik: Rp {{ number_format($totalTarikAll, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a 
            href="{{ route('admin.nasabah') }}" 
            class="p-3.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 shadow-xs transition-colors flex items-center gap-3 group"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                <x-heroicon-o-users class="size-4" />
            </div>
            <div>
                <span class="text-xs font-semibold text-zinc-900 dark:text-white block">Data Nasabah</span>
                <span class="text-[10px] text-zinc-400">Buku nasabah</span>
            </div>
        </a>

        <a 
            href="{{ route('admin.setor') }}" 
            class="p-3.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 shadow-xs transition-colors flex items-center gap-3 group"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                <x-heroicon-o-arrow-down-tray class="size-4" />
            </div>
            <div>
                <span class="text-xs font-semibold text-zinc-900 dark:text-white block">Setor Tunai</span>
                <span class="text-[10px] text-zinc-400">Pencatatan deposit</span>
            </div>
        </a>

        <a 
            href="{{ route('admin.tarik') }}" 
            class="p-3.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 shadow-xs transition-colors flex items-center gap-3 group"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                <x-heroicon-o-arrow-up-tray class="size-4" />
            </div>
            <div>
                <span class="text-xs font-semibold text-zinc-900 dark:text-white block">Tarik Tunai</span>
                <span class="text-[10px] text-zinc-400">Penarikan kas</span>
            </div>
        </a>

        <a 
            href="{{ route('admin.transaksi') }}" 
            class="p-3.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 shadow-xs transition-colors flex items-center gap-3 group"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                <x-heroicon-o-document-text class="size-4" />
            </div>
            <div>
                <span class="text-xs font-semibold text-zinc-900 dark:text-white block">Buku Transaksi</span>
                <span class="text-[10px] text-zinc-400">Rekapitulasi mutasi</span>
            </div>
        </a>
    </div>

    <!-- Two-column Content: Recent Transactions (left 2/3) + Top Savers (right 1/3) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3.5">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white">Transaksi Terbaru</h2>
                    <p class="text-[11px] text-zinc-400">Mutasi setor & tarik terakhir tercatat</p>
                </div>
                <a href="{{ route('admin.transaksi') }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-0.5">
                    <span>Semua Transaksi</span>
                    <x-heroicon-s-chevron-right class="size-3" />
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
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-400 font-medium">
                                <th class="pb-2 px-3">Kode & Waktu</th>
                                <th class="pb-2 px-3">Nasabah</th>
                                <th class="pb-2 px-3">Jenis</th>
                                <th class="pb-2 px-3 text-right">Nominal</th>
                                <th class="pb-2 px-3 text-right">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($recentTransactions as $trx)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                    <td class="py-2.5 px-3">
                                        <span class="font-mono font-medium text-zinc-900 dark:text-zinc-200 block">{{ $trx->kode_transaksi }}</span>
                                        <span class="text-[10px] text-zinc-400">{{ $trx->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <span class="font-semibold text-zinc-900 dark:text-white block">{{ $trx->nasabah->nama ?? 'Nasabah Dihapus' }}</span>
                                        <span class="text-[10px] font-mono text-zinc-400">{{ $trx->nasabah->nomor_nasabah ?? '-' }}</span>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if ($trx->jenis_transaksi === 'setor')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">
                                                SETOR
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300">
                                                TARIK
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold whitespace-nowrap {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-medium text-zinc-700 dark:text-zinc-300 whitespace-nowrap">
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
        <div class="lg:col-span-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3.5">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white">Saldo Terbesar</h2>
                        <p class="text-[11px] text-zinc-400">Nasabah saldo tabungan tertinggi</p>
                    </div>
                    <a href="{{ route('admin.nasabah') }}" class="text-xs text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-2">
                    @foreach ($topNasabahs as $index => $item)
                        <div class="p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="size-5 rounded-md bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center text-[10px] font-bold font-mono">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-xs font-semibold text-zinc-900 dark:text-white">{{ $item->nama }}</p>
                                    <p class="text-[10px] font-mono text-zinc-400">{{ $item->nomor_nasabah }}</p>
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
            <div class="mt-5 pt-3 border-t border-zinc-100 dark:border-zinc-800 text-[11px] text-zinc-400">
                <span class="font-medium text-zinc-600 dark:text-zinc-300">Tips Teller:</span> Nasabah dapat login mandiri cukup dengan <strong>ID Nasabah</strong> & <strong>Nomor HP</strong>.
            </div>
        </div>
    </div>
</div>
