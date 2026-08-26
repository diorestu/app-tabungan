<div class="w-full max-w-md mx-auto">
    <!-- Header Card -->
    <div class="text-center mb-8">
        <div class="inline-flex size-14 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 items-center justify-center mb-4 border border-emerald-500/30 shadow-md shadow-emerald-600/10">
            <x-icon-wallet class="size-7" />
        </div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white tracking-tight">Login Portal Nasabah</h1>
        <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 mt-1.5">Masuk dengan <strong>ID Nasabah</strong> dan <strong>Nomor Handphone</strong> terdaftar</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 sm:p-8 shadow-xl dark:shadow-2xl backdrop-blur-xl relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-500"></div>

        @if ($errorMessage)
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/80 text-rose-700 dark:text-rose-300 text-xs flex items-start gap-2.5">
                <x-heroicon-s-exclamation-triangle class="size-4 shrink-0 mt-0.5 text-rose-500 dark:text-rose-400" />
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        <form wire:submit="login" class="space-y-4">
            <!-- ID Nasabah -->
            <div>
                <label for="nomor_nasabah" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Nomor / ID Nasabah <span class="text-emerald-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 dark:text-zinc-500">
                        <x-heroicon-s-identification class="size-4" />
                    </div>
                    <input 
                        type="text" 
                        id="nomor_nasabah" 
                        wire:model="nomor_nasabah"
                        placeholder="Contoh: 226080001" 
                        class="w-full pl-10 pr-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-semibold tabular-nums"
                        autofocus
                    />
                </div>
                @error('nomor_nasabah')
                    <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nomor HP -->
            <div>
                <label for="no_hp" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Nomor Handphone Terdaftar <span class="text-emerald-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 dark:text-zinc-500">
                        <x-heroicon-s-phone class="size-4" />
                    </div>
                    <input 
                        type="tel" 
                        inputmode="numeric"
                        autocomplete="tel"
                        id="no_hp" 
                        wire:model="no_hp"
                        placeholder="Contoh: 081234567890" 
                        class="w-full pl-10 pr-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-semibold tabular-nums"
                    />
                </div>
                @error('no_hp')
                    <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full mt-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Masuk ke Buku Tabungan</span>
                <span wire:loading.inline-flex class="items-center justify-center gap-2">
                    <svg class="animate-spin size-4 shrink-0 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memverifikasi data...</span>
                </span>
            </button>
        </form>
    </div>

    <!-- Switch Login & Back to Home -->
    <div class="mt-6 space-y-2 text-center text-xs text-zinc-500 dark:text-zinc-400">
        <div>
            <span>Bukan nasabah? </span>
            <a href="{{ route('login') }}" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                <span>Login sebagai Petugas / Teller Tabungan</span>
                <x-heroicon-s-chevron-right class="size-3.5" />
            </a>
        </div>
        <div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-zinc-500 hover:text-emerald-600 dark:text-zinc-400 dark:hover:text-emerald-400 transition-colors pt-2">
                <x-heroicon-s-arrow-left class="size-3.5" />
                <span>Kembali ke Halaman Utama / Landing Page</span>
            </a>
        </div>
    </div>
</div>
