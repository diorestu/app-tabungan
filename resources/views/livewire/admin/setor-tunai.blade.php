<div class="max-w-4xl mx-auto space-y-4 sm:space-y-5">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 rounded-xl flex items-center justify-between shadow-xs">
        <div>
            <h1 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white tracking-tight">Pencatatan Setoran Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Pilih rekening nasabah dan catat nominal uang tunai yang disetorkan</p>
        </div>

        <a 
            href="{{ route('admin.tarik') }}" 
            class="text-xs px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 transition-colors flex items-center gap-1"
        >
            <span>Tarik Tunai</span>
            <x-heroicon-s-chevron-right class="size-3" />
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
        <!-- Main Form (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xs">
                <form wire:submit="processSetor" class="space-y-4">
                    <!-- Step 1: Select Nasabah -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-1">
                            1. Rekening Nasabah <span class="text-emerald-500">*</span>
                        </label>

                        @if ($selectedNasabah)
                            <div class="p-3 rounded-lg bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="size-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($selectedNasabah->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white">{{ $selectedNasabah->nama }}</h4>
                                        <div class="flex items-center gap-2 text-[11px]">
                                            <span class="font-mono text-emerald-700 dark:text-emerald-400 font-medium">{{ $selectedNasabah->nomor_nasabah }}</span>
                                            <span class="text-zinc-300 dark:text-zinc-600">&bull;</span>
                                            <span class="text-zinc-500 font-mono">{{ $selectedNasabah->no_hp }}</span>
                                        </div>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    wire:click="clearSelectedNasabah" 
                                    class="text-xs px-2 py-1 rounded bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 transition-colors cursor-pointer"
                                >
                                    Ganti
                                </button>
                            </div>
                        @else
                            <div class="relative">
                                <input 
                                    type="text" 
                                    wire:model.live.debounce.250ms="nasabahSearch" 
                                    placeholder="Ketik nama, ID nasabah, atau No. HP..."
                                    class="w-full pl-8 pr-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                    autofocus
                                />
                                <x-heroicon-o-magnifying-glass class="size-3.5 absolute left-2.5 top-2.5 text-zinc-400" />
                            </div>

                            @if ($searchResults->isNotEmpty())
                                <div class="mt-1.5 bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden shadow-lg divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ($searchResults as $result)
                                        <button 
                                            type="button" 
                                            wire:click="selectNasabah({{ $result->id }})"
                                            class="w-full text-left p-2.5 hover:bg-zinc-50 dark:hover:bg-zinc-800/80 transition-colors flex items-center justify-between cursor-pointer"
                                        >
                                            <div>
                                                <p class="text-xs font-semibold text-zinc-900 dark:text-white">{{ $result->nama }}</p>
                                                <p class="text-[11px] text-zinc-500 dark:text-zinc-400 font-mono">{{ $result->nomor_nasabah }} &bull; {{ $result->no_hp }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-bold font-mono text-emerald-600 dark:text-emerald-400">{{ $result->formatted_saldo }}</span>
                                                <span class="text-[10px] text-zinc-400 block">Pilih &rarr;</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(strlen($nasabahSearch) >= 2)
                                <p class="text-[11px] text-zinc-400 mt-1 italic">Tidak ditemukan nasabah dengan kata kunci tersebut.</p>
                            @endif
                        @endif
                        @error('nasabah_id') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Step 2: Nominal Setoran -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-1">
                            2. Nominal Uang Setoran (Rp) <span class="text-emerald-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-sm font-semibold text-zinc-400 pointer-events-none">
                                Rp
                            </span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.200ms="nominal"
                                min="5000"
                                step="1000"
                                placeholder="0"
                                class="w-full pl-9 pr-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-base text-zinc-900 dark:text-white font-mono font-bold placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            />
                        </div>
                        @error('nominal') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror

                        <!-- Quick Nominal Chips -->
                        <div class="mt-2 flex flex-wrap items-center gap-1">
                            <span class="text-[10px] text-zinc-400 mr-1">Cepat:</span>
                            @foreach ([50000, 100000, 200000, 500000, 1000000, 2000000, 5000000] as $chip)
                                <button 
                                    type="button" 
                                    wire:click="setPresetAmount({{ $chip }})"
                                    class="px-2 py-0.5 rounded bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-[11px] font-mono text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 transition-colors cursor-pointer"
                                >
                                    {{ number_format($chip / 1000, 0) }}rb
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 3: Keterangan -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-1">
                            3. Keterangan / Berita Setoran
                        </label>
                        <input 
                            type="text" 
                            wire:model="keterangan" 
                            placeholder="Contoh: Setor tabungan rutin"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700/80 rounded-lg text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-lg shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                        wire:loading.attr="disabled"
                        @if(!$selectedNasabah) disabled @endif
                    >
                        <span wire:loading.remove>Proses & Catat Setoran</span>
                        <span wire:loading.inline-flex class="items-center justify-center gap-2">
                            <svg class="animate-spin size-3.5 shrink-0 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Menyimpan...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Real-time Preview / Summary Card (Right 1 col) -->
        <div class="lg:col-span-1 space-y-3">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-xs space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400">Ringkasan Saldo</h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Saldo Rekening:</span>
                        <span class="font-mono font-medium text-zinc-800 dark:text-zinc-200">
                            {{ $selectedNasabah ? $selectedNasabah->formatted_saldo : 'Rp 0' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">+ Tambahan Setor:</span>
                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format((float)($nominal ?: 0), 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                        <span class="font-semibold text-zinc-900 dark:text-white">Saldo Akhir Baru:</span>
                        <span class="font-mono text-sm font-bold text-zinc-900 dark:text-white">
                            @if ($selectedNasabah)
                                Rp {{ number_format((float)$selectedNasabah->saldo + (float)($nominal ?: 0), 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </span>
                    </div>
                </div>

                <div class="p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 text-[11px] text-zinc-500 space-y-1">
                    <p class="font-semibold text-zinc-700 dark:text-zinc-300">Catatan Teller:</p>
                    <p>&bull; Pastikan uang tunai fisik telah dihitung sebelum konfirmasi simpan.</p>
                    <p>&bull; Struk resmi QR verifikasi otomatis tersedia setelah disimpan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS RECEIPT MODAL -->
    @if ($showSuccessModal && $lastTransaction)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-sm overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <!-- Receipt Header -->
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 text-center">
                    <div class="size-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2 border border-emerald-200 dark:border-emerald-800">
                        <x-heroicon-s-check class="size-5" />
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Setoran Berhasil Dicatat</h3>
                    <p class="text-xs text-zinc-400 font-mono mt-0.5">{{ $lastTransaction->kode_transaksi }}</p>
                </div>

                <!-- Receipt Body -->
                <div class="p-4 space-y-2.5 text-xs">
                    <div class="text-center pb-2 border-b border-zinc-100 dark:border-zinc-800">
                        <h4 class="font-bold text-xs uppercase text-zinc-900 dark:text-white">{{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}</h4>
                        <p class="text-[10px] text-zinc-400">{{ \App\Models\Setting::get('alamat_lembaga') }} &bull; Telp: {{ \App\Models\Setting::get('telepon_lembaga') }}</p>
                    </div>

                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Waktu:</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-mono">{{ $lastTransaction->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Nasabah:</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $lastTransaction->nasabah->nama }} ({{ $lastTransaction->nasabah->nomor_nasabah }})</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Nominal Setor:</span>
                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm">{{ $lastTransaction->formatted_nominal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Saldo Sebelum:</span>
                        <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $lastTransaction->formatted_saldo_awal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500">
                        <span>Saldo Akhir:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white text-sm">{{ $lastTransaction->formatted_saldo_akhir }}</span>
                    </div>
                    <div class="flex justify-between py-1 text-zinc-500">
                        <span>Petugas:</span>
                        <span class="text-zinc-800 dark:text-zinc-300">{{ Auth::guard('web')->user()->name ?? 'Teller' }}</span>
                    </div>

                    <!-- Anti-Counterfeit Verification QR Code -->
                    <div class="p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-2.5">
                        <div class="space-y-0.5 text-left">
                            <span class="text-[10px] font-bold text-zinc-900 dark:text-white uppercase flex items-center gap-1">
                                <x-heroicon-s-shield-check class="size-3 text-emerald-600" />
                                Struk Terverifikasi
                            </span>
                            <span class="text-[9px] text-zinc-400 block leading-tight">Scan QR untuk cek integritas struk</span>
                            <span class="text-[8px] font-mono text-zinc-400 block break-all">Kode: {{ $lastTransaction->verification_code }}</span>
                        </div>
                        <img src="{{ $lastTransaction->qr_code_data_uri }}" alt="QR Verifikasi" class="size-12 rounded bg-white p-0.5 shrink-0 border border-zinc-200">
                    </div>

                    @if ($waStatusMessage)
                        <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-[11px] flex items-center gap-1.5">
                            <x-heroicon-o-chat-bubble-left-right class="size-3.5 shrink-0 text-emerald-600" />
                            <span>{{ $waStatusMessage }}</span>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="p-3 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="px-2.5 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-medium rounded-lg flex items-center gap-1 cursor-pointer"
                        >
                            <x-heroicon-o-printer class="size-3.5" />
                            <span>Cetak</span>
                        </button>
                        
                        <a 
                            href="{{ $this->directWhatsAppUrl }}" 
                            target="_blank" 
                            class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-lg flex items-center gap-1 cursor-pointer shadow-xs"
                            title="Kirim Struk WhatsApp"
                        >
                            <x-heroicon-o-chat-bubble-left-right class="size-3.5" />
                            <span>WhatsApp</span>
                        </a>
                    </div>

                    <button 
                        type="button" 
                        wire:click="closeSuccessModal" 
                        class="px-3 py-1.5 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-lg cursor-pointer"
                    >
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
