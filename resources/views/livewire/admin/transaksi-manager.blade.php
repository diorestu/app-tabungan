<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl print:hidden shadow-sm transition-colors">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Buku Transaksi Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Rekapitulasi lengkap mutasi kas setor & tarik tunai dari semua nasabah</p>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button"
                wire:click="exportCsv" 
                class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2 cursor-pointer active:scale-95"
            >
                <x-heroicon-o-arrow-down-tray class="size-4" />
                <span>Export Excel / CSV</span>
            </button>
            <button 
                type="button"
                onclick="window.print()" 
                class="px-3.5 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl border border-zinc-200 dark:border-zinc-700 transition-all flex items-center gap-2 cursor-pointer"
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
                <h2 class="text-2xl font-black uppercase tracking-wider">{{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}</h2>
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
                    <x-heroicon-o-magnifying-glass class="size-3.5 absolute left-2.5 top-2.5 text-zinc-400 dark:text-zinc-500" />
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
                    <x-heroicon-o-arrow-path class="size-4" />
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
                <x-heroicon-o-document-text class="size-10 mx-auto text-zinc-400 dark:text-zinc-600 mb-2" />
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
                                <td class="py-3 px-3 text-center print:hidden whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button 
                                            type="button" 
                                            wire:click="openReceipt({{ $trx->id }})"
                                            title="Lihat & Cetak Struk"
                                            class="p-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-printer class="size-3.5" />
                                        </button>
                                        <a 
                                            href="{{ \App\Services\WhatsAppService::getDirectWhatsAppUrl($trx) }}" 
                                            target="_blank"
                                            title="Kirim Struk via WhatsApp"
                                            class="p-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-chat-bubble-left-right class="size-3.5" />
                                        </a>
                                    </div>
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
                        @if ($selectedReceipt->jenis_transaksi === 'setor')
                            <x-heroicon-s-arrow-down-tray class="size-6" />
                        @else
                            <x-heroicon-s-arrow-up-tray class="size-6" />
                        @endif
                    </div>
                    <h3 class="text-base font-extrabold text-zinc-900 dark:text-white">Struk Transaksi Tabungan</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 font-mono mt-0.5">{{ $selectedReceipt->kode_transaksi }}</p>
                </div>

                <!-- Receipt Body -->
                <div class="p-6 space-y-3 text-xs">
                    <div class="text-center pb-2.5 mb-2 border-b border-zinc-100 dark:border-zinc-800">
                        <h4 class="font-bold text-xs text-zinc-900 dark:text-white uppercase">{{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}</h4>
                        <p class="text-[10px] text-zinc-500">{{ \App\Models\Setting::get('alamat_lembaga') }} • Telp: {{ \App\Models\Setting::get('telepon_lembaga') }}</p>
                    </div>

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

                    <!-- Anti-Counterfeit Verification QR Code -->
                    <div class="py-2.5 px-3 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3">
                        <div class="space-y-0.5 text-left">
                            <span class="text-[10px] font-bold text-zinc-900 dark:text-white uppercase flex items-center gap-1">
                                <x-heroicon-s-shield-check class="size-3.5 text-emerald-600 dark:text-emerald-400" />
                                Struk Resmi Terverifikasi
                            </span>
                            <span class="text-[9px] text-zinc-500 block leading-tight">Scan QR untuk verifikasi keaslian di server pusat</span>
                            <span class="text-[8px] font-mono text-zinc-400 block break-all mt-0.5">Kode: {{ $selectedReceipt->verification_code }}</span>
                        </div>
                        <img src="{{ $selectedReceipt->qr_code_data_uri }}" alt="QR Verifikasi" class="size-14 rounded-lg bg-white p-1 shadow-sm shrink-0 border border-zinc-200">
                    </div>

                    @if ($waStatusMessage)
                        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-[11px] flex items-center gap-2">
                            <x-heroicon-o-chat-bubble-left-right class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                            <span>{{ $waStatusMessage }}</span>
                        </div>
                    @endif

                    <p class="text-[10px] text-center text-zinc-400 dark:text-zinc-500 italic pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        {{ \App\Models\Setting::get('pesan_struk', 'Simpan struk ini sebagai bukti transaksi resmi.') }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="p-5 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="px-3.5 py-2 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 shadow-sm cursor-pointer"
                        >
                            <x-heroicon-o-printer class="size-4" />
                            <span>Cetak</span>
                        </button>
                        <a 
                            href="{{ \App\Services\WhatsAppService::getDirectWhatsAppUrl($selectedReceipt) }}" 
                            target="_blank"
                            class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 shadow-md shadow-emerald-600/20 cursor-pointer transition-all"
                            title="Kirim atau buka struk via WhatsApp"
                        >
                            <x-heroicon-o-chat-bubble-left-right class="size-4" />
                            <span>Kirim WhatsApp</span>
                        </a>
                    </div>

                    <button 
                        type="button" 
                        wire:click="closeReceipt" 
                        class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer text-center"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


