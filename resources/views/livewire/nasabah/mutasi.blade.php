<div class="space-y-4" x-data="{ showDateFilter: false, selectedTrx: null }">
    <!-- Header & Action Bar -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 rounded-xl print:hidden flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
        <div>
            <div class="flex items-center gap-1 text-[11px] text-zinc-400 mb-0.5">
                <a href="{{ route('nasabah.dashboard') }}" class="hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors flex items-center gap-1">
                    <x-heroicon-s-arrow-left class="size-3" />
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
            <h1 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tracking-tight">Riwayat Mutasi Rekening</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Pencatatan riwayat setor dan tarik tabungan Anda</p>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button"
                wire:click="exportCsv" 
                class="w-full sm:w-auto px-3.5 py-1.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-lg border border-zinc-200 dark:border-zinc-700 transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                title="Unduh Rekening Koran (CSV)"
            >
                <x-heroicon-s-arrow-down-tray class="size-3.5 text-zinc-500 dark:text-zinc-400" />
                <span>Export CSV</span>
            </button>

            <button 
                type="button"
                onclick="window.print()" 
                class="w-full sm:w-auto px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
            >
                <x-heroicon-s-printer class="size-3.5" />
                <span>Cetak Rekening Koran</span>
            </button>
        </div>
    </div>

    <!-- Printable Header (Visible only when printing) -->
    <div class="hidden print:block mb-6 p-4 border-b-2 border-black text-black">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-wider">{{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}</h2>
                <p class="text-xs">{{ \App\Models\Setting::get('slogan_lembaga', 'Layanan Simpanan & Tabungan Terpercaya') }}</p>
                <p class="text-[10px] text-zinc-600">{{ \App\Models\Setting::get('alamat_lembaga') }} &bull; Telp: {{ \App\Models\Setting::get('telepon_lembaga') }}</p>
                <p class="text-xs font-bold mt-1">Laporan Mutasi Rekening Tabungan Nasabah</p>
            </div>
            <div class="text-right text-xs">
                <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
                <p><strong>No. Nasabah:</strong> {{ $nasabah->nomor_nasabah }}</p>
                <p><strong>Saldo Terkini:</strong> {{ $nasabah->formatted_saldo }}</p>
                <p><strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3.5 print:hidden space-y-3 shadow-xs">
        <!-- Search & Filter Toggle Row -->
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari kode transaksi atau keterangan..."
                    class="w-full pl-8 pr-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
                <x-heroicon-o-magnifying-glass class="size-3.5 absolute left-2.5 top-2.5 text-zinc-400" />
            </div>

            <!-- Date Toggle Button -->
            <button 
                type="button" 
                @click="showDateFilter = !showDateFilter"
                class="px-2.5 py-1.5 rounded-lg border text-xs font-medium flex items-center gap-1 transition-colors cursor-pointer {{ $startDate || $endDate ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800' : 'bg-zinc-50 dark:bg-zinc-950 text-zinc-700 dark:text-zinc-300 border-zinc-300 dark:border-zinc-700/80' }}"
            >
                <x-heroicon-o-calendar class="size-3.5" />
                <span class="hidden sm:inline">Tanggal</span>
            </button>

            @if ($search || $jenis || $startDate || $endDate)
                <button 
                    type="button" 
                    wire:click="resetFilter"
                    title="Reset Filter"
                    class="p-1.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400 rounded-lg border border-zinc-200 dark:border-zinc-700 transition-colors cursor-pointer"
                >
                    <x-heroicon-s-x-mark class="size-3.5" />
                </button>
            @endif
        </div>

        <!-- Horizontal Quick Filter Pills -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5">
            <button 
                type="button" 
                wire:click="$set('jenis', '')"
                class="px-2.5 py-1 rounded-md text-xs font-medium transition-colors cursor-pointer whitespace-nowrap {{ empty($jenis) ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 font-semibold' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Semua
            </button>
            <button 
                type="button" 
                wire:click="$set('jenis', 'setor')"
                class="px-2.5 py-1 rounded-md text-xs font-medium transition-colors cursor-pointer whitespace-nowrap {{ $jenis === 'setor' ? 'bg-emerald-600 text-white font-semibold' : 'bg-zinc-100 dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}"
            >
                + Setor Tunai
            </button>
            <button 
                type="button" 
                wire:click="$set('jenis', 'tarik')"
                class="px-2.5 py-1 rounded-md text-xs font-medium transition-colors cursor-pointer whitespace-nowrap {{ $jenis === 'tarik' ? 'bg-rose-600 text-white font-semibold' : 'bg-zinc-100 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40' }}"
            >
                - Tarik Tunai
            </button>
        </div>

        <!-- Expandable Date Filter Row -->
        <div 
            x-show="showDateFilter" 
            x-transition 
            class="grid grid-cols-2 gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800"
            style="{{ empty($startDate) && empty($endDate) ? 'display: none;' : '' }}"
        >
            <div>
                <label class="block text-[10px] font-medium text-zinc-500 mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="startDate"
                    class="w-full px-2.5 py-1 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-md text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
            </div>
            <div>
                <label class="block text-[10px] font-medium text-zinc-500 mb-1">Sampai Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="endDate"
                    class="w-full px-2.5 py-1 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-md text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
            </div>
        </div>

        <!-- Filter Metrics Bar -->
        <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-500">
            <div>
                Total Masuk: <span class="font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">+Rp {{ number_format($filteredSetor, 0, ',', '.') }}</span>
            </div>
            <div>
                Total Keluar: <span class="font-bold text-rose-600 dark:text-rose-400 tabular-nums">-Rp {{ number_format($filteredTarik, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 sm:p-4 print:bg-transparent print:border-none print:p-0 shadow-xs">
        @if ($transaksis->isEmpty())
            <div class="text-center py-10 text-zinc-400 dark:text-zinc-500 text-xs">
                Tidak ada data mutasi yang sesuai filter.
            </div>
        @else
            <!-- MOBILE VIEW: Interactive Cards -->
            <div class="space-y-2 md:hidden">
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
                                    {{ $trx->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Tarik Tunai' }}
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

            <!-- DESKTOP VIEW: Clean Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-400 font-medium print:text-black print:border-black">
                            <th class="pb-2.5 px-3">No</th>
                            <th class="pb-2.5 px-3">Tanggal & Waktu</th>
                            <th class="pb-2.5 px-3">Kode Transaksi</th>
                            <th class="pb-2.5 px-3">Jenis</th>
                            <th class="pb-2.5 px-3 text-right">Debit (Setor)</th>
                            <th class="pb-2.5 px-3 text-right">Kredit (Tarik)</th>
                            <th class="pb-2.5 px-3 text-right">Saldo Akhir</th>
                            <th class="pb-2.5 px-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 print:divide-zinc-300 print:text-black">
                        @foreach ($transaksis as $index => $trx)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-2.5 px-3 text-zinc-400 print:text-black">
                                    {{ $transaksis->firstItem() + $index }}
                                </td>
                                <td class="py-2.5 px-3 text-zinc-500 whitespace-nowrap font-mono text-[11px] print:text-black">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-2.5 px-3 font-mono text-zinc-700 dark:text-zinc-300 print:text-black">
                                    {{ $trx->kode_transaksi }}
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $trx->jenis_transaksi === 'setor' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' }} print:text-black">
                                        {{ $trx->jenis_transaksi === 'setor' ? 'SETOR' : 'TARIK' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-right font-mono font-medium whitespace-nowrap text-emerald-600 dark:text-emerald-400 tabular-nums print:text-black">
                                    {{ $trx->jenis_transaksi === 'setor' ? 'Rp ' . number_format($trx->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-2.5 px-3 text-right font-mono font-medium whitespace-nowrap text-rose-600 dark:text-rose-400 tabular-nums print:text-black">
                                    {{ $trx->jenis_transaksi === 'tarik' ? 'Rp ' . number_format($trx->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-2.5 px-3 text-right font-mono font-semibold text-zinc-900 dark:text-zinc-100 whitespace-nowrap tabular-nums print:text-black">
                                    {{ $trx->formatted_saldo_akhir }}
                                </td>
                                <td class="py-2.5 px-3 text-zinc-500 max-w-xs truncate print:text-black">
                                    {{ $trx->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 print:hidden">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile Transaction Detail Bottom-Sheet Modal -->
    <div 
        x-show="selectedTrx" 
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
            @click.outside="selectedTrx = null"
            class="w-full max-w-md bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-t-2xl sm:rounded-2xl overflow-hidden shadow-xl p-5 space-y-4 text-zinc-900 dark:text-zinc-100"
        >
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white">Detail Mutasi Transaksi</h3>
                <button @click="selectedTrx = null" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                    <x-heroicon-s-x-mark class="size-4" />
                </button>
            </div>

            <template x-if="selectedTrx">
                <div class="space-y-2 text-xs">
                    <div class="text-center py-2">
                        <span 
                            class="text-2xl font-bold tabular-nums tracking-tight" 
                            :class="selectedTrx.jenis === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                            x-text="(selectedTrx.jenis === 'setor' ? '+ ' : '- ') + selectedTrx.nominal"
                        ></span>
                        <span class="block text-[11px] font-mono text-zinc-400 mt-0.5" x-text="selectedTrx.kode"></span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Jenis Mutasi</span>
                        <span class="font-semibold uppercase text-zinc-900 dark:text-white" x-text="selectedTrx.jenis === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai'"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Waktu</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium" x-text="selectedTrx.waktu"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Saldo Sebelum</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium tabular-nums" x-text="selectedTrx.saldo_awal"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Saldo Akhir</span>
                        <span class="font-bold text-zinc-900 dark:text-white tabular-nums" x-text="selectedTrx.saldo_akhir"></span>
                    </div>
                    <div class="flex justify-between py-1.5 text-zinc-500">
                        <span>Keterangan</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-medium" x-text="selectedTrx.keterangan"></span>
                    </div>
                </div>
            </template>

            <div class="flex items-center gap-2 pt-1">
                <a 
                    :href="'https://wa.me/?text=' + encodeURIComponent('📄 *BUKTI MUTASI TABUNGANKU*\n\n• Jenis: ' + (selectedTrx ? (selectedTrx.jenis === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai') : '') + '\n• Kode: ' + (selectedTrx ? selectedTrx.kode : '') + '\n• Nominal: ' + (selectedTrx ? ((selectedTrx.jenis === 'setor' ? '+' : '-') + selectedTrx.nominal) : '') + '\n• Saldo Akhir: ' + (selectedTrx ? selectedTrx.saldo_akhir : '') + '\n• Waktu: ' + (selectedTrx ? selectedTrx.waktu : '') + '\n• Keterangan: ' + (selectedTrx ? selectedTrx.keterangan : ''))"
                    target="_blank"
                    class="w-1/2 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5 cursor-pointer shadow-xs"
                >
                    <x-heroicon-o-chat-bubble-left-right class="size-3.5" />
                    <span>Bagikan WA</span>
                </a>
                <button 
                    type="button" 
                    @click="selectedTrx = null" 
                    class="w-1/2 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
