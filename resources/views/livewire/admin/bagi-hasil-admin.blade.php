<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-2xl shadow-sm transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="size-11 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-500/20">
                <x-heroicon-o-calculator class="size-6" />
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tracking-tight">Otomasi Biaya Admin & Bagi Hasil</h1>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Pemrosesan batch pemotongan administrasi bulanan dan pendistribusian bagi hasil/bunga simpanan</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs self-start sm:self-auto">
            <button 
                type="button" 
                wire:click="$set('activeTab', 'admin_fee')" 
                class="px-3.5 py-1.5 rounded-lg font-bold transition-all cursor-pointer {{ $activeTab === 'admin_fee' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Biaya Admin Bulanan
            </button>
            <button 
                type="button" 
                wire:click="$set('activeTab', 'bagi_hasil')" 
                class="px-3.5 py-1.5 rounded-lg font-bold transition-all cursor-pointer {{ $activeTab === 'bagi_hasil' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Bagi Hasil Simpanan
            </button>
            <button 
                type="button" 
                wire:click="$set('activeTab', 'riwayat')" 
                class="px-3.5 py-1.5 rounded-lg font-bold transition-all cursor-pointer {{ $activeTab === 'riwayat' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Riwayat Batch
            </button>
        </div>
    </div>

    @if (session('success_batch'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2.5 shadow-sm">
            <x-heroicon-s-check-circle class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
            <span class="font-medium">{{ session('success_batch') }}</span>
        </div>
    @endif

    @if (session('error_batch'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs flex items-center gap-2.5 shadow-sm">
            <x-heroicon-s-x-circle class="size-5 shrink-0 text-rose-600 dark:text-rose-400" />
            <span class="font-medium">{{ session('error_batch') }}</span>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- TAB 1: BIAYA ADMIN BULANAN -->
    <!-- ======================================================== -->
    @if ($activeTab === 'admin_fee')
        <div class="space-y-6">
            <!-- Configuration Parameters -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Parameter Biaya Administrasi</h3>
                        <p class="text-[11px] text-zinc-500">Tentukan tarif potongan admin dan batas saldo minimum yang berlaku</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nominal Biaya Admin / Rekening
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="admin_fee_amount" 
                                wire:change="simulateAdminFee"
                                class="w-full pl-11 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl font-mono font-bold text-xs text-zinc-900 dark:text-white"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Minimum Saldo Dikenakan Biaya
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="admin_min_balance" 
                                wire:change="simulateAdminFee"
                                class="w-full pl-11 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl font-mono font-bold text-xs text-zinc-900 dark:text-white"
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
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl font-bold text-xs text-zinc-900 dark:text-white"
                        />
                    </div>
                </div>
            </div>

            <!-- Simulation Summary Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm">
                    <span class="text-[11px] text-zinc-500 font-semibold block">Nasabah Memenuhi Syarat</span>
                    <span class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white mt-1 block">
                        {{ number_format($admin_fee_simulation['total_nasabah'] ?? 0, 0, ',', '.') }} Rekening
                    </span>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm">
                    <span class="text-[11px] text-zinc-500 font-semibold block">Biaya per Rekening</span>
                    <span class="text-2xl font-extrabold font-mono text-rose-600 dark:text-rose-400 mt-1 block">
                        Rp {{ number_format($admin_fee_simulation['fee_per_nasabah'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="bg-amber-600 text-white rounded-2xl p-5 shadow-lg shadow-amber-600/20">
                    <span class="text-[11px] text-amber-100 font-semibold block">Estimasi Total Pendapatan Admin</span>
                    <span class="text-2xl font-extrabold font-mono mt-1 block">
                        Rp {{ number_format($admin_fee_simulation['total_potongan'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Preview List & Execute Button -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Pratinjau Nasabah Terkena Potongan (Sampling)</h3>
                        <p class="text-[11px] text-zinc-500">Periksa simulasi sebelum melakukan eksekusi pemotongan saldo massal</p>
                    </div>

                    <button 
                        type="button" 
                        wire:click="openConfirmAdminModal" 
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/30 flex items-center gap-2 cursor-pointer transition-all self-start sm:self-auto"
                    >
                        <x-heroicon-s-bolt class="size-4" />
                        <span>Eksekusi Pemotongan Biaya Admin</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-semibold">
                                <th class="pb-2.5 px-3">No. Rekening</th>
                                <th class="pb-2.5 px-3">Nama Nasabah</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Awal</th>
                                <th class="pb-2.5 px-3 text-right">Potongan Admin</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Akhir Baru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($admin_fee_simulation['preview_list'] ?? [] as $row)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="py-2.5 px-3 font-mono font-bold">{{ $row['nomor'] }}</td>
                                    <td class="py-2.5 px-3">{{ $row['nama'] }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono">Rp {{ number_format($row['saldo_awal'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-rose-600 dark:text-rose-400">- Rp {{ number_format($row['potongan'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-zinc-900 dark:text-white">Rp {{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
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

    <!-- ======================================================== -->
    <!-- TAB 2: BAGI HASIL / BUNGA SIMPANAN -->
    <!-- ======================================================== -->
    @if ($activeTab === 'bagi_hasil')
        <div class="space-y-6">
            <!-- Configuration Parameters -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Parameter Bagi Hasil / Bunga Simpanan</h3>
                        <p class="text-[11px] text-zinc-500">Tentukan persentase bagi hasil dan batas saldo minimum yang berhak menerima bonus</p>
                    </div>
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
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl font-mono font-bold text-xs text-zinc-900 dark:text-white"
                            />
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center font-bold text-zinc-400">%</span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Minimum Saldo Berhak Bagi Hasil
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="bagi_hasil_min_balance" 
                                wire:change="simulateBagiHasil"
                                class="w-full pl-11 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl font-mono font-bold text-xs text-zinc-900 dark:text-white"
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
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl font-bold text-xs text-zinc-900 dark:text-white"
                        />
                    </div>
                </div>
            </div>

            <!-- Simulation Summary Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm">
                    <span class="text-[11px] text-zinc-500 font-semibold block">Nasabah Berhak Menerima</span>
                    <span class="text-2xl font-extrabold font-mono text-zinc-900 dark:text-white mt-1 block">
                        {{ number_format($bagi_hasil_simulation['total_nasabah'] ?? 0, 0, ',', '.') }} Rekening
                    </span>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm">
                    <span class="text-[11px] text-zinc-500 font-semibold block">Rate Diterapkan</span>
                    <span class="text-2xl font-extrabold font-mono text-emerald-600 dark:text-emerald-400 mt-1 block">
                        {{ $bagi_hasil_simulation['rate'] ?? 0 }}%
                    </span>
                </div>

                <div class="bg-emerald-600 text-white rounded-2xl p-5 shadow-lg shadow-emerald-600/20">
                    <span class="text-[11px] text-emerald-100 font-semibold block">Estimasi Total Bagi Hasil Dibagikan</span>
                    <span class="text-2xl font-extrabold font-mono mt-1 block">
                        Rp {{ number_format($bagi_hasil_simulation['total_bagi_hasil'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Preview List & Execute Button -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Pratinjau Nasabah Penerima Bagi Hasil (Sampling)</h3>
                        <p class="text-[11px] text-zinc-500">Periksa estimasi penambahan saldo sebelum melakukan eksekusi massal</p>
                    </div>

                    <button 
                        type="button" 
                        wire:click="openConfirmBagiHasilModal" 
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 flex items-center gap-2 cursor-pointer transition-all self-start sm:self-auto"
                    >
                        <x-heroicon-s-sparkles class="size-4" />
                        <span>Eksekusi Distribusi Bagi Hasil</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-semibold">
                                <th class="pb-2.5 px-3">No. Rekening</th>
                                <th class="pb-2.5 px-3">Nama Nasabah</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Saat Ini</th>
                                <th class="pb-2.5 px-3 text-right">Estimasi Bagi Hasil</th>
                                <th class="pb-2.5 px-3 text-right">Saldo Akhir Baru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($bagi_hasil_simulation['preview_list'] ?? [] as $row)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="py-2.5 px-3 font-mono font-bold">{{ $row['nomor'] }}</td>
                                    <td class="py-2.5 px-3">{{ $row['nama'] }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono">Rp {{ number_format($row['saldo_awal'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">+ Rp {{ number_format($row['bonus'], 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-zinc-900 dark:text-white">Rp {{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
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

    <!-- ======================================================== -->
    <!-- TAB 3: RIWAYAT EKSEKUSI SETTLEMENT -->
    <!-- ======================================================== -->
    @if ($activeTab === 'riwayat')
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
            <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Riwayat Eksekusi Batch Settlement</h3>
                <p class="text-[11px] text-zinc-500">Rekam jejak pemotongan biaya admin dan pembagian bagi hasil bulanan</p>
            </div>

            @if ($settlementLogs->isEmpty())
                <div class="text-center py-8 text-zinc-400 text-xs">
                    Belum ada riwayat eksekusi batch biaya admin atau bagi hasil.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-semibold">
                                <th class="pb-2.5 px-3">Waktu Eksekusi</th>
                                <th class="pb-2.5 px-3">Eksekutor</th>
                                <th class="pb-2.5 px-3">Tipe Batch</th>
                                <th class="pb-2.5 px-3">Deskripsi Eksekusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($settlementLogs as $log)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="py-3 px-3 font-mono text-zinc-600 dark:text-zinc-400 whitespace-nowrap">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="py-3 px-3 font-semibold text-zinc-900 dark:text-white">
                                        {{ $log->user_name }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $log->action === 'batch_biaya_admin' ? 'bg-rose-500/10 text-rose-600 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' }}">
                                            {{ $log->action === 'batch_biaya_admin' ? 'Biaya Admin' : 'Bagi Hasil' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-zinc-800 dark:text-zinc-200">
                                        {{ $log->description }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $settlementLogs->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- CONFIRMATION MODAL: ADMIN FEE -->
    @if ($showConfirmAdminModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100 p-6 text-center space-y-4">
                <div class="size-14 rounded-2xl bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-500/30">
                    <x-heroicon-o-exclamation-triangle class="size-7" />
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-zinc-900 dark:text-white">Konfirmasi Pemotongan Biaya Admin</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Sistem akan memotong saldo sebanyak <strong class="font-bold text-rose-600">Rp {{ number_format((float)$admin_fee_amount, 0, ',', '.') }}</strong> dari <strong>{{ $admin_fee_simulation['total_nasabah'] ?? 0 }} rekening</strong> nasabah aktif. Tindakan ini bersifat permanen dan dicatat sebagai transaksi resmi.
                    </p>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="button" wire:click="closeConfirmAdminModal" class="w-1/2 py-2.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button type="button" wire:click="executeAdminFee" class="w-1/2 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/30 cursor-pointer">
                        Ya, Eksekusi
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- CONFIRMATION MODAL: BAGI HASIL -->
    @if ($showConfirmBagiHasilModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100 p-6 text-center space-y-4">
                <div class="size-14 rounded-2xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto border border-emerald-500/30">
                    <x-heroicon-o-sparkles class="size-7" />
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-zinc-900 dark:text-white">Konfirmasi Distribusi Bagi Hasil</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Sistem akan menambahkan bonus saldo dengan rate <strong>{{ $bagi_hasil_rate }}%</strong> ke <strong>{{ $bagi_hasil_simulation['total_nasabah'] ?? 0 }} rekening</strong> nasabah dengan total estimasi <strong class="font-bold text-emerald-600">Rp {{ number_format($bagi_hasil_simulation['total_bagi_hasil'] ?? 0, 0, ',', '.') }}</strong>.
                    </p>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="button" wire:click="closeConfirmBagiHasilModal" class="w-1/2 py-2.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button type="button" wire:click="executeBagiHasil" class="w-1/2 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 cursor-pointer">
                        Ya, Distribusikan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
