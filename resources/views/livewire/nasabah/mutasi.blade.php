<div class="space-y-4 sm:space-y-6" x-data="{ showDateFilter: false, selectedTrx: null }">
    <!-- Header & Action Bar -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-4 sm:p-5 rounded-2xl print:hidden flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm transition-colors">
        <div>
            <div class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('nasabah.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Riwayat Mutasi Rekening</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Daftar lengkap transaksi setor & tarik tunai Anda</p>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button"
                onclick="window.print()" 
                class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-950/20 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Rekening Koran
            </button>
        </div>
    </div>

    <!-- Printable Header (Visible only when printing) -->
    <div class="hidden print:block mb-6 p-4 border-b-2 border-black text-black">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-wider">TabunganKu</h2>
                <p class="text-xs">Laporan Mutasi Rekening Tabungan Nasabah</p>
            </div>
            <div class="text-right text-xs">
                <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
                <p><strong>No. Nasabah:</strong> {{ $nasabah->nomor_nasabah }}</p>
                <p><strong>Saldo Terkini:</strong> {{ $nasabah->formatted_saldo }}</p>
                <p><strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Section (Mobile Friendly Touch Controls) -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 print:hidden space-y-3 shadow-sm transition-colors">
        <!-- Search & Filter Toggle Row -->
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari kode transaksi atau catatan..."
                    class="w-full pl-9 pr-3 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 absolute left-3 top-3 text-zinc-400 dark:text-zinc-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
            </div>

            <!-- Date Toggle Button -->
            <button 
                type="button" 
                @click="showDateFilter = !showDateFilter"
                class="px-3 py-2.5 rounded-xl border text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer"
                :class="showDateFilter || '{{ $startDate || $endDate }}' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/40' : 'bg-zinc-50 dark:bg-zinc-950 text-zinc-700 dark:text-zinc-300 border-zinc-300 dark:border-zinc-800'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="hidden sm:inline">Tanggal</span>
            </button>

            @if ($search || $jenis || $startDate || $endDate)
                <button 
                    type="button" 
                    wire:click="resetFilter"
                    title="Reset Filter"
                    class="p-2.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-xl border border-zinc-200 dark:border-zinc-700 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        <!-- Horizontal Quick Filter Pills (Semua, Setor, Tarik) -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            <button 
                type="button" 
                wire:click="$set('jenis', '')"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all cursor-pointer {{ empty($jenis) ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow font-bold' : 'bg-zinc-100 dark:bg-zinc-950 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-800' }}"
            >
                Semua Transaksi
            </button>
            <button 
                type="button" 
                wire:click="$set('jenis', 'setor')"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1 {{ $jenis === 'setor' ? 'bg-emerald-600 text-white shadow font-bold' : 'bg-zinc-100 dark:bg-zinc-950 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 border border-zinc-200 dark:border-zinc-800' }}"
            >
                <span>+</span> Setor Tunai
            </button>
            <button 
                type="button" 
                wire:click="$set('jenis', 'tarik')"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all cursor-pointer flex items-center gap-1 {{ $jenis === 'tarik' ? 'bg-amber-600 text-white shadow font-bold' : 'bg-zinc-100 dark:bg-zinc-950 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 border border-zinc-200 dark:border-zinc-800' }}"
            >
                <span>-</span> Tarik Tunai
            </button>
        </div>

        <!-- Expandable Date Filter Row -->
        <div 
            x-show="showDateFilter" 
            x-transition 
            class="grid grid-cols-2 gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800/80"
            style="{{ empty($startDate) && empty($endDate) ? 'display: none;' : '' }}"
        >
            <div>
                <label class="block text-[10px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="startDate"
                    class="w-full px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Sampai Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="endDate"
                    class="w-full px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
            </div>
        </div>

        <!-- Filter Metrics Bar -->
        <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between text-[11px] text-zinc-500 dark:text-zinc-400">
            <div>
                Total Masuk: <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">+Rp {{ number_format($filteredSetor, 0, ',', '.') }}</span>
            </div>
            <div>
                Total Keluar: <span class="font-mono font-bold text-amber-600 dark:text-amber-400">-Rp {{ number_format($filteredTarik, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Transactions List (Mobile Cards Feed & Desktop Table) -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-3 sm:p-5 print:bg-transparent print:border-none print:p-0 shadow-sm transition-colors">
        @if ($transaksis->isEmpty())
            <div class="text-center py-12 text-zinc-400 dark:text-zinc-500 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8 mx-auto text-zinc-400 dark:text-zinc-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Tidak ada data mutasi yang sesuai filter.
            </div>
        @else
            <!-- MOBILE VIEW: Interactive Banking Cards Feed -->
            <div class="space-y-2.5 md:hidden">
                @foreach ($transaksis as $trx)
                    <div 
                        @click="selectedTrx = {{ json_encode([
                            'kode' => $trx->kode_transaksi,
                            'jenis' => $trx->jenis_transaksi,
                            'nominal' => $trx->formatted_nominal,
                            'saldo_awal' => $trx->formatted_saldo_awal,
                            'saldo_akhir' => $trx->formatted_saldo_akhir,
                            'keterangan' => $trx->keterangan ?: '-',
                            'waktu' => $trx->created_at->format('d M Y, H:i:s'),
                        ]) }}"
                        class="p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-950/70 border border-zinc-200 dark:border-zinc-800/80 active:bg-zinc-100 dark:active:bg-zinc-800/60 transition-all flex items-center justify-between cursor-pointer shadow-sm"
                    >
                        <div class="flex items-center gap-3">
                            <div class="size-11 rounded-2xl flex items-center justify-center shrink-0 {{ $trx->jenis_transaksi === 'setor' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' }}">
                                @if ($trx->jenis_transaksi === 'setor')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 9.39a.75.75 0 10-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-4.75a.75.75 0 00-1.5 0v-4.59l-1.95 2.1a.75.75 0 101.1 1.02l3.25-3.5a.75.75 0 000-1.02l-3.25-3.5a.75.75 0 10-1.1 1.02l1.95 2.1v4.59z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>

                            <div class="space-y-0.5">
                                <h4 class="text-xs font-bold text-zinc-900 dark:text-white">
                                    {{ $trx->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Tarik Tunai' }}
                                </h4>
                                <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-mono">
                                    {{ $trx->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-[10px] text-zinc-400 dark:text-zinc-500 font-mono truncate max-w-[130px]">
                                    {{ $trx->kode_transaksi }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right space-y-0.5">
                            <span class="text-xs font-bold font-mono block {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                            </span>
                            <span class="text-[10px] font-mono text-zinc-500 dark:text-zinc-400 block">
                                Sisa {{ $trx->formatted_saldo_akhir }}
                            </span>
                            <span class="text-[9px] text-zinc-400 dark:text-zinc-500 flex items-center justify-end gap-0.5">
                                <span>Detail</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- DESKTOP VIEW: Clean Table (Visible on md and up) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold print:text-black print:border-black">
                            <th class="pb-3 px-3">No</th>
                            <th class="pb-3 px-3">Tanggal & Waktu</th>
                            <th class="pb-3 px-3">Kode Transaksi</th>
                            <th class="pb-3 px-3">Jenis</th>
                            <th class="pb-3 px-3 text-right">Debit (Setor)</th>
                            <th class="pb-3 px-3 text-right">Kredit (Tarik)</th>
                            <th class="pb-3 px-3 text-right">Saldo Akhir</th>
                            <th class="pb-3 px-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/60 print:divide-zinc-300 print:text-black">
                        @foreach ($transaksis as $index => $trx)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-3 text-zinc-500 print:text-black">
                                    {{ $transaksis->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-3 text-zinc-700 dark:text-zinc-300 whitespace-nowrap print:text-black">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-3 font-mono font-semibold text-zinc-900 dark:text-zinc-200 print:text-black">
                                    {{ $trx->kode_transaksi }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $trx->jenis_transaksi === 'setor' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }} print:text-black">
                                        {{ $trx->jenis_transaksi === 'setor' ? 'SETOR' : 'TARIK' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-semibold whitespace-nowrap text-emerald-600 dark:text-emerald-400 print:text-black">
                                    {{ $trx->jenis_transaksi === 'setor' ? 'Rp ' . number_format($trx->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-semibold whitespace-nowrap text-amber-600 dark:text-amber-400 print:text-black">
                                    {{ $trx->jenis_transaksi === 'tarik' ? 'Rp ' . number_format($trx->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-zinc-900 dark:text-zinc-200 whitespace-nowrap print:text-black">
                                    {{ $trx->formatted_saldo_akhir }}
                                </td>
                                <td class="py-3 px-3 text-zinc-500 dark:text-zinc-400 max-w-xs truncate print:text-black">
                                    {{ $trx->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 print:hidden">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile Transaction Detail Bottom-Sheet Modal -->
    <div 
        x-show="selectedTrx" 
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
            @click.outside="selectedTrx = null"
            class="w-full max-w-md bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-t-3xl sm:rounded-3xl overflow-hidden shadow-2xl p-5 space-y-4 text-zinc-900 dark:text-zinc-100"
        >
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Detail Mutasi Transaksi</h3>
                <button @click="selectedTrx = null" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <template x-if="selectedTrx">
                <div class="space-y-2.5 text-xs">
                    <div class="text-center py-2">
                        <span 
                            class="text-2xl font-extrabold font-mono" 
                            :class="selectedTrx.jenis === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'"
                            x-text="(selectedTrx.jenis === 'setor' ? '+ ' : '- ') + selectedTrx.nominal"
                        ></span>
                        <span class="block text-[11px] text-zinc-500 dark:text-zinc-400 font-mono mt-0.5" x-text="selectedTrx.kode"></span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Jenis Mutasi:</span>
                        <span class="font-bold uppercase text-zinc-900 dark:text-white" x-text="selectedTrx.jenis === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai'"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Waktu:</span>
                        <span class="font-mono text-zinc-800 dark:text-zinc-200 font-medium" x-text="selectedTrx.waktu"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Saldo Sebelum:</span>
                        <span class="font-mono text-zinc-800 dark:text-zinc-200 font-medium" x-text="selectedTrx.saldo_awal"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400">
                        <span>Saldo Akhir:</span>
                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400" x-text="selectedTrx.saldo_akhir"></span>
                    </div>
                    <div class="flex justify-between py-1 text-zinc-600 dark:text-zinc-400">
                        <span>Keterangan:</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium" x-text="selectedTrx.keterangan"></span>
                    </div>
                </div>
            </template>

            <button 
                type="button" 
                @click="selectedTrx = null" 
                class="w-full py-2.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl transition-colors cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>


