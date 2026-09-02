<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-2xl shadow-sm transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="size-11 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                <x-heroicon-o-book-open class="size-6" />
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tracking-tight">Cetak Buku Tabungan Fisik</h1>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Pencetakan mutasi langsung ke halaman buku tabungan fisik (kompatibel Passbook/Dot-Matrix & Laser)</p>
            </div>
        </div>

        @if ($selectedNasabah)
            <button 
                type="button" 
                onclick="window.print()" 
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 flex items-center gap-2 cursor-pointer self-start sm:self-auto transition-all"
            >
                <x-heroicon-o-printer class="size-4" />
                <span>Cetak ke Buku Tabungan (Print)</span>
            </button>
        @endif
    </div>

    <!-- Configuration Panel (Hidden on Print) -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4 print:hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Nasabah Selector / Search -->
            <div class="lg:col-span-2 space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Cari & Pilih Nasabah <span class="text-indigo-500">*</span>
                </label>
                @if ($selectedNasabah)
                    <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($selectedNasabah->nama, 0, 2)) }}
                            </div>
                            <div>
                                <span class="font-bold text-xs text-zinc-900 dark:text-white block">{{ $selectedNasabah->nama }}</span>
                                <span class="font-mono text-[11px] text-indigo-700 dark:text-indigo-300">Rek: {{ $selectedNasabah->nomor_nasabah }} • Saldo: {{ $selectedNasabah->formatted_saldo }}</span>
                            </div>
                        </div>
                        <button type="button" wire:click="clearNasabah" class="text-zinc-400 hover:text-rose-500 text-xs font-bold px-2 py-1">
                            Ganti
                        </button>
                    </div>
                @else
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Ketik Nama atau Nomor Rekening..." 
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-indigo-500"
                        />
                        @if (!empty($nasabahs) && count($nasabahs) > 0)
                            <div class="absolute z-20 left-0 right-0 mt-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-xl overflow-hidden divide-y divide-zinc-100 dark:divide-zinc-800">
                                @foreach ($nasabahs as $n)
                                    <button 
                                        type="button" 
                                        wire:click="selectNasabah({{ $n->id }})" 
                                        class="w-full p-2.5 text-left hover:bg-indigo-50 dark:hover:bg-indigo-950/40 flex items-center justify-between text-xs transition-colors cursor-pointer"
                                    >
                                        <div>
                                            <span class="font-bold text-zinc-900 dark:text-white">{{ $n->nama }}</span>
                                            <span class="font-mono text-zinc-400 text-[10px] block">{{ $n->nomor_nasabah }}</span>
                                        </div>
                                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-xs">{{ $n->formatted_saldo }}</span>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Start Line Index (For continuing print) -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Mulai Baris ke- <span class="text-zinc-400 text-[10px]">(Offset Baris)</span>
                </label>
                <div class="relative">
                    <input 
                        type="number" 
                        min="1" 
                        max="30" 
                        wire:model.live="startLine" 
                        class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono font-bold text-zinc-900 dark:text-white focus:ring-1 focus:ring-indigo-500"
                    />
                </div>
            </div>

            <!-- Page Number -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Halaman Buku ke-
                </label>
                <input 
                    type="number" 
                    min="1" 
                    wire:model.live="pageNumber" 
                    class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono font-bold text-zinc-900 dark:text-white focus:ring-1 focus:ring-indigo-500"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-zinc-500 text-[11px] whitespace-nowrap">Filter Mulai Tanggal:</span>
                <input type="date" wire:model.live="startDate" class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-zinc-500 text-[11px] whitespace-nowrap">Hingga Tanggal:</span>
                <input type="date" wire:model.live="endDate" class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono">
            </div>
        </div>
    </div>

    <!-- Passbook Print Preview Container -->
    @if ($selectedNasabah)
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 sm:p-8 shadow-sm transition-colors print:p-0 print:border-none print:shadow-none">
            <!-- Passbook Header -->
            <div class="flex items-center justify-between pb-3 border-b-2 border-zinc-800 text-zinc-900 dark:text-white print:text-black">
                <div>
                    <h3 class="font-extrabold text-sm uppercase">{{ $settings['nama_lembaga'] ?? config('app.name') }}</h3>
                    <p class="font-mono text-xs">{{ $selectedNasabah->nomor_nasabah }} • {{ $selectedNasabah->nama }}</p>
                </div>
                <div class="text-right font-mono text-xs">
                    <span>HALAMAN: {{ $pageNumber }}</span>
                </div>
            </div>

            <!-- Passbook Lines Grid -->
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-[11px] font-mono print:text-black border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-400 dark:border-zinc-600 text-zinc-600 dark:text-zinc-400 font-bold uppercase text-[10px] print:text-black">
                            <th class="py-2 px-2 text-center w-8">NO</th>
                            <th class="py-2 px-2 text-center w-20">TANGGAL</th>
                            <th class="py-2 px-2 text-center w-14">SANDI</th>
                            <th class="py-2 px-2 text-right w-28">DEBET (TARIK)</th>
                            <th class="py-2 px-2 text-right w-28">KREDIT (SETOR)</th>
                            <th class="py-2 px-2 text-right w-32">SALDO</th>
                            <th class="py-2 px-2 text-center w-14">USER</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 print:divide-zinc-300">
                        <!-- Blank Spacer Lines (If Start Line > 1) -->
                        @if ($startLine > 1)
                            @for ($i = 1; $i < $startLine; $i++)
                                <tr class="h-7 text-zinc-300 dark:text-zinc-700 print:text-transparent">
                                    <td class="px-2 text-center">{{ $i }}</td>
                                    <td class="px-2"></td>
                                    <td class="px-2"></td>
                                    <td class="px-2"></td>
                                    <td class="px-2"></td>
                                    <td class="px-2"></td>
                                    <td class="px-2"></td>
                                </tr>
                            @endfor
                        @endif

                        <!-- Real Transaction Rows -->
                        @forelse ($transaksis as $idx => $trx)
                            <tr class="h-7 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 text-zinc-900 dark:text-zinc-100 print:text-black">
                                <td class="px-2 text-center font-semibold text-zinc-400 print:text-black">
                                    {{ $startLine + $idx }}
                                </td>
                                <td class="px-2 text-center whitespace-nowrap">
                                    {{ $trx->created_at->format('d/m/y') }}
                                </td>
                                <td class="px-2 text-center font-bold">
                                    {{ $trx->jenis_transaksi === 'setor' ? 'STR' : 'TRK' }}
                                </td>
                                <td class="px-2 text-right font-bold {{ $trx->jenis_transaksi === 'tarik' ? 'text-rose-600 print:text-black' : 'text-zinc-400 print:text-black' }}">
                                    {{ $trx->jenis_transaksi === 'tarik' ? number_format((float)$trx->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-2 text-right font-bold {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 print:text-black' : 'text-zinc-400 print:text-black' }}">
                                    {{ $trx->jenis_transaksi === 'setor' ? number_format((float)$trx->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-2 text-right font-extrabold text-zinc-900 dark:text-white print:text-black">
                                    {{ number_format((float)$trx->saldo_akhir, 0, ',', '.') }}
                                </td>
                                <td class="px-2 text-center text-[10px] text-zinc-500 print:text-black uppercase">
                                    {{ substr($trx->user->name ?? 'TL', 0, 4) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-zinc-400 text-xs">
                                    Tidak ada catatan transaksi untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-12 text-center text-zinc-400 dark:text-zinc-500 space-y-2">
            <x-heroicon-o-book-open class="size-12 mx-auto text-zinc-300 dark:text-zinc-700" />
            <h3 class="font-bold text-sm text-zinc-700 dark:text-zinc-300">Pilih Nasabah Terlebih Dahulu</h3>
            <p class="text-xs">Gunakan kotak pencarian di atas untuk memilih nasabah yang ingin dicetak buku tabungannya.</p>
        </div>
    @endif
</div>
