<div class="space-y-4 sm:space-y-6">
    <!-- Header & Action Bar -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-4 sm:p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm transition-colors">
        <div>
            <div class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('nasabah.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight flex items-center gap-2">
                <span>Kantong Target Tabungan</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 font-bold border border-emerald-200 dark:border-emerald-800">
                    {{ $targets->count() }} Kantong
                </span>
            </h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Rencanakan dan wujudkan impian Anda dengan tabungan terpisah</p>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button"
                wire:click="openCreateModal"
                class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-950/20 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95"
            >
                <x-heroicon-s-plus class="size-4" />
                <span>Buat Target Baru</span>
            </button>
        </div>
    </div>

    <!-- Alert Flash -->
    @if (session()->has('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/80 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2.5 shadow-sm">
            <x-heroicon-s-check-circle class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" />
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Summary Metrics Card -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <!-- Metric 1: Total Saldo di Semua Kantong -->
        <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-1.5">
                <span class="text-xs font-semibold">Terkumpul di Kantong</span>
                <span class="p-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                    <x-heroicon-s-banknotes class="size-4" />
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight tabular-nums">
                Rp {{ number_format($totalTerkumpulSemua, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-1 block">
                Dari target total Rp {{ number_format($totalTargetSemua, 0, ',', '.') }}
            </span>
        </div>

        <!-- Metric 2: Target Tercapai -->
        <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-1.5">
                <span class="text-xs font-semibold">Target Tercapai</span>
                <span class="p-1.5 rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400">
                    <x-heroicon-s-trophy class="size-4" />
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-white tracking-tight tabular-nums">
                {{ $countTercapai }} <span class="text-xs font-medium text-zinc-500">/ {{ $targets->count() }} Kantong</span>
            </div>
            <span class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-1 block">
                {{ $targets->count() > 0 ? round(($countTercapai / $targets->count()) * 100) : 0 }}% impian terwujud
            </span>
        </div>

        <!-- Metric 3: Saldo Utama Siap Ditabung -->
        <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-1.5">
                <span class="text-xs font-semibold">Saldo Utama Tersedia</span>
                <span class="p-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                    <x-icon-wallet class="size-4" />
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-white tracking-tight tabular-nums">
                {{ $nasabah->formatted_saldo }}
            </div>
            <span class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-1 block">
                Dapat dialokasikan ke kantong target
            </span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 p-3 sm:p-4 rounded-2xl flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 shadow-sm">
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
            <button 
                type="button" 
                wire:click="$set('filterStatus', '')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer {{ $filterStatus === '' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
            >
                Semua ({{ $targets->count() }})
            </button>
            <button 
                type="button" 
                wire:click="$set('filterStatus', 'berjalan')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer {{ $filterStatus === 'berjalan' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
            >
                Sedang Berjalan
            </button>
            <button 
                type="button" 
                wire:click="$set('filterStatus', 'tercapai')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer {{ $filterStatus === 'tercapai' ? 'bg-amber-600 text-white shadow-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
            >
                Tercapai 🎉
            </button>
        </div>

        <!-- Search Input -->
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                <x-heroicon-s-magnifying-glass class="size-3.5" />
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari kantong impian..." 
                class="w-full pl-9 pr-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
        </div>
    </div>

    <!-- Target Cards Grid -->
    @if ($targets->isEmpty())
        <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800/80 rounded-3xl p-8 sm:p-12 text-center shadow-sm">
            <div class="size-16 rounded-3xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 border border-emerald-200 dark:border-emerald-800">
                <x-heroicon-o-sparkles class="size-8" />
            </div>
            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Belum Ada Kantong Target Tabungan</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 max-w-md mx-auto mt-1.5">
                Mulai menabung dengan tujuan yang jelas seperti Qurban, Liburan, Pendidikan, atau Dana Darurat dengan membuat kantong target pertama Anda.
            </p>
            <button 
                type="button" 
                wire:click="openCreateModal"
                class="mt-5 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-950/20 transition-all inline-flex items-center gap-2 cursor-pointer active:scale-95"
            >
                <x-heroicon-s-plus class="size-4" />
                <span>Buat Kantong Target Sekarang</span>
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($targets as $target)
                @php
                    $isTercapai = $target->status === 'tercapai';
                    $progress = $target->progress_percentage;
                @endphp
                <div class="bg-white dark:bg-zinc-900/60 border {{ $isTercapai ? 'border-amber-300 dark:border-amber-700/60' : 'border-zinc-200 dark:border-zinc-800/80' }} rounded-3xl p-5 sm:p-6 shadow-sm relative overflow-hidden flex flex-col justify-between transition-all hover:shadow-md">
                    @if ($isTercapai)
                        <div class="absolute top-0 right-0 px-3 py-1 bg-gradient-to-l from-amber-500 to-amber-600 text-white text-[10px] font-black tracking-wider uppercase rounded-bl-xl shadow-sm flex items-center gap-1">
                            <span>Tercapai</span>
                            <span>🎉</span>
                        </div>
                    @endif

                    <div>
                        <!-- Top: Category Badge & Actions -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-[11px] font-bold">
                                <span>{{ $target->kategori_nama }}</span>
                            </span>

                            <div class="flex items-center gap-1">
                                <button 
                                    type="button"
                                    wire:click="openDetailModal({{ $target->id }})"
                                    class="p-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200 transition-colors cursor-pointer"
                                    title="Riwayat Mutasi Kantong"
                                >
                                    <x-heroicon-s-clock class="size-4" />
                                </button>
                                <button 
                                    type="button"
                                    wire:click="openEditModal({{ $target->id }})"
                                    class="p-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200 transition-colors cursor-pointer"
                                    title="Edit Target"
                                >
                                    <x-heroicon-s-pencil-square class="size-4" />
                                </button>
                                <button 
                                    type="button"
                                    wire:click="openDeleteModal({{ $target->id }})"
                                    class="p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors cursor-pointer"
                                    title="Hapus Kantong"
                                >
                                    <x-heroicon-s-trash class="size-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Target Name & Note -->
                        <h3 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tracking-tight">
                            {{ $target->nama_target }}
                        </h3>
                        @if ($target->catatan)
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 line-clamp-2">{{ $target->catatan }}</p>
                        @endif

                        <!-- Nominal Info -->
                        <div class="mt-4 flex items-baseline justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-zinc-400 block">Terkumpul</span>
                                <span class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    {{ $target->formatted_terkumpul_nominal }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-zinc-400 block">Target</span>
                                <span class="text-sm sm:text-base font-bold text-zinc-700 dark:text-zinc-300 tabular-nums">
                                    {{ $target->formatted_target_nominal }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <div class="flex items-center justify-between text-[11px] mb-1.5">
                                <span class="font-semibold text-zinc-500 dark:text-zinc-400">
                                    {{ $progress }}% Tercapai
                                </span>
                                <span class="font-semibold text-zinc-500 dark:text-zinc-400 tabular-nums">
                                    Sisa: {{ $target->formatted_sisa_nominal }}
                                </span>
                            </div>
                            <div class="w-full h-3 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden p-0.5 border border-zinc-200/80 dark:border-zinc-700/80">
                                <div 
                                    class="h-full rounded-full transition-all duration-500 {{ $isTercapai ? 'bg-gradient-to-r from-amber-500 to-emerald-500' : 'bg-gradient-to-r from-emerald-500 to-teal-400' }}"
                                    style="width: {{ $progress }}%"
                                ></div>
                            </div>
                        </div>

                        <!-- Deadline Pill -->
                        @if ($target->tenggat_waktu)
                            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-zinc-500 dark:text-zinc-400">
                                <x-heroicon-s-calendar class="size-3.5 text-zinc-400" />
                                <span>Target Waktu: <strong>{{ $target->tenggat_waktu->format('d M Y') }}</strong></span>
                            </div>
                        @endif
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="mt-5 pt-4 border-t border-zinc-100 dark:border-zinc-800/80 grid grid-cols-2 gap-2">
                        <button 
                            type="button"
                            wire:click="openAlokasiModal({{ $target->id }})"
                            class="py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer active:scale-95"
                        >
                            <x-heroicon-s-arrow-up-circle class="size-4" />
                            <span>Isi Kantong</span>
                        </button>

                        <button 
                            type="button"
                            wire:click="openTarikModal({{ $target->id }})"
                            {{ $target->terkumpul_nominal <= 0 ? 'disabled' : '' }}
                            class="py-2 px-3 rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-bold transition-all border border-zinc-200 dark:border-zinc-700 flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <x-heroicon-s-arrow-down-circle class="size-4" />
                            <span>Tarik Saldo</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- MODAL 1: Create / Edit Target -->
    <flux:modal wire:model="showCreateModal" class="md:w-[480px]">
        <div class="space-y-5">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">
                    {{ $editTargetId ? 'Edit Kantong Target Tabungan' : 'Buat Kantong Target Baru' }}
                </h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    Tentukan impian finansial Anda dan alokasikan dana sesuai kebutuhan
                </p>
            </div>

            <form wire:submit="saveTarget" class="space-y-4">
                <!-- Nama Target -->
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Nama Target / Impian <span class="text-emerald-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="nama_target" 
                        placeholder="Contoh: Tabungan Qurban 1448 H" 
                        class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    />
                    @error('nama_target') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Kategori Target <span class="text-emerald-500">*</span>
                    </label>
                    <select 
                        wire:model="kategori" 
                        class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    >
                        @foreach ($kategoriOptions as $key => $opt)
                            <option value="{{ $key }}">{{ $opt['nama'] }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Target Nominal -->
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Target Nominal (Rp) <span class="text-emerald-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-xs text-zinc-400">Rp</span>
                        <input 
                            type="number" 
                            wire:model="target_nominal" 
                            placeholder="Contoh: 3500000" 
                            min="10000"
                            step="1000"
                            class="w-full pl-10 pr-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all tabular-nums font-semibold"
                        />
                    </div>
                    @error('target_nominal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Tenggat Waktu -->
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Tenggat Waktu / Target Selesai (Opsional)
                    </label>
                    <input 
                        type="date" 
                        wire:model="tenggat_waktu" 
                        class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    />
                    @error('tenggat_waktu') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Catatan & Rencana (Opsional)
                    </label>
                    <textarea 
                        wire:model="catatan" 
                        rows="2"
                        placeholder="Contoh: Beli kambing qurban tipe A untuk Idul Adha tahun depan"
                        class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    ></textarea>
                    @error('catatan') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button 
                        type="button" 
                        wire:click="$set('showCreateModal', false)"
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-950/20 cursor-pointer active:scale-95"
                    >
                        {{ $editTargetId ? 'Simpan Perubahan' : 'Buat Kantong' }}
                    </button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- MODAL 2: Alokasi / Isi Saldo ke Kantong -->
    <flux:modal wire:model="showAlokasiModal" class="md:w-[450px]">
        @if ($activeTarget)
            <div class="space-y-4">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span>Isi Saldo ke Kantong</span>
                    </h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Menabung ke <strong>{{ $activeTarget->nama_target }}</strong> dari Saldo Tabungan Utama
                    </p>
                </div>

                <!-- Saldo Utama Banner -->
                <div class="p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-zinc-500 dark:text-zinc-400 block text-[11px]">Saldo Utama Anda:</span>
                        <span class="font-black text-sm text-zinc-900 dark:text-white tabular-nums">{{ $nasabah->formatted_saldo }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-zinc-500 dark:text-zinc-400 block text-[11px]">Terkumpul Saat Ini:</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $activeTarget->formatted_terkumpul_nominal }}</span>
                    </div>
                </div>

                <form wire:submit="prosesAlokasi" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nominal yang Ingin Ditabung (Rp) <span class="text-emerald-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-xs text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model="alokasi_nominal" 
                                placeholder="0" 
                                min="1000"
                                step="1000"
                                class="w-full pl-10 pr-3.5 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-base font-black text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all tabular-nums"
                                autofocus
                            />
                        </div>
                        @error('alokasi_nominal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror

                        <!-- Quick Nominal Pills -->
                        <div class="grid grid-cols-4 gap-1.5 mt-2.5">
                            <button type="button" wire:click="setQuickAlokasi(50000)" class="py-1.5 text-[11px] font-bold rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-emerald-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">50 rb</button>
                            <button type="button" wire:click="setQuickAlokasi(100000)" class="py-1.5 text-[11px] font-bold rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-emerald-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">100 rb</button>
                            <button type="button" wire:click="setQuickAlokasi(250000)" class="py-1.5 text-[11px] font-bold rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-emerald-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">250 rb</button>
                            <button type="button" wire:click="setQuickAlokasi(500000)" class="py-1.5 text-[11px] font-bold rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-emerald-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">500 rb</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button 
                            type="button" 
                            wire:click="$set('showAlokasiModal', false)"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-950/20 cursor-pointer active:scale-95"
                        >
                            Konfirmasi Menabung
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </flux:modal>

    <!-- MODAL 3: Tarik Saldo dari Kantong ke Saldo Utama -->
    <flux:modal wire:model="showTarikModal" class="md:w-[450px]">
        @if ($activeTarget)
            <div class="space-y-4">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span>Tarik Dana dari Kantong</span>
                    </h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Memindahkan dana dari <strong>{{ $activeTarget->nama_target }}</strong> kembali ke Saldo Tabungan Utama
                    </p>
                </div>

                <div class="p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-zinc-500 dark:text-zinc-400 block text-[11px]">Tersedia di Kantong Ini:</span>
                        <span class="font-black text-sm text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $activeTarget->formatted_terkumpul_nominal }}</span>
                    </div>
                    <button 
                        type="button" 
                        wire:click="setTarikSemua"
                        class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 cursor-pointer hover:bg-emerald-200"
                    >
                        Tarik Semua
                    </button>
                </div>

                <form wire:submit="prosesTarik" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nominal Penarikan (Rp) <span class="text-emerald-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-xs text-zinc-400">Rp</span>
                            <input 
                                type="number" 
                                wire:model="tarik_nominal" 
                                placeholder="0" 
                                min="1000"
                                step="1000"
                                class="w-full pl-10 pr-3.5 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-base font-black text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all tabular-nums"
                                autofocus
                            />
                        </div>
                        @error('tarik_nominal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button 
                            type="button" 
                            wire:click="$set('showTarikModal', false)"
                            class="px-4 py-2 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 text-xs font-bold rounded-xl bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 shadow-md cursor-pointer active:scale-95"
                        >
                            Pindahkan ke Saldo Utama
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </flux:modal>

    <!-- MODAL 4: Detail & Riwayat Mutasi Kantong -->
    <flux:modal wire:model="showDetailModal" class="md:w-[500px]">
        @if ($activeTarget)
            <div class="space-y-4">
                <div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                        {{ $activeTarget->kategori_nama }}
                    </span>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mt-1">
                        {{ $activeTarget->nama_target }}
                    </h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        Riwayat alokasi dan penarikan pada kantong ini
                    </p>
                </div>

                <!-- Info Box -->
                <div class="grid grid-cols-2 gap-2 p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-xs">
                    <div>
                        <span class="text-zinc-500 text-[10px] uppercase font-bold block">Terkumpul</span>
                        <span class="text-base font-black text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $activeTarget->formatted_terkumpul_nominal }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-zinc-500 text-[10px] uppercase font-bold block">Target Akhir</span>
                        <span class="text-base font-black text-zinc-900 dark:text-white tabular-nums">{{ $activeTarget->formatted_target_nominal }}</span>
                    </div>
                </div>

                <!-- Riwayat List -->
                <div>
                    <h4 class="text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-2">Riwayat Transaksi Kantong:</h4>
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        @forelse ($activeTarget->histories as $history)
                            <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800/80 flex items-center justify-between text-xs">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="size-2 rounded-full {{ $history->tipe === 'alokasi' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span class="font-bold text-zinc-900 dark:text-white">
                                            {{ $history->tipe === 'alokasi' ? 'Alokasi Tabungan' : 'Tarik ke Saldo Utama' }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-zinc-400 block ml-3.5 mt-0.5">
                                        {{ $history->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="font-black tabular-nums {{ $history->tipe === 'alokasi' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $history->tipe === 'alokasi' ? '+' : '-' }} {{ $history->formatted_nominal }}
                                    </span>
                                    <span class="text-[10px] text-zinc-400 block tabular-nums">
                                        Saldo: Rp {{ number_format((float)$history->saldo_target_sesudah, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-xs text-zinc-400">
                                Belum ada riwayat alokasi pada kantong ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button 
                        type="button" 
                        wire:click="$set('showDetailModal', false)"
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        @endif
    </flux:modal>

    <!-- MODAL 5: Delete Confirmation -->
    <flux:modal wire:model="showDeleteModal" class="md:w-[420px]">
        @if ($deleteTarget)
            <div class="space-y-4">
                <div class="size-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                    <x-heroicon-s-trash class="size-6" />
                </div>

                <div class="text-center">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">
                        Hapus Kantong Target?
                    </h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Apakah Anda yakin ingin menghapus kantong <strong>"{{ $deleteTarget->nama_target }}"</strong>?
                    </p>
                    @if ($deleteTarget->terkumpul_nominal > 0)
                        <div class="mt-3 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-xs text-left">
                            ℹ️ Sisa saldo di kantong sebesar <strong>{{ $deleteTarget->formatted_terkumpul_nominal }}</strong> akan <strong>otomatis dikembalikan ke Saldo Utama</strong> Anda.
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-center gap-2 pt-2">
                    <button 
                        type="button" 
                        wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="confirmDelete"
                        class="px-4 py-2 text-xs font-bold rounded-xl bg-rose-600 hover:bg-rose-500 text-white shadow-md shadow-rose-950/20 cursor-pointer active:scale-95"
                    >
                        Ya, Hapus Kantong
                    </button>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
