<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
        <div>
            <h1 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tracking-tight">Dompet Kas</h1>
        </div>

        <button 
            type="button" 
            wire:click="openCreateModal"
            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-lg shadow-xs transition-colors cursor-pointer"
        >
            <x-heroicon-s-plus class="size-4" />
            <span>Tambah Dompet</span>
        </button>
    </div>

    <!-- Feedback Flash Messages -->
    @if (session()->has('success'))
        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-lg text-xs text-emerald-700 dark:text-emerald-300 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-s-check-circle class="size-4 text-emerald-600 dark:text-emerald-400 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" wire:click="$refresh" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-lg text-xs text-amber-700 dark:text-amber-300 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-s-exclamation-triangle class="size-4 text-amber-600 dark:text-amber-400 shrink-0" />
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" wire:click="$refresh" class="text-amber-500 hover:text-amber-700">&times;</button>
        </div>
    @endif

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="p-4 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Total Saldo Dompet</span>
                <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400">
                    <x-heroicon-s-wallet class="size-4" />
                </div>
            </div>
            <div class="mt-2 text-xl font-bold font-mono text-zinc-900 dark:text-white">
                Rp {{ number_format($totalSaldoAll, 0, ',', '.') }}
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Dompet Aktif</span>
                <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400">
                    <x-heroicon-s-building-library class="size-4" />
                </div>
            </div>
            <div class="mt-2 text-xl font-bold font-mono text-zinc-900 dark:text-white">
                {{ $totalDompetAktif }} <span class="text-xs font-normal text-zinc-400 font-sans">Rekening</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Total Masuk</span>
                <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400">
                    <x-heroicon-s-arrow-down-tray class="size-4" />
                </div>
            </div>
            <div class="mt-2 text-xl font-bold font-mono text-zinc-900 dark:text-white">
                Rp {{ number_format($totalUangMasuk, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-xs">
        <!-- Search Bar -->
        <div class="p-3.5 border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="relative w-full sm:w-72">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari nomor rekening..."
                    class="w-full pl-8 pr-3 py-1.5 bg-white dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                />
                <x-heroicon-o-magnifying-glass class="size-3.5 absolute left-2.5 top-2.5 text-zinc-400" />
            </div>

            <div class="text-[11px] text-zinc-500 dark:text-zinc-400">
                Menampilkan <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ $dompets->total() }}</span> rekening dompet
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-950 text-zinc-500 dark:text-zinc-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Bank / Nama</th>
                        <th class="px-4 py-3 font-semibold">Nomor Rekening</th>
                        <th class="px-4 py-3 font-semibold">Saldo Terkumpul</th>
                        <th class="px-4 py-3 font-semibold text-center">Transaksi Masuk</th>
                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                        <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($dompets as $dompet)
                        <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="size-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs ring-1 ring-blue-200 dark:ring-blue-800/60">
                                        M
                                    </div>
                                    <div>
                                        <div class="font-bold text-zinc-900 dark:text-white">{{ $dompet->nama }}</div>
                                        <div class="text-[10px] text-zinc-400">Bank Mandiri</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-zinc-800 dark:text-zinc-200 font-semibold px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-[11px]">
                                    {{ $dompet->nomor_rekening }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $dompet->formatted_saldo }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button 
                                    type="button" 
                                    wire:click="viewDetail({{ $dompet->id }})"
                                    class="inline-flex items-center gap-1 font-mono text-zinc-600 dark:text-zinc-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer"
                                >
                                    <span>{{ $dompet->total_transaksi_masuk }}</span>
                                    <span class="text-[10px] text-zinc-400 underline decoration-dotted">trx</span>
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button 
                                    type="button" 
                                    wire:click="toggleStatus({{ $dompet->id }})"
                                    class="cursor-pointer"
                                    title="Klik untuk mengubah status"
                                >
                                    @if ($dompet->status === 'aktif')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                            <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500 border border-zinc-200 dark:border-zinc-700">
                                            <span class="size-1.5 rounded-full bg-zinc-400"></span>
                                            Non-aktif
                                        </span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button 
                                        type="button" 
                                        wire:click="openEditModal({{ $dompet->id }})"
                                        class="p-1.5 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors cursor-pointer"
                                        title="Edit Dompet"
                                    >
                                        <x-heroicon-o-pencil-square class="size-4" />
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="confirmDelete({{ $dompet->id }})"
                                        class="p-1.5 rounded-md hover:bg-rose-50 dark:hover:bg-rose-950/50 text-rose-500 hover:text-rose-700 dark:hover:text-rose-400 transition-colors cursor-pointer"
                                        title="Hapus Dompet"
                                    >
                                        <x-heroicon-o-trash class="size-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-400 text-xs">
                                <x-heroicon-o-wallet class="size-8 mx-auto text-zinc-300 dark:text-zinc-600 mb-1" />
                                Tidak ada data dompet kas ditemukan. Silakan tambahkan dompet kas baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dompets->hasPages())
            <div class="p-3 border-t border-zinc-200 dark:border-zinc-800">
                {{ $dompets->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Input HANYA berisi Mandiri dan Nomor Rekening) -->
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">
                            {{ $selectedId ? 'Edit Dompet Kas' : 'Tambah Dompet Kas' }}
                        </h3>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeFormModal" 
                        class="p-1 rounded-md text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                    >
                        <x-heroicon-s-x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="save" class="p-4 sm:p-5 space-y-4">
                    <!-- Input 1: Bank / Nama (Mandiri) -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-1">
                            Bank <span class="text-emerald-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model="nama" 
                                value="Mandiri"
                                class="w-full pl-9 pr-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-semibold text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                required
                            />
                            <div class="absolute left-2.5 top-2.5 size-4 rounded bg-blue-50 dark:bg-blue-950 text-blue-600 flex items-center justify-center font-bold text-[10px]">
                                M
                            </div>
                        </div>
                        @error('nama') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Input 2: Nomor Rekening -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-1">
                            Nomor Rekening <span class="text-emerald-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model="nomor_rekening" 
                                placeholder="Contoh: 140-00-1234567-8"
                                class="w-full pl-8 pr-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-lg text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                required
                                autofocus
                            />
                            <x-heroicon-o-credit-card class="size-4 absolute left-2.5 top-2.5 text-zinc-400" />
                        </div>
                        @error('nomor_rekening') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="pt-3 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeFormModal" 
                            class="px-3 py-2 rounded-lg text-xs font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-500 transition-colors shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <x-heroicon-s-check class="size-4" />
                            <span>{{ $selectedId ? 'Simpan Perubahan' : 'Simpan Dompet' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal && $targetDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-xl w-full max-w-sm overflow-hidden p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-full bg-rose-100 dark:bg-rose-950 text-rose-600 shrink-0">
                        <x-heroicon-s-exclamation-triangle class="size-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Konfirmasi Hapus</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Yakin ingin menghapus dompet kas ini?</p>
                    </div>
                </div>

                <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg text-xs space-y-1">
                    <div class="font-bold text-zinc-900 dark:text-white">{{ $targetDelete->nama }}</div>
                    <div class="font-mono text-zinc-500">No. Rek: {{ $targetDelete->nomor_rekening }}</div>
                    <div class="font-mono text-emerald-600 dark:text-emerald-400 font-semibold">Saldo: {{ $targetDelete->formatted_saldo }}</div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button 
                        type="button" 
                        wire:click="$set('showDeleteModal', false)" 
                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="delete" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-rose-600 hover:bg-rose-500 transition-colors shadow-xs cursor-pointer"
                    >
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Transactions Modal -->
    @if ($showDetailModal && $detailDompet)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">
                            Riwayat Uang Masuk &bull; {{ $detailDompet->nama }}
                        </h3>
                        <p class="text-[11px] text-zinc-400 font-mono">No. Rekening: {{ $detailDompet->nomor_rekening }} &bull; Saldo: {{ $detailDompet->formatted_saldo }}</p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="$set('showDetailModal', false)" 
                        class="p-1 rounded-md text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                    >
                        <x-heroicon-s-x-mark class="size-5" />
                    </button>
                </div>

                <div class="p-4 max-h-96 overflow-y-auto">
                    <table class="w-full text-left text-xs divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-950 text-zinc-500 text-[10px] uppercase">
                            <tr>
                                <th class="px-3 py-2">Waktu</th>
                                <th class="px-3 py-2">Kode</th>
                                <th class="px-3 py-2">Nasabah</th>
                                <th class="px-3 py-2 text-right">Nominal Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($detailDompet->transaksis as $trx)
                                <tr>
                                    <td class="px-3 py-2 text-zinc-400 font-mono text-[11px]">
                                        {{ $trx->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-3 py-2 font-mono text-[11px] text-zinc-600 dark:text-zinc-400">
                                        {{ $trx->kode_transaksi }}
                                    </td>
                                    <td class="px-3 py-2 font-medium text-zinc-900 dark:text-white">
                                        {{ $trx->nasabah?->nama ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ $trx->formatted_nominal }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-6 text-center text-zinc-400 text-xs">
                                        Belum ada riwayat uang masuk pada rekening dompet ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                    <button 
                        type="button" 
                        wire:click="$set('showDetailModal', false)" 
                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 transition-colors cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
