<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-2xl shadow-sm transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tracking-tight">Pengaturan & Manajemen Sistem</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Konfigurasi profil lembaga, manajemen akun petugas/teller, dan keamanan akun</p>
        </div>
        
        <!-- Navigation Tabs -->
        <div class="flex items-center p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700/60 self-start sm:self-auto">
            <button 
                type="button" 
                wire:click="setTab('lembaga')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $activeTab === 'lembaga' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-building-office-2 class="size-4" />
                <span>Profil Lembaga</span>
            </button>
            <button 
                type="button" 
                wire:click="setTab('petugas')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $activeTab === 'petugas' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-users class="size-4" />
                <span>Petugas & Teller</span>
            </button>
            <button 
                type="button" 
                wire:click="setTab('wa')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $activeTab === 'wa' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-chat-bubble-left-right class="size-4" />
                <span>WhatsApp Gateway</span>
            </button>
            <button 
                type="button" 
                wire:click="setTab('keamanan')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $activeTab === 'keamanan' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-key class="size-4" />
                <span>Akun & Password</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 1: PROFIL LEMBAGA & STRUK -->
    <!-- ======================================================== -->
    @if ($activeTab === 'lembaga')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
            <!-- Left 2 Cols: Form Lembaga -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors">
                    <div class="flex items-center gap-3 pb-4 border-b border-zinc-100 dark:border-zinc-800 mb-5">
                        <div class="size-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <x-heroicon-o-building-office-2 class="size-5" />
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Profil Lembaga / Instansi</h2>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Informasi ini dicantumkan pada header struk dan laporan mutasi kas</p>
                        </div>
                    </div>

                    @if (session('success_institution'))
                        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2 animate-fade-in">
                            <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                            <span>{{ session('success_institution') }}</span>
                        </div>
                    @endif

                    <form wire:submit="saveInstitutionSettings" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Nama Lembaga / Koperasi / Sekolah <span class="text-emerald-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="nama_lembaga" 
                                placeholder="Contoh: Koperasi Simpan Pinjam Sejahtera"
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-1 focus:ring-emerald-500"
                            />
                            @error('nama_lembaga') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Slogan / Tagline
                            </label>
                            <input 
                                type="text" 
                                wire:model="slogan_lembaga" 
                                placeholder="Contoh: Layanan Simpanan & Tabungan Terpercaya"
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-1 focus:ring-emerald-500"
                            />
                            @error('slogan_lembaga') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                    Nomor Telepon / WhatsApp
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="telepon_lembaga" 
                                    placeholder="Contoh: 0812-3456-7890"
                                    class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 font-mono focus:ring-1 focus:ring-emerald-500"
                                />
                                @error('telepon_lembaga') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                    Alamat Lokasi / Kantor
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="alamat_lembaga" 
                                    placeholder="Contoh: Jl. Merdeka No. 45, Jakarta Pusat"
                                    class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-1 focus:ring-emerald-500"
                                />
                                @error('alamat_lembaga') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Catatan Kaki Struk Bukti Transaksi (Footer Struk)
                            </label>
                            <textarea 
                                wire:model="pesan_struk" 
                                rows="2"
                                placeholder="Contoh: Simpan struk ini sebagai bukti transaksi resmi."
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-1 focus:ring-emerald-500"
                            ></textarea>
                            @error('pesan_struk') <span class="text-[11px] text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md cursor-pointer active:scale-95 transition-all flex items-center gap-2"
                            >
                                <x-heroicon-s-check class="size-4" />
                                <span>Simpan Pengaturan Lembaga</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right 1 Col: Live Preview -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm transition-colors">
                    <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-3">Live Preview Struk Transaksi</h3>
                    <div class="p-5 rounded-2xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 max-w-sm mx-auto font-mono text-xs text-zinc-800 dark:text-zinc-300 shadow-inner">
                        <div class="text-center pb-3 border-b border-dashed border-zinc-300 dark:border-zinc-700">
                            <h4 class="font-bold text-sm text-zinc-900 dark:text-white uppercase">{{ $nama_lembaga ?: 'Nama Lembaga' }}</h4>
                            <p class="text-[10px] text-zinc-500">{{ $slogan_lembaga ?: 'Layanan Tabungan' }}</p>
                            <p class="text-[10px] text-zinc-500 mt-0.5">{{ $alamat_lembaga ?: 'Alamat Kantor' }} • Telp: {{ $telepon_lembaga ?: '-' }}</p>
                        </div>
                        <div class="py-3 space-y-1.5 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">KODE:</span>
                                <span class="font-bold text-zinc-900 dark:text-zinc-100">TRX-SETOR-2026-0001</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">WAKTU:</span>
                                <span>{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">NASABAH:</span>
                                <span class="font-bold">Budi Santoso</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">MUTASI:</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">+ Rp 500.000</span>
                            </div>
                            <div class="flex justify-between pt-1 border-t border-zinc-200 dark:border-zinc-800 font-bold">
                                <span>SALDO AKHIR:</span>
                                <span>Rp 1.500.000</span>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-dashed border-zinc-300 dark:border-zinc-700 text-center text-[10px] text-zinc-500 italic">
                            {{ $pesan_struk ?: 'Simpan struk ini sebagai bukti transaksi resmi.' }}
                        </div>
                    </div>
                </div>
            </div>
    @endif

    <!-- ======================================================== -->
    <!-- TAB: WHATSAPP GATEWAY NOTIFICATION -->
    <!-- ======================================================== -->
    @if ($activeTab === 'wa')
        <div class="space-y-6 animate-fade-in">
            @if (session('success_wa'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <x-heroicon-s-check-circle class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span class="font-medium">{{ session('success_wa') }}</span>
                    </div>
                    <button type="button" wire:click="openTestWaModal" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-[11px] font-bold cursor-pointer transition-colors shadow-sm">
                        Uji Coba Sekarang &rarr;
                    </button>
                </div>
            @endif

            <form wire:submit="saveWhatsAppSettings" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left 5 Cols: Gateway Driver & Credential -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <x-heroicon-o-chat-bubble-left-right class="size-5" />
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Koneksi Gateway WhatsApp</h2>
                                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Penyedia API dan kredensial pengiriman</p>
                                </div>
                            </div>
                            
                            <!-- Master Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="wa_gateway_enabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-zinc-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <!-- Status Badge -->
                        <div class="p-3.5 rounded-xl border {{ $wa_gateway_enabled ? 'bg-emerald-500/5 border-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-zinc-100 dark:bg-zinc-800/60 border-zinc-200 dark:border-zinc-700/80 text-zinc-600 dark:text-zinc-400' }} flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="size-2 rounded-full {{ $wa_gateway_enabled ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-400' }}"></span>
                                <span class="text-xs font-bold">{{ $wa_gateway_enabled ? 'Gateway WhatsApp AKTIF' : 'Gateway WhatsApp NONAKTIF' }}</span>
                            </div>
                            <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 font-semibold">
                                Driver: {{ strtoupper($wa_provider) }}
                            </span>
                        </div>

                        <!-- Provider Selection -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Provider Gateway <span class="text-emerald-500">*</span>
                            </label>
                            <select 
                                wire:model.live="wa_provider" 
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                            >
                                <option value="mock">🧪 Mock Driver (Simulasi Log / Testing Gratis)</option>
                                <option value="fonnte">⚡ Fonnte (Rekomendasi Indonesia - fonnte.com)</option>
                                <option value="wablas">🌐 Wablas (wablas.com)</option>
                                <option value="custom">🔌 Custom Webhook URL (REST API Sendiri)</option>
                            </select>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-1">
                                @if ($wa_provider === 'mock')
                                    Mode simulasi: Notifikasi dicatat di system log tanpa memerlukan kuota API berbayar.
                                @elseif ($wa_provider === 'fonnte')
                                    Dapatkan token API resmi dari dashboard <a href="https://fonnte.com" target="_blank" class="text-emerald-600 underline">fonnte.com</a>.
                                @elseif ($wa_provider === 'wablas')
                                    Dapatkan API token dan endpoint domain dari <a href="https://wablas.com" target="_blank" class="text-emerald-600 underline">wablas.com</a>.
                                @else
                                    Sistem akan mengirim payload JSON {phone, message} via HTTP POST ke endpoint Anda.
                                @endif
                            </p>
                        </div>

                        <!-- API Token / Auth Key -->
                        @if ($wa_provider !== 'mock')
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                    API Token / Authorization Key <span class="text-emerald-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="wa_api_token" 
                                    placeholder="Contoh: a1b2c3d4e5f6g7h8..."
                                    class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                                />
                                @error('wa_api_token') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Endpoint URL (For Custom / Wablas) -->
                        @if ($wa_provider === 'custom' || $wa_provider === 'wablas')
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                    Endpoint Server URL
                                </label>
                                <input 
                                    type="url" 
                                    wire:model="wa_endpoint_url" 
                                    placeholder="{{ $wa_provider === 'wablas' ? 'https://phone.wablas.com' : 'https://api.domain-anda.com/send' }}"
                                    class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                                />
                                @error('wa_endpoint_url') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Sender / Device ID -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Nomor Sender / Device ID (Opsional)
                            </label>
                            <input 
                                type="text" 
                                wire:model="wa_sender_number" 
                                placeholder="Contoh: 081234567890"
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                            />
                        </div>

                        <!-- Auto Send Toggle -->
                        <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="wa_auto_send" class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 size-4">
                                <div>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white block">Kirim Otomatis Setiap Transaksi Selesai</span>
                                    <span class="text-[11px] text-zinc-500 dark:text-zinc-400">Sistem langsung mengirimkan struk ke nomor HP nasabah sesaat setelah teller memproses setor atau tarik tunai</span>
                                </div>
                            </label>
                        </div>

                        <!-- Test Action Trigger -->
                        <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                            <button 
                                type="button" 
                                wire:click="openTestWaModal"
                                class="w-full py-2.5 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-2 cursor-pointer shadow-sm"
                            >
                                <x-heroicon-o-paper-airplane class="size-4 text-emerald-600 dark:text-emerald-400" />
                                <span>Kirim Pesan Uji Coba (Test)</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right 7 Cols: Message Templates & Live Preview -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors space-y-5">
                        <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                            <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Template Pesan Struk Digital</h2>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Kustomisasi format teks notifikasi WhatsApp yang diterima nasabah</p>
                        </div>

                        <!-- Variables Legend Helper -->
                        <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 space-y-2">
                            <span class="text-[11px] font-bold text-zinc-700 dark:text-zinc-300 block">Variabel Dinamis yang Tersedia:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{nama}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{nomor_nasabah}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{nominal}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{saldo_akhir}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{kode_transaksi}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{tanggal}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{waktu}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{teller}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-mono text-[10px]">{nama_lembaga}</span>
                            </div>
                        </div>

                        <!-- Template Setoran -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5 flex items-center justify-between">
                                <span>Template Notifikasi Setoran Tunai <span class="text-emerald-500">*</span></span>
                                <span class="text-[10px] font-normal text-emerald-600 dark:text-emerald-400">Gunakan tanda *tebal* dan `kode` WhatsApp</span>
                            </label>
                            <textarea 
                                wire:model="wa_template_setor" 
                                rows="6" 
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                            ></textarea>
                            @error('wa_template_setor') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Template Penarikan -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5 flex items-center justify-between">
                                <span>Template Notifikasi Penarikan Tunai <span class="text-emerald-500">*</span></span>
                                <span class="text-[10px] font-normal text-rose-600 dark:text-rose-400">Gunakan tanda *tebal* dan `kode` WhatsApp</span>
                            </label>
                            <textarea 
                                wire:model="wa_template_tarik" 
                                rows="6" 
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                            ></textarea>
                            @error('wa_template_tarik') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-3">
                            <button 
                                type="submit" 
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2 cursor-pointer"
                            >
                                <x-heroicon-s-check class="size-4" />
                                <span>Simpan Pengaturan WhatsApp</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- TAB 2: MANAJEMEN PETUGAS & TELLER -->
    <!-- ======================================================== -->
    @if ($activeTab === 'petugas')
        <div class="space-y-6 animate-fade-in">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-zinc-100 dark:border-zinc-800 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <x-heroicon-o-users class="size-5" />
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Daftar Petugas & Teller</h2>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Kelola akun administrator dan staf teller yang berwenang melayani nasabah</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        wire:click="openCreatePetugasModal"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer self-start sm:self-auto"
                    >
                        <x-heroicon-s-plus class="size-4" />
                        <span>Tambah Petugas Baru</span>
                    </button>
                </div>

                @if (session('success_petugas'))
                    <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                        <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ session('success_petugas') }}</span>
                    </div>
                @endif

                @if (session('error_petugas'))
                    <div class="mb-4 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs flex items-center gap-2">
                        <x-heroicon-s-exclamation-triangle class="size-4 shrink-0 text-rose-600 dark:text-rose-400" />
                        <span>{{ session('error_petugas') }}</span>
                    </div>
                @endif

                <!-- Table Petugas -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold">
                                <th class="pb-3 px-3">Nama Petugas</th>
                                <th class="pb-3 px-3">Email Login</th>
                                <th class="pb-3 px-3 text-center">Peran / Role</th>
                                <th class="pb-3 px-3 text-center">Status</th>
                                <th class="pb-3 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($petugasList as $p)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                    <td class="py-3.5 px-3 font-semibold text-zinc-900 dark:text-white">
                                        <div class="flex items-center gap-2.5">
                                            <div class="size-7 rounded-full bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center font-bold text-[11px]">
                                                {{ strtoupper(substr($p->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span>{{ $p->name }}</span>
                                                @if ($p->id === Auth::guard('web')->id())
                                                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">Anda</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-3 font-mono text-zinc-600 dark:text-zinc-400">
                                        {{ $p->email }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        @if ($p->role === 'admin')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-700 dark:text-purple-300 border border-purple-500/30">
                                                <x-heroicon-s-shield-check class="size-3 text-purple-600 dark:text-purple-400" />
                                                <span>Admin Utama</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                                <x-heroicon-s-banknotes class="size-3 text-emerald-600 dark:text-emerald-400" />
                                                <span>Petugas Teller</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        @if ($p->id === Auth::guard('web')->id())
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Aktif</span>
                                            </span>
                                        @else
                                            <button 
                                                type="button" 
                                                wire:click="togglePetugasStatus({{ $p->id }})"
                                                title="Klik untuk ubah status akun petugas"
                                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold cursor-pointer transition-colors {{ $p->status === 'aktif' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-500 border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-200' }}"
                                            >
                                                <span class="size-1.5 rounded-full {{ $p->status === 'aktif' ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                                <span>{{ ucfirst($p->status ?? 'aktif') }}</span>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button 
                                                type="button" 
                                                wire:click="openEditPetugasModal({{ $p->id }})"
                                                title="Edit Petugas"
                                                class="p-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors cursor-pointer"
                                            >
                                                <x-heroicon-o-pencil-square class="size-3.5" />
                                            </button>

                                            @if ($p->id !== Auth::guard('web')->id())
                                                <button 
                                                    type="button" 
                                                    wire:click="openDeletePetugasModal({{ $p->id }})"
                                                    title="Hapus Petugas"
                                                    class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 hover:text-white dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white transition-colors cursor-pointer"
                                                >
                                                    <x-heroicon-o-trash class="size-3.5" />
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- TAB 3: AKUN SAYA & KEAMANAN -->
    <!-- ======================================================== -->
    @if ($activeTab === 'keamanan')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in">
            <!-- Profil Saya -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors">
                <div class="flex items-center gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
                    <div class="size-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <x-heroicon-o-user class="size-5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Profil Petugas Login</h2>
                        <p class="text-[11px] text-zinc-500">Perbarui identitas profil administrator Anda</p>
                    </div>
                </div>

                @if (session('success_profile'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                        <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ session('success_profile') }}</span>
                    </div>
                @endif

                <form wire:submit="updateAdminProfile" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nama Petugas <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="admin_name" 
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('admin_name') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Email Login <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="admin_email" 
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('admin_email') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2.5 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-white font-bold text-xs rounded-xl shadow-sm cursor-pointer transition-colors"
                    >
                        Perbarui Profil Saya
                    </button>
                </form>
            </div>

            <!-- Ganti Password -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm transition-colors">
                <div class="flex items-center gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
                    <div class="size-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <x-heroicon-o-key class="size-5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Ganti Password Akun</h2>
                        <p class="text-[11px] text-zinc-500">Perbarui kata sandi untuk mengamankan akses sistem</p>
                    </div>
                </div>

                @if (session('success_password'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                        <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ session('success_password') }}</span>
                    </div>
                @endif

                <form wire:submit="updatePassword" class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Password Saat Ini <span class="text-amber-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="current_password" 
                            placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-amber-500"
                        />
                        @error('current_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Password Baru <span class="text-amber-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="new_password" 
                            placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-amber-500"
                        />
                        @error('new_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Konfirmasi Password Baru <span class="text-amber-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="new_password_confirmation" 
                            placeholder="Ulangi password baru"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-amber-500"
                        />
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs rounded-xl shadow-md cursor-pointer transition-colors"
                    >
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- MODAL CREATE PETUGAS -->
    <!-- ======================================================== -->
    @if ($showCreatePetugasModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tambah Petugas / Teller Baru</h3>
                        <p class="text-xs text-zinc-500">Berikan akses ke sistem pencatatan tabungan</p>
                    </div>
                    <button type="button" wire:click="closeCreatePetugasModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1 rounded-lg">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="savePetugas" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Nama Lengkap Petugas <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="petugas_name" 
                            placeholder="Contoh: Rina Anggraini"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('petugas_name') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Email Login <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="petugas_email" 
                            placeholder="Contoh: rina.teller@tabungan.test"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('petugas_email') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Peran / Role Akses <span class="text-emerald-500">*</span>
                        </label>
                        <select 
                            wire:model="petugas_role" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        >
                            <option value="teller">Petugas Teller (Setor, Tarik, Mutasi)</option>
                            <option value="admin">Administrator (Akses Penuh)</option>
                        </select>
                        @error('petugas_role') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                                Password <span class="text-emerald-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                wire:model="petugas_password" 
                                placeholder="Minimal 6 karakter"
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                            />
                            @error('petugas_password') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                                Konfirmasi Password <span class="text-emerald-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                wire:model="petugas_password_confirmation" 
                                placeholder="Ulangi password"
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                            />
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeCreatePetugasModal"
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md cursor-pointer"
                        >
                            Simpan Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- MODAL EDIT PETUGAS -->
    <!-- ======================================================== -->
    @if ($showEditPetugasModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Edit Data Petugas</h3>
                        <p class="text-xs text-zinc-500">Perbarui informasi dan hak akses akun petugas</p>
                    </div>
                    <button type="button" wire:click="closeEditPetugasModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1 rounded-lg">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="updatePetugas" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Nama Lengkap Petugas <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="petugas_name" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('petugas_name') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Email Login <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="petugas_email" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('petugas_email') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                                Peran / Role Akses
                            </label>
                            <select 
                                wire:model="petugas_role" 
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                            >
                                <option value="teller">Petugas Teller</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                                Status Akun
                            </label>
                            <select 
                                wire:model="petugas_status" 
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                            >
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Reset Password (Optional) -->
                    <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 space-y-3">
                        <span class="text-[11px] font-bold text-zinc-700 dark:text-zinc-300 block">Reset Password (Kosongkan jika tidak ingin mengubah):</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <input 
                                    type="password" 
                                    wire:model="petugas_password" 
                                    placeholder="Password baru (opsional)"
                                    class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                                />
                                @error('petugas_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input 
                                    type="password" 
                                    wire:model="petugas_password_confirmation" 
                                    placeholder="Ulangi password baru"
                                    class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-emerald-500"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeEditPetugasModal"
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md cursor-pointer"
                        >
                            Perbarui Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- MODAL DELETE PETUGAS -->
    <!-- ======================================================== -->
    @if ($showDeletePetugasModal && $deletePetugas)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100 p-6 text-center">
                <div class="size-12 rounded-2xl bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3.5 border border-rose-500/30">
                    <x-heroicon-o-trash class="size-6" />
                </div>
                <h3 class="text-base font-extrabold text-zinc-900 dark:text-white">Hapus Akun Petugas?</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    Apakah Anda yakin ingin menghapus akun petugas <strong>{{ $deletePetugas->name }}</strong> ({{ $deletePetugas->email }})?
                </p>

                <div class="mt-6 flex items-center gap-3">
                    <button 
                        type="button" 
                        wire:click="closeDeletePetugasModal" 
                        class="w-1/2 py-2.5 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="confirmDeletePetugas" 
                        class="w-1/2 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/30 transition-colors cursor-pointer"
                    >
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- MODAL UJI COBA WHATSAPP GATEWAY -->
    <!-- ======================================================== -->
    @if ($showTestWaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl animate-scale-in text-zinc-900 dark:text-zinc-100">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <x-heroicon-o-paper-airplane class="size-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Uji Coba Kirim WhatsApp</h3>
                            <p class="text-xs text-zinc-500">Kirim pesan uji coba untuk memvalidasi konfigurasi gateway</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeTestWaModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white p-1 rounded-lg">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="sendTestWhatsApp" class="p-6 space-y-4">
                    @if ($test_wa_result)
                        <div class="p-4 rounded-xl border {{ $test_wa_success ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200' : 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200' }} text-xs flex items-start gap-2.5">
                            @if ($test_wa_success)
                                <x-heroicon-s-check-circle class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
                            @else
                                <x-heroicon-s-x-circle class="size-5 shrink-0 text-rose-600 dark:text-rose-400" />
                            @endif
                            <div class="space-y-1">
                                <span class="font-bold block">{{ $test_wa_success ? 'Pesan Terkirim / Berhasil!' : 'Gagal Mengirim Pesan' }}</span>
                                <p class="text-[11px] font-mono leading-relaxed">{{ $test_wa_result }}</p>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nomor WhatsApp Tujuan <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="test_wa_phone" 
                            placeholder="Contoh: 081298765432 atau 6281298765432"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('test_wa_phone') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Isi Pesan Uji Coba <span class="text-emerald-500">*</span>
                        </label>
                        <textarea 
                            wire:model="test_wa_message" 
                            rows="4" 
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        ></textarea>
                        @error('test_wa_message') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeTestWaModal"
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer"
                        >
                            Tutup
                        </button>
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md cursor-pointer flex items-center gap-2"
                        >
                            <span wire:loading.remove>Kirim Pesan Uji Coba</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Mengirim...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
