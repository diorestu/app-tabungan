<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-2xl shadow-sm transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="size-11 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                <x-heroicon-o-banknotes class="size-6" />
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tracking-tight">Tutup Kas & Rekonsiliasi Kasir</h1>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Penghitungan fisik kas masuk/keluar harian, verifikasi selisih, dan cetak berita acara tutup kas</p>
            </div>
        </div>

        @if ($activeShift)
            <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-xs font-bold self-start sm:self-auto">
                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Sesi Kasir AKTIF • Buka {{ $activeShift->opened_at->format('H:i') }} WIB</span>
            </div>
        @endif
    </div>

    @if (session('success_shift'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2.5 shadow-sm">
            <x-heroicon-s-check-circle class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
            <span class="font-medium">{{ session('success_shift') }}</span>
        </div>
    @endif

    @if (session('error_shift'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs flex items-center gap-2.5 shadow-sm">
            <x-heroicon-s-x-circle class="size-5 shrink-0 text-rose-600 dark:text-rose-400" />
            <span class="font-medium">{{ session('error_shift') }}</span>
        </div>
    @endif

    @if (!$activeShift)
        <!-- ========================================== -->
        <!-- OPEN SHIFT CARD (When no active shift) -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 sm:p-8 shadow-sm max-w-xl mx-auto text-center space-y-6">
            <div class="size-16 rounded-3xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto border border-emerald-500/20">
                <x-heroicon-o-lock-open class="size-8" />
            </div>

            <div>
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Buka Sesi Kasir Harian</h2>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Masukkan modal awal uang kas di laci kasir sebelum melayani transaksi nasabah</p>
            </div>

            <form wire:submit="openShift" class="space-y-4 text-left text-xs">
                <div>
                    <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Modal Awal Kasir (Uang Kembalian / Float Cash) <span class="text-emerald-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-zinc-400">Rp</span>
                        <input 
                            type="number" 
                            wire:model="modal_awal_input" 
                            placeholder="Contoh: 500000"
                            class="w-full pl-11 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm font-mono font-bold text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>
                    @error('modal_awal_input') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Catatan Sesi Kasir (Opsional)
                    </label>
                    <input 
                        type="text" 
                        wire:model="catatan_buka" 
                        placeholder="Contoh: Shift Pagi Kasir 1"
                        class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                    />
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer mt-2"
                >
                    <x-heroicon-s-check class="size-4" />
                    <span>Mulai & Buka Kasir Hari Ini</span>
                </button>
            </form>
        </div>
    @else
        <!-- ========================================== -->
        <!-- ACTIVE SHIFT MONITOR & CLOSING CALCULATOR -->
        <!-- ========================================== -->
        <div class="space-y-6">
            <!-- 4 Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <span class="text-[11px] text-zinc-500 font-semibold block">Modal Awal Kas</span>
                    <span class="text-lg font-bold font-mono text-zinc-800 dark:text-zinc-200 mt-1 block">{{ $activeShift->formatted_modal_awal }}</span>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold block flex items-center gap-1">
                        <x-heroicon-s-arrow-down-tray class="size-3.5" />
                        Total Setoran (Kas Masuk)
                    </span>
                    <span class="text-lg font-bold font-mono text-emerald-600 dark:text-emerald-400 mt-1 block">+ {{ $activeShift->formatted_total_setoran }}</span>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <span class="text-[11px] text-rose-600 dark:text-rose-400 font-semibold block flex items-center gap-1">
                        <x-heroicon-s-arrow-up-tray class="size-3.5" />
                        Total Penarikan (Kas Keluar)
                    </span>
                    <span class="text-lg font-bold font-mono text-rose-600 dark:text-rose-400 mt-1 block">- {{ $activeShift->formatted_total_penarikan }}</span>
                </div>

                <div class="bg-emerald-600 text-white rounded-2xl p-4 sm:p-5 shadow-lg shadow-emerald-600/20">
                    <span class="text-[11px] text-emerald-100 font-semibold block">Saldo Kas Sistem (Wajib Ada)</span>
                    <span class="text-xl font-extrabold font-mono mt-1 block">{{ $activeShift->formatted_saldo_sistem }}</span>
                </div>
            </div>

            <!-- Denomination & Reconciliation Workspace -->
            <form wire:submit="submitTutupKas" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left 7 Cols: Denominations Input Counter -->
                <div class="lg:col-span-7 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                        <div>
                            <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Hitung Fisik Uang di Laci (Pecahan)</h3>
                            <p class="text-[11px] text-zinc-500">Masukkan jumlah lembar/keping uang yang dihitung secara fisik</p>
                        </div>
                        <span class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">
                            Total Fisik: Rp {{ number_format($liveFisik, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <!-- 100k -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-rose-600 dark:text-rose-400 block text-[11px]">Rp 100.000</span>
                            <div class="flex items-center gap-1.5">
                                <input type="number" min="0" wire:model.live="p100k" class="w-full px-2.5 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                                <span class="text-[10px] text-zinc-400">lbr</span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Rp {{ number_format($p100k * 100000, 0, ',', '.') }}</span>
                        </div>

                        <!-- 50k -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-blue-600 dark:text-blue-400 block text-[11px]">Rp 50.000</span>
                            <div class="flex items-center gap-1.5">
                                <input type="number" min="0" wire:model.live="p50k" class="w-full px-2.5 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                                <span class="text-[10px] text-zinc-400">lbr</span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Rp {{ number_format($p50k * 50000, 0, ',', '.') }}</span>
                        </div>

                        <!-- 20k -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 block text-[11px]">Rp 20.000</span>
                            <div class="flex items-center gap-1.5">
                                <input type="number" min="0" wire:model.live="p20k" class="w-full px-2.5 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                                <span class="text-[10px] text-zinc-400">lbr</span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Rp {{ number_format($p20k * 20000, 0, ',', '.') }}</span>
                        </div>

                        <!-- 10k -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-purple-600 dark:text-purple-400 block text-[11px]">Rp 10.000</span>
                            <div class="flex items-center gap-1.5">
                                <input type="number" min="0" wire:model.live="p10k" class="w-full px-2.5 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                                <span class="text-[10px] text-zinc-400">lbr</span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Rp {{ number_format($p10k * 10000, 0, ',', '.') }}</span>
                        </div>

                        <!-- 5k -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-amber-600 dark:text-amber-400 block text-[11px]">Rp 5.000</span>
                            <div class="flex items-center gap-1.5">
                                <input type="number" min="0" wire:model.live="p5k" class="w-full px-2.5 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                                <span class="text-[10px] text-zinc-400">lbr</span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Rp {{ number_format($p5k * 5000, 0, ',', '.') }}</span>
                        </div>

                        <!-- 2k -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-teal-600 dark:text-teal-400 block text-[11px]">Rp 2.000</span>
                            <div class="flex items-center gap-1.5">
                                <input type="number" min="0" wire:model.live="p2k" class="w-full px-2.5 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                                <span class="text-[10px] text-zinc-400">lbr</span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Rp {{ number_format($p2k * 2000, 0, ',', '.') }}</span>
                        </div>

                        <!-- 1k -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-zinc-600 dark:text-zinc-400 block text-[11px]">Rp 1.000</span>
                            <div class="flex items-center gap-1.5">
                                <input type="number" min="0" wire:model.live="p1k" class="w-full px-2.5 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                                <span class="text-[10px] text-zinc-400">lbr</span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Rp {{ number_format($p1k * 1000, 0, ',', '.') }}</span>
                        </div>

                        <!-- Koin -->
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-1.5">
                            <span class="font-bold text-yellow-600 dark:text-yellow-400 block text-[11px]">Koin / Receh</span>
                            <div class="flex items-center gap-1">
                                <input type="number" min="0" wire:model.live="pkoin" class="w-full px-2 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-center font-bold text-zinc-900 dark:text-white">
                            </div>
                            <span class="text-[10px] font-mono text-zinc-500 block text-right">Total Koin</span>
                        </div>
                    </div>
                </div>

                <!-- Right 5 Cols: Reconciliation Status & Action -->
                <div class="lg:col-span-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                            <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Hasil Rekonsiliasi Kasir</h3>
                            <p class="text-[11px] text-zinc-500">Perbandingan antara saldo kas fisik vs catatan sistem</p>
                        </div>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800">
                                <span class="text-zinc-500">Saldo Pembukuan Sistem:</span>
                                <span class="font-mono font-bold text-zinc-900 dark:text-white">Rp {{ number_format($liveSistem, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800">
                                <span class="text-zinc-500">Saldo Fisik Uang di Laci:</span>
                                <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm">Rp {{ number_format($liveFisik, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-800 items-center">
                                <span class="text-zinc-700 dark:text-zinc-300 font-bold">Status Selisih Kas:</span>
                                <span class="font-mono font-extrabold text-sm px-2.5 py-0.5 rounded-lg {{ $liveSelisih == 0 ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20' : ($liveSelisih > 0 ? 'bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/20' : 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/20') }}">
                                    {{ $liveSelisih == 0 ? '✅ KAS SEIMBANG (BALANCE)' : ($liveSelisih > 0 ? '+ Rp ' . number_format($liveSelisih, 0, ',', '.') . ' (LEBIH/SURPLUS)' : '- Rp ' . number_format(abs($liveSelisih), 0, ',', '.') . ' (KURANG/DEFISIT)') }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                                Catatan Tutup Kas (Penjelasan jika ada selisih)
                            </label>
                            <textarea 
                                wire:model="catatan_tutup" 
                                rows="2" 
                                placeholder="Tuliskan catatan kondisi fisik uang atau alasan selisih kas jika ada..."
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                            ></textarea>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-3 bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 font-bold text-xs rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <x-heroicon-s-lock-closed class="size-4" />
                        <span>Selesaikan Tutup Kas & Buat Berita Acara</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- SHIFT HISTORY & RECONCILIATION AUDIT LOG -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
            <div>
                <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Riwayat Rekonsiliasi & Tutup Kas</h3>
                <p class="text-[11px] text-zinc-500">Arsip berita acara serah terima kasir harian per shift</p>
            </div>
        </div>

        @if ($historyShifts->isEmpty())
            <div class="text-center py-8 text-zinc-400 text-xs">
                Belum ada data rekonsiliasi kasir.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold">
                            <th class="pb-3 px-3">Tanggal Shift</th>
                            <th class="pb-3 px-3">Teller</th>
                            <th class="pb-3 px-3 text-right">Modal Awal</th>
                            <th class="pb-3 px-3 text-right">Setoran</th>
                            <th class="pb-3 px-3 text-right">Penarikan</th>
                            <th class="pb-3 px-3 text-right">Saldo Sistem</th>
                            <th class="pb-3 px-3 text-right">Saldo Fisik</th>
                            <th class="pb-3 px-3 text-center">Selisih</th>
                            <th class="pb-3 px-3 text-center">Status</th>
                            <th class="pb-3 px-3 text-center">Berita Acara</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($historyShifts as $shift)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-3 font-mono font-bold text-zinc-900 dark:text-white whitespace-nowrap">
                                    {{ $shift->shift_date->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-3 text-zinc-800 dark:text-zinc-200 font-medium">
                                    {{ $shift->user->name ?? '-' }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-zinc-600 dark:text-zinc-400">
                                    {{ $shift->formatted_modal_awal }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-emerald-600 dark:text-emerald-400 font-semibold">
                                    + {{ $shift->formatted_total_setoran }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-rose-600 dark:text-rose-400 font-semibold">
                                    - {{ $shift->formatted_total_penarikan }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-zinc-900 dark:text-white">
                                    {{ $shift->formatted_saldo_sistem }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $shift->formatted_saldo_fisik }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-bold text-[11px] {{ $shift->selisih == 0 ? 'text-zinc-500' : ($shift->selisih > 0 ? 'text-blue-600' : 'text-rose-600') }}">
                                    {{ $shift->formatted_selisih }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if ($shift->status === 'disetujui')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20">
                                            Disetujui
                                        </span>
                                    @elseif ($shift->status === 'ditutup')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/20">
                                            Menunggu Approval
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/20">
                                            Shift Buka
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <button 
                                        type="button" 
                                        wire:click="openBeritaAcara({{ $shift->id }})"
                                        class="p-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors cursor-pointer"
                                        title="Cetak Berita Acara Kasir"
                                    >
                                        <x-heroicon-o-document-text class="size-3.5" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $historyShifts->links() }}
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- BERITA ACARA PRINT MODAL -->
    <!-- ========================================== -->
    @if ($showBeritaAcaraModal && $selectedBeritaAcara)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100 max-h-[90vh] flex flex-col">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between print:hidden">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                            <x-heroicon-o-document-check class="size-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Berita Acara Rekonsiliasi Tutup Kas</h3>
                            <p class="text-xs text-zinc-500">ID Shift #{{ $selectedBeritaAcara->id }} • {{ $selectedBeritaAcara->shift_date->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeBeritaAcara" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1 rounded-lg">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Printable Sheet Body -->
                <div class="p-6 sm:p-8 overflow-y-auto space-y-6 text-xs print:p-0 print:text-black">
                    <!-- Institution Header -->
                    <div class="text-center pb-4 border-b-2 border-zinc-800 dark:border-zinc-200 space-y-1">
                        <h2 class="text-base font-extrabold uppercase tracking-wide text-zinc-900 dark:text-white">{{ $settings['nama_lembaga'] ?? config('app.name') }}</h2>
                        <p class="text-xs text-zinc-500">{{ $settings['alamat_lembaga'] ?? 'Alamat Kantor Pusat' }} • Telp: {{ $settings['telepon_lembaga'] ?? '-' }}</p>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300 pt-2">BERITA ACARA REKONSILIASI FISIK KASIR HARIAN (TELLER CASH SHEET)</h3>
                    </div>

                    <!-- Shift Info Grid -->
                    <div class="grid grid-cols-2 gap-4 bg-zinc-50 dark:bg-zinc-950 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 text-xs">
                        <div class="space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Tanggal Shift:</span>
                                <span class="font-bold">{{ $selectedBeritaAcara->shift_date->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Waktu Buka / Tutup:</span>
                                <span class="font-mono">{{ $selectedBeritaAcara->opened_at->format('H:i') }} - {{ $selectedBeritaAcara->closed_at ? $selectedBeritaAcara->closed_at->format('H:i') : 'Aktif' }} WIB</span>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Petugas Teller:</span>
                                <span class="font-bold">{{ $selectedBeritaAcara->user->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Supervisor / Admin:</span>
                                <span class="font-bold">{{ $selectedBeritaAcara->supervisor->name ?? 'Belum Disetujui' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary Table -->
                    <div class="space-y-2">
                        <h4 class="font-bold text-xs uppercase text-zinc-700 dark:text-zinc-300">1. Ringkasan Arus Kas Masuk / Keluar:</h4>
                        <table class="w-full text-xs border border-zinc-200 dark:border-zinc-800">
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                <tr>
                                    <td class="p-2.5 text-zinc-600 dark:text-zinc-400">A. Modal Awal Kasir (Opening Cash)</td>
                                    <td class="p-2.5 text-right font-mono font-bold">{{ $selectedBeritaAcara->formatted_modal_awal }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 text-emerald-600 dark:text-emerald-400">B. Total Penerimaan Setoran Tunai</td>
                                    <td class="p-2.5 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">+ {{ $selectedBeritaAcara->formatted_total_setoran }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 text-rose-600 dark:text-rose-400">C. Total Pengeluaran Penarikan Tunai</td>
                                    <td class="p-2.5 text-right font-mono font-bold text-rose-600 dark:text-rose-400">- {{ $selectedBeritaAcara->formatted_total_penarikan }}</td>
                                </tr>
                                <tr class="bg-zinc-100 dark:bg-zinc-800/80 font-bold">
                                    <td class="p-2.5">D. Total Saldo Pembukuan Sistem (A + B - C)</td>
                                    <td class="p-2.5 text-right font-mono text-sm">{{ $selectedBeritaAcara->formatted_saldo_sistem }}</td>
                                </tr>
                                <tr class="bg-emerald-50 dark:bg-emerald-950/40 font-bold text-emerald-800 dark:text-emerald-200">
                                    <td class="p-2.5">E. Total Fisik Uang di Laci Kasir</td>
                                    <td class="p-2.5 text-right font-mono text-sm">{{ $selectedBeritaAcara->formatted_saldo_fisik }}</td>
                                </tr>
                                <tr class="font-extrabold {{ $selectedBeritaAcara->selisih == 0 ? 'text-zinc-900 dark:text-white' : ($selectedBeritaAcara->selisih > 0 ? 'text-blue-600' : 'text-rose-600') }}">
                                    <td class="p-2.5">F. Selisih Kas (Fisik - Sistem)</td>
                                    <td class="p-2.5 text-right font-mono text-sm">{{ $selectedBeritaAcara->formatted_selisih }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Denomination Breakdown Detail -->
                    @if ($selectedBeritaAcara->pecahan_uang)
                        <div class="space-y-2">
                            <h4 class="font-bold text-xs uppercase text-zinc-700 dark:text-zinc-300">2. Rincian Fisik Pecahan Uang:</h4>
                            <div class="grid grid-cols-4 gap-2 text-[11px] font-mono">
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">100.000 x {{ $selectedBeritaAcara->pecahan_uang['100k'] ?? 0 }}</span>
                                    <span class="font-bold">Rp {{ number_format(($selectedBeritaAcara->pecahan_uang['100k'] ?? 0) * 100000, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">50.000 x {{ $selectedBeritaAcara->pecahan_uang['50k'] ?? 0 }}</span>
                                    <span class="font-bold">Rp {{ number_format(($selectedBeritaAcara->pecahan_uang['50k'] ?? 0) * 50000, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">20.000 x {{ $selectedBeritaAcara->pecahan_uang['20k'] ?? 0 }}</span>
                                    <span class="font-bold">Rp {{ number_format(($selectedBeritaAcara->pecahan_uang['20k'] ?? 0) * 20000, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">10.000 x {{ $selectedBeritaAcara->pecahan_uang['10k'] ?? 0 }}</span>
                                    <span class="font-bold">Rp {{ number_format(($selectedBeritaAcara->pecahan_uang['10k'] ?? 0) * 10000, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">5.000 x {{ $selectedBeritaAcara->pecahan_uang['5k'] ?? 0 }}</span>
                                    <span class="font-bold">Rp {{ number_format(($selectedBeritaAcara->pecahan_uang['5k'] ?? 0) * 5000, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">2.000 x {{ $selectedBeritaAcara->pecahan_uang['2k'] ?? 0 }}</span>
                                    <span class="font-bold">Rp {{ number_format(($selectedBeritaAcara->pecahan_uang['2k'] ?? 0) * 2000, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">1.000 x {{ $selectedBeritaAcara->pecahan_uang['1k'] ?? 0 }}</span>
                                    <span class="font-bold">Rp {{ number_format(($selectedBeritaAcara->pecahan_uang['1k'] ?? 0) * 1000, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg">
                                    <span class="text-zinc-500 block text-[9px]">Koin / Receh</span>
                                    <span class="font-bold">Rp {{ number_format((float)($selectedBeritaAcara->pecahan_uang['koin'] ?? 0), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Signatures Block for Print Compliance -->
                    <div class="pt-8 grid grid-cols-2 text-center text-xs">
                        <div class="space-y-12">
                            <span class="text-zinc-500 block">Petugas Teller / Kasir,</span>
                            <span class="font-bold underline block">{{ $selectedBeritaAcara->user->name ?? '-' }}</span>
                        </div>
                        <div class="space-y-12">
                            <span class="text-zinc-500 block">Supervisor / Kepala Kas,</span>
                            <span class="font-bold underline block">{{ $selectedBeritaAcara->supervisor->name ?? '( ................................... )' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-4 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3 print:hidden">
                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 cursor-pointer"
                        >
                            <x-heroicon-o-printer class="size-4" />
                            <span>Cetak Berita Acara</span>
                        </button>

                        @if (Auth::guard('web')->user()?->role === 'admin' && $selectedBeritaAcara->status !== 'disetujui')
                            <button 
                                type="button" 
                                wire:click="approveShift({{ $selectedBeritaAcara->id }})"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-1.5 cursor-pointer"
                            >
                                <x-heroicon-s-check-badge class="size-4" />
                                <span>Setujui (Approve Shift)</span>
                            </button>
                        @endif
                    </div>

                    <button 
                        type="button" 
                        wire:click="closeBeritaAcara" 
                        class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
