<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-2xl shadow-sm transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white tracking-tight">Pengaturan Sistem</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Konfigurasi profil lembaga, format struk bukti transaksi, dan keamanan akun petugas</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Sistem Siap Digunakan</span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Institution Settings & Receipt Preview -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Institution Settings Card -->
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
                            placeholder="Contoh: Simpan struk ini sebagai bukti transaksi sah."
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

            <!-- Live Receipt Preview -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm transition-colors">
                <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider mb-3">Live Preview Format Struk Transaksi</h3>
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

        <!-- Right 1 Col: Admin Profile & Change Password -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Admin Profile -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm transition-colors">
                <div class="flex items-center gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
                    <div class="size-8 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <x-heroicon-o-user class="size-4" />
                    </div>
                    <div>
                        <h2 class="text-xs font-bold text-zinc-900 dark:text-white">Profil Petugas</h2>
                        <p class="text-[10px] text-zinc-500">Data akun administrator login</p>
                    </div>
                </div>

                @if (session('success_profile'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                        <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ session('success_profile') }}</span>
                    </div>
                @endif

                <form wire:submit="updateAdminProfile" class="space-y-3.5">
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Nama Petugas <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="admin_name" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('admin_name') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Email Login <span class="text-emerald-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="admin_email" 
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-1 focus:ring-emerald-500"
                        />
                        @error('admin_email') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2 bg-zinc-800 hover:bg-zinc-700 text-white font-semibold text-xs rounded-xl shadow-sm cursor-pointer transition-colors"
                    >
                        Perbarui Profil
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm transition-colors">
                <div class="flex items-center gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
                    <div class="size-8 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <x-heroicon-o-key class="size-4" />
                    </div>
                    <div>
                        <h2 class="text-xs font-bold text-zinc-900 dark:text-white">Ganti Password</h2>
                        <p class="text-[10px] text-zinc-500">Amankan akses portal teller</p>
                    </div>
                </div>

                @if (session('success_password'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2">
                        <x-heroicon-s-check-circle class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ session('success_password') }}</span>
                    </div>
                @endif

                <form wire:submit="updatePassword" class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Password Saat Ini <span class="text-amber-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="current_password" 
                            placeholder="••••••••"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-amber-500"
                        />
                        @error('current_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Password Baru <span class="text-amber-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="new_password" 
                            placeholder="Minimal 6 karakter"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-amber-500"
                        />
                        @error('new_password') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 mb-1">
                            Konfirmasi Password Baru <span class="text-amber-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            wire:model="new_password_confirmation" 
                            placeholder="Ulangi password baru"
                            class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-xl text-xs text-zinc-900 dark:text-white font-mono focus:ring-1 focus:ring-amber-500"
                        />
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs rounded-xl shadow-md cursor-pointer transition-colors"
                    >
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
