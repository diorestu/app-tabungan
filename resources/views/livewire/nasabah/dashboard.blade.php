<div class="space-y-5" x-data="{ showSaldo: true, copied: false, activeModalTrx: null }">
    <!-- Digital Passbook Card (Refined Obsidian Finish) -->
    <div class="rounded-2xl bg-zinc-900 text-white border border-zinc-800/80 p-5 sm:p-6 shadow-xs flex flex-col justify-between min-h-[160px]">
        <!-- Balance with Show/Hide Toggle & No. Rekening -->
        <div>
            <div class="flex items-center justify-between text-zinc-400">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-medium uppercase tracking-wider text-zinc-400">No. Rekening</span>
                    <span class="text-xs font-mono font-semibold text-zinc-200 tracking-wider tabular-nums bg-zinc-800/80 px-2 py-0.5 rounded-md border border-zinc-700/60">
                        {{ $nasabah->nomor_nasabah }}
                    </span>
                    <button 
                        type="button" 
                        @click="navigator.clipboard.writeText('{{ $nasabah->nomor_nasabah }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="p-1 rounded hover:bg-zinc-800 text-zinc-400 hover:text-zinc-200 transition-colors cursor-pointer"
                        title="Salin No. Rekening"
                    >
                        <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <svg x-show="copied" xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <button 
                    type="button" 
                    @click="showSaldo = !showSaldo" 
                    class="p-1.5 rounded-md bg-zinc-800/80 hover:bg-zinc-700/80 text-zinc-400 hover:text-zinc-200 border border-zinc-700/60 transition-colors cursor-pointer"
                    title="Sembunyikan/Tampilkan Saldo"
                >
                    <!-- Eye Open -->
                    <svg x-show="showSaldo" xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                        <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    <!-- Eye Slash -->
                    <svg x-show="!showSaldo" xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                        <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.3-4.38 1.651 1.651 0 000-1.185A10.004 10.004 0 009.999 3a9.956 9.956 0 00-4.744 1.194L3.28 2.22zM7.752 6.69l1.092 1.092a2.5 2.5 0 013.374 3.373l1.091 1.092a4 4 0 00-5.557-5.557z" clip-rule="evenodd" />
                        <path d="M10.748 13.93l2.523 2.523a9.987 9.987 0 01-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 010-1.186A10.007 10.007 0 012.839 6.02L6.07 9.252a4 4 0 004.678 4.678z" />
                    </svg>
                </button>
            </div>

            <div class="mt-3">
                <div class="text-[11px] text-zinc-400 font-medium">Saldo Tabungan Tersedia</div>
                <h2 x-show="showSaldo" class="text-3xl sm:text-4xl font-bold tracking-tight text-white tabular-nums mt-0.5">
                    {{ $nasabah->formatted_saldo }}
                </h2>
                <h2 x-show="!showSaldo" class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-500 tabular-nums mt-0.5" style="display: none;">
                    Rp &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
                </h2>
            </div>
        </div>

        <!-- Card Bottom: Pemilik Rekening -->
        <div class="flex items-center justify-between pt-4 mt-4 border-t border-zinc-800">
            <div>
                <span class="text-[10px] uppercase tracking-wider text-zinc-400 font-medium block">Pemilik Rekening</span>
                <span class="text-xs sm:text-sm font-semibold text-zinc-100 tracking-wide uppercase truncate max-w-[200px] sm:max-w-none block">
                    {{ $nasabah->nama }}
                </span>
            </div>

            <div class="text-right">
                <span class="text-[10px] uppercase tracking-wider text-zinc-400 font-medium block">Wilayah</span>
                <span class="text-xs font-medium text-zinc-300">
                    {{ $nasabah->wilayah_nama }}
                </span>
            </div>
        </div>
    </div>

    <!-- Copied Toast Alert -->
    <div 
        x-show="copied" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs text-center font-medium shadow-xs"
        style="display: none;"
    >
        ID Nasabah <strong>{{ $nasabah->nomor_nasabah }}</strong> berhasil disalin ke clipboard.
    </div>

    <!-- Quick Action Bar -->
    <div class="grid grid-cols-4 gap-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-2 sm:p-3 rounded-xl shadow-xs">
        <!-- 1. Mutasi -->
        <a 
            href="{{ route('nasabah.mutasi') }}" 
            class="flex flex-col items-center justify-center py-2 px-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-center group"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 flex items-center justify-center transition-colors mb-1">
                <x-heroicon-o-document-text class="size-4" />
            </div>
            <span class="text-[11px] font-medium text-zinc-700 dark:text-zinc-300">Mutasi</span>
        </a>

        <!-- 2. Target Impian -->
        <a 
            href="{{ route('nasabah.target') }}" 
            class="flex flex-col items-center justify-center py-2 px-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-center group"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 flex items-center justify-center transition-colors mb-1">
                <x-heroicon-o-sparkles class="size-4" />
            </div>
            <span class="text-[11px] font-medium text-zinc-700 dark:text-zinc-300">Target</span>
        </a>

        <!-- 3. Salin ID -->
        <button 
            type="button" 
            @click="navigator.clipboard.writeText('{{ $nasabah->nomor_nasabah }}'); copied = true; setTimeout(() => copied = false, 2000)"
            class="flex flex-col items-center justify-center py-2 px-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-center group cursor-pointer"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 flex items-center justify-center transition-colors mb-1">
                <x-heroicon-o-clipboard-document class="size-4" />
            </div>
            <span class="text-[11px] font-medium text-zinc-700 dark:text-zinc-300">Salin ID</span>
        </button>

        <!-- 4. Profil & Rekening -->
        <a 
            href="{{ route('nasabah.profil') }}" 
            class="flex flex-col items-center justify-center py-2 px-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-center group"
        >
            <div class="size-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 flex items-center justify-center transition-colors mb-1">
                <x-heroicon-o-user class="size-4" />
            </div>
            <span class="text-[11px] font-medium text-zinc-700 dark:text-zinc-300">Profil</span>
        </a>
    </div>

    <!-- Summary Metrics (Setoran & Penarikan) -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
        <div class="grid grid-cols-2 divide-x divide-zinc-200 dark:divide-zinc-800">
            <!-- Total Setoran -->
            <div class="pr-4">
                <div class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400">
                    <span class="size-2 rounded-full bg-emerald-500"></span>
                    <span>Total Setoran Masuk</span>
                </div>
                <div class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tabular-nums tracking-tight mt-1">
                    Rp {{ number_format($totalSetor, 0, ',', '.') }}
                </div>
            </div>

            <!-- Total Penarikan -->
            <div class="pl-4">
                <div class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400">
                    <span class="size-2 rounded-full bg-rose-500"></span>
                    <span>Total Penarikan Kas</span>
                </div>
                <div class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tabular-nums tracking-tight mt-1">
                    Rp {{ number_format($totalTarik, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Kantong Target Impian Widget -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">
                    Kantong Target Tabungan
                </h3>
            </div>
            <a 
                href="{{ route('nasabah.target') }}" 
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-0.5"
            >
                <span>Kelola</span>
                <x-heroicon-s-chevron-right class="size-3" />
            </a>
        </div>

        @if ($targetTabungans->isEmpty())
            <div class="py-6 text-center text-xs text-zinc-400 dark:text-zinc-500">
                <p>Belum ada kantong target tabungan.</p>
                <a 
                    href="{{ route('nasabah.target') }}"
                    class="mt-1.5 inline-flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400 hover:underline"
                >
                    <x-heroicon-s-plus class="size-3.5" />
                    <span>Buat Kantong Target Baru</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach ($targetTabungans as $tg)
                    @php $p = $tg->progress_percentage; @endphp
                    <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 font-medium mb-1">
                                <span>{{ $tg->kategori_nama }}</span>
                                <span class="{{ $tg->status === 'tercapai' ? 'text-amber-500 font-bold' : 'text-zinc-600 dark:text-zinc-400' }}">{{ $p }}%</span>
                            </div>
                            <h4 class="text-xs font-semibold text-zinc-900 dark:text-white truncate">{{ $tg->nama_target }}</h4>
                            <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100 mt-1 tabular-nums">
                                {{ $tg->formatted_terkumpul_nominal }}
                            </div>
                        </div>
                        <div class="w-full h-1 rounded-full bg-zinc-200 dark:bg-zinc-800 overflow-hidden mt-2.5">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $p }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Transactions: Mobile Feed / Desktop Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">
                    Mutasi Terakhir
                </h3>
            </div>
            <a 
                href="{{ route('nasabah.mutasi') }}" 
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-0.5"
            >
                <span>Lihat Semua</span>
                <x-heroicon-s-chevron-right class="size-3" />
            </a>
        </div>

        @if ($recentTransactions->isEmpty())
            <div class="text-center py-8 text-zinc-400 dark:text-zinc-500 text-xs">
                Belum ada transaksi mutasi tercatat.
            </div>
        @else
            <!-- Mobile App Feed List (Visible on mobile) -->
            <div class="space-y-2 md:hidden">
                @foreach ($recentTransactions as $trx)
                    <div 
                        @click="activeModalTrx = {{ json_encode([
                            'kode' => $trx->kode_transaksi,
                            'jenis' => $trx->jenis_transaksi,
                            'nominal' => $trx->formatted_nominal,
                            'saldo_awal' => $trx->formatted_saldo_awal,
                            'saldo_akhir' => $trx->formatted_saldo_akhir,
                            'keterangan' => $trx->keterangan ?: '-',
                            'waktu' => $trx->created_at->format('d M Y, H:i:s'),
                        ]) }}"
                        class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 active:bg-zinc-100 dark:active:bg-zinc-800/60 transition-colors flex items-center justify-between cursor-pointer"
                    >
                        <div class="flex items-center gap-2.5">
                            <div class="size-8 rounded-lg flex items-center justify-center shrink-0 {{ $trx->jenis_transaksi === 'setor' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400' }}">
                                @if ($trx->jenis_transaksi === 'setor')
                                    <x-heroicon-s-arrow-down-tray class="size-4" />
                                @else
                                    <x-heroicon-s-arrow-up-tray class="size-4" />
                                @endif
                            </div>

                            <div>
                                <h4 class="text-xs font-semibold text-zinc-900 dark:text-white">
                                    {{ $trx->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai' }}
                                </h4>
                                <p class="text-[10px] text-zinc-400 font-mono mt-0.5">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-xs font-bold tabular-nums block {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                            </span>
                            <span class="text-[10px] tabular-nums text-zinc-400 block mt-0.5">
                                Saldo {{ $trx->formatted_saldo_akhir }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table (Visible on md and up) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-400 font-medium">
                            <th class="pb-2 px-3">Waktu</th>
                            <th class="pb-2 px-3">Kode</th>
                            <th class="pb-2 px-3">Jenis</th>
                            <th class="pb-2 px-3 text-right">Nominal</th>
                            <th class="pb-2 px-3 text-right">Saldo Akhir</th>
                            <th class="pb-2 px-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($recentTransactions as $trx)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-2.5 px-3 text-zinc-500 whitespace-nowrap font-mono text-[11px]">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2.5 px-3 font-mono text-zinc-700 dark:text-zinc-300">{{ $trx->kode_transaksi }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $trx->jenis_transaksi === 'setor' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' }}">
                                        {{ $trx->jenis_transaksi === 'setor' ? 'SETOR' : 'TARIK' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-right font-bold tabular-nums whitespace-nowrap {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                                </td>
                                <td class="py-2.5 px-3 text-right font-semibold tabular-nums text-zinc-700 dark:text-zinc-300 whitespace-nowrap">{{ $trx->formatted_saldo_akhir }}</td>
                                <td class="py-2.5 px-3 text-zinc-500 truncate max-w-xs">{{ $trx->keterangan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Mobile Transaction Detail Bottom-Sheet / Modal -->
    <div 
        x-show="activeModalTrx" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/50 backdrop-blur-xs"
        style="display: none;"
    >
        <div 
            @click.outside="activeModalTrx = null"
            class="w-full max-w-md bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-t-2xl sm:rounded-2xl overflow-hidden shadow-xl p-5 space-y-4 text-zinc-900 dark:text-zinc-100"
        >
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white">Detail Transaksi</h3>
                <button @click="activeModalTrx = null" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                    <x-heroicon-s-x-mark class="size-4" />
                </button>
            </div>

            <template x-if="activeModalTrx">
                <div class="space-y-2 text-xs">
                    <div class="text-center py-2">
                        <span 
                            class="text-2xl font-bold tabular-nums tracking-tight" 
                            :class="activeModalTrx.jenis === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                            x-text="(activeModalTrx.jenis === 'setor' ? '+ ' : '- ') + activeModalTrx.nominal"
                        ></span>
                        <span class="block text-[11px] font-mono text-zinc-400 mt-0.5" x-text="activeModalTrx.kode"></span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Jenis Transaksi</span>
                        <span class="font-semibold uppercase text-zinc-900 dark:text-white" x-text="activeModalTrx.jenis === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai'"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Waktu Transaksi</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium" x-text="activeModalTrx.waktu"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Saldo Sebelum</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium tabular-nums" x-text="activeModalTrx.saldo_awal"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Saldo Akhir</span>
                        <span class="font-bold text-zinc-900 dark:text-white tabular-nums" x-text="activeModalTrx.saldo_akhir"></span>
                    </div>
                    <div class="flex justify-between py-1.5 text-zinc-500">
                        <span>Keterangan</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium" x-text="activeModalTrx.keterangan"></span>
                    </div>
                </div>
            </template>

            <button 
                type="button" 
                @click="activeModalTrx = null" 
                class="w-full py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>
