<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Pengaturan & Manajemen Sistem</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Konfigurasi profil lembaga, akun petugas teller, WhatsApp gateway, dan keamanan akun</p>
        </div>
        
        <!-- Navigation Tabs -->
        <div class="flex items-center gap-1 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs self-start sm:self-auto overflow-x-auto max-w-full">
            <button 
                type="button" 
                wire:click="setTab('lembaga')"
                class="px-3 py-1.5 rounded-lg font-semibold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'lembaga' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-building-office-2 class="size-3.5" />
                <span>Profil Lembaga</span>
            </button>
            <button 
                type="button" 
                wire:click="setTab('petugas')"
                class="px-3 py-1.5 rounded-lg font-semibold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'petugas' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-users class="size-3.5" />
                <span>Petugas & Teller</span>
            </button>
            <button 
                type="button" 
                wire:click="setTab('wa')"
                class="px-3 py-1.5 rounded-lg font-semibold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'wa' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-chat-bubble-left-right class="size-3.5" />
                <span>WhatsApp Gateway</span>
            </button>
            <button 
                type="button" 
                wire:click="setTab('keamanan')"
                class="px-3 py-1.5 rounded-lg font-semibold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'keamanan' ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <x-heroicon-o-key class="size-3.5" />
                <span>Akun & Password</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: PROFIL LEMBAGA & STRUK -->
    @if ($activeTab === 'lembaga')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Form Lembaga -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 space-y-4">
                    <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                        <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Profil Lembaga / Instansi</h2>
                        <p class="text-[11px] text-zinc-500">Informasi ini dicantumkan pada header struk dan laporan mutasi kas</p>
                    </div>

                    @if (session('success_institution'))
                        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
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
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                            @error('nama_lembaga') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Slogan / Tagline
                            </label>
                            <input 
                                type="text" 
                                wire:model="slogan_lembaga" 
                                placeholder="Contoh: Layanan Simpanan & Tabungan Terpercaya"
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                            @error('slogan_lembaga') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
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
                                    class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                                />
                                @error('telepon_lembaga') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                    Alamat Lokasi / Kantor
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="alamat_lembaga" 
                                    placeholder="Contoh: Jl. Merdeka No. 45, Jakarta Pusat"
                                    class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                                />
                                @error('alamat_lembaga') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Catatan Kaki Struk Bukti Transaksi (Footer)
                            </label>
                            <textarea 
                                wire:model="pesan_struk" 
                                rows="2"
                                placeholder="Contoh: Simpan struk ini sebagai bukti transaksi resmi."
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            ></textarea>
                            @error('pesan_struk') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl cursor-pointer transition-colors flex items-center gap-1.5"
                            >
                                <x-heroicon-s-check class="size-4" />
                                <span>Simpan Pengaturan Lembaga</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right 1 Col: Live Preview Struk -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
                    <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-3">Pratinjau Struk Transaksi</h3>
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono text-xs text-zinc-800 dark:text-zinc-300">
                        <div class="text-center pb-2.5 border-b border-dashed border-zinc-300 dark:border-zinc-700">
                            <h4 class="font-bold text-xs text-zinc-900 dark:text-white uppercase">{{ $nama_lembaga ?: 'Nama Lembaga' }}</h4>
                            <p class="text-[10px] text-zinc-500">{{ $slogan_lembaga ?: 'Layanan Tabungan' }}</p>
                            <p class="text-[9px] text-zinc-400 mt-0.5">{{ $alamat_lembaga ?: 'Alamat Kantor' }} • Telp: {{ $telepon_lembaga ?: '-' }}</p>
                        </div>
                        <div class="py-2.5 space-y-1 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">KODE:</span>
                                <span class="font-semibold text-zinc-900 dark:text-zinc-100">TRX-SETOR-2026-0001</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">WAKTU:</span>
                                <span>{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">NASABAH:</span>
                                <span class="font-semibold">Budi Santoso</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">MUTASI:</span>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">+ Rp 500.000</span>
                            </div>
                            <div class="flex justify-between pt-1 border-t border-zinc-200 dark:border-zinc-800 font-semibold">
                                <span>SALDO AKHIR:</span>
                                <span>Rp 1.500.000</span>
                            </div>
                        </div>
                        <div class="pt-2.5 border-t border-dashed border-zinc-300 dark:border-zinc-700 text-center text-[10px] text-zinc-400 italic">
                            {{ $pesan_struk ?: 'Simpan struk ini sebagai bukti transaksi resmi.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 2: MANAJEMEN PETUGAS & TELLER -->
    @if ($activeTab === 'petugas')
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Daftar Petugas & Teller</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Kelola akun administrator dan staf teller kasir</p>
                </div>

                <button 
                    type="button" 
                    wire:click="openCreatePetugasModal"
                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer self-start sm:self-auto"
                >
                    <x-heroicon-s-plus class="size-4" />
                    <span>Tambah Petugas Baru</span>
                </button>
            </div>

            @if (session('success_petugas'))
                <div class="m-5 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                    <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                    <span>{{ session('success_petugas') }}</span>
                </div>
            @endif

            @if (session('error_petugas'))
                <div class="m-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs flex items-center gap-2">
                    <x-heroicon-s-exclamation-triangle class="size-4 shrink-0 text-rose-600 dark:text-rose-400" />
                    <span>{{ session('error_petugas') }}</span>
                </div>
            @endif

            <!-- Table Petugas -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 text-zinc-500 dark:text-zinc-400 font-semibold">
                            <th class="py-3 px-4">Nama Petugas</th>
                            <th class="py-3 px-4">Email Login</th>
                            <th class="py-3 px-4 text-center">Peran</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/70">
                        @foreach ($petugasList as $p)
                            <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-4 font-semibold text-zinc-900 dark:text-white">
                                    <div class="flex items-center gap-2.5">
                                        <div class="size-7 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 flex items-center justify-center font-bold text-[11px]">
                                            {{ strtoupper(substr($p->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span>{{ $p->name }}</span>
                                            @if ($p->id === Auth::guard('web')->id())
                                                <span class="ml-1.5 px-1.5 py-0.5 rounded text-[9px] font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-mono text-zinc-500">
                                    {{ $p->email }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($p->role === 'admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                            Teller
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($p->id === Auth::guard('web')->id())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <button 
                                            type="button" 
                                            wire:click="togglePetugasStatus({{ $p->id }})"
                                            title="Klik untuk ubah status akun petugas"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-medium cursor-pointer transition-colors {{ $p->status === 'aktif' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-500 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200' }}"
                                        >
                                            <span class="size-1.5 rounded-full {{ $p->status === 'aktif' ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                            <span>{{ ucfirst($p->status ?? 'aktif') }}</span>
                                        </button>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1">
                                        <button 
                                            type="button" 
                                            wire:click="openEditPetugasModal({{ $p->id }})"
                                            title="Edit Petugas"
                                            class="p-1.5 rounded-lg text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                                        >
                                            <x-heroicon-o-pencil-square class="size-4" />
                                        </button>

                                        @if ($p->id !== Auth::guard('web')->id())
                                            <button 
                                                type="button" 
                                                wire:click="openDeletePetugasModal({{ $p->id }})"
                                                title="Hapus Petugas"
                                                class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                            >
                                                <x-heroicon-o-trash class="size-4" />
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
    @endif

    <!-- TAB 3: WHATSAPP GATEWAY NOTIFICATION -->
    @if ($activeTab === 'wa')
        <div class="space-y-6">
            @if (session('success_wa'))
                <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span class="font-medium">{{ session('success_wa') }}</span>
                    </div>
                    <button type="button" wire:click="openTestWaModal" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-[11px] font-semibold cursor-pointer transition-colors">
                        Uji Coba Sekarang &rarr;
                    </button>
                </div>
            @endif

            <form wire:submit="saveWhatsAppSettings" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left 5 Cols: Gateway Configuration -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                            <div>
                                <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Koneksi Gateway WhatsApp</h2>
                                <p class="text-[11px] text-zinc-500">Penyedia API & kredensial pengiriman notifikasi</p>
                            </div>
                            
                            <!-- Master Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="wa_gateway_enabled" class="sr-only peer">
                                <div class="w-10 h-5.5 bg-zinc-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4.5 after:w-4.5 after:transition-all dark:border-zinc-600 peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <!-- Status Badge -->
                        <div class="p-3 rounded-xl border {{ $wa_gateway_enabled ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-200' : 'bg-zinc-50 dark:bg-zinc-950 border-zinc-200 dark:border-zinc-800 text-zinc-500' }} flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="size-2 rounded-full {{ $wa_gateway_enabled ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                <span class="font-semibold">{{ $wa_gateway_enabled ? 'Gateway WhatsApp Aktif' : 'Gateway WhatsApp Nonaktif' }}</span>
                            </div>
                            <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                                {{ strtoupper($wa_provider) }}
                            </span>
                        </div>

                        <!-- Provider Selection -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Provider Gateway <span class="text-emerald-500">*</span>
                            </label>
                            <select 
                                wire:model.live="wa_provider" 
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                            >
                                <option value="mock">Mock Driver (Simulasi Log / Gratis)</option>
                                <option value="fonnte">Fonnte (Rekomendasi Indonesia - fonnte.com)</option>
                                <option value="wablas">Wablas (wablas.com)</option>
                                <option value="custom">Custom Webhook URL (REST API)</option>
                            </select>
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
                                    class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
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
                                    class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
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
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                        </div>

                        <!-- Auto Send Toggle -->
                        <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <label class="flex items-start gap-2.5 cursor-pointer">
                                <input type="checkbox" wire:model="wa_auto_send" class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 size-4">
                                <div>
                                    <span class="text-xs font-semibold text-zinc-900 dark:text-white block">Kirim Otomatis Setiap Transaksi</span>
                                    <span class="text-[11px] text-zinc-400">Kirim struk otomatis ke nomor HP nasabah sesaat setelah setor/tarik tunai</span>
                                </div>
                            </label>
                        </div>

                        <!-- Test Action Trigger -->
                        <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <button 
                                type="button" 
                                wire:click="openTestWaModal"
                                class="w-full py-2 px-3 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <x-heroicon-o-paper-airplane class="size-4 text-emerald-600 dark:text-emerald-400" />
                                <span>Kirim Pesan Uji Coba</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right 7 Cols: Message Templates -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 space-y-4">
                        <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                            <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Template Pesan Notifikasi</h2>
                            <p class="text-[11px] text-zinc-500">Format teks notifikasi WhatsApp yang dikirimkan ke nasabah</p>
                        </div>

                        <!-- Variables Legend -->
                        <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 space-y-1.5">
                            <span class="text-[10px] uppercase font-semibold text-zinc-400 tracking-wider block">Variabel Tersedia:</span>
                            <div class="flex flex-wrap gap-1 font-mono text-[10px]">
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{nama}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{nomor_nasabah}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{nominal}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{saldo_akhir}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{kode_transaksi}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{tanggal}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{waktu}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{teller}</span>
                                <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded">{nama_lembaga}</span>
                            </div>
                        </div>

                        <!-- Template Setoran -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5 flex items-center justify-between">
                                <span>Template Setor Tunai <span class="text-emerald-500">*</span></span>
                            </label>
                            <textarea 
                                wire:model="wa_template_setor" 
                                rows="5" 
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            ></textarea>
                            @error('wa_template_setor') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Template Penarikan -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5 flex items-center justify-between">
                                <span>Template Tarik Tunai <span class="text-emerald-500">*</span></span>
                            </label>
                            <textarea 
                                wire:model="wa_template_tarik" 
                                rows="5" 
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            ></textarea>
                            @error('wa_template_tarik') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button 
                                type="submit" 
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer"
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

    <!-- TAB 4: AKUN SAYA & KEAMANAN -->
    @if ($activeTab === 'keamanan')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Profil Saya -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 space-y-4">
                <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Profil Petugas Login</h2>
                    <p class="text-[11px] text-zinc-500">Perbarui identitas profil administrator Anda</p>
                </div>

                @if (session('success_profile'))
                    <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
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
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
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
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('admin_email') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2 bg-zinc-900 hover:bg-zinc-800 dark:bg-zinc-100 dark:hover:bg-white text-white dark:text-zinc-900 font-semibold text-xs rounded-xl cursor-pointer transition-colors"
                    >
                        Perbarui Profil Saya
                    </button>
                </form>
            </div>

            <!-- Ganti Password -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 space-y-4">
                <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Ganti Password Akun</h2>
                    <p class="text-[11px] text-zinc-500">Perbarui kata sandi untuk mengamankan akses sistem</p>
                </div>

                @if (session('success_password'))
                    <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                        <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ session('success_password') }}</span>
                    </div>
                @endif

                <form wire:submit="updatePassword" class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Password Saat Ini <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="current_password" 
                            placeholder="••••••••"
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('current_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Password Baru <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="new_password" 
                            placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('new_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Konfirmasi Password Baru <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="new_password_confirmation" 
                            placeholder="Ulangi password baru"
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl cursor-pointer transition-colors"
                    >
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL CREATE PETUGAS -->
    @if ($showCreatePetugasModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Tambah Petugas Baru</h3>
                        <p class="text-xs text-zinc-500">Berikan akses ke sistem pencatatan kasir tabungan</p>
                    </div>
                    <button type="button" wire:click="closeCreatePetugasModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg cursor-pointer">
                        <x-heroicon-o-x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="savePetugas" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nama Lengkap Petugas <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="petugas_name" 
                            placeholder="Contoh: Rina Anggraini"
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('petugas_name') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Email Login <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="petugas_email" 
                            placeholder="Contoh: rina.teller@tabungan.test"
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('petugas_email') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Peran / Role Akses <span class="text-emerald-500">*</span>
                        </label>
                        <select 
                            wire:model="petugas_role" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                        >
                            <option value="teller">Petugas Teller (Setor, Tarik, Mutasi)</option>
                            <option value="admin">Administrator (Akses Penuh)</option>
                        </select>
                        @error('petugas_role') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Password <span class="text-emerald-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                wire:model="petugas_password" 
                                placeholder="Minimal 6 karakter"
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                            @error('petugas_password') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Konfirmasi Password <span class="text-emerald-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                wire:model="petugas_password_confirmation" 
                                placeholder="Ulangi password"
                                class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                            />
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeCreatePetugasModal"
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl cursor-pointer transition-colors"
                        >
                            Simpan Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL EDIT PETUGAS -->
    @if ($showEditPetugasModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Edit Data Petugas</h3>
                        <p class="text-xs text-zinc-500">Perbarui informasi dan hak akses akun petugas</p>
                    </div>
                    <button type="button" wire:click="closeEditPetugasModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg cursor-pointer">
                        <x-heroicon-o-x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="updatePetugas" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nama Lengkap Petugas <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="petugas_name" 
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('petugas_name') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Email Login <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="petugas_email" 
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        />
                        @error('petugas_email') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Peran
                            </label>
                            <select 
                                wire:model="petugas_role" 
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                            >
                                <option value="teller">Petugas Teller</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Status Akun
                            </label>
                            <select 
                                wire:model="petugas_status" 
                                class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                            >
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Reset Password (Optional) -->
                    <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 space-y-2">
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 block">Reset Password (Kosongkan jika tidak ingin mengubah):</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <input 
                                    type="password" 
                                    wire:model="petugas_password" 
                                    placeholder="Password baru (opsional)"
                                    class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500"
                                />
                                @error('petugas_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input 
                                    type="password" 
                                    wire:model="petugas_password_confirmation" 
                                    placeholder="Ulangi password baru"
                                    class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-emerald-500"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeEditPetugasModal"
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl cursor-pointer transition-colors"
                        >
                            Perbarui Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL DELETE PETUGAS -->
    @if ($showDeletePetugasModal && $deletePetugas)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-sm overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100 p-6 text-center">
                <div class="size-12 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3.5 border border-rose-200 dark:border-rose-900">
                    <x-heroicon-o-trash class="size-6" />
                </div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Hapus Akun Petugas?</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    Apakah Anda yakin ingin menghapus akun petugas <strong>{{ $deletePetugas->name }}</strong> ({{ $deletePetugas->email }})?
                </p>

                <div class="mt-5 flex items-center gap-2.5">
                    <button 
                        type="button" 
                        wire:click="closeDeletePetugasModal" 
                        class="flex-1 py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="confirmDeletePetugas" 
                        class="flex-1 py-2 px-4 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl transition-colors cursor-pointer"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL UJI COBA WHATSAPP GATEWAY -->
    @if ($showTestWaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Uji Coba Kirim WhatsApp</h3>
                        <p class="text-xs text-zinc-500">Kirim pesan uji coba untuk memvalidasi konfigurasi gateway</p>
                    </div>
                    <button type="button" wire:click="closeTestWaModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg cursor-pointer">
                        <x-heroicon-o-x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="sendTestWhatsApp" class="p-6 space-y-4">
                    @if ($test_wa_result)
                        <div class="p-3.5 rounded-xl border {{ $test_wa_success ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200' : 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200' }} text-xs flex items-start gap-2">
                            @if ($test_wa_success)
                                <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" />
                            @else
                                <x-heroicon-s-x-circle class="size-4 shrink-0 text-rose-600 dark:text-rose-400 mt-0.5" />
                            @endif
                            <div class="space-y-0.5">
                                <span class="font-semibold block">{{ $test_wa_success ? 'Pesan Terkirim Berhasil!' : 'Gagal Mengirim Pesan' }}</span>
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
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
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
                            class="w-full px-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs font-mono text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                        ></textarea>
                        @error('test_wa_message') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="closeTestWaModal"
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl cursor-pointer transition-colors"
                        >
                            Tutup
                        </button>
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl cursor-pointer flex items-center gap-2 transition-colors"
                        >
                            <span wire:loading.remove>Kirim Pesan</span>
                            <span wire:loading class="flex items-center gap-1.5">
                                <svg class="animate-spin size-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Mengirim...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
