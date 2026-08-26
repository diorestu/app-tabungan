<div x-data="{
    darkMode: document.documentElement.classList.contains('dark'),
    toggle() {
        this.darkMode = !this.darkMode;
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    }
}" class="inline-flex items-center">
    <button 
        type="button" 
        @click="toggle()" 
        class="p-2 rounded-xl text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 transition-all cursor-pointer focus:outline-none"
        title="Ganti Tema (Light / Dark Mode)"
        aria-label="Ganti Tema"
    >
        <!-- Sun Icon (shown in dark mode to switch to light) -->
        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <!-- Moon Icon (shown in light mode to switch to dark) -->
        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="size-4 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>
