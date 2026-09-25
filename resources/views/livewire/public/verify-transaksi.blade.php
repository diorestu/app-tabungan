<div class="max-w-md mx-auto py-6 px-4">
    @if ($isValid && $transaksi)
        <!-- VALID CERTIFICATE CARD -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-xs text-zinc-900 dark:text-zinc-100">
            <!-- Header Verified -->
            <div class="p-5 bg-zinc-900 text-white text-center border-b border-zinc-800">
                <div class="inline-flex size-10 rounded-xl bg-emerald-500/20 text-emerald-400 items-center justify-center mb-2">
                    <x-heroicon-s-check-badge class="size-6" />
                </div>
                <h1 class="text-sm font-bold tracking-tight uppercase">TRANSAKSI RESMI TERVERIFIKASI</h1>
                <p class="text-[11px] text-zinc-400 mt-0.5">Catatan transaksi sah dan terdaftar pada sistem basis data pusat</p>
            </div>

            <!-- Certificate Content -->
            <div class="p-5 space-y-4 text-xs">
                <!-- Institution Header -->
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="size-8 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-800">
                        <div>
                            <h3 class="font-bold text-xs text-zinc-900 dark:text-white">{{ $settings['nama_lembaga'] ?? config('app.name') }}</h3>
                            <p class="text-[10px] text-zinc-500">{{ $settings['slogan_lembaga'] ?? 'Sistem Buku Tabungan Digital' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] text-zinc-400 uppercase font-medium block">Kode Verifikasi</span>
                        <span class="font-mono font-semibold text-zinc-700 dark:text-zinc-300 text-[11px]">{{ $transaksi->verification_code }}</span>
                    </div>
                </div>

                <!-- Transaction Details Grid -->
                <div class="bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 rounded-xl p-3.5 space-y-2">
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Nomor Struk:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white">{{ $transaksi->kode_transaksi }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Jenis Transaksi:</span>
                        <span class="font-semibold uppercase px-2 py-0.5 rounded text-[10px] {{ $transaksi->jenis_transaksi === 'setor' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' }}">
                            {{ $transaksi->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Waktu Transaksi:</span>
                        <span class="font-mono text-zinc-800 dark:text-zinc-200">{{ $transaksi->created_at->translatedFormat('d F Y, H:i:s') }} WIB</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Nama Nasabah:</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">
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
                        <span class="text-zinc-500">No. Rekening:</span>
                        <span class="font-mono text-zinc-800 dark:text-zinc-200">
                            {{ substr($transaksi->nasabah->nomor_nasabah ?? '000000', 0, 4) }}****
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Nominal Transaksi:</span>
                        <span class="font-mono font-bold text-sm {{ $transaksi->jenis_transaksi === 'setor' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $transaksi->formatted_nominal }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-zinc-200/60 dark:border-zinc-800/80">
                        <span class="text-zinc-500">Saldo Akhir Transaksi:</span>
                        <span class="font-mono font-bold text-zinc-900 dark:text-white">{{ $transaksi->formatted_saldo_akhir }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-zinc-500">Petugas / Teller:</span>
                        <span class="text-zinc-800 dark:text-zinc-200">{{ $transaksi->user->name ?? 'Teller Bank' }}</span>
                    </div>
                </div>

                <!-- Digital Signature -->
                <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 space-y-1 font-mono text-[10px]">
                    <div class="flex items-center justify-between text-zinc-400">
                        <span class="font-semibold flex items-center gap-1">
                            <x-heroicon-s-lock-closed class="size-3 text-emerald-600" />
                            Digital Signature (HMAC SHA-256)
                        </span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">Valid</span>
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-400 break-all leading-tight bg-white dark:bg-zinc-900 p-2 rounded border border-zinc-200 dark:border-zinc-800">
                        {{ $transaksi->digital_signature }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="w-1/2 py-2 px-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-lg shadow-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                    >
                        <x-heroicon-o-printer class="size-3.5" />
                        <span>Cetak Bukti</span>
                    </button>
                    <a 
                        href="/" 
                        class="w-1/2 py-2 px-3 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-lg transition-colors text-center border border-zinc-200 dark:border-zinc-700"
                    >
                        Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- INVALID CERTIFICATE WARNING -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-xs p-6 text-center space-y-4">
            <div class="size-12 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                <x-heroicon-s-exclamation-triangle class="size-6" />
            </div>

            <div>
                <h2 class="text-base font-bold text-zinc-900 dark:text-white uppercase">DOKUMEN TIDAK TERVERIFIKASI</h2>
                <p class="text-xs text-zinc-500 mt-1 max-w-sm mx-auto">
                    Kode transaksi <strong class="font-mono text-rose-600">"{{ $code }}"</strong> tidak ditemukan pada basis data sistem resmi.
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400 text-xs text-left space-y-1.5">
                <span class="font-semibold text-zinc-800 dark:text-zinc-200 block">
                    Peringatan Keamanan Transaksi:
                </span>
                <p class="text-[11px] leading-relaxed">
                    Struk transaksi fisik mungkin palsu atau belum tersimpan ke server. Pastikan Anda memindai kode QR dari struk resmi yang dicetak oleh petugas teller.
                </p>
            </div>

            <div class="pt-1">
                <a href="/" class="inline-flex items-center gap-1.5 px-4 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-xs font-semibold rounded-lg shadow-xs">
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
        </div>
    @endif
</div>
