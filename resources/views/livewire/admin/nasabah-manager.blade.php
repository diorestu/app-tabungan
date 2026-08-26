<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl shadow-sm transition-colors">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Data Nasabah Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Kelola informasi rekening dan data kontak nasabah</p>
        </div>

        <button 
            type="button" 
            wire:click="openCreateModal"
            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer active:scale-95 shrink-0"
        >
            <x-heroicon-s-plus class="size-4" />
            <span>Registrasi Nasabah Baru</span>
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-center gap-3 justify-between shadow-sm transition-colors">
        <div class="w-full sm:w-80 relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari nama, ID nasabah, No. HP, NIK..."
                class="w-full pl-9 pr-4 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
            />
            <x-heroicon-o-magnifying-glass class="size-4 absolute left-3 top-2.5 text-zinc-400 dark:text-zinc-500" />
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <select 
                wire:model.live="statusFilter"
                class="w-full sm:w-44 px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
            >
                <option value="">Semua Status</option>
                <option value="aktif">Status Aktif</option>
                <option value="dibekukan">Status Dibekukan</option>
                <option value="nonaktif">Status Non-Aktif</option>
            </select>
        </div>
    </div>

    <!-- Nasabah Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-6 shadow-sm transition-colors">
        @if ($nasabahs->isEmpty())
            <div class="text-center py-12 text-zinc-400 dark:text-zinc-500 text-xs">
                <x-heroicon-o-users class="size-10 mx-auto text-zinc-400 dark:text-zinc-600 mb-2" />
                Tidak ada data nasabah ditemukan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold">
                            <th class="pb-3 px-3">No</th>
                            <th class="pb-3 px-3">ID Nasabah</th>
                            <th class="pb-3 px-3">Nama & NIK</th>
                            <th class="pb-3 px-3">No. Handphone</th>
                            <th class="pb-3 px-3 text-right">Saldo Tabungan</th>
                            <th class="pb-3 px-3 text-center">Status</th>
                            <th class="pb-3 px-3 text-center">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/60">
                        @foreach ($nasabahs as $index => $nasabah)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3.5 px-3 text-zinc-500">
                                    {{ $nasabahs->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-3 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $nasabah->nomor_nasabah }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-semibold text-zinc-900 dark:text-white block">{{ $nasabah->nama }}</span>
                                    <span class="text-[10px] text-zinc-400 dark:text-zinc-500 font-mono">NIK: {{ $nasabah->nik ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-zinc-700 dark:text-zinc-300">
                                    {{ $nasabah->no_hp }}
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-zinc-900 dark:text-white whitespace-nowrap">
                                    {{ $nasabah->formatted_saldo }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    @if ($nasabah->status === 'aktif')
                                        <button 
                                            type="button" 
                                            wire:click="toggleFreeze({{ $nasabah->id }})"
                                            title="Klik untuk bekukan rekening nasabah"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-colors cursor-pointer"
                                        >
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </button>
                                    @elseif ($nasabah->status === 'dibekukan')
                                        <button 
                                            type="button" 
                                            wire:click="toggleFreeze({{ $nasabah->id }})"
                                            title="Klik untuk buka blokir (aktifkan) rekening"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 hover:bg-amber-500/25 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-s-lock-closed class="size-3 text-amber-600 dark:text-amber-400" />
                                            <span>Dibekukan</span>
                                        </button>
                                    @else
                                        <button 
                                            type="button" 
                                            wire:click="setStatus({{ $nasabah->id }}, 'aktif')"
                                            title="Klik untuk aktifkan kembali"
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                                        >
                                            <span>Non-Aktif</span>
                                        </button>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Shortcut Setor -->
                                        @if ($nasabah->status === 'aktif')
                                            <a 
                                                href="{{ route('admin.setor', ['nasabah_id' => $nasabah->id]) }}" 
                                                title="Setor Tunai"
                                                class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition-colors"
                                            >
                                                <x-heroicon-s-arrow-down-tray class="size-3.5" />
                                            </a>
                                        @else
                                            <button 
                                                type="button" 
                                                disabled 
                                                title="{{ $nasabah->status === 'dibekukan' ? 'Rekening dibekukan - transaksi ditolak' : 'Rekening non-aktif' }}"
                                                class="p-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-600 opacity-40 cursor-not-allowed"
                                            >
                                                <x-heroicon-s-arrow-down-tray class="size-3.5" />
                                            </button>
                                        @endif

                                        <!-- Shortcut Tarik -->
                                        @if ($nasabah->status === 'aktif')
                                            <a 
                                                href="{{ route('admin.tarik', ['nasabah_id' => $nasabah->id]) }}" 
                                                title="Tarik Tunai"
                                                class="p-1.5 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-colors"
                                            >
                                                <x-heroicon-s-arrow-up-tray class="size-3.5" />
                                            </a>
                                        @else
                                            <button 
                                                type="button" 
                                                disabled 
                                                title="{{ $nasabah->status === 'dibekukan' ? 'Rekening dibekukan - transaksi ditolak' : 'Rekening non-aktif' }}"
                                                class="p-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-600 opacity-40 cursor-not-allowed"
                                            >
                                                <x-heroicon-s-arrow-up-tray class="size-3.5" />
                                            </button>
                                        @endif

                                        <!-- Freeze / Unfreeze Quick Action -->
                                        <button 
                                            type="button" 
                                            wire:click="toggleFreeze({{ $nasabah->id }})"
                                            title="{{ $nasabah->status === 'dibekukan' ? 'Buka Blokir (Aktifkan Rekening)' : 'Bekukan Rekening Nasabah' }}"
                                            class="p-1.5 rounded-lg {{ $nasabah->status === 'dibekukan' ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-zinc-100 hover:bg-amber-50 dark:bg-zinc-800 dark:hover:bg-amber-950/40 text-zinc-600 hover:text-amber-600 dark:text-zinc-300 dark:hover:text-amber-400' }} transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-lock-closed class="size-3.5" />
                                        </button>

                                        <!-- Detail Button -->
                                        <button 
                                            type="button" 
                                            wire:click="openDetailModal({{ $nasabah->id }})"
                                            title="Lihat Detail & Mutasi"
                                            class="p-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-eye class="size-3.5" />
                                        </button>

                                        <!-- Edit Button -->
                                        <button 
                                            type="button" 
                                            wire:click="openEditModal({{ $nasabah->id }})"
                                            title="Edit Data"
                                            class="p-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-pencil-square class="size-3.5" />
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            type="button" 
                                            wire:click="openDeleteModal({{ $nasabah->id }})"
                                            title="Hapus Nasabah"
                                            class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 hover:text-white dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-trash class="size-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $nasabahs->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL CREATE NASABAH -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl animate-scale-in">
                <div class="p-5 border-b border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-white">Registrasi Nasabah Baru</h3>
                        <p class="text-xs text-zinc-400">Buat buku rekening tabungan baru</p>
                    </div>
                    <button type="button" wire:click="closeCreateModal" class="text-zinc-400 hover:text-white p-1 rounded-lg">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="saveNasabah" class="p-5 space-y-4">
                    <!-- Nomor Nasabah -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">
                            Nomor ID Nasabah (Auto / Kustom) <span class="text-emerald-400">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nomor_nasabah" 
                            class="w-full px-3 py-2 bg-zinc-950 border border-zinc-700 rounded-xl text-xs text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('nomor_nasabah') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">
                            Nama Lengkap Nasabah <span class="text-emerald-400">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nama" 
                            placeholder="Contoh: Budi Santoso"
                            class="w-full px-3 py-2 bg-zinc-950 border border-zinc-700 rounded-xl text-xs text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('nama') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- No Handphone -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">
                            Nomor Handphone (Untuk Login Nasabah) <span class="text-emerald-400">*</span>
                        </label>
                        <input 
                            type="tel" 
                            wire:model="no_hp" 
                            placeholder="Contoh: 081234567890"
                            class="w-full px-3 py-2 bg-zinc-950 border border-zinc-700 rounded-xl text-xs text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('no_hp') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">
                            Nomor Induk Kependudukan (NIK - Opsional)
                        </label>
                        <input 
                            type="text" 
                            wire:model="nik" 
                            placeholder="Contoh: 320101..."
                            class="w-full px-3 py-2 bg-zinc-950 border border-zinc-700 rounded-xl text-xs text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('nik') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">
                            Alamat Domisili
                        </label>
                        <textarea 
                            wire:model="alamat" 
                            rows="2"
                            placeholder="Contoh: Jl. Melati No. 12..."
                            class="w-full px-3 py-2 bg-zinc-950 border border-zinc-700 rounded-xl text-xs text-white focus:ring-1 focus:ring-emerald-500"
                        ></textarea>
                        @error('alamat') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Setoran Awal -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Setoran Awal (Rp - Opsional)
                        </label>
                        <input 
                            type="number" 
                            wire:model="setoran_awal" 
                            min="0"
                            step="1000"
                            placeholder="0"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        <span class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-1 block">Otomatis dicatat sebagai transaksi setoran awal jika > 0</span>
                        @error('setoran_awal') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-3 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeCreateModal" 
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md cursor-pointer"
                        >
                            Simpan Nasabah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL EDIT NASABAH -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/70 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Edit Data Nasabah</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 font-mono">ID: {{ $nomor_nasabah }}</p>
                    </div>
                    <button type="button" wire:click="closeEditModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1 rounded-lg">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="updateNasabah" class="p-5 space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Nama Lengkap Nasabah <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nama" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('nama') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- No Handphone -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Nomor Handphone (Untuk Login) <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            wire:model="no_hp" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('no_hp') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Nomor Induk Kependudukan (NIK)
                        </label>
                        <input 
                            type="text" 
                            wire:model="nik" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('nik') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Alamat Domisili
                        </label>
                        <textarea 
                            wire:model="alamat" 
                            rows="2"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        ></textarea>
                        @error('alamat') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Status Akun
                        </label>
                        <select 
                            wire:model="status" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        >
                            <option value="aktif">Aktif (Bisa Transaksi & Login)</option>
                            <option value="dibekukan">Dibekukan (Blokir Sementara)</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="pt-3 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeEditModal" 
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md cursor-pointer"
                        >
                            Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL DETAIL NASABAH -->
    @if ($showDetailModal && $detailNasabah)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/70 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Detail Buku Tabungan Nasabah</h3>
                            @if ($detailNasabah->status === 'aktif')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">Aktif</span>
                            @elseif ($detailNasabah->status === 'dibekukan')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">Dibekukan</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-300 dark:border-zinc-700">Non-Aktif</span>
                            @endif
                        </div>
                        <p class="text-xs font-mono text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $detailNasabah->nomor_nasabah }}</p>
                    </div>
                    <button type="button" wire:click="closeDetailModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1 rounded-lg">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-5 space-y-6 max-h-[80vh] overflow-y-auto">
                    <!-- Profile Header Summary -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800">
                            <span class="text-[10px] text-zinc-500 block">Nama Nasabah</span>
                            <span class="text-xs font-bold text-zinc-900 dark:text-white">{{ $detailNasabah->nama }}</span>
                        </div>
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800">
                            <span class="text-[10px] text-zinc-500 block">No. Handphone (Login)</span>
                            <span class="text-xs font-bold font-mono text-zinc-800 dark:text-zinc-200">{{ $detailNasabah->no_hp }}</span>
                        </div>
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800">
                            <span class="text-[10px] text-zinc-500 block">NIK</span>
                            <span class="text-xs font-bold font-mono text-zinc-800 dark:text-zinc-200">{{ $detailNasabah->nik ?? '-' }}</span>
                        </div>
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-200 dark:border-emerald-800/60">
                            <span class="text-[10px] text-emerald-700 dark:text-emerald-300 block">Saldo Tabungan</span>
                            <span class="text-sm font-black font-mono text-emerald-600 dark:text-emerald-400">{{ $detailNasabah->formatted_saldo }}</span>
                        </div>
                    </div>

                    @if ($detailNasabah->status === 'dibekukan')
                        <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-600/40 text-amber-900 dark:text-amber-200 text-xs flex items-start justify-between gap-3">
                            <div class="flex items-start gap-2">
                                <x-heroicon-s-lock-closed class="size-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                                <div>
                                    <strong class="block font-semibold">Rekening Sedang Dibekukan</strong>
                                    <span>Transaksi setor/tarik tunai dan akses login portal nasabah sedang diblokir sementara.</span>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                wire:click="toggleFreeze({{ $detailNasabah->id }})"
                                class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shrink-0 cursor-pointer shadow-sm"
                            >
                                Buka Blokir
                            </button>
                        </div>
                    @endif

                    <!-- Alamat -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800 text-xs text-zinc-600 dark:text-zinc-400">
                        <span class="text-[10px] text-zinc-500 block font-semibold">Alamat:</span>
                        {{ $detailNasabah->alamat ?? 'Tidak dicantumkan' }}
                    </div>

                    <!-- 10 Transaksi Terakhir -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">10 Mutasi Transaksi Terakhir</h4>
                            <span class="text-[10px] text-zinc-500">Otomatis diperbarui</span>
                        </div>

                        @if ($detailNasabah->transaksis->isEmpty())
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 py-4 text-center">Belum ada transaksi pada rekening ini.</p>
                        @else
                            <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-800 rounded-xl">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-zinc-50 dark:bg-zinc-950 text-zinc-500 dark:text-zinc-400 font-semibold border-b border-zinc-200 dark:border-zinc-800">
                                        <tr>
                                            <th class="p-2.5">Waktu</th>
                                            <th class="p-2.5">Kode</th>
                                            <th class="p-2.5">Jenis</th>
                                            <th class="p-2.5 text-right">Nominal</th>
                                            <th class="p-2.5 text-right">Saldo Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                        @foreach ($detailNasabah->transaksis as $trx)
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                                <td class="p-2.5 text-zinc-500 dark:text-zinc-400 whitespace-nowrap">{{ $trx->created_at->format('d/m/y H:i') }}</td>
                                                <td class="p-2.5 font-mono text-zinc-800 dark:text-zinc-300">{{ $trx->kode_transaksi }}</td>
                                                <td class="p-2.5">
                                                    @if ($trx->jenis_transaksi === 'setor')
                                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold text-[11px]">+ SETOR</span>
                                                    @else
                                                        <span class="text-amber-600 dark:text-amber-400 font-bold text-[11px]">- TARIK</span>
                                                    @endif
                                                </td>
                                                <td class="p-2.5 text-right font-mono font-bold {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                                    {{ $trx->formatted_nominal }}
                                                </td>
                                                <td class="p-2.5 text-right font-mono text-zinc-700 dark:text-zinc-300">
                                                    {{ $trx->formatted_saldo_akhir }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        @if ($detailNasabah->status === 'aktif')
                            <a 
                                href="{{ route('admin.setor', ['nasabah_id' => $detailNasabah->id]) }}" 
                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center gap-1.5 cursor-pointer"
                            >
                                <x-heroicon-s-arrow-down-tray class="size-3.5" />
                                <span>Setor</span>
                            </a>
                            <a 
                                href="{{ route('admin.tarik', ['nasabah_id' => $detailNasabah->id]) }}" 
                                class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center gap-1.5 cursor-pointer"
                            >
                                <x-heroicon-s-arrow-up-tray class="size-3.5" />
                                <span>Tarik</span>
                            </a>
                        @endif

                        <button 
                            type="button" 
                            wire:click="toggleFreeze({{ $detailNasabah->id }})" 
                            class="px-3 py-1.5 {{ $detailNasabah->status === 'dibekukan' ? 'bg-emerald-600 hover:bg-emerald-500 text-white' : 'bg-amber-600 hover:bg-amber-500 text-white' }} text-xs font-semibold rounded-lg shadow-sm flex items-center gap-1.5 cursor-pointer"
                        >
                            <x-heroicon-s-lock-closed class="size-3.5" />
                            <span>{{ $detailNasabah->status === 'dibekukan' ? 'Buka Blokir' : 'Bekukan Rekening' }}</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            wire:click="openDeleteModal({{ $detailNasabah->id }})" 
                            class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 hover:text-white dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white text-xs font-semibold rounded-lg border border-rose-200 dark:border-rose-800/60 transition-colors cursor-pointer flex items-center gap-1.5"
                        >
                            <x-heroicon-o-trash class="size-3.5" />
                            <span>Hapus Nasabah</span>
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeDetailModal" 
                            class="px-4 py-1.5 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-300 text-xs font-semibold rounded-lg cursor-pointer"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL DELETE KONFIRMASI -->
    @if ($showDeleteModal && $deleteNasabah)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <div class="p-6 text-center">
                    <div class="size-14 rounded-2xl bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 border border-rose-500/30">
                        <x-heroicon-o-trash class="size-7" />
                    </div>

                    <h3 class="text-lg font-extrabold text-zinc-900 dark:text-white tracking-tight">Hapus Data Nasabah?</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Tindakan ini permanen dan akan menghapus seluruh data rekening nasabah.</p>

                    <div class="my-5 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-950/80 border border-zinc-200 dark:border-zinc-800 text-left space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">ID Nasabah:</span>
                            <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $deleteNasabah->nomor_nasabah }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Nama Nasabah:</span>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ $deleteNasabah->nama }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">No. Handphone:</span>
                            <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $deleteNasabah->no_hp }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Total Transaksi:</span>
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $deleteNasabah->transaksis_count ?? 0 }} transaksi</span>
                        </div>
                        <div class="pt-2 border-t border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
                            <span class="text-zinc-700 dark:text-zinc-300 font-semibold">Sisa Saldo:</span>
                            <span class="font-mono font-black text-sm {{ (float)$deleteNasabah->saldo > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-700 dark:text-zinc-300' }}">
                                {{ $deleteNasabah->formatted_saldo }}
                            </span>
                        </div>
                    </div>

                    @if ((float)$deleteNasabah->saldo > 0)
                        <div class="p-3 mb-5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/60 text-amber-800 dark:text-amber-300 text-left text-[11px] flex items-start gap-2">
                            <x-heroicon-s-exclamation-triangle class="size-4 shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" />
                            <div>
                                <strong>Peringatan Saldo Masih Ada:</strong> Nasabah ini masih memiliki saldo tabungan sebesar <strong>{{ $deleteNasabah->formatted_saldo }}</strong>.
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <button 
                            type="button" 
                            wire:click="closeDeleteModal" 
                            class="w-1/2 py-2.5 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            wire:click="confirmDelete" 
                            class="w-1/2 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/30 transition-colors cursor-pointer flex items-center justify-center gap-1.5"
                        >
                            <x-heroicon-s-trash class="size-4" />
                            <span>Ya, Hapus</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


