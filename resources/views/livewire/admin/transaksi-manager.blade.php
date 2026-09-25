<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl print:hidden">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Buku Transaksi Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Rekapitulasi lengkap mutasi kas setor & tarik tunai dari semua nasabah</p>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button"
                wire:click="exportCsv" 
                class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-colors flex items-center gap-2 cursor-pointer"
            >
                <x-heroicon-o-arrow-down-tray class="size-4" />
                <span>Export CSV</span>
            </button>
            <button 
                type="button"
                onclick="window.print()" 
                class="px-3.5 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl border border-zinc-200 dark:border-zinc-700 transition-colors flex items-center gap-2 cursor-pointer"
            >
                <x-heroicon-o-printer class="size-4" />
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Printable Header (Visible only on print) -->
    <div class="hidden print:block mb-6 p-4 border-b-2 border-black text-black">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold uppercase tracking-wider">{{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}</h2>
                <p class="text-xs">{{ \App\Models\Setting::get('slogan_lembaga', 'Layanan Simpanan & Tabungan Terpercaya') }}</p>
                <p class="text-[10px] text-zinc-600">{{ \App\Models\Setting::get('alamat_lembaga') }} • Telp: {{ \App\Models\Setting::get('telepon_lembaga') }}</p>
                <p class="text-xs font-bold mt-1">Laporan Rekapitulasi Kas & Mutasi Tabungan</p>
            </div>
            <div class="text-right text-xs">
                <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                <p><strong>Petugas:</strong> {{ Auth::guard('web')->user()->name ?? 'Administrator' }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Hidden on print) -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 print:hidden space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search -->
            <div>
                <label class="block text-[11px] font-medium text-zinc-500 dark:text-zinc-400 mb-1">Cari Transaksi / Nasabah</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Kode, nama, ID, No. HP..."
                        class="w-full pl-8 pr-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all"
                    />
                    <x-heroicon-o-magnifying-glass class="size-3.5 absolute left-2.5 top-2.5 text-zinc-400 dark:text-zinc-500" />
                </div>
            </div>

            <!-- Jenis Transaksi -->
            <div>
                <label class="block text-[11px] font-medium text-zinc-500 dark:text-zinc-400 mb-1">Jenis Mutasi</label>
                <select 
                    wire:model.live="jenis"
                    class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all cursor-pointer"
                >
                    <option value="">Semua (Setor & Tarik)</option>
                    <option value="setor">Hanya Setor Tunai (+)</option>
                    <option value="tarik">Hanya Tarik Tunai (-)</option>
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-[11px] font-medium text-zinc-500 dark:text-zinc-400 mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="startDate"
                    class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all font-mono"
                />
            </div>

            <!-- End Date -->
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-medium text-zinc-500 dark:text-zinc-400 mb-1">Sampai Tanggal</label>
                    <input 
                        type="date" 
                        wire:model.live="endDate"
                        class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all font-mono"
                    />
                </div>
                <button 
                    type="button" 
                    wire:click="resetFilter"
                    title="Reset Filter"
                    class="p-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-xl border border-zinc-200 dark:border-zinc-700 transition-colors shrink-0 cursor-pointer"
                >
                    <x-heroicon-o-arrow-path class="size-4" />
                </button>
            </div>
        </div>

        <!-- Filter Summary Bar -->
        <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800/80 flex flex-wrap items-center justify-between gap-4 text-xs">
            <div class="flex flex-wrap items-center gap-5">
                <div class="text-zinc-500 dark:text-zinc-400">
                    Total Setor: <span class="font-mono font-semibold text-emerald-600 dark:text-emerald-400 tabular-nums">Rp {{ number_format($totalSetor, 0, ',', '.') }}</span>
                </div>
                <div class="text-zinc-500 dark:text-zinc-400">
                    Total Tarik: <span class="font-mono font-semibold text-rose-600 dark:text-rose-400 tabular-nums">Rp {{ number_format($totalTarik, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="text-zinc-500 dark:text-zinc-400">
                Arus Kas Bersih: <span class="font-mono font-semibold tabular-nums {{ $totalSetor - $totalTarik >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    Rp {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden print:bg-transparent print:border-none print:p-0">
        @if ($transaksis->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="size-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-3 text-zinc-400 dark:text-zinc-500">
                    <x-heroicon-o-document-text class="size-6" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Tidak ada transaksi ditemukan</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">Tidak ada mutasi kas tabungan yang sesuai dengan filter pencarian.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 text-zinc-500 dark:text-zinc-400 font-semibold print:text-black print:border-black">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Kode Transaksi</th>
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Nasabah</th>
                            <th class="py-3 px-4 text-center">Jenis</th>
                            <th class="py-3 px-4 text-right">Nominal</th>
                            <th class="py-3 px-4 text-right">Saldo Akhir</th>
                            <th class="py-3 px-4">Petugas</th>
                            <th class="py-3 px-4 text-center print:hidden">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/70 print:divide-zinc-300 print:text-black">
                        @foreach ($transaksis as $index => $trx)
                            <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-4 text-center text-zinc-400 dark:text-zinc-500 tabular-nums print:text-black">
                                    {{ $transaksis->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 font-mono font-semibold text-zinc-800 dark:text-zinc-200 print:text-black">
                                    {{ $trx->kode_transaksi }}
                                </td>
                                <td class="py-3 px-4 text-zinc-500 dark:text-zinc-400 whitespace-nowrap print:text-black font-mono text-[11px]">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-4 print:text-black">
                                    <span class="font-semibold text-zinc-900 dark:text-white block print:text-black">{{ $trx->nasabah->nama ?? 'Nasabah Terhapus' }}</span>
                                    <span class="text-[11px] font-mono text-zinc-400 dark:text-zinc-500 print:text-black">{{ $trx->nasabah->nomor_nasabah ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($trx->jenis_transaksi === 'setor')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 print:text-black print:border-none">
                                            SETOR
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 print:text-black print:border-none">
                                            TARIK
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold whitespace-nowrap tabular-nums {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} print:text-black">
                                    {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} {{ $trx->formatted_nominal }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-zinc-700 dark:text-zinc-300 whitespace-nowrap tabular-nums print:text-black">
                                    {{ $trx->formatted_saldo_akhir }}
                                </td>
                                <td class="py-3 px-4 text-zinc-500 dark:text-zinc-400 text-xs print:text-black">
                                    {{ $trx->user->name ?? 'System' }}
                                </td>
                                <td class="py-3 px-4 text-center print:hidden whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1">
                                        <button 
                                            type="button" 
                                            wire:click="openReceipt({{ $trx->id }})"
                                            title="Lihat & Cetak Struk"
                                            class="p-1.5 rounded-lg text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-printer class="size-4" />
                                        </button>
                                        <a 
                                            href="{{ \App\Services\WhatsAppService::getDirectWhatsAppUrl($trx) }}" 
                                            target="_blank"
                                            title="Kirim Struk via WhatsApp"
                                            class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition-colors"
                                        >
                                            <x-heroicon-o-chat-bubble-left-right class="size-4" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transaksis->hasPages())
                <div class="p-4 border-t border-zinc-200 dark:border-zinc-800 print:hidden">
                    {{ $transaksis->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- RECEIPT MODAL -->
    @if ($showReceiptModal && $selectedReceipt)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-sm overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <!-- Receipt Header -->
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 text-center">
                    <div class="size-10 rounded-xl {{ $selectedReceipt->jenis_transaksi === 'setor' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400' }} flex items-center justify-center mx-auto mb-2 border border-zinc-200 dark:border-zinc-800">
                        @if ($selectedReceipt->jenis_transaksi === 'setor')
                            <x-heroicon-s-arrow-down-tray class="size-5" />
                        @else
                            <x-heroicon-s-arrow-up-tray class="size-5" />
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Struk Transaksi Tabungan</h3>
                    <p class="text-xs text-zinc-500 font-mono mt-0.5">{{ $selectedReceipt->kode_transaksi }}</p>
                </div>

                <!-- Receipt Body -->
                <div class="p-5 space-y-3 text-xs">
                    <div class="text-center pb-2 border-b border-zinc-100 dark:border-zinc-800">
                        <h4 class="font-bold text-xs text-zinc-900 dark:text-white uppercase">{{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}</h4>
                        <p class="text-[10px] text-zinc-400 mt-0.5">{{ \App\Models\Setting::get('alamat_lembaga') }} • Telp: {{ \App\Models\Setting::get('telepon_lembaga') }}</p>
                    </div>

                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Jenis:</span>
                        <span class="font-semibold {{ $selectedReceipt->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }} uppercase">
                            {{ $selectedReceipt->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Tarik Tunai' }}
                        </span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Waktu:</span>
                        <span class="text-zinc-700 dark:text-zinc-300 font-mono">{{ $selectedReceipt->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Nasabah:</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $selectedReceipt->nasabah->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500">ID Nasabah:</span>
                        <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $selectedReceipt->nasabah->nomor_nasabah ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Nominal:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white text-sm tabular-nums">{{ $selectedReceipt->formatted_nominal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Saldo Awal:</span>
                        <span class="font-mono text-zinc-600 dark:text-zinc-400 tabular-nums">{{ $selectedReceipt->formatted_saldo_awal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Saldo Akhir:</span>
                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm tabular-nums">{{ $selectedReceipt->formatted_saldo_akhir }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-zinc-500">Petugas:</span>
                        <span class="text-zinc-700 dark:text-zinc-300">{{ $selectedReceipt->user->name ?? 'Teller' }}</span>
                    </div>

                    <!-- Verification QR Code Card -->
                    <div class="py-2.5 px-3 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3">
                        <div class="space-y-0.5 text-left">
                            <span class="text-[10px] font-bold text-zinc-900 dark:text-white uppercase flex items-center gap-1">
                                <x-heroicon-s-shield-check class="size-3.5 text-emerald-600 dark:text-emerald-400" />
                                Terverifikasi
                            </span>
                            <span class="text-[9px] text-zinc-400 block leading-tight">Scan QR untuk validasi keaslian</span>
                            <span class="text-[8px] font-mono text-zinc-400 block break-all">Kode: {{ $selectedReceipt->verification_code }}</span>
                        </div>
                        <img src="{{ $selectedReceipt->qr_code_data_uri }}" alt="QR Verifikasi" class="size-12 rounded-lg bg-white p-0.5 border border-zinc-200 shrink-0">
                    </div>

                    @if ($waStatusMessage)
                        <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                            <x-heroicon-o-chat-bubble-left-right class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                            <span>{{ $waStatusMessage }}</span>
                        </div>
                    @endif

                    <p class="text-[10px] text-center text-zinc-400 italic pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        {{ \App\Models\Setting::get('pesan_struk', 'Simpan struk ini sebagai bukti transaksi resmi.') }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="p-4 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-semibold rounded-xl flex items-center gap-1.5 cursor-pointer transition-colors"
                        >
                            <x-heroicon-o-printer class="size-3.5" />
                            <span>Cetak</span>
                        </button>
                        <a 
                            href="{{ \App\Services\WhatsAppService::getDirectWhatsAppUrl($selectedReceipt) }}" 
                            target="_blank"
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl flex items-center gap-1.5 cursor-pointer transition-colors"
                            title="Kirim via WhatsApp"
                        >
                            <x-heroicon-o-chat-bubble-left-right class="size-3.5" />
                            <span>WhatsApp</span>
                        </a>
                    </div>

                    <button 
                        type="button" 
                        wire:click="closeReceipt" 
                        class="px-3.5 py-1.5 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl cursor-pointer transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
