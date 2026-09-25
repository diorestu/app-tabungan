<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Otomasi Biaya Admin & Bagi Hasil</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Pemrosesan batch pemotongan administrasi dan distribusi bagi hasil tabungan</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-1 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs self-start sm:self-auto">
            <button 
                type="button" 
                wire:click="$set('activeTab', 'admin_fee')" 
                class="px-3 py-1.5 rounded-lg font-semibold transition-all cursor-pointer {{ $activeTab === 'admin_fee' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Biaya Admin
            </button>
            <button 
                type="button" 
                wire:click="$set('activeTab', 'bagi_hasil')" 
                class="px-3 py-1.5 rounded-lg font-semibold transition-all cursor-pointer {{ $activeTab === 'bagi_hasil' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Bagi Hasil
            </button>
            <button 
                type="button" 
                wire:click="$set('activeTab', 'riwayat')" 
                class="px-3 py-1.5 rounded-lg font-semibold transition-all cursor-pointer {{ $activeTab === 'riwayat' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Riwayat Batch
            </button>
        </div>
    </div>

    @if (session('success_batch'))
        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
            <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
            <span class="font-medium">{{ session('success_batch') }}</span>
        </div>
    @endif

    @if (session('error_batch'))
        <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs flex items-center gap-2">
            <x-heroicon-s-x-circle class="size-4 shrink-0 text-rose-600 dark:text-rose-400" />
            <span class="font-medium">{{ session('error_batch') }}</span>
        </div>
    @endif

    <!-- TAB 1: BIAYA ADMIN BULANAN -->
    @if ($activeTab === 'admin_fee')
        <div class="space-y-6">
            <!-- Configuration Parameters -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 space-y-4">
                <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Parameter Biaya Administrasi</h3>
                    <p class="text-[11px] text-zinc-500">Tentukan tarif potongan admin dan batas saldo minimum nasabah</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nominal Biaya Admin / Rekening
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-semibold text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="admin_fee_amount" 
                                wire:change="simulateAdminFee"
                                class="w-full pl-11 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl font-mono font-semibold text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Minimum Saldo Dikenakan Biaya
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-semibold text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="admin_min_balance" 
                                wire:change="simulateAdminFee"
                                class="w-full pl-11 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl font-mono font-semibold text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Periode Pemotongan (Label Bulan)
                        </label>
                        <input 
                            type="text" 
                            wire:model="admin_fee_period" 
                            placeholder="Contoh: Agustus 2026"
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl font-medium text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                    </div>
                </div>
            </div>

            <!-- Simulation Summary Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4">
                    <span class="text-[11px] text-zinc-500 font-medium block">Nasabah Memenuhi Syarat</span>
                    <span class="text-xl font-bold font-mono text-zinc-900 dark:text-white mt-1 block tabular-nums">
                        {{ number_format($admin_fee_simulation['total_nasabah'] ?? 0, 0, ',', '.') }} Rekening
                    </span>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4">
                    <span class="text-[11px] text-zinc-500 font-medium block">Biaya per Rekening</span>
                    <span class="text-xl font-bold font-mono text-rose-600 dark:text-rose-400 mt-1 block tabular-nums">
                        Rp {{ number_format($admin_fee_simulation['fee_per_nasabah'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="bg-zinc-900 dark:bg-zinc-800 text-white rounded-2xl p-4 border border-zinc-800 dark:border-zinc-700">
                    <span class="text-[11px] text-zinc-400 font-medium block">Estimasi Total Pendapatan</span>
                    <span class="text-xl font-bold font-mono text-emerald-400 mt-1 block tabular-nums">
                        Rp {{ number_format($admin_fee_simulation['total_potongan'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Preview List & Execute Button -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Pratinjau Sampling Potongan</h3>
                        <p class="text-[11px] text-zinc-500">Periksa simulasi sebelum melakukan eksekusi pemotongan saldo massal</p>
                    </div>

                    <button 
                        type="button" 
                        wire:click="openConfirmAdminModal" 
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl flex items-center gap-2 cursor-pointer transition-colors self-start sm:self-auto"
                    >
                        <x-heroicon-s-bolt class="size-4" />
                        <span>Eksekusi Pemotongan Biaya Admin</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold">
                                <th class="pb-2.5 px-3">No. Rekening</th>
                                <th class="pb-2.5 px-3">Nama Nasabah</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Awal</th>
                                <th class="pb-2.5 px-3 text-right">Potongan Admin</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($admin_fee_simulation['preview_list'] ?? [] as $row)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="py-2.5 px-3 font-mono font-semibold">{{ $row['nomor'] }}</td>
                                    <td class="py-2.5 px-3 text-zinc-900 dark:text-zinc-100">{{ $row['nama'] }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono text-zinc-500 tabular-nums">Rp {{ number_format($row['saldo_awal'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-semibold text-rose-600 dark:text-rose-400 tabular-nums">- Rp {{ number_format($row['potongan'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-zinc-900 dark:text-white tabular-nums">Rp {{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-zinc-400 text-xs">Tidak ada nasabah yang memenuhi kriteria minimum saldo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 2: BAGI HASIL / BUNGA SIMPANAN -->
    @if ($activeTab === 'bagi_hasil')
        <div class="space-y-6">
            <!-- Configuration Parameters -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 space-y-4">
                <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Parameter Bagi Hasil / Bunga Simpanan</h3>
                    <p class="text-[11px] text-zinc-500">Tentukan persentase bagi hasil dan batas saldo minimum penerima</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Rate Bagi Hasil / Bunga (%)
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                step="0.01" 
                                wire:model.live.debounce.300ms="bagi_hasil_rate" 
                                wire:change="simulateBagiHasil"
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl font-mono font-semibold text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center font-semibold text-zinc-400">%</span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Minimum Saldo Berhak Bagi Hasil
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-semibold text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="bagi_hasil_min_balance" 
                                wire:change="simulateBagiHasil"
                                class="w-full pl-11 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl font-mono font-semibold text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Periode Bagi Hasil (Label Bulan)
                        </label>
                        <input 
                            type="text" 
                            wire:model="bagi_hasil_period" 
                            placeholder="Contoh: Agustus 2026"
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl font-medium text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                    </div>
                </div>
            </div>

            <!-- Simulation Summary Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4">
                    <span class="text-[11px] text-zinc-500 font-medium block">Nasabah Berhak Menerima</span>
                    <span class="text-xl font-bold font-mono text-zinc-900 dark:text-white mt-1 block tabular-nums">
                        {{ number_format($bagi_hasil_simulation['total_nasabah'] ?? 0, 0, ',', '.') }} Rekening
                    </span>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4">
                    <span class="text-[11px] text-zinc-500 font-medium block">Rate Diterapkan</span>
                    <span class="text-xl font-bold font-mono text-emerald-600 dark:text-emerald-400 mt-1 block tabular-nums">
                        {{ $bagi_hasil_simulation['rate'] ?? 0 }}%
                    </span>
                </div>

                <div class="bg-zinc-900 dark:bg-zinc-800 text-white rounded-2xl p-4 border border-zinc-800 dark:border-zinc-700">
                    <span class="text-[11px] text-zinc-400 font-medium block">Total Bagi Hasil Dibagikan</span>
                    <span class="text-xl font-bold font-mono text-emerald-400 mt-1 block tabular-nums">
                        Rp {{ number_format($bagi_hasil_simulation['total_bagi_hasil'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Preview List & Execute Button -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Pratinjau Penerima Bagi Hasil</h3>
                        <p class="text-[11px] text-zinc-500">Periksa estimasi penambahan saldo sebelum melakukan eksekusi massal</p>
                    </div>

                    <button 
                        type="button" 
                        wire:click="openConfirmBagiHasilModal" 
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl flex items-center gap-2 cursor-pointer transition-colors self-start sm:self-auto"
                    >
                        <x-heroicon-s-sparkles class="size-4" />
                        <span>Eksekusi Distribusi Bagi Hasil</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold">
                                <th class="pb-2.5 px-3">No. Rekening</th>
                                <th class="pb-2.5 px-3">Nama Nasabah</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Saat Ini</th>
                                <th class="pb-2.5 px-3 text-right">Bagi Hasil</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($bagi_hasil_simulation['preview_list'] ?? [] as $row)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="py-2.5 px-3 font-mono font-semibold">{{ $row['nomor'] }}</td>
                                    <td class="py-2.5 px-3 text-zinc-900 dark:text-zinc-100">{{ $row['nama'] }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono text-zinc-500 tabular-nums">Rp {{ number_format($row['saldo_awal'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-semibold text-emerald-600 dark:text-emerald-400 tabular-nums">+ Rp {{ number_format($row['bonus'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-zinc-900 dark:text-white tabular-nums">Rp {{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-zinc-400 text-xs">Tidak ada nasabah yang memenuhi kriteria minimum saldo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 3: RIWAYAT EKSEKUSI SETTLEMENT -->
    @if ($activeTab === 'riwayat')
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Riwayat Eksekusi Batch Settlement</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Rekam jejak pemotongan biaya admin dan pembagian bagi hasil bulanan</p>
            </div>

            @if ($settlementLogs->isEmpty())
                <div class="p-8 text-center text-zinc-400 text-xs">
                    Belum ada riwayat eksekusi batch biaya admin atau bagi hasil.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 text-zinc-500 dark:text-zinc-400 font-semibold">
                                <th class="py-3 px-4">Waktu Eksekusi</th>
                                <th class="py-3 px-4">Eksekutor</th>
                                <th class="py-3 px-4">Tipe Batch</th>
                                <th class="py-3 px-4">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/70">
                            @foreach ($settlementLogs as $log)
                                <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40">
                                    <td class="py-3 px-4 font-mono text-zinc-500 whitespace-nowrap text-[11px]">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-zinc-900 dark:text-white">
                                        {{ $log->user_name }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase {{ $log->action === 'batch_biaya_admin' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60' }}">
                                            {{ $log->action === 'batch_biaya_admin' ? 'Biaya Admin' : 'Bagi Hasil' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-zinc-700 dark:text-zinc-300">
                                        {{ $log->description }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($settlementLogs->hasPages())
                    <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                        {{ $settlementLogs->links() }}
                    </div>
                @endif
            @endif
        </div>
    @endif

    <!-- CONFIRMATION MODAL: ADMIN FEE -->
    @if ($showConfirmAdminModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-md overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100 p-6 text-center space-y-4">
                <div class="size-12 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-900">
                    <x-heroicon-o-exclamation-triangle class="size-6" />
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Konfirmasi Pemotongan Biaya Admin</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Sistem akan memotong saldo sebanyak <strong class="font-semibold text-rose-600">Rp {{ number_format((float)$admin_fee_amount, 0, ',', '.') }}</strong> dari <strong>{{ $admin_fee_simulation['total_nasabah'] ?? 0 }} rekening</strong> nasabah aktif. Tindakan ini permanen dan dicatat sebagai transaksi resmi.
                    </p>
                </div>

                <div class="pt-2 flex items-center gap-2.5">
                    <button type="button" wire:click="closeConfirmAdminModal" class="flex-1 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl cursor-pointer transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="executeAdminFee" class="flex-1 py-2 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl cursor-pointer transition-colors">
                        Ya, Eksekusi
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- CONFIRMATION MODAL: BAGI HASIL -->
    @if ($showConfirmBagiHasilModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-md overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100 p-6 text-center space-y-4">
                <div class="size-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto border border-emerald-200 dark:border-emerald-800/60">
                    <x-heroicon-o-sparkles class="size-6" />
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Konfirmasi Distribusi Bagi Hasil</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Sistem akan menambahkan bagi hasil dengan rate <strong>{{ $bagi_hasil_rate }}%</strong> ke <strong>{{ $bagi_hasil_simulation['total_nasabah'] ?? 0 }} rekening</strong> nasabah dengan total estimasi <strong class="font-semibold text-emerald-600">Rp {{ number_format($bagi_hasil_simulation['total_bagi_hasil'] ?? 0, 0, ',', '.') }}</strong>.
                    </p>
                </div>

                <div class="pt-2 flex items-center gap-2.5">
                    <button type="button" wire:click="closeConfirmBagiHasilModal" class="flex-1 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl cursor-pointer transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="executeBagiHasil" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl cursor-pointer transition-colors">
                        Ya, Distribusikan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
