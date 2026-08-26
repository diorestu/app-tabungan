<div x-data="{
    darkMode: document.documentElement.classList.contains('dark'),
    init() {
        this.darkMode = document.documentElement.classList.contains('dark');
    },
    async toggle() {
        this.darkMode = !this.darkMode;
        const newTheme = this.darkMode ? 'dark' : 'light';
        
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        localStorage.setItem('theme', newTheme);
        localStorage.setItem('flux.appearance', newTheme);

        if (typeof window.Flux !== 'undefined' && typeof window.Flux.applyAppearance === 'function') {
            window.Flux.applyAppearance(newTheme);
        }

        try {
            const csrfToken = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') || '{{ csrf_token() }}';
            await fetch('{{ route('theme.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: newTheme })
            });
        } catch (e) {
            // Silently fail if offline or network error
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
        <x-heroicon-o-sun x-show="darkMode" class="size-4 text-amber-400" />
        <!-- Moon Icon (shown in light mode to switch to dark) -->
        <x-heroicon-o-moon x-show="!darkMode" class="size-4 text-zinc-700" style="display: none;" />
    </button>
</div>


