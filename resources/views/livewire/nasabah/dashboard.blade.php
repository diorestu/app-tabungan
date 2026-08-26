<div class="space-y-4 sm:space-y-6" x-data="{ showSaldo: true, copied: false, activeModalTrx: null }">
    <!-- Digital ATM Passbook Card (Soft Greenish-White Tint) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#f4fbf7] via-[#f9fdfa] to-[#edf9f2] dark:from-zinc-900 dark:via-zinc-900 dark:to-emerald-950/40 p-5 sm:p-6 text-zinc-900 dark:text-white shadow-xl border border-emerald-200/80 dark:border-emerald-800/60 flex flex-col justify-between min-h-[160px] sm:min-h-[175px]">
        <!-- Subtle Decorative Glows -->
        <div class="absolute -right-12 -bottom-12 size-48 rounded-full bg-emerald-300/20 dark:bg-emerald-400/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -top-12 size-48 rounded-full bg-teal-300/20 dark:bg-teal-400/10 blur-3xl pointer-events-none"></div>

        <!-- Balance with Show/Hide Toggle & No. Rekening -->
        <div class="relative z-10">
            <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-400">
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No. Rekening:</span>
                    <span class="text-xs sm:text-sm font-black text-emerald-800 dark:text-emerald-300 tracking-wider tabular-nums bg-white/90 dark:bg-zinc-800 px-2.5 py-0.5 rounded-lg border border-emerald-200/80 dark:border-emerald-800/80 shadow-sm">
                        {{ $nasabah->nomor_nasabah }}
                    </span>
                    <button 
                        type="button" 
                        @click="navigator.clipboard.writeText('{{ $nasabah->nomor_nasabah }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="p-1 rounded-md hover:bg-emerald-100 dark:hover:bg-zinc-700 text-emerald-700 dark:text-emerald-400 transition-colors cursor-pointer"
                        title="Salin No. Rekening"
                    >
                        <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <svg x-show="copied" xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-emerald-600 dark:text-emerald-300" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <button 
                    type="button" 
                    @click="showSaldo = !showSaldo" 
                    class="p-1.5 rounded-lg bg-white/80 dark:bg-zinc-800 hover:bg-white dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 border border-zinc-200/80 dark:border-zinc-700 transition-colors cursor-pointer shadow-sm"
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

            <div class="mt-2.5">
                <h2 x-show="showSaldo" class="text-3xl sm:text-4xl font-black tracking-tight text-zinc-900 dark:text-white tabular-nums">
                    {{ $nasabah->formatted_saldo }}
                </h2>
                <h2 x-show="!showSaldo" class="text-3xl sm:text-4xl font-black tracking-tight text-zinc-400 dark:text-zinc-500" style="display: none;">
                    Rp ••••••••
                </h2>
            </div>
        </div>

        <!-- Card Bottom: Pemilik Rekening -->
        <div class="flex items-center justify-between relative z-10 pt-3.5 border-t border-emerald-200/70 dark:border-zinc-800">
            <div>
                <span class="text-[9px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400 font-semibold block">Pemilik Rekening</span>
                <span class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white tracking-wide uppercase truncate max-w-[200px] sm:max-w-none block">
                    {{ $nasabah->nama }}
                </span>
            </div>

            <div class="text-right">
                <span class="text-[9px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400 font-semibold block">Wilayah</span>
                <span class="text-xs font-bold text-zinc-700 dark:text-zinc-300">
                    {{ $nasabah->wilayah_nama }}
                </span>
            </div>
        </div>
    </div>

    <!-- Copied Toast Alert -->
    <div 
        x-show="copied" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs text-center font-medium shadow-sm"
        style="display: none;"
    >
        ✓ ID Nasabah <strong>{{ $nasabah->nomor_nasabah }}</strong> berhasil disalin ke clipboard!
    </div>

    <!-- Quick Action Bar (Mobile Banking Icons) -->
    <div class="grid grid-cols-4 gap-2 sm:gap-4 bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-3 sm:p-4 rounded-2xl shadow-sm transition-colors">
        <!-- 1. Mutasi -->
        <a 
            href="{{ route('nasabah.mutasi') }}" 
            class="flex flex-col items-center justify-center p-2.5 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800/60 active:scale-95 transition-all text-center group"
        >
            <div class="size-11 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-all shadow-sm mb-1.5 border border-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white leading-tight">Mutasi</span>
        </a>

        <!-- 2. Cetak Mutasi -->
        <button 
            type="button" 
            onclick="window.print()" 
            class="flex flex-col items-center justify-center p-2.5 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800/60 active:scale-95 transition-all text-center group cursor-pointer"
        >
            <div class="size-11 rounded-2xl bg-teal-500/10 text-teal-600 dark:text-teal-400 group-hover:bg-teal-600 group-hover:text-white flex items-center justify-center transition-all shadow-sm mb-1.5 border border-teal-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </div>
            <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white leading-tight">Cetak</span>
        </button>

        <!-- 3. Salin ID -->
        <button 
            type="button" 
            @click="navigator.clipboard.writeText('{{ $nasabah->nomor_nasabah }}'); copied = true; setTimeout(() => copied = false, 2000)"
            class="flex flex-col items-center justify-center p-2.5 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800/60 active:scale-95 transition-all text-center group cursor-pointer"
        >
            <div class="size-11 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-all shadow-sm mb-1.5 border border-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                </svg>
            </div>
            <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white leading-tight">Salin ID</span>
        </button>

        <!-- 4. Profil & Rekening -->
        <a 
            href="{{ route('nasabah.profil') }}" 
            class="flex flex-col items-center justify-center p-2.5 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800/60 active:scale-95 transition-all text-center group"
        >
            <div class="size-11 rounded-2xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 group-hover:bg-zinc-800 group-hover:text-white flex items-center justify-center transition-all shadow-sm mb-1.5 border border-zinc-200 dark:border-zinc-700">
                <x-heroicon-s-user class="size-5" />
            </div>
            <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white leading-tight">Profil</span>
        </a>
    </div>

    <!-- Summary Metrics (Single Unified Card: Total Setoran & Penarikan) -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-5 shadow-sm transition-colors">
        <div class="grid grid-cols-2 divide-x divide-zinc-100 dark:divide-zinc-800/80">
            <!-- Total Setoran -->
            <div class="pr-3 sm:pr-4 flex flex-col justify-between">
                <div class="flex items-center gap-2">
                    <div class="size-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 9.39a.75.75 0 10-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-400">Total Setoran</span>
                </div>
                <div class="mt-2.5">
                    <p class="text-base sm:text-xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums tracking-tight">
                        Rp {{ number_format($totalSetor, 0, ',', '.') }}
                    </p>
                    <span class="text-[10px] text-zinc-400 dark:text-zinc-500 block mt-0.5">Akumulasi uang masuk</span>
                </div>
            </div>

            <!-- Total Penarikan -->
            <div class="pl-3 sm:pl-4 flex flex-col justify-between">
                <div class="flex items-center gap-2">
                    <div class="size-7 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-4.75a.75.75 0 00-1.5 0v-4.59l-1.95 2.1a.75.75 0 101.1 1.02l3.25-3.5a.75.75 0 000-1.02l-3.25-3.5a.75.75 0 10-1.1 1.02l1.95 2.1v4.59z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-400">Total Penarikan</span>
                </div>
                <div class="mt-2.5">
                    <p class="text-base sm:text-xl font-black text-amber-600 dark:text-amber-400 tabular-nums tracking-tight">
                        Rp {{ number_format($totalTarik, 0, ',', '.') }}
                    </p>
                    <span class="text-[10px] text-zinc-400 dark:text-zinc-500 block mt-0.5">Akumulasi uang keluar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions: Mobile Feed / Desktop Table -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-5 shadow-sm transition-colors">
        <div class="flex items-center justify-between mb-3.5">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white tracking-tight">Aktivitas Terkini</h3>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">5 transaksi mutasi terakhir</p>
            </div>
            <a 
                href="{{ route('nasabah.mutasi') }}" 
                class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1"
            >
                <span>Lihat Semua</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        @if ($recentTransactions->isEmpty())
            <div class="text-center py-8 text-zinc-500 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 mx-auto text-zinc-600 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Belum ada transaksi tabungan yang tercatat.
            </div>
        @else
            <!-- Mobile App Feed List (Visible on mobile) -->
            <div class="space-y-2.5 md:hidden">
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
                        class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950/70 border border-zinc-200 dark:border-zinc-800/80 active:bg-zinc-100 dark:active:bg-zinc-800/60 transition-colors flex items-center justify-between cursor-pointer"
                    >
                        <div class="flex items-center gap-3">
                            <!-- Type Icon -->
                            <div class="size-10 rounded-xl flex items-center justify-center shrink-0 {{ $trx->jenis_transaksi === 'setor' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' }}">
                                @if ($trx->jenis_transaksi === 'setor')
                                    <x-heroicon-s-arrow-down-tray class="size-5" />
                                @else
                                    <x-heroicon-s-arrow-up-tray class="size-5" />
                                @endif
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-zinc-900 dark:text-white">
                                    {{ $trx->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai' }}
                                </h4>
                                <p class="text-[10px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                                    {{ $trx->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-xs font-bold tabular-nums block {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                            </span>
                            <span class="text-[10px] tabular-nums text-zinc-500 dark:text-zinc-400 block mt-0.5">
                                Sisa {{ $trx->formatted_saldo_akhir }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table (Visible on md and up) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold">
                            <th class="pb-2.5 px-3">Waktu</th>
                            <th class="pb-2.5 px-3">Kode</th>
                            <th class="pb-2.5 px-3">Jenis</th>
                            <th class="pb-2.5 px-3 text-right">Nominal</th>
                            <th class="pb-2.5 px-3 text-right">Saldo Akhir</th>
                            <th class="pb-2.5 px-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/60">
                        @foreach ($recentTransactions as $trx)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-3 text-zinc-700 dark:text-zinc-300 whitespace-nowrap">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-3 font-semibold text-zinc-900 dark:text-zinc-200">{{ $trx->kode_transaksi }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $trx->jenis_transaksi === 'setor' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                                        {{ $trx->jenis_transaksi === 'setor' ? 'SETOR' : 'TARIK' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right font-bold tabular-nums whitespace-nowrap {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                                </td>
                                <td class="py-3 px-3 text-right font-semibold tabular-nums text-zinc-700 dark:text-zinc-300 whitespace-nowrap">{{ $trx->formatted_saldo_akhir }}</td>
                                <td class="py-3 px-3 text-zinc-500 dark:text-zinc-400 truncate max-w-xs">{{ $trx->keterangan ?: '-' }}</td>
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
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm"
        style="display: none;"
    >
        <div 
            @click.outside="activeModalTrx = null"
            class="w-full max-w-md bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-t-3xl sm:rounded-3xl overflow-hidden shadow-2xl p-5 space-y-4 text-zinc-900 dark:text-zinc-100"
        >
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Detail Transaksi</h3>
                <button @click="activeModalTrx = null" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <template x-if="activeModalTrx">
                <div class="space-y-2.5 text-xs">
                    <div class="text-center py-2">
                        <span 
                            class="text-2xl font-extrabold tabular-nums tracking-tight" 
                            :class="activeModalTrx.jenis === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'"
                            x-text="(activeModalTrx.jenis === 'setor' ? '+ ' : '- ') + activeModalTrx.nominal"
                        ></span>
                        <span class="block text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5" x-text="activeModalTrx.kode"></span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Jenis Transaksi:</span>
                        <span class="font-bold uppercase text-zinc-900 dark:text-white" x-text="activeModalTrx.jenis === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai'"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Waktu Transaksi:</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium" x-text="activeModalTrx.waktu"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Saldo Sebelum:</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium tabular-nums" x-text="activeModalTrx.saldo_awal"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Saldo Akhir:</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 tabular-nums" x-text="activeModalTrx.saldo_akhir"></span>
                    </div>
                    <div class="flex justify-between py-1 text-zinc-600 dark:text-zinc-400">
                        <span>Keterangan:</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium" x-text="activeModalTrx.keterangan"></span>
                    </div>
                </div>
            </template>

            <button 
                type="button" 
                @click="activeModalTrx = null" 
                class="w-full py-2.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl transition-colors cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>
