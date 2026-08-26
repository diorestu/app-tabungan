<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-zinc-900 border border-zinc-800 p-5 rounded-2xl flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="size-2 rounded-full bg-emerald-400"></span>
                <span class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">Form Setor Tunai</span>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight mt-1">Pencatatan Setoran Tabungan</h1>
            <p class="text-xs text-zinc-400">Pilih rekening nasabah dan masukkan jumlah uang tunai yang disetorkan</p>
        </div>

        <a 
            href="{{ route('admin.tarik') }}" 
            class="text-xs px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 border border-zinc-700 transition-colors flex items-center gap-1.5"
        >
            Pindah ke Tarik Tunai &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm">
                <form wire:submit="processSetor" class="space-y-5">
                    <!-- Step 1: Select Nasabah -->
                    <div>
                        <label class="block text-xs font-bold text-zinc-200 mb-1.5">
                            1. Pilih Rekening Nasabah <span class="text-emerald-400">*</span>
                        </label>

                        @if ($selectedNasabah)
                            <div class="p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-500/40 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-xl bg-emerald-600/30 text-emerald-400 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($selectedNasabah->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">{{ $selectedNasabah->nama }}</h4>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="font-mono text-emerald-400 font-semibold">{{ $selectedNasabah->nomor_nasabah }}</span>
                                            <span class="text-zinc-500">•</span>
                                            <span class="text-zinc-400 font-mono">{{ $selectedNasabah->no_hp }}</span>
                                        </div>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    wire:click="clearSelectedNasabah" 
                                    class="text-xs px-2.5 py-1 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 transition-colors"
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
                                    class="w-full pl-10 pr-4 py-2.5 bg-zinc-950 border border-zinc-700 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    autofocus
                                />
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 absolute left-3.5 top-3 text-zinc-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            @if ($searchResults->isNotEmpty())
                                <div class="mt-2 bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden shadow-xl divide-y divide-zinc-800">
                                    @foreach ($searchResults as $result)
                                        <button 
                                            type="button" 
                                            wire:click="selectNasabah({{ $result->id }})"
                                            class="w-full text-left p-3 hover:bg-zinc-800/80 transition-colors flex items-center justify-between"
                                        >
                                            <div>
                                                <p class="text-xs font-semibold text-white">{{ $result->nama }}</p>
                                                <p class="text-[11px] text-zinc-400 font-mono">{{ $result->nomor_nasabah }} • {{ $result->no_hp }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-bold font-mono text-emerald-400">{{ $result->formatted_saldo }}</span>
                                                <span class="text-[10px] text-zinc-500 block">Pilih &rarr;</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(strlen($nasabahSearch) >= 2)
                                <p class="text-[11px] text-zinc-500 mt-1.5 italic">Tidak ditemukan nasabah dengan kata kunci tersebut.</p>
                            @endif
                        @endif
                        @error('nasabah_id') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Step 2: Nominal Setoran -->
                    <div>
                        <label class="block text-xs font-bold text-zinc-200 mb-1.5">
                            2. Nominal Uang Setoran (Rp) <span class="text-emerald-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-zinc-400 pointer-events-none">
                                Rp
                            </span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.200ms="nominal"
                                min="5000"
                                step="1000"
                                placeholder="0"
                                class="w-full pl-11 pr-4 py-3 bg-zinc-950 border border-zinc-700 rounded-xl text-lg text-white font-mono font-bold placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            />
                        </div>
                        @error('nominal') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror

                        <!-- Quick Nominal Chips -->
                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                            <span class="text-[10px] text-zinc-500 mr-1">Cepat:</span>
                            @foreach ([50000, 100000, 200000, 500000, 1000000, 2000000, 5000000] as $chip)
                                <button 
                                    type="button" 
                                    wire:click="setPresetAmount({{ $chip }})"
                                    class="px-2.5 py-1 rounded-lg bg-zinc-800 hover:bg-emerald-600/30 hover:text-emerald-300 text-[11px] font-mono text-zinc-300 border border-zinc-700 transition-colors"
                                >
                                    {{ number_format($chip / 1000, 0) }}rb
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 3: Keterangan -->
                    <div>
                        <label class="block text-xs font-bold text-zinc-200 mb-1.5">
                            3. Keterangan / Berita Setoran
                        </label>
                        <input 
                            type="text" 
                            wire:model="keterangan" 
                            placeholder="Contoh: Setor tabungan rutin bulanan"
                            class="w-full px-3.5 py-2.5 bg-zinc-950 border border-zinc-700 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                        wire:loading.attr="disabled"
                        @if(!$selectedNasabah) disabled @endif
                    >
                        <span wire:loading.remove>Proses & Catat Setoran Tunai</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan transaksi...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Real-time Preview / Summary Card (Right 1 col) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400">Ringkasan Kalkulasi Saldo</h3>

                <div class="space-y-3 pt-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-zinc-400">Saldo Rekening Saat Ini:</span>
                        <span class="font-mono font-semibold text-zinc-200">
                            {{ $selectedNasabah ? $selectedNasabah->formatted_saldo : 'Rp 0' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-xs">
                        <span class="text-emerald-400 font-semibold">+ Tambahan Setoran:</span>
                        <span class="font-mono font-bold text-emerald-400">
                            Rp {{ number_format((float)($nominal ?: 0), 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="pt-3 border-t border-zinc-800 flex justify-between items-center">
                        <span class="text-xs font-bold text-white">Saldo Akhir Baru:</span>
                        <span class="font-mono text-base font-black text-white">
                            @if ($selectedNasabah)
                                Rp {{ number_format((float)$selectedNasabah->saldo + (float)($nominal ?: 0), 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </span>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-zinc-950 border border-zinc-800 text-[11px] text-zinc-400 space-y-1">
                    <p class="font-semibold text-zinc-300">Catatan Petugas:</p>
                    <p>• Pastikan fisik uang tunai dihitung dan diverifikasi keasliannya sebelum menekan tombol simpan.</p>
                    <p>• Struk transaksi otomatis tersedia untuk dicetak setelah transaksi berhasil disimpan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS RECEIPT MODAL -->
    @if ($showSuccessModal && $lastTransaction)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm">
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in">
                <!-- Receipt Header -->
                <div class="p-6 bg-gradient-to-b from-emerald-900/50 to-zinc-900 border-b border-zinc-800 text-center relative">
                    <div class="size-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-2.5 border border-emerald-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-white">Setoran Berhasil Dicatat!</h3>
                    <p class="text-xs text-zinc-400">Kode Transaksi: <strong class="text-emerald-400 font-mono">{{ $lastTransaction->kode_transaksi }}</strong></p>
                </div>

                <!-- Receipt Body -->
                <div class="p-6 space-y-3.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-zinc-800/80">
                        <span class="text-zinc-400">Waktu:</span>
                        <span class="text-zinc-200 font-mono">{{ $lastTransaction->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-800/80">
                        <span class="text-zinc-400">Nasabah:</span>
                        <span class="font-bold text-white">{{ $lastTransaction->nasabah->nama }} ({{ $lastTransaction->nasabah->nomor_nasabah }})</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-800/80">
                        <span class="text-zinc-400">Nominal Setor:</span>
                        <span class="font-mono font-bold text-emerald-400 text-sm">{{ $lastTransaction->formatted_nominal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-800/80">
                        <span class="text-zinc-400">Saldo Sebelumnya:</span>
                        <span class="font-mono text-zinc-300">{{ $lastTransaction->formatted_saldo_awal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-800/80">
                        <span class="text-zinc-400">Saldo Akhir:</span>
                        <span class="font-mono font-bold text-white text-sm">{{ $lastTransaction->formatted_saldo_akhir }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-zinc-400">Petugas:</span>
                        <span class="text-zinc-300">{{ Auth::guard('web')->user()->name ?? 'Teller' }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-5 bg-zinc-950 border-t border-zinc-800 flex items-center justify-between gap-3">
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Struk Setor
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeSuccessModal" 
                        class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold rounded-xl"
                    >
                        Selesai / Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

