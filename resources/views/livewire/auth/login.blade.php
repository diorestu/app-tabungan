<div class="w-full max-w-md mx-auto">
    <!-- Header Card -->
    <div class="text-center mb-8">
        <div class="inline-flex size-14 rounded-2xl bg-zinc-100 dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 items-center justify-center mb-4 border border-zinc-200 dark:border-zinc-700 shadow-md shadow-zinc-200 dark:shadow-zinc-950">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white tracking-tight">Login Petugas / Teller</h1>
        <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 mt-1.5">Masuk untuk mengelola nasabah & mencatat transaksi tabungan</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 sm:p-8 shadow-xl dark:shadow-2xl backdrop-blur-xl relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-500"></div>

        @if ($errorMessage)
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/80 text-rose-700 dark:text-rose-300 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 mt-0.5 text-rose-500 dark:text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        <form wire:submit="login" class="space-y-4">
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Email Petugas <span class="text-emerald-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 dark:text-zinc-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        wire:model="email"
                        placeholder="admin@tabungan.test" 
                        class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                        autofocus
                    />
                </div>
                @error('email')
                    <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Password <span class="text-emerald-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 dark:text-zinc-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        wire:model="password"
                        placeholder="••••••••" 
                        class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono"
                    />
                </div>
                @error('password')
                    <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Default Credential Notice -->
            <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-[11px] text-zinc-600 dark:text-zinc-400">
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">Akun Petugas Bawaan:</span><br>
                Email: <span class="text-zinc-800 dark:text-zinc-200 font-mono font-medium">admin@tabungan.test</span> | Pass: <span class="text-zinc-800 dark:text-zinc-200 font-mono font-medium">password</span>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full mt-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Masuk ke Panel Petugas</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memverifikasi...
                </span>
            </button>
        </form>
    </div>

    <!-- Switch Login -->
    <div class="mt-6 text-center text-xs text-zinc-500 dark:text-zinc-400">
        <span>Ingin melihat saldo Anda? </span>
        <a href="{{ route('nasabah.login') }}" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
            Login Portal Nasabah (ID & No HP) &rarr;
        </a>
    </div>
</div>


