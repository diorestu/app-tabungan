<div class="max-w-xl mx-auto py-4 sm:py-8 px-4">
    @if ($isValid && $transaksi)
        <!-- VALID CERTIFICATE CARD -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl overflow-hidden shadow-2xl transition-colors relative">
            <!-- Top Verified Security Banner -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-6 text-white text-center relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                    <x-heroicon-s-shield-check class="size-48" />
                </div>
                
                <div class="inline-flex size-14 rounded-2xl bg-white/20 backdrop-blur-md items-center justify-center mb-3 ring-4 ring-white/10 shadow-lg">
                    <x-heroicon-s-check-badge class="size-8 text-white" />
                </div>
                <h1 class="text-lg sm:text-xl font-extrabold tracking-tight">TRANSAKSI RESMI TERVERIFIKASI</h1>
                <p class="text-xs text-emerald-100 mt-1 font-medium">Dokumen dan catatan transaksi terdaftar sah pada Basis Data Pusat</p>

                <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-950/40 text-emerald-200 text-[11px] font-mono border border-emerald-400/30">
                    <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Status: SAH & TERCATAT</span>
                </div>
            </div>

            <!-- Certificate Content -->
            <div class="p-6 sm:p-8 space-y-6 text-xs text-zinc-700 dark:text-zinc-300">
                <!-- Institution Header -->
                <div class="flex items-center justify-between pb-5 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="size-10 rounded-2xl object-cover shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-800">
                        <div>
                            <h3 class="font-extrabold text-sm text-zinc-900 dark:text-white">{{ $settings['nama_lembaga'] ?? config('app.name') }}</h3>
                            <p class="text-[11px] text-zinc-500">{{ $settings['slogan_lembaga'] ?? 'Sistem Buku Tabungan Digital' }}</p>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <span class="text-[10px] text-zinc-400 uppercase font-semibold block">Kode Verifikasi</span>
                        <span class="font-mono font-bold text-zinc-800 dark:text-zinc-200 text-xs">{{ $transaksi->verification_code }}</span>
                    </div>
                </div>

                <!-- Transaction Details Grid -->
                <div class="bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-5 space-y-3">
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Nomor Struk:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white text-xs">{{ $transaksi->kode_transaksi }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Jenis Transaksi:</span>
                        <span class="font-extrabold uppercase px-2.5 py-0.5 rounded-full text-[10px] {{ $transaksi->jenis_transaksi === 'setor' ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/20' }}">
                            {{ $transaksi->jenis_transaksi === 'setor' ? 'Setoran Tunai' : 'Penarikan Tunai' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Waktu Transaksi:</span>
                        <span class="font-mono text-zinc-800 dark:text-zinc-200">{{ $transaksi->created_at->translatedFormat('d F Y, H:i:s') }} WIB</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Nama Nasabah:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">
                            @php
                                $nama = $transaksi->nasabah->nama ?? 'Nasabah';
                                $words = explode(' ', $nama);
                                $masked = array_map(function($w) {
                                    return mb_substr($w, 0, 1) . str_repeat('*', max(1, mb_strlen($w) - 1));
                                }, $words);
                            @endphp
                            {{ implode(' ', $masked) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">No. Rekening Nasabah:</span>
                        <span class="font-mono text-zinc-800 dark:text-zinc-200">
                            {{ substr($transaksi->nasabah->nomor_nasabah ?? '000000', 0, 4) }}****
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Nominal Transaksi:</span>
                        <span class="font-mono font-extrabold text-base {{ $transaksi->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $transaksi->formatted_nominal }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500 dark:text-zinc-400">Saldo Akhir Transaksi:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white">{{ $transaksi->formatted_saldo_akhir }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-zinc-500 dark:text-zinc-400">Petugas / Teller:</span>
                        <span class="text-zinc-800 dark:text-zinc-200">{{ $transaksi->user->name ?? 'Teller Bank' }}</span>
                    </div>
                </div>

                <!-- Digital Signature / Anti-counterfeit Hash -->
                <div class="p-3.5 rounded-xl bg-zinc-100 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700/70 space-y-1.5 font-mono text-[10px]">
                    <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400">
                        <span class="font-bold flex items-center gap-1">
                            <x-heroicon-s-lock-closed class="size-3 text-emerald-600 dark:text-emerald-400" />
                            Digital Signature (HMAC SHA-256)
                        </span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Integrity Verified</span>
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-300 break-all leading-relaxed bg-white dark:bg-zinc-900 p-2 rounded-lg border border-zinc-200 dark:border-zinc-800">
                        {{ $transaksi->digital_signature }}
                    </p>
                </div>

                <!-- Verification Notice -->
                <div class="text-center text-[11px] text-zinc-500 dark:text-zinc-400 space-y-1">
                    <p>Halaman ini memvalidasi keaslian struk cetak maupun bukti transfer tabungan secara digital.</p>
                    <p class="text-[10px] text-zinc-400">Diverifikasi pada: {{ now()->translatedFormat('d F Y, H:i:s') }} WIB</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="w-1/2 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                    >
                        <x-heroicon-o-printer class="size-4" />
                        <span>Cetak Bukti Validasi</span>
                    </button>
                    <a 
                        href="/" 
                        class="w-1/2 py-2.5 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl transition-all text-center"
                    >
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- INVALID CERTIFICATE WARNING -->
        <div class="bg-white dark:bg-zinc-900 border border-rose-200 dark:border-rose-900 rounded-3xl overflow-hidden shadow-2xl p-6 sm:p-8 text-center space-y-5">
            <div class="size-16 rounded-3xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-500/20 shadow-lg shadow-rose-500/10">
                <x-heroicon-s-exclamation-triangle class="size-9" />
            </div>

            <div>
                <h2 class="text-lg font-extrabold text-zinc-900 dark:text-white">DOKUMEN TIDAK TERVERIFIKASI</h2>
                <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1 max-w-md mx-auto">
                    Kode transaksi atau tanda tangan verifikasi <strong class="font-mono text-rose-600 dark:text-rose-400">"{{ $code }}"</strong> tidak ditemukan pada basis data sistem resmi.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs text-left space-y-2">
                <span class="font-bold flex items-center gap-1.5 text-rose-700 dark:text-rose-300">
                    <x-heroicon-s-shield-exclamation class="size-4" />
                    Peringatan Keamanan Transaksi:
                </span>
                <ul class="list-disc list-inside space-y-1 text-[11px] text-rose-700 dark:text-rose-300/90 leading-relaxed">
                    <li>Struk transaksi fisik mungkin palsu atau telah dimodifikasi.</li>
                    <li>Pastikan Anda memindai kode QR dari struk resmi yang dicetak langsung oleh petugas teller.</li>
                    <li>Segera hubungi pihak pengelola lembaga untuk konfirmasi keaslian struk.</li>
                </ul>
            </div>

            <div class="pt-2">
                <a href="/" class="inline-flex items-center gap-2 px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-xs font-bold rounded-xl shadow-md">
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
        </div>
    @endif
</div>
