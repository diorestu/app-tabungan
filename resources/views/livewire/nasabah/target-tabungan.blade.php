<div class="space-y-4 sm:space-y-5">
    <!-- Header & Action Bar -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
        <div>
            <div class="flex items-center gap-1 text-[11px] text-zinc-400 mb-0.5">
                <a href="{{ route('nasabah.dashboard') }}" class="hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors flex items-center gap-1">
                    <x-heroicon-s-arrow-left class="size-3" />
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
            <h1 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tracking-tight flex items-center gap-2">
                <span>Kantong Target Tabungan</span>
                <span class="text-xs px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-semibold border border-zinc-200 dark:border-zinc-700">
                    {{ $targets->count() }} Kantong
                </span>
            </h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Rencanakan alokasi tabungan berencana untuk berbagai kebutuhan</p>
        </div>

        <div>
            <button 
                type="button" 
                wire:click="openCreateModal"
                class="w-full sm:w-auto px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
            >
                <x-heroicon-s-plus class="size-3.5" />
                <span>Buat Target Baru</span>
            </button>
        </div>
    </div>

    <!-- Alert Flash -->
    @if (session()->has('success'))
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2 shadow-xs">
            <x-heroicon-s-check-circle class="size-4 text-emerald-600 dark:text-emerald-400 shrink-0" />
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Summary Metrics Card -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <!-- Metric 1: Total Saldo di Semua Kantong -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
            <span class="text-xs text-zinc-500 dark:text-zinc-400 block mb-1">Terkumpul di Kantong</span>
            <div class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight tabular-nums">
                Rp {{ number_format($totalTerkumpulSemua, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-zinc-400 mt-0.5 block">
                Target: Rp {{ number_format($totalTargetSemua, 0, ',', '.') }}
            </span>
        </div>

        <!-- Metric 2: Target Tercapai -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
            <span class="text-xs text-zinc-500 dark:text-zinc-400 block mb-1">Target Tercapai</span>
            <div class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight tabular-nums">
                {{ $countTercapai }} <span class="text-xs font-normal text-zinc-400">/ {{ $targets->count() }} Kantong</span>
            </div>
            <span class="text-[11px] text-zinc-400 mt-0.5 block">
                {{ $targets->count() > 0 ? round(($countTercapai / $targets->count()) * 100) : 0 }}% impian terwujud
            </span>
        </div>

        <!-- Metric 3: Saldo Utama Siap Ditabung -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs">
            <span class="text-xs text-zinc-500 dark:text-zinc-400 block mb-1">Saldo Utama Tersedia</span>
            <div class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight tabular-nums">
                {{ $nasabah->formatted_saldo }}
            </div>
            <span class="text-[11px] text-zinc-400 mt-0.5 block">
                Siap dialokasikan ke kantong
            </span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 shadow-xs">
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5">
            <button 
                type="button" 
                wire:click="$set('filterStatus', '')"
                class="px-2.5 py-1 rounded-md text-xs font-medium transition-colors whitespace-nowrap cursor-pointer {{ $filterStatus === '' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 font-semibold' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Semua ({{ $targets->count() }})
            </button>
            <button 
                type="button" 
                wire:click="$set('filterStatus', 'berjalan')"
                class="px-2.5 py-1 rounded-md text-xs font-medium transition-colors whitespace-nowrap cursor-pointer {{ $filterStatus === 'berjalan' ? 'bg-emerald-600 text-white font-semibold' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Sedang Berjalan
            </button>
            <button 
                type="button" 
                wire:click="$set('filterStatus', 'tercapai')"
                class="px-2.5 py-1 rounded-md text-xs font-medium transition-colors whitespace-nowrap cursor-pointer {{ $filterStatus === 'tercapai' ? 'bg-amber-600 text-white font-semibold' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                Tercapai
            </button>
        </div>

        <!-- Search Input -->
        <div class="relative w-full sm:w-60">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari kantong impian..." 
                class="w-full pl-8 pr-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
            />
            <x-heroicon-o-magnifying-glass class="size-3.5 absolute left-2.5 top-2.5 text-zinc-400" />
        </div>
    </div>

    <!-- Target Cards Grid -->
    @if ($targets->isEmpty())
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-8 sm:p-12 text-center shadow-xs">
            <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Belum Ada Kantong Target Tabungan</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto mt-1">
                Mulai menabung terencana seperti Qurban, Pendidikan, Liburan, atau Dana Darurat.
            </p>
            <button 
                type="button" 
                wire:click="openCreateModal"
                class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors inline-flex items-center gap-1.5 cursor-pointer"
            >
                <x-heroicon-s-plus class="size-3.5" />
                <span>Buat Kantong Target Baru</span>
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($targets as $target)
                @php
                    $progress = $target->progress_percentage;
                    $isTercapai = $target->status === 'tercapai';
                    $cat = $kategoriOptions[$target->kategori] ?? [
                        'nama' => 'Lainnya',
                        'ikon' => 'tag',
                        'warna' => 'bg-zinc-100 text-zinc-600 border-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700',
                    ];
                @endphp

                <div class="bg-white dark:bg-zinc-900 border {{ $isTercapai ? 'border-amber-400/60 dark:border-amber-500/40' : 'border-zinc-200 dark:border-zinc-800' }} rounded-xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Card Top Bar: Category Pill & Actions -->
                        <div class="flex items-center justify-between gap-2 mb-2.5">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium border {{ $cat['warna'] }}">
                                    {{ $cat['nama'] }}
                                </span>
                                @if ($isTercapai)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                        Tercapai 🎉
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-1">
                                <button 
                                    type="button"
                                    wire:click="openDetailModal({{ $target->id }})"
                                    class="p-1 rounded text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                                    title="Riwayat Alokasi"
                                >
                                    <x-heroicon-s-clock class="size-3.5" />
                                </button>
                                <button 
                                    type="button"
                                    wire:click="openEditModal({{ $target->id }})"
                                    class="p-1 rounded text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                                    title="Edit Target"
                                >
                                    <x-heroicon-s-pencil-square class="size-3.5" />
                                </button>
                                <button 
                                    type="button"
                                    wire:click="openDeleteModal({{ $target->id }})"
                                    class="p-1 rounded text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
                                    title="Hapus Kantong"
                                >
                                    <x-heroicon-s-trash class="size-3.5" />
                                </button>
                            </div>
                        </div>

                        <!-- Target Name & Note -->
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white tracking-tight">
                            {{ $target->nama_target }}
                        </h3>
                        @if ($target->catatan)
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 line-clamp-1">{{ $target->catatan }}</p>
                        @endif

                        <!-- Nominal Info -->
                        <div class="mt-3 flex items-baseline justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-medium text-zinc-400 block">Terkumpul</span>
                                <span class="text-base font-bold text-zinc-900 dark:text-white tabular-nums">
                                    {{ $target->formatted_terkumpul_nominal }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-medium text-zinc-400 block">Target</span>
                                <span class="text-xs font-semibold text-zinc-500 tabular-nums">
                                    {{ $target->formatted_target_nominal }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-2.5">
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 font-medium mb-1">
                                <span>{{ $progress }}% Tercapai</span>
                                <span>Sisa: {{ $target->formatted_sisa_nominal }}</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                                <div 
                                    class="h-full rounded-full transition-all duration-300 {{ $isTercapai ? 'bg-amber-500' : 'bg-emerald-500' }}"
                                    style="width: {{ $progress }}%"
                                ></div>
                            </div>
                        </div>

                        <!-- Deadline Pill -->
                        @if ($target->tenggat_waktu)
                            <div class="mt-2.5 flex items-center gap-1 text-[11px] text-zinc-400">
                                <x-heroicon-s-calendar class="size-3" />
                                <span>Target: {{ $target->tenggat_waktu->format('d M Y') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 grid grid-cols-2 gap-2">
                        <button 
                            type="button"
                            wire:click="openAlokasiModal({{ $target->id }})"
                            class="py-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition-colors flex items-center justify-center gap-1 cursor-pointer"
                        >
                            <x-heroicon-s-plus class="size-3.5" />
                            <span>Isi Saldo</span>
                        </button>

                        <button 
                            type="button"
                            wire:click="openTarikModal({{ $target->id }})"
                            {{ $target->terkumpul_nominal <= 0 ? 'disabled' : '' }}
                            class="py-1.5 px-3 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold transition-colors border border-zinc-200 dark:border-zinc-700 flex items-center justify-center gap-1 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                        >
                            <x-heroicon-s-arrow-down-tray class="size-3.5" />
                            <span>Tarik Saldo</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- MODAL 1: Create / Edit Target Tabungan -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-md overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">
                            {{ $editTargetId ? 'Edit Kantong Target' : 'Buat Kantong Target Baru' }}
                        </h3>
                    </div>
                    <button type="button" wire:click="closeCreateModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                        <x-heroicon-s-x-mark class="size-4" />
                    </button>
                </div>

                <form wire:submit="saveTarget" class="p-5 space-y-3.5 text-xs">
                    <!-- Nama Target -->
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Nama Target <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nama_target" 
                            placeholder="Contoh: Tabungan Qurban, Liburan" 
                            class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('nama_target') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Kategori <span class="text-emerald-500">*</span>
                        </label>
                        <select 
                            wire:model="kategori" 
                            class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                            @foreach ($kategoriOptions as $key => $opt)
                                <option value="{{ $key }}">{{ $opt['nama'] }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Target Nominal -->
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Target Nominal (Rp) <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            wire:model="target_nominal" 
                            placeholder="Contoh: 3500000" 
                            min="10000"
                            step="1000"
                            class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs font-mono text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('target_nominal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tenggat Waktu -->
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Tenggat Waktu (Opsional)
                        </label>
                        <input 
                            type="date" 
                            wire:model="tenggat_waktu" 
                            class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('tenggat_waktu') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Catatan (Opsional)
                        </label>
                        <textarea 
                            wire:model="catatan" 
                            rows="2"
                            placeholder="Catatan tambahan rencana tabungan..."
                            class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        ></textarea>
                        @error('catatan') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                        <button 
                            type="button" 
                            wire:click="closeCreateModal"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white shadow-xs cursor-pointer"
                        >
                            {{ $editTargetId ? 'Simpan' : 'Buat Target' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 2: Alokasi / Isi Saldo ke Kantong -->
    @if ($showAlokasiModal && $activeTarget)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-md overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Isi Saldo ke Kantong</h3>
                        <p class="text-[11px] text-zinc-500">Menabung ke <strong>{{ $activeTarget->nama_target }}</strong></p>
                    </div>
                    <button type="button" wire:click="closeAlokasiModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                        <x-heroicon-s-x-mark class="size-4" />
                    </button>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    <!-- Saldo Banner -->
                    <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <span class="text-zinc-500 block text-[11px]">Saldo Utama Anda:</span>
                            <span class="font-bold text-xs text-zinc-900 dark:text-white tabular-nums">{{ $nasabah->formatted_saldo }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-zinc-500 block text-[11px]">Terkumpul di Kantong:</span>
                            <span class="font-bold text-xs text-zinc-900 dark:text-white tabular-nums">{{ $activeTarget->formatted_terkumpul_nominal }}</span>
                        </div>
                    </div>

                    <form wire:submit="prosesAlokasi" class="space-y-3.5">
                        <div>
                            <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                                Nominal Menabung (Rp) <span class="text-emerald-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                wire:model="alokasi_nominal" 
                                placeholder="0" 
                                min="1000"
                                step="1000"
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-sm font-mono font-bold text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                autofocus
                            />
                            @error('alokasi_nominal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror

                            <!-- Quick Nominal Pills -->
                            <div class="grid grid-cols-4 gap-1.5 mt-2">
                                <button type="button" wire:click="setQuickAlokasi(50000)" class="py-1 text-[11px] font-semibold rounded bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">50 rb</button>
                                <button type="button" wire:click="setQuickAlokasi(100000)" class="py-1 text-[11px] font-semibold rounded bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">100 rb</button>
                                <button type="button" wire:click="setQuickAlokasi(250000)" class="py-1 text-[11px] font-semibold rounded bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">250 rb</button>
                                <button type="button" wire:click="setQuickAlokasi(500000)" class="py-1 text-[11px] font-semibold rounded bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer">500 rb</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                            <button 
                                type="button" 
                                wire:click="closeAlokasiModal"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white shadow-xs cursor-pointer"
                            >
                                Simpan ke Kantong
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL 3: Tarik Saldo dari Kantong ke Saldo Utama -->
    @if ($showTarikModal && $activeTarget)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-md overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Tarik Saldo dari Kantong</h3>
                        <p class="text-[11px] text-zinc-500">Kembalikan ke Saldo Utama</p>
                    </div>
                    <button type="button" wire:click="closeTarikModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                        <x-heroicon-s-x-mark class="size-4" />
                    </button>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <span class="text-zinc-500 block text-[11px]">Tersedia di Kantong:</span>
                            <span class="font-bold text-xs text-zinc-900 dark:text-white tabular-nums">{{ $activeTarget->formatted_terkumpul_nominal }}</span>
                        </div>
                        <button 
                            type="button" 
                            wire:click="setTarikSemua"
                            class="px-2 py-0.5 text-[11px] font-semibold rounded bg-zinc-200 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 cursor-pointer"
                        >
                            Tarik Semua
                        </button>
                    </div>

                    <form wire:submit="prosesTarik" class="space-y-3.5">
                        <div>
                            <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                                Nominal Penarikan (Rp) <span class="text-emerald-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                wire:model="tarik_nominal" 
                                placeholder="0" 
                                min="1000"
                                step="1000"
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-sm font-mono font-bold text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                autofocus
                            />
                            @error('tarik_nominal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                            <button 
                                type="button" 
                                wire:click="closeTarikModal"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 shadow-xs cursor-pointer"
                            >
                                Pindahkan ke Saldo Utama
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL 4: Detail & Riwayat Mutasi Kantong -->
    @if ($showDetailModal && $activeTarget)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-md overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                            {{ $activeTarget->kategori_nama }}
                        </span>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white mt-1">
                            {{ $activeTarget->nama_target }}
                        </h3>
                    </div>
                    <button type="button" wire:click="closeDetailModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1">
                        <x-heroicon-s-x-mark class="size-4" />
                    </button>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    <!-- Info Box -->
                    <div class="grid grid-cols-2 gap-2 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800">
                        <div>
                            <span class="text-zinc-400 text-[10px] uppercase font-medium block">Terkumpul</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white tabular-nums">{{ $activeTarget->formatted_terkumpul_nominal }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-zinc-400 text-[10px] uppercase font-medium block">Target</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white tabular-nums">{{ $activeTarget->formatted_target_nominal }}</span>
                        </div>
                    </div>

                    <!-- Riwayat List -->
                    <div>
                        <h4 class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Riwayat Alokasi:</h4>
                        <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1">
                            @forelse ($activeTarget->histories as $history)
                                <div class="p-2 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="size-1.5 rounded-full {{ $history->tipe === 'alokasi' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            <span class="font-medium text-zinc-900 dark:text-white">
                                                {{ $history->tipe === 'alokasi' ? 'Alokasi Masuk' : 'Ditarik Keluar' }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-zinc-400 block ml-3 mt-0.5">
                                            {{ $history->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold tabular-nums {{ $history->tipe === 'alokasi' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $history->tipe === 'alokasi' ? '+' : '-' }} {{ $history->formatted_nominal }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-xs text-zinc-400">
                                    Belum ada riwayat alokasi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-zinc-200 dark:border-zinc-800">
                        <button 
                            type="button" 
                            wire:click="closeDetailModal"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL 5: Delete Confirmation -->
    @if ($showDeleteModal && $deleteTarget)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-sm overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100 p-5 text-center space-y-3">
                <div class="size-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                    <x-heroicon-o-trash class="size-5" />
                </div>

                <div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">
                        Hapus Kantong Target?
                    </h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Hapus kantong <strong>"{{ $deleteTarget->nama_target }}"</strong>?
                    </p>
                    @if ($deleteTarget->terkumpul_nominal > 0)
                        <div class="mt-2.5 p-2.5 rounded-lg bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-xs text-left">
                            Sisa saldo <strong>{{ $deleteTarget->formatted_terkumpul_nominal }}</strong> akan otomatis dikembalikan ke Saldo Utama Anda.
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-center gap-2 pt-2">
                    <button 
                        type="button" 
                        wire:click="closeDeleteModal"
                        class="w-1/2 py-2 text-xs font-semibold rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="confirmDelete"
                        class="w-1/2 py-2 text-xs font-semibold rounded-lg bg-rose-600 hover:bg-rose-500 text-white shadow-xs cursor-pointer"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
