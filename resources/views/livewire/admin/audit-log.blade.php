<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Audit Trail & Log Aktivitas</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Pencatatan rekam jejak aktivitas sensitif, transaksi, login, dan audit keamanan sistem</p>
        </div>

        <button 
            type="button" 
            wire:click="exportCsv" 
            class="px-3.5 py-2 bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 font-semibold text-xs rounded-xl flex items-center gap-2 cursor-pointer self-start sm:self-auto transition-colors"
        >
            <x-heroicon-o-arrow-down-tray class="size-4" />
            <span>Ekspor Log (CSV)</span>
        </button>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 flex items-center gap-3.5">
            <div class="size-9 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-600 dark:text-zinc-300">
                <x-heroicon-o-document-text class="size-5" />
            </div>
            <div>
                <span class="text-[11px] text-zinc-500 font-medium block">Total Aktivitas</span>
                <span class="text-lg font-bold text-zinc-900 dark:text-white font-mono tabular-nums">{{ number_format($totalLogs, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 flex items-center gap-3.5">
            <div class="size-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <x-heroicon-o-user-group class="size-5" />
            </div>
            <div>
                <span class="text-[11px] text-zinc-500 font-medium block">Aktivitas Petugas</span>
                <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400 font-mono tabular-nums">{{ number_format($totalPetugasAction, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 flex items-center gap-3.5">
            <div class="size-9 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-600 dark:text-zinc-300">
                <x-heroicon-o-device-phone-mobile class="size-5" />
            </div>
            <div>
                <span class="text-[11px] text-zinc-500 font-medium block">Aktivitas Nasabah</span>
                <span class="text-lg font-bold text-zinc-900 dark:text-white font-mono tabular-nums">{{ number_format($totalNasabahAction, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Keyword -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <x-heroicon-o-magnifying-glass class="size-4" />
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari user, aksi, IP, atau deskripsi..." 
                    class="w-full pl-9 pr-3.5 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20"
                />
            </div>

            <!-- Filter Action Type -->
            <div>
                <select 
                    wire:model.live="action" 
                    class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                >
                    <option value="">Semua Aksi</option>
                    <option value="login">Login Masuk</option>
                    <option value="logout">Logout Keluar</option>
                    <option value="setor_tunai">Setor Tunai</option>
                    <option value="tarik_tunai">Tarik Tunai</option>
                    <option value="tambah_nasabah">Tambah Nasabah</option>
                    <option value="edit_nasabah">Edit Nasabah</option>
                    <option value="bekukan_nasabah">Bekukan Rekening</option>
                    <option value="aktifkan_nasabah">Buka Blokir</option>
                    <option value="target_tabungan">Target Impian</option>
                    <option value="update_pengaturan">Pengaturan Sistem</option>
                </select>
            </div>

            <!-- Filter User Type -->
            <div>
                <select 
                    wire:model.live="userType" 
                    class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 cursor-pointer"
                >
                    <option value="">Semua Pengguna</option>
                    <option value="petugas">Petugas / Teller</option>
                    <option value="nasabah">Nasabah Portal</option>
                    <option value="system">Sistem Otomatis</option>
                </select>
            </div>

            <!-- Reset Filter Button -->
            <div>
                <button 
                    type="button" 
                    wire:click="resetFilter" 
                    class="w-full py-2 px-3 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold rounded-xl transition-colors cursor-pointer text-center"
                >
                    Reset Filter
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-zinc-400 text-[11px] whitespace-nowrap">Dari:</span>
                <input 
                    type="date" 
                    wire:model.live="startDate" 
                    class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono"
                />
            </div>
            <div class="flex items-center gap-2">
                <span class="text-zinc-400 text-[11px] whitespace-nowrap">Sampai:</span>
                <input 
                    type="date" 
                    wire:model.live="endDate" 
                    class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-mono"
                />
            </div>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
        @if ($logs->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="size-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-3 text-zinc-400 dark:text-zinc-500">
                    <x-heroicon-o-shield-check class="size-6" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Tidak ada log aktivitas</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">Tidak ditemukan riwayat log aktivitas yang sesuai dengan filter pencarian.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 text-zinc-500 dark:text-zinc-400 font-semibold">
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Pengguna</th>
                            <th class="py-3 px-4">Aksi / Event</th>
                            <th class="py-3 px-4">Deskripsi Aktivitas</th>
                            <th class="py-3 px-4">IP Address</th>
                            <th class="py-3 px-4 text-center">Rincian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800/70">
                        @foreach ($logs as $log)
                            <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="py-3 px-4 font-mono text-zinc-500 whitespace-nowrap text-[11px]">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-medium uppercase {{ $log->user_type === 'petugas' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300' : ($log->user_type === 'nasabah' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300' : 'bg-zinc-100 text-zinc-500') }}">
                                            {{ $log->user_type }}
                                        </span>
                                        <span class="font-semibold text-zinc-900 dark:text-white truncate max-w-[130px]">{{ $log->user_name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-mono font-medium text-[11px] text-zinc-800 dark:text-zinc-200">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-zinc-600 dark:text-zinc-400 max-w-xs truncate">
                                    {{ $log->description }}
                                </td>
                                <td class="py-3 px-4 font-mono text-[11px] text-zinc-400 whitespace-nowrap">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button 
                                        type="button" 
                                        wire:click="showLogDetail({{ $log->id }})"
                                        class="p-1.5 rounded-lg text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                                        title="Lihat Detail Payload"
                                    >
                                        <x-heroicon-o-eye class="size-4" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($logs->hasPages())
                <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- DETAIL PAYLOAD MODAL -->
    @if ($showDetailModal && $selectedLog)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-xl text-zinc-900 dark:text-zinc-100">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Rincian Audit Log #{{ $selectedLog->id }}</h3>
                        <p class="text-xs text-zinc-500 font-mono">{{ $selectedLog->created_at->translatedFormat('d F Y H:i:s') }}</p>
                    </div>
                    <button type="button" wire:click="closeDetailModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg cursor-pointer">
                        <x-heroicon-o-x-mark class="size-5" />
                    </button>
                </div>

                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-3 bg-zinc-50 dark:bg-zinc-950 p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-800">
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-medium">Pengguna:</span>
                            <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $selectedLog->user_name }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-medium">Event:</span>
                            <span class="font-mono font-semibold text-zinc-800 dark:text-zinc-200">{{ $selectedLog->action }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-medium">IP Address:</span>
                            <span class="font-mono text-zinc-600 dark:text-zinc-400">{{ $selectedLog->ip_address ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-medium">Tipe:</span>
                            <span class="uppercase font-semibold text-zinc-700 dark:text-zinc-300">{{ $selectedLog->user_type }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-zinc-500 block mb-1 font-medium">Deskripsi:</span>
                        <p class="p-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl leading-relaxed text-zinc-800 dark:text-zinc-200">
                            {{ $selectedLog->description }}
                        </p>
                    </div>

                    @if ($selectedLog->user_agent)
                        <div>
                            <span class="text-zinc-500 block mb-1 font-medium">Perangkat / User Agent:</span>
                            <p class="p-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl font-mono text-[10px] text-zinc-500 break-all">
                                {{ $selectedLog->user_agent }}
                            </p>
                        </div>
                    @endif

                    @if ($selectedLog->properties)
                        <div>
                            <span class="text-zinc-500 block mb-1 font-medium">JSON Payload:</span>
                            <pre class="p-3 bg-zinc-950 text-emerald-400 rounded-xl font-mono text-[10px] overflow-x-auto border border-zinc-800 max-h-48">{{ json_encode($selectedLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                        <button 
                            type="button" 
                            wire:click="closeDetailModal"
                            class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 font-semibold text-xs rounded-xl cursor-pointer transition-colors"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
