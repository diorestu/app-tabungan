<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Data Nasabah Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Kelola rekening nasabah, status akun, dan pencetakan buku tabungan</p>
        </div>

        <button 
            type="button" 
            wire:click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-colors cursor-pointer shrink-0"
        >
            <x-heroicon-s-plus class="size-4" />
            <span>Registrasi Nasabah Baru</span>
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 flex flex-col sm:flex-row items-center gap-3 justify-between">
        <div class="w-full sm:w-80 relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari nama, ID nasabah, No. HP, NIK..."
                class="w-full pl-9 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all"
            />
            <x-heroicon-o-magnifying-glass class="size-4 absolute left-3 top-2.5 text-zinc-400 dark:text-zinc-500" />
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <select 
                wire:model.live="statusFilter"
                class="w-full sm:w-44 px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all cursor-pointer"
            >
                <option value="">Semua Status</option>
                <option value="aktif">Status Aktif</option>
                <option value="dibekukan">Status Dibekukan</option>
                <option value="nonaktif">Status Non-Aktif</option>
            </select>
        </div>
    </div>

    <!-- Nasabah Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
        @if ($nasabahs->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="size-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-3 text-zinc-400 dark:text-zinc-500">
                    <x-heroicon-o-users class="size-6" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Tidak ada data nasabah</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">Tidak ditemukan nasabah yang sesuai dengan filter pencarian Anda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 text-zinc-500 dark:text-zinc-400 font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">ID Nasabah</th>
                            <th class="py-3 px-4">Nama & NIK</th>
                            <th class="py-3 px-4">No. HP</th>
                            <th class="py-3 px-4 text-right">Saldo</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/70">
                        @foreach ($nasabahs as $index => $nasabah)
                            <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-4 text-center text-zinc-400 dark:text-zinc-500 tabular-nums">
                                    {{ $nasabahs->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-mono font-semibold text-emerald-600 dark:text-emerald-400">
                                        {{ $nasabah->nomor_nasabah }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-zinc-900 dark:text-white">{{ $nasabah->nama }}</div>
                                    <div class="text-[11px] text-zinc-400 dark:text-zinc-500 font-mono">NIK: {{ $nasabah->nik ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-4 font-mono text-zinc-600 dark:text-zinc-400">
                                    {{ $nasabah->no_hp }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-zinc-900 dark:text-white tabular-nums whitespace-nowrap">
                                    {{ $nasabah->formatted_saldo }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($nasabah->status === 'aktif')
                                        <button 
                                            type="button" 
                                            wire:click="toggleFreeze({{ $nasabah->id }})"
                                            title="Klik untuk bekukan rekening"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100 transition-colors cursor-pointer"
                                        >
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </button>
                                    @elseif ($nasabah->status === 'dibekukan')
                                        <button 
                                            type="button" 
                                            wire:click="toggleFreeze({{ $nasabah->id }})"
                                            title="Klik untuk buka blokir rekening"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 hover:bg-amber-100 transition-colors cursor-pointer"
                                        >
                                            <span class="size-1.5 rounded-full bg-amber-500"></span>
                                            <span>Dibekukan</span>
                                        </button>
                                    @else
                                        <button 
                                            type="button" 
                                            wire:click="setStatus({{ $nasabah->id }}, 'aktif')"
                                            title="Klik untuk aktifkan rekening"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 transition-colors cursor-pointer"
                                        >
                                            <span class="size-1.5 rounded-full bg-zinc-400"></span>
                                            <span>Non-Aktif</span>
                                        </button>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1">
                                        <!-- Shortcut Setor -->
                                        @if ($nasabah->status === 'aktif')
                                            <a 
                                                href="{{ route('admin.setor', ['nasabah_id' => $nasabah->id]) }}" 
                                                title="Setor Tunai"
                                                class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition-colors"
                                            >
                                                <x-heroicon-s-arrow-down-tray class="size-4" />
                                            </a>
                                        @else
                                            <span class="p-1.5 text-zinc-300 dark:text-zinc-700 cursor-not-allowed">
                                                <x-heroicon-s-arrow-down-tray class="size-4" />
                                            </span>
                                        @endif

                                        <!-- Shortcut Tarik -->
                                        @if ($nasabah->status === 'aktif')
                                            <a 
                                                href="{{ route('admin.tarik', ['nasabah_id' => $nasabah->id]) }}" 
                                                title="Tarik Tunai"
                                                class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors"
                                            >
                                                <x-heroicon-s-arrow-up-tray class="size-4" />
                                            </a>
                                        @else
                                            <span class="p-1.5 text-zinc-300 dark:text-zinc-700 cursor-not-allowed">
                                                <x-heroicon-s-arrow-up-tray class="size-4" />
                                            </span>
                                        @endif

                                        <!-- Detail Button -->
                                        <button 
                                            type="button" 
                                            wire:click="openDetailModal({{ $nasabah->id }})"
                                            title="Lihat Detail & Mutasi"
                                            class="p-1.5 rounded-lg text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-eye class="size-4" />
                                        </button>

                                        <!-- Cetak Buku Tabungan Button -->
                                        <button 
                                            type="button" 
                                            wire:click="openBukuTabungan({{ $nasabah->id }})"
                                            title="Cetak Buku Tabungan"
                                            class="p-1.5 rounded-lg text-zinc-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-book-open class="size-4" />
                                        </button>

                                        <!-- Edit Button -->
                                        <button 
                                            type="button" 
                                            wire:click="openEditModal({{ $nasabah->id }})"
                                            title="Edit Data"
                                            class="p-1.5 rounded-lg text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-pencil-square class="size-4" />
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            type="button" 
                                            wire:click="openDeleteModal({{ $nasabah->id }})"
                                            title="Hapus Nasabah"
                                            class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-trash class="size-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($nasabahs->hasPages())
                <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                    {{ $nasabahs->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- MODAL CREATE NASABAH -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Registrasi Nasabah Baru</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Penomoran otomatis 9 digit standar perbankan</p>
                    </div>
                    <button type="button" wire:click="closeCreateModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg cursor-pointer">
                        <x-heroicon-o-x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="saveNasabah" class="p-6 space-y-4">
                    <!-- Wilayah Selection & Auto ID Preview -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Wilayah / Lokasi Pendaftaran <span class="text-emerald-500">*</span>
                            </label>
                            <select 
                                wire:model.live="wilayah_code" 
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                            >
                                <option value="1">1 - Sumatera</option>
                                <option value="2">2 - Jawa</option>
                                <option value="3">3 - Bali</option>
                                <option value="4">4 - Kalimantan</option>
                                <option value="5">5 - Sulawesi</option>
                                <option value="6">6 - Nusa Tenggara</option>
                                <option value="7">7 - Maluku</option>
                                <option value="8">8 - Papua</option>
                            </select>
                        </div>

                        <!-- ID Nasabah Auto Card -->
                        <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-zinc-400 uppercase tracking-wider block font-semibold">Nomor Rekening Otomatis</span>
                                <span class="text-[11px] text-zinc-500">
                                    {{ \App\Models\Nasabah::WILAYAH[$wilayah_code] ?? 'Jawa' }} (Kode {{ $wilayah_code }}) • Periode {{ date('y/m') }}
                                </span>
                            </div>
                            <span class="text-sm font-bold font-mono text-emerald-600 dark:text-emerald-400">
                                {{ $nomor_nasabah }}
                            </span>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nama Lengkap <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nama" 
                            placeholder="Contoh: Budi Santoso"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('nama') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- No Handphone -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nomor Handphone (Untuk Login Portal) <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            wire:model="no_hp" 
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('no_hp') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            NIK (Nomor Induk Kependudukan - Opsional)
                        </label>
                        <input 
                            type="text" 
                            wire:model="nik" 
                            placeholder="16 digit NIK"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('nik') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Alamat Domisili
                        </label>
                        <textarea 
                            wire:model="alamat" 
                            rows="2"
                            placeholder="Alamat lengkap nasabah..."
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        ></textarea>
                        @error('alamat') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Setoran Awal -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Setoran Awal (Rp - Opsional)
                        </label>
                        <input 
                            type="number" 
                            wire:model="setoran_awal" 
                            min="0"
                            step="1000"
                            placeholder="0"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        <span class="text-[11px] text-zinc-400 mt-1 block">Otomatis dicatat sebagai transaksi setoran awal jika diisi</span>
                        @error('setoran_awal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeCreateModal" 
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl cursor-pointer transition-colors"
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Edit Data Nasabah</h3>
                        <p class="text-xs text-zinc-500 font-mono">ID: {{ $nomor_nasabah }}</p>
                    </div>
                    <button type="button" wire:click="closeEditModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg cursor-pointer">
                        <x-heroicon-o-x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="updateNasabah" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nama Lengkap <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nama" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('nama') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nomor Handphone (Untuk Login) <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            wire:model="no_hp" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('no_hp') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nomor Induk Kependudukan (NIK)
                        </label>
                        <input 
                            type="text" 
                            wire:model="nik" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('nik') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Alamat Domisili
                        </label>
                        <textarea 
                            wire:model="alamat" 
                            rows="2"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        ></textarea>
                        @error('alamat') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Status Akun
                        </label>
                        <select 
                            wire:model="status" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                        >
                            <option value="aktif">Aktif (Bisa Transaksi & Login)</option>
                            <option value="dibekukan">Dibekukan (Blokir Sementara)</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeEditModal" 
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl cursor-pointer transition-colors"
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100 flex flex-col max-h-[85vh]">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Detail Rekening Nasabah</h3>
                            @if ($detailNasabah->status === 'aktif')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">Aktif</span>
                            @elseif ($detailNasabah->status === 'dibekukan')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">Dibekukan</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">Non-Aktif</span>
                            @endif
                        </div>
                        <p class="text-xs font-mono text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $detailNasabah->nomor_nasabah }}</p>
                    </div>
                    <button type="button" wire:click="closeDetailModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg cursor-pointer">
                        <x-heroicon-o-x-mark class="size-5" />
                    </button>
                </div>

                <div class="p-6 space-y-5 overflow-y-auto">
                    <!-- Profile Summary Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800">
                            <span class="text-[10px] text-zinc-400 block font-medium">Nama</span>
                            <span class="text-xs font-semibold text-zinc-900 dark:text-white truncate block">{{ $detailNasabah->nama }}</span>
                        </div>
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800">
                            <span class="text-[10px] text-zinc-400 block font-medium">No. Handphone</span>
                            <span class="text-xs font-mono font-semibold text-zinc-900 dark:text-white">{{ $detailNasabah->no_hp }}</span>
                        </div>
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800">
                            <span class="text-[10px] text-zinc-400 block font-medium">NIK</span>
                            <span class="text-xs font-mono text-zinc-700 dark:text-zinc-300">{{ $detailNasabah->nik ?? '-' }}</span>
                        </div>
                        <div class="p-3 bg-emerald-50/60 dark:bg-emerald-950/30 rounded-xl border border-emerald-200 dark:border-emerald-800/60">
                            <span class="text-[10px] text-emerald-700 dark:text-emerald-300 block font-medium">Saldo Tabungan</span>
                            <span class="text-xs font-bold font-mono text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $detailNasabah->formatted_saldo }}</span>
                        </div>
                    </div>

                    @if ($detailNasabah->status === 'dibekukan')
                        <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 text-xs flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <x-heroicon-s-lock-closed class="size-4 text-amber-600 dark:text-amber-400 shrink-0" />
                                <span class="text-amber-800 dark:text-amber-300">Rekening dibekukan. Transaksi setor & tarik dinonaktifkan sementara.</span>
                            </div>
                            <button 
                                type="button" 
                                wire:click="toggleFreeze({{ $detailNasabah->id }})"
                                class="px-2.5 py-1 bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs rounded-lg shrink-0 cursor-pointer transition-colors"
                            >
                                Buka Blokir
                            </button>
                        </div>
                    @endif

                    @if ($detailNasabah->alamat)
                        <div class="text-xs text-zinc-600 dark:text-zinc-400">
                            <span class="font-medium text-zinc-900 dark:text-zinc-200">Alamat:</span> {{ $detailNasabah->alamat }}
                        </div>
                    @endif

                    <!-- 10 Transaksi Terakhir -->
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <h4 class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">10 Mutasi Transaksi Terakhir</h4>
                            <span class="text-[11px] text-zinc-400">Terbaru</span>
                        </div>

                        @if ($detailNasabah->transaksis->isEmpty())
                            <div class="p-6 text-center text-xs text-zinc-400 border border-zinc-200 dark:border-zinc-800 rounded-xl">
                                Belum ada riwayat transaksi pada rekening ini.
                            </div>
                        @else
                            <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-800 rounded-xl">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-zinc-50 dark:bg-zinc-950 text-zinc-500 dark:text-zinc-400 font-semibold border-b border-zinc-200 dark:border-zinc-800">
                                        <tr>
                                            <th class="py-2.5 px-3">Waktu</th>
                                            <th class="py-2.5 px-3">Kode</th>
                                            <th class="py-2.5 px-3">Jenis</th>
                                            <th class="py-2.5 px-3 text-right">Nominal</th>
                                            <th class="py-2.5 px-3 text-right">Saldo Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                        @foreach ($detailNasabah->transaksis as $trx)
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                                <td class="py-2.5 px-3 text-zinc-400 whitespace-nowrap">{{ $trx->created_at->format('d/m/y H:i') }}</td>
                                                <td class="py-2.5 px-3 font-mono text-zinc-700 dark:text-zinc-300">{{ $trx->kode_transaksi }}</td>
                                                <td class="py-2.5 px-3">
                                                    @if ($trx->jenis_transaksi === 'setor')
                                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold text-[11px]">+ SETOR</span>
                                                    @else
                                                        <span class="text-amber-600 dark:text-amber-400 font-semibold text-[11px]">- TARIK</span>
                                                    @endif
                                                </td>
                                                <td class="py-2.5 px-3 text-right font-mono font-semibold tabular-nums {{ $trx->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                                    {{ $trx->formatted_nominal }}
                                                </td>
                                                <td class="py-2.5 px-3 text-right font-mono text-zinc-700 dark:text-zinc-300 tabular-nums">
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

                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-2.5 shrink-0">
                    <div class="flex items-center gap-2">
                        @if ($detailNasabah->status === 'aktif')
                            <a 
                                href="{{ route('admin.setor', ['nasabah_id' => $detailNasabah->id]) }}" 
                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 transition-colors"
                            >
                                <x-heroicon-s-arrow-down-tray class="size-3.5" />
                                <span>Setor</span>
                            </a>
                            <a 
                                href="{{ route('admin.tarik', ['nasabah_id' => $detailNasabah->id]) }}" 
                                class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 transition-colors"
                            >
                                <x-heroicon-s-arrow-up-tray class="size-3.5" />
                                <span>Tarik</span>
                            </a>
                        @endif

                        <button 
                            type="button" 
                            wire:click="openBukuTabungan({{ $detailNasabah->id }})" 
                            class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-lg flex items-center gap-1.5 transition-colors cursor-pointer"
                        >
                            <x-heroicon-o-book-open class="size-3.5" />
                            <span>Buku Tabungan</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            wire:click="openDeleteModal({{ $detailNasabah->id }})" 
                            class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                        >
                            Hapus
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeDetailModal" 
                            class="px-4 py-1.5 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-md overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="p-6 text-center">
                    <div class="size-12 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 border border-rose-200 dark:border-rose-900">
                        <x-heroicon-o-trash class="size-6" />
                    </div>

                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Hapus Data Nasabah?</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Tindakan ini permanen dan akan menghapus seluruh data rekening nasabah ini.</p>

                    <div class="my-4 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-left space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">ID Nasabah:</span>
                            <span class="font-mono font-semibold text-emerald-600 dark:text-emerald-400">{{ $deleteNasabah->nomor_nasabah }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Nama:</span>
                            <span class="font-semibold text-zinc-900 dark:text-white">{{ $deleteNasabah->nama }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">No. HP:</span>
                            <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $deleteNasabah->no_hp }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Total Transaksi:</span>
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $deleteNasabah->transaksis_count ?? 0 }} transaksi</span>
                        </div>
                        <div class="pt-2 border-t border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
                            <span class="text-zinc-700 dark:text-zinc-300 font-semibold">Sisa Saldo:</span>
                            <span class="font-mono font-bold text-sm {{ (float)$deleteNasabah->saldo > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-700 dark:text-zinc-300' }}">
                                {{ $deleteNasabah->formatted_saldo }}
                            </span>
                        </div>
                    </div>

                    @if ((float)$deleteNasabah->saldo > 0)
                        <div class="p-3 mb-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-left text-xs flex items-start gap-2">
                            <x-heroicon-s-exclamation-triangle class="size-4 shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" />
                            <div>
                                <strong>Perhatian:</strong> Nasabah ini masih memiliki saldo tabungan aktif sebesar <strong>{{ $deleteNasabah->formatted_saldo }}</strong>.
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-2.5">
                        <button 
                            type="button" 
                            wire:click="closeDeleteModal" 
                            class="flex-1 py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            wire:click="confirmDelete" 
                            class="flex-1 py-2 px-4 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1.5"
                        >
                            <x-heroicon-s-trash class="size-4" />
                            <span>Hapus Nasabah</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL BUKU TABUNGAN & CETAK REKENING KORAN -->
    @if ($showBukuTabunganModal && $bukuNasabah)
        @php
            $bukuQuery = $bukuNasabah->transaksis()->oldest();
            if (!empty($bukuStartDate)) {
                $bukuQuery->whereDate('created_at', '>=', $bukuStartDate);
            }
            if (!empty($bukuEndDate)) {
                $bukuQuery->whereDate('created_at', '<=', $bukuEndDate);
            }
            $bukuTransactions = $bukuQuery->get();
            $totalBukuSetor = $bukuTransactions->where('jenis_transaksi', 'setor')->sum('nominal');
            $totalBukuTarik = $bukuTransactions->where('jenis_transaksi', 'tarik')->sum('nominal');
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-zinc-950/60 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-4xl overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100 my-6">
                <!-- Action Bar (Hidden on print) -->
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-3 print:hidden">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <x-heroicon-o-book-open class="size-5" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Buku Tabungan & Rekening Koran</h3>
                            <p class="text-xs text-zinc-500">{{ $bukuNasabah->nama }} • <span class="font-mono">{{ $bukuNasabah->nomor_nasabah }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            wire:click="exportBukuCsv"
                            class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer"
                        >
                            <x-heroicon-o-arrow-down-tray class="size-4" />
                            <span>Export CSV</span>
                        </button>
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="px-3.5 py-1.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 font-semibold text-xs rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer"
                        >
                            <x-heroicon-o-printer class="size-4" />
                            <span>Cetak Buku</span>
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeBukuTabungan" 
                            class="px-3.5 py-1.5 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl cursor-pointer transition-colors"
                        >
                            Tutup
                        </button>
                    </div>
                </div>

                <!-- Passbook Document Body -->
                <div class="p-6 sm:p-8 space-y-6 max-h-[75vh] overflow-y-auto print:max-h-none print:overflow-visible print:p-0">
                    <!-- Institution Letterhead -->
                    <div class="pb-4 border-b-2 border-zinc-900 dark:border-zinc-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight uppercase text-zinc-900 dark:text-white">
                                {{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}
                            </h2>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 font-medium">
                                {{ \App\Models\Setting::get('slogan_lembaga', 'Layanan Simpanan & Tabungan Terpercaya') }}
                            </p>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-500 mt-0.5">
                                {{ \App\Models\Setting::get('alamat_lembaga') }} • Telp: {{ \App\Models\Setting::get('telepon_lembaga') }}
                            </p>
                        </div>
                        <div class="sm:text-right text-xs">
                            <span class="inline-block px-2.5 py-1 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold rounded-lg uppercase tracking-wider text-[10px] border border-zinc-200 dark:border-zinc-700 mb-1">
                                LEMBAR BUKU TABUNGAN
                            </span>
                            <p class="text-[11px] text-zinc-500">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Customer Identity Card -->
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-mono">
                        <div>
                            <span class="text-[10px] text-zinc-500 block font-sans">Nomor Rekening / ID:</span>
                            <span class="font-bold text-sm text-emerald-600 dark:text-emerald-400">{{ $bukuNasabah->nomor_nasabah }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-500 block font-sans">Nama Nasabah:</span>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ $bukuNasabah->nama }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-500 block font-sans">No. Handphone:</span>
                            <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $bukuNasabah->no_hp }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-500 block font-sans">Saldo Akhir:</span>
                            <span class="font-bold text-sm text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $bukuNasabah->formatted_saldo }}</span>
                        </div>
                    </div>

                    <!-- Passbook Table -->
                    <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
                        <table class="w-full text-left text-xs font-mono border-collapse">
                            <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold border-b border-zinc-200 dark:border-zinc-700">
                                <tr>
                                    <th class="p-2.5 text-center border-r border-zinc-200 dark:border-zinc-700 w-10">No</th>
                                    <th class="p-2.5 border-r border-zinc-200 dark:border-zinc-700 w-24">Tanggal</th>
                                    <th class="p-2.5 border-r border-zinc-200 dark:border-zinc-700 w-32">Kode</th>
                                    <th class="p-2.5 border-r border-zinc-200 dark:border-zinc-700 font-sans">Uraian / Keterangan</th>
                                    <th class="p-2.5 text-right border-r border-zinc-200 dark:border-zinc-700 w-28 text-amber-600 dark:text-amber-400">Debit (Tarik)</th>
                                    <th class="p-2.5 text-right border-r border-zinc-200 dark:border-zinc-700 w-28 text-emerald-600 dark:text-emerald-400">Kredit (Setor)</th>
                                    <th class="p-2.5 text-right border-r border-zinc-200 dark:border-zinc-700 w-32">Saldo</th>
                                    <th class="p-2.5 text-center w-20">Petugas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                @forelse ($bukuTransactions as $idx => $bTrx)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                        <td class="p-2 text-center border-r border-zinc-200 dark:border-zinc-800 text-zinc-500 tabular-nums">{{ $idx + 1 }}</td>
                                        <td class="p-2 border-r border-zinc-200 dark:border-zinc-800 whitespace-nowrap">{{ $bTrx->created_at->format('d/m/y H:i') }}</td>
                                        <td class="p-2 border-r border-zinc-200 dark:border-zinc-800 font-semibold text-zinc-700 dark:text-zinc-300">{{ $bTrx->kode_transaksi }}</td>
                                        <td class="p-2 border-r border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200 font-sans text-[11px] truncate max-w-xs">{{ $bTrx->keterangan ?? '-' }}</td>
                                        <td class="p-2 text-right border-r border-zinc-200 dark:border-zinc-800 text-amber-600 dark:text-amber-400 font-semibold tabular-nums">
                                            {{ $bTrx->jenis_transaksi === 'tarik' ? number_format($bTrx->nominal, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="p-2 text-right border-r border-zinc-200 dark:border-zinc-800 text-emerald-600 dark:text-emerald-400 font-semibold tabular-nums">
                                            {{ $bTrx->jenis_transaksi === 'setor' ? number_format($bTrx->nominal, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="p-2 text-right border-r border-zinc-200 dark:border-zinc-800 font-bold text-zinc-900 dark:text-white tabular-nums">
                                            {{ number_format($bTrx->saldo_akhir, 0, ',', '.') }}
                                        </td>
                                        <td class="p-2 text-center text-zinc-500 font-sans text-[10px]">{{ $bTrx->user?->name ? substr($bTrx->user->name, 0, 8) : 'Teller' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-6 text-center text-zinc-400 dark:text-zinc-500 font-sans italic">Belum ada catatan mutasi tabungan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-zinc-100 dark:bg-zinc-800/80 font-bold border-t-2 border-zinc-200 dark:border-zinc-700 text-xs">
                                <tr>
                                    <td colspan="4" class="p-2.5 text-right font-sans">TOTAL MUTASI:</td>
                                    <td class="p-2.5 text-right text-amber-600 dark:text-amber-400 font-mono tabular-nums">Rp {{ number_format($totalBukuTarik, 0, ',', '.') }}</td>
                                    <td class="p-2.5 text-right text-emerald-600 dark:text-emerald-400 font-mono tabular-nums">Rp {{ number_format($totalBukuSetor, 0, ',', '.') }}</td>
                                    <td class="p-2.5 text-right text-zinc-900 dark:text-white font-mono font-bold tabular-nums" colspan="2">{{ $bukuNasabah->formatted_saldo }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Signatures Footer -->
                    <div class="pt-6 grid grid-cols-2 gap-8 text-center text-xs">
                        <div>
                            <p class="text-zinc-500">Nasabah Penyimpan,</p>
                            <div class="h-16"></div>
                            <p class="font-bold text-zinc-900 dark:text-white border-t border-dashed border-zinc-400 inline-block px-8 pt-1">
                                ( {{ $bukuNasabah->nama }} )
                            </p>
                        </div>
                        <div>
                            <p class="text-zinc-500">Petugas / Pengelola,</p>
                            <div class="h-16"></div>
                            <p class="font-bold text-zinc-900 dark:text-white border-t border-dashed border-zinc-400 inline-block px-8 pt-1">
                                ( {{ Auth::guard('web')->user()->name ?? 'Administrator' }} )
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
