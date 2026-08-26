<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl flex items-center justify-between shadow-sm transition-colors">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Pencatatan Penarikan Tabungan</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Pilih rekening nasabah dan verifikasi ketersediaan saldo sebelum menyerahkan uang tunai</p>
        </div>

        <a 
            href="{{ route('admin.setor') }}" 
            class="text-xs px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 transition-colors flex items-center gap-1.5"
        >
            <span>Pindah ke Setor Tunai</span>
            <x-heroicon-s-chevron-right class="size-3.5" />
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors">
                <form wire:submit="processTarik" class="space-y-5">
                    <!-- Step 1: Select Nasabah -->
                    <div>
                        <label class="block text-xs font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                            1. Rekening Nasabah yang Menarik Dana <span class="text-amber-500">*</span>
                        </label>

                        @if ($selectedNasabah)
                            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-300 dark:border-amber-500/40 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-xl bg-amber-500/20 text-amber-700 dark:text-amber-400 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($selectedNasabah->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white">{{ $selectedNasabah->nama }}</h4>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="font-mono text-amber-600 dark:text-amber-400 font-semibold">{{ $selectedNasabah->nomor_nasabah }}</span>
                                            <span class="text-zinc-400 dark:text-zinc-500">•</span>
                                            <span class="text-zinc-700 dark:text-zinc-300 font-bold">Saldo: {{ $selectedNasabah->formatted_saldo }}</span>
                                        </div>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    wire:click="clearSelectedNasabah" 
                                    class="text-xs px-2.5 py-1 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors cursor-pointer"
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
                                    class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    autofocus
                                />
                                <x-heroicon-o-magnifying-glass class="size-4 absolute left-3.5 top-3 text-zinc-400 dark:text-zinc-500" />
                            </div>

                            @if ($searchResults->isNotEmpty())
                                <div class="mt-2 bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-xl divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ($searchResults as $result)
                                        <button 
                                            type="button" 
                                            wire:click="selectNasabah({{ $result->id }})"
                                            class="w-full text-left p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/80 transition-colors flex items-center justify-between cursor-pointer"
                                        >
                                            <div>
                                                <p class="text-xs font-semibold text-zinc-900 dark:text-white">{{ $result->nama }}</p>
                                                <p class="text-[11px] text-zinc-500 dark:text-zinc-400 font-mono">{{ $result->nomor_nasabah }} • {{ $result->no_hp }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-bold font-mono text-emerald-600 dark:text-emerald-400">{{ $result->formatted_saldo }}</span>
                                                <span class="text-[10px] text-zinc-400 dark:text-zinc-500 flex items-center justify-end gap-0.5">
                                                    <span>Pilih</span>
                                                    <x-heroicon-s-chevron-right class="size-3" />
                                                </span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(strlen($nasabahSearch) >= 2)
                                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1.5 italic">Tidak ditemukan nasabah dengan kata kunci tersebut.</p>
                            @endif
                        @endif
                        @error('nasabah_id') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Step 2: Nominal Penarikan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-zinc-800 dark:text-zinc-200">
                                2. Nominal Uang Ditarik (Rp) <span class="text-amber-500">*</span>
                            </label>
                            @if ($selectedNasabah && (float)$selectedNasabah->saldo > 0)
                                <button 
                                    type="button" 
                                    wire:click="setAllBalance"
                                    class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 hover:underline cursor-pointer"
                                >
                                    Tarik Semua Saldo
                                </button>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-zinc-500 pointer-events-none">
                                Rp
                            </span>
                            <input 
                                type="number" 
                                wire:model.live.debounce.200ms="nominal"
                                min="5000"
                                step="1000"
                                placeholder="0"
                                class="w-full pl-11 pr-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-lg text-zinc-900 dark:text-white font-mono font-bold placeholder-zinc-400 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-amber-500"
                            />
                        </div>
                        @error('nominal') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror

                        <!-- Quick Nominal Chips -->
                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                            <span class="text-[10px] text-zinc-500 mr-1">Cepat:</span>
                            @foreach ([50000, 100000, 200000, 500000, 1000000, 2000000] as $chip)
                                <button 
                                    type="button" 
                                    wire:click="setPresetAmount({{ $chip }})"
                                    class="px-2.5 py-1 rounded-lg bg-zinc-100 hover:bg-amber-50 hover:text-amber-600 dark:bg-zinc-800 dark:hover:bg-amber-600/30 dark:hover:text-amber-300 text-[11px] font-mono text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 transition-colors cursor-pointer"
                                >
                                    {{ number_format($chip / 1000, 0) }}rb
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 3: Keterangan -->
                    <div>
                        <label class="block text-xs font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                            3. Keterangan / Keperluan Penarikan
                        </label>
                        <input 
                            type="text" 
                            wire:model="keterangan" 
                            placeholder="Contoh: Tarik tunai keperluan keluarga"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-4 bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-amber-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99] disabled:opacity-50"
                        wire:loading.attr="disabled"
                        @if(!$selectedNasabah || ($nominal && (float)$nominal > (float)$selectedNasabah->saldo)) disabled @endif
                    >
                        <span wire:loading.remove>Proses & Catat Penarikan Tunai</span>
                        <span wire:loading.inline-flex class="items-center justify-center gap-2">
                            <svg class="animate-spin size-4 shrink-0 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Menyimpan transaksi...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Real-time Preview / Summary Card (Right 1 col) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4 transition-colors">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Ringkasan Kalkulasi Saldo</h3>

                <div class="space-y-3 pt-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-zinc-500 dark:text-zinc-400">Saldo Rekening Awal:</span>
                        <span class="font-mono font-semibold text-zinc-800 dark:text-zinc-200">
                            {{ $selectedNasabah ? $selectedNasabah->formatted_saldo : 'Rp 0' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-xs">
                        <span class="text-amber-600 dark:text-amber-400 font-semibold">- Jumlah Ditarik:</span>
                        <span class="font-mono font-bold text-amber-600 dark:text-amber-400">
                            Rp {{ number_format((float)($nominal ?: 0), 0, ',', '.') }}
                        </span>
                    </div>

                    @php
                        $nominalVal = (float)($nominal ?: 0);
                        $saldoVal = $selectedNasabah ? (float)$selectedNasabah->saldo : 0;
                        $sisa = $saldoVal - $nominalVal;
                    @endphp

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                        <span class="text-xs font-bold text-zinc-900 dark:text-white">Sisa Saldo:</span>
                        <span class="font-mono text-base font-black {{ $sisa < 0 ? 'text-rose-500 dark:text-rose-400' : 'text-zinc-900 dark:text-white' }}">
                            @if ($selectedNasabah)
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </span>
                    </div>

                    @if ($sisa < 0)
                        <div class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-[11px]">
                            ⚠️ <strong>Peringatan:</strong> Nominal melebihi total saldo tabungan nasabah.
                        </div>
                    @endif
                </div>

                <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-[11px] text-zinc-600 dark:text-zinc-400 space-y-1">
                    <p class="font-semibold text-zinc-800 dark:text-zinc-300">Peringatan Teller:</p>
                    <p>• Periksa identitas nasabah (KTP/NIK & No HP) sebelum memproses penarikan uang kas.</p>
                    <p>• Berikan uang tunai beserta struk resmi penarikan tabungan kepada nasabah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS RECEIPT MODAL -->
    @if ($showSuccessModal && $lastTransaction)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/75 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <!-- Receipt Header -->
                <div class="p-6 bg-gradient-to-b from-amber-50 to-white dark:from-amber-900/50 dark:to-zinc-900 border-b border-zinc-200 dark:border-zinc-800 text-center relative">
                    <div class="size-12 rounded-2xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-2.5 border border-amber-500/30">
                        <x-heroicon-s-check-circle class="size-7" />
                    </div>
                    <h3 class="text-lg font-extrabold text-zinc-900 dark:text-white">Penarikan Tunai Sukses!</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Kode Transaksi: <strong class="text-amber-600 dark:text-amber-400 font-mono">{{ $lastTransaction->kode_transaksi }}</strong></p>
                </div>

                <!-- Receipt Body -->
                <div class="p-6 space-y-3.5 text-xs">
                    <div class="text-center pb-2.5 mb-1 border-b border-zinc-100 dark:border-zinc-800">
                        <h4 class="font-bold text-xs text-zinc-900 dark:text-white uppercase">{{ \App\Models\Setting::get('nama_lembaga', 'TabunganKu Digital') }}</h4>
                        <p class="text-[10px] text-zinc-500">{{ \App\Models\Setting::get('alamat_lembaga') }} • Telp: {{ \App\Models\Setting::get('telepon_lembaga') }}</p>
                    </div>

                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Waktu:</span>
                        <span class="text-zinc-800 dark:text-zinc-200 font-mono">{{ $lastTransaction->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Nasabah:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $lastTransaction->nasabah->nama }} ({{ $lastTransaction->nasabah->nomor_nasabah }})</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Nominal Penarikan:</span>
                        <span class="font-mono font-bold text-amber-600 dark:text-amber-400 text-sm">{{ $lastTransaction->formatted_nominal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Saldo Sebelumnya:</span>
                        <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $lastTransaction->formatted_saldo_awal }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-100 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Sisa Saldo Akhir:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white text-sm">{{ $lastTransaction->formatted_saldo_akhir }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-zinc-500 dark:text-zinc-400">Petugas / Teller:</span>
                        <span class="text-zinc-800 dark:text-zinc-300">{{ Auth::guard('web')->user()->name ?? 'Teller' }}</span>
                    </div>

                    <p class="text-[10px] text-center text-zinc-400 dark:text-zinc-500 italic pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        {{ \App\Models\Setting::get('pesan_struk', 'Simpan struk ini sebagai bukti transaksi resmi.') }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="p-5 bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3">
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-md cursor-pointer"
                    >
                        <x-heroicon-o-printer class="size-4" />
                        Cetak Struk Tarik
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeSuccessModal" 
                        class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer"
                    >
                        Selesai / Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


