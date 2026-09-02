<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Format phone number to international format without + or spaces (e.g. 6281234567890).
     */
    public static function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^\d]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '628' . substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Build dynamic transaction message text based on template.
     */
    public static function buildTransactionMessage(Transaksi $transaksi): string
    {
        $transaksi->loadMissing(['nasabah', 'user']);
        $nasabah = $transaksi->nasabah;
        $teller = $transaksi->user;

        $settings = Setting::getAllSettings();
        $namaLembaga = $settings['nama_lembaga'] ?? config('app.name', 'TabunganKu');
        $sloganLembaga = $settings['slogan_lembaga'] ?? 'Sistem Pencatatan Tabungan Digital';
        $alamatLembaga = $settings['alamat_lembaga'] ?? '';
        $teleponLembaga = $settings['telepon_lembaga'] ?? '';

        $isSetor = $transaksi->jenis_transaksi === 'setor';
        $defaultSetorTemplate = "🟢 *STRUK SETORAN TABUNGAN*\n*{nama_lembaga}*\n\nHalo *{nama}*,\nSetoran tunai Anda telah berhasil dicatat ke rekening tabungan.\n\n📄 *Rincian Transaksi:*\n• No. Rekening: `{nomor_nasabah}`\n• No. Struk: `{kode_transaksi}`\n• Tanggal & Waktu: {tanggal} {waktu}\n• Nominal Setor: *+{nominal}*\n• Saldo Akhir: *{saldo_akhir}*\n• Teller: {teller}\n• Keterangan: {keterangan}\n\nTerima kasih telah menabung di *{nama_lembaga}*. Simpan bukti digital ini sebagai tanda transaksi yang sah.\n\n_Pesan otomatis dari Sistem TabunganKu_";

        $defaultTarikTemplate = "🔴 *STRUK PENARIKAN TABUNGAN*\n*{nama_lembaga}*\n\nHalo *{nama}*,\nPenarikan tunai dari rekening tabungan Anda telah berhasil diproses.\n\n📄 *Rincian Transaksi:*\n• No. Rekening: `{nomor_nasabah}`\n• No. Struk: `{kode_transaksi}`\n• Tanggal & Waktu: {tanggal} {waktu}\n• Nominal Tarik: *-{nominal}*\n• Saldo Akhir: *{saldo_akhir}*\n• Teller: {teller}\n• Keterangan: {keterangan}\n\nTerima kasih atas kepercayaan Anda pada *{nama_lembaga}*.\n\n_Pesan otomatis dari Sistem TabunganKu_";

        $template = $isSetor
            ? ($settings['wa_template_setor'] ?? $defaultSetorTemplate)
            : ($settings['wa_template_tarik'] ?? $defaultTarikTemplate);

        $replacements = [
            '{nama}' => $nasabah?->nama ?? 'Nasabah',
            '{nomor_nasabah}' => $nasabah?->nomor_nasabah ?? '-',
            '{no_hp}' => $nasabah?->no_hp ?? '-',
            '{jenis_transaksi}' => strtoupper($transaksi->jenis_transaksi),
            '{nominal}' => 'Rp ' . number_format($transaksi->nominal, 0, ',', '.'),
            '{saldo_awal}' => 'Rp ' . number_format($transaksi->saldo_awal, 0, ',', '.'),
            '{saldo_akhir}' => 'Rp ' . number_format($transaksi->saldo_akhir, 0, ',', '.'),
            '{kode_transaksi}' => $transaksi->kode_transaksi,
            '{tanggal}' => $transaksi->created_at->translatedFormat('d F Y'),
            '{waktu}' => $transaksi->created_at->format('H:i') . ' WIB',
            '{teller}' => $teller?->name ?? 'Petugas Teller',
            '{keterangan}' => $transaksi->keterangan ?: '-',
            '{nama_lembaga}' => $namaLembaga,
            '{slogan_lembaga}' => $sloganLembaga,
            '{alamat_lembaga}' => $alamatLembaga,
            '{telepon_lembaga}' => $teleponLembaga,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Generate direct WhatsApp Web / Mobile URL for click-to-send.
     */
    public static function getDirectWhatsAppUrl(Transaksi $transaksi): string
    {
        $phone = self::formatPhoneNumber($transaksi->nasabah?->no_hp ?? '');
        $message = self::buildTransactionMessage($transaksi);

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }

    /**
     * Send transaction receipt via configured WhatsApp gateway.
     */
    public function sendTransactionReceipt(Transaksi $transaksi): array
    {
        $settings = Setting::getAllSettings();
        $enabled = ($settings['wa_gateway_enabled'] ?? '0') === '1';

        if (!$enabled) {
            return [
                'success' => false,
                'message' => 'WhatsApp Gateway sedang nonaktif di pengaturan.',
                'direct_url' => self::getDirectWhatsAppUrl($transaksi),
            ];
        }

        $phone = self::formatPhoneNumber($transaksi->nasabah?->no_hp ?? '');
        if (empty($phone)) {
            return [
                'success' => false,
                'message' => 'Nomor HP nasabah tidak valid atau kosong.',
            ];
        }

        $message = self::buildTransactionMessage($transaksi);

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send WhatsApp message via selected driver.
     */
    public function sendMessage(string $phone, string $message): array
    {
        $phone = self::formatPhoneNumber($phone);
        $settings = Setting::getAllSettings();
        $provider = $settings['wa_provider'] ?? 'mock';
        $apiToken = $settings['wa_api_token'] ?? '';
        $endpoint = $settings['wa_endpoint_url'] ?? '';

        try {
            switch ($provider) {
                case 'fonnte':
                    return $this->sendViaFonnte($phone, $message, $apiToken);

                case 'wablas':
                    return $this->sendViaWablas($phone, $message, $apiToken, $endpoint);

                case 'custom':
                    return $this->sendViaCustomWebhook($phone, $message, $endpoint, $apiToken);

                case 'mock':
                default:
                    Log::info("WhatsApp Gateway [Mock Driver] Sent to {$phone}:\n{$message}");
                    return [
                        'success' => true,
                        'provider' => 'mock',
                        'message' => "Pesan berhasil disimulasikan (Mock Driver) untuk nomor {$phone}.",
                    ];
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp Gateway Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan WhatsApp: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send via Fonnte API.
     */
    protected function sendViaFonnte(string $phone, string $message, string $token): array
    {
        if (empty($token)) {
            return ['success' => false, 'message' => 'API Token Fonnte belum diisi di Pengaturan.'];
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->timeout(15)->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62',
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'provider' => 'fonnte',
                'message' => 'Pesan WhatsApp berhasil dikirim via Fonnte.',
                'response' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'provider' => 'fonnte',
            'message' => 'Fonnte Error: ' . ($response->json('reason') ?? $response->body()),
        ];
    }

    /**
     * Send via Wablas API.
     */
    protected function sendViaWablas(string $phone, string $message, string $token, string $endpoint): array
    {
        if (empty($token)) {
            return ['success' => false, 'message' => 'API Token Wablas belum diisi di Pengaturan.'];
        }

        $baseUrl = !empty($endpoint) ? rtrim($endpoint, '/') : 'https://phone.wablas.com';

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->timeout(15)->post($baseUrl . '/api/send-message', [
            'phone' => $phone,
            'message' => $message,
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'provider' => 'wablas',
                'message' => 'Pesan WhatsApp berhasil dikirim via Wablas.',
                'response' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'provider' => 'wablas',
            'message' => 'Wablas Error: ' . ($response->json('message') ?? $response->body()),
        ];
    }

    /**
     * Send via Custom Webhook.
     */
    protected function sendViaCustomWebhook(string $phone, string $message, string $endpoint, string $token): array
    {
        if (empty($endpoint)) {
            return ['success' => false, 'message' => 'URL Webhook Custom belum diisi di Pengaturan.'];
        }

        $request = Http::timeout(15);
        if (!empty($token)) {
            $request = $request->withHeaders(['Authorization' => 'Bearer ' . $token]);
        }

        $response = $request->post($endpoint, [
            'phone' => $phone,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'provider' => 'custom',
                'message' => 'Pesan WhatsApp berhasil diteruskan ke Custom Webhook.',
                'response' => $response->json() ?? $response->body(),
            ];
        }

        return [
            'success' => false,
            'provider' => 'custom',
            'message' => 'Webhook Error (HTTP ' . $response->status() . '): ' . $response->body(),
        ];
    }
}
