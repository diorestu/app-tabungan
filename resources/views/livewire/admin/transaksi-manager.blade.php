<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl print:hidden shadow-sm transition-colors">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Buku Transaksi Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Rekapitulasi lengkap mutasi kas setor & tarik tunai dari semua nasabah</p>
        </div>

        <div class="flex items-center gap-2">
            <button 
                onclick="window.print()" 
                class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl border border-zinc-200 dark:border-zinc-700 transition-all flex items-center gap-2 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan Transaksi
            </button>
        </div>
    </div>

    <!-- Printable Header (Visible only on print) -->
    <div class="hidden print:block mb-6 p-4 border-b-2 border-black text-black">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-wider">TabunganKu</h2>
                <p class="text-xs">Laporan Rekapitulasi Kas & Mutasi Tabungan</p>
            </div>
            <div class="text-right text-xs">
                <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                <p><strong>Petugas:</strong> {{ Auth::guard('web')->user()->name ?? 'Administrator' }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Hidden on print) -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 print:hidden shadow-sm transition-colors">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Cari Transaksi / Nasabah</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Kode, nama, ID nasabah, HP..."
                        class="w-full pl-8 pr-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    />
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 absolute left-2.5 top-2.5 text-zinc-400 dark:text-zinc-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Jenis Transaksi -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Jenis Mutasi</label>
                <select 
                    wire:model.live="jenis"
                    class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                >
                    <option value="">Semua (Setor & Tarik)</option>
                    <option value="setor">Hanya Setor Tunai (+)</option>
                    <option value="tarik">Hanya Tarik Tunai (-)</option>
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="startDate"
                    class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
            </div>

            <!-- End Date -->
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Sampai Tanggal</label>
                    <input 
                        type="date" 
                        wire:model.live="endDate"
                        class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    />
                </div>
                <button 
                    type="button" 
                    wire:click="resetFilter"
                    title="Reset Filter"
                    class="p-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-xl border border-zinc-200 dark:border-zinc-700 transition-colors shrink-0 cursor-pointer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filter Summary Badges -->
        <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex flex-wrap items-center gap-5 text-xs">
            <div class="text-zinc-600 dark:text-zinc-400">
                Total Setoran: <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalSetor, 0, ',', '.') }}</span>
            </div>
            <div class="text-zinc-600 dark:text-zinc-400">
                Total Penarikan: <span class="font-mono font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($totalTarik, 0, ',', '.') }}</span>
            </div>
            <div class="text-zinc-600 dark:text-zinc-400 ml-auto">
                Arus Kas Bersih: <span class="font-mono font-bold {{ $totalSetor - $totalTarik >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    Rp {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-6 print:bg-transparent print:border-none print:p-0 shadow-sm transition-colors">
        @if ($transaksis->isEmpty())
            <div class="text-center py-12 text-zinc-400 dark:text-zinc-500 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 mx-auto text-zinc-400 dark:text-zinc-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Tidak ada data transaksi yang sesuai filter.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold print:text-black print:border-black">
                            <th class="pb-3 px-3">No</th>
                            <th class="pb-3 px-3">Kode Transaksi</th>
                            <th class="pb-3 px-3">Waktu</th>
                            <th class="pb-3 px-3">Nasabah</th>
                            <th class="pb-3 px-3">Jenis</th>
                            <th class="pb-3 px-3 text-right">Nominal</th>
                            <th class="pb-3 px-3 text-right">Saldo Akhir</th>
                            <th class="pb-3 px-3">Petugas</th>
                            <th class="pb-3 px-3 text-center print:hidden">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/60 print:divide-zinc-300 print:text-black">
                        @foreach ($transaksis as $index => $trx)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-3 text-zinc-500 print:text-black">
                                    {{ $transaksis->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-zinc-900 dark:text-zinc-200 print:text-black">
                                    {{ $trx->kode_transaksi }}
                                </td>
                                <td class="py-3 px-3 text-zinc-700 dark:text-zinc-300 whitespace-nowrap print:text-black">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-3 print:text-black">
                                    <span class="font-semibold text-zinc-900 dark:text-white block print:text-black">{{ $trx->nasabah->nama ?? 'Nasabah Terhapus' }}</span>
                                    <span class="text-[10px] font-mono text-zinc-500 print:text-black">{{ $trx->nasabah->nomor_nasabah ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($trx->jenis_transaksi === 'setor')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 print:text-black print:border-none">
                                            SETOR
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 print:text-black print:border-none">
                                            TARIK
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold whitespace-nowrap {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} print:text-black">
                                    {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-semibold text-zinc-700 dark:text-zinc-300 whitespace-nowrap print:text-black">
                                    {{ $trx->formatted_saldo_akhir }}
                                </td>
                                <td class="py-3 px-3 text-zinc-600 dark:text-zinc-400 text-[11px] print:text-black">
                                    {{ $trx->user->name ?? 'System' }}
                                </td>
                                <td class="py-3 px-3 text-center print:hidden">
                                    <button 
                                        type="button" 
                                        wire:click="openReceipt({{ $trx->id }})"
                                        title="Lihat & Cetak Struk"
                                        class="p-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors cursor-pointer"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 print:hidden">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>

    <!-- RECEIPT MODAL -->
    @if ($showReceiptModal && $selectedReceipt)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/75 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <!-- Receipt Header -->
                <div class="p-6 bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800 text-center relative">
                    <div class="size-12 rounded-2xl {{ $selectedReceipt->jenis_transaksi === 'setor' ? 'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400' }} flex items-center justify-center mx-auto mb-2.5 border border-zinc-200 dark:border-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1.581.814L12 14.943l-2.419 1.871A1 1 0 018 16V4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-base font-extrabold text-zinc-900 dark:text-white">Struk Transaksi Tabungan</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 font-mono mt-0.5">{{ $selectedReceipt->kode_transaksi }}</p>
                </div>

                <!-- Receipt Body -->
                <div class="p-6 space-y-3 text-xs">
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">Jenis Transaksi:</span>
                        <span class="font-bold {{ $selectedReceipt->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }} uppercase">
                            {{ $selectedReceipt->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Tarik Tunai' }}
                        </span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">Waktu:</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-mono">{{ $selectedReceipt->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">Nasabah:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $selectedReceipt->nasabah->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">ID Nasabah:</span>
                        <span class="font-mono text-zinc-800 dark:text-zinc-300">{{ $selectedReceipt->nasabah->nomor_nasabah ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">Nominal:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white text-sm">{{ $selectedReceipt->formatted_nominal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">Saldo Awal:</span>
                        <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $selectedReceipt->formatted_saldo_awal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800">
                        <span class="text-zinc-500 dark:text-zinc-400">Saldo Akhir:</span>
                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm">{{ $selectedReceipt->formatted_saldo_akhir }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-zinc-500 dark:text-zinc-400">Petugas:</span>
                        <span class="text-zinc-800 dark:text-zinc-300">{{ $selectedReceipt->user->name ?? 'Teller' }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-5 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3">
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-md cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Struk Ini
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeReceipt" 
                        class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


