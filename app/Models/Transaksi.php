<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'verification_code',
        'nasabah_id',
        'user_id',
        'jenis_transaksi',
        'nominal',
        'saldo_awal',
        'saldo_akhir',
        'keterangan',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $transaksi) {
            if (empty($transaksi->verification_code)) {
                $transaksi->verification_code = strtoupper(substr(hash('sha256', \Illuminate\Support\Str::uuid() . '|' . microtime(true) . '|' . config('app.key')), 0, 24));
            }
        });
    }

    protected $casts = [
        'nominal' => 'decimal:2',
        'saldo_awal' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getVerificationUrlAttribute(): string
    {
        return url('/v/' . $this->verification_code);
    }

    public function getQrCodeDataUriAttribute(): string
    {
        return \App\Services\QrCodeService::svgDataUri($this->verification_url);
    }

    public function getDigitalSignatureAttribute(): string
    {
        $timestamp = $this->created_at ? $this->created_at->timestamp : time();
        return hash_hmac('sha256', $this->kode_transaksi . '|' . $this->nominal . '|' . $this->saldo_akhir . '|' . $timestamp, config('app.key'));
    }

    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->nominal, 0, ',', '.');
    }

    public function getFormattedSaldoAwalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->saldo_awal, 0, ',', '.');
    }

    public function getFormattedSaldoAkhirAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->saldo_akhir, 0, ',', '.');
    }

    public static function generateKodeTransaksi(string $jenis = 'TRX'): string
    {
        $prefix = strtoupper($jenis === 'setor' ? 'STR' : ($jenis === 'tarik' ? 'TRK' : 'TRX')) . '-' . date('Ymd') . '-';
        $last = self::where('kode_transaksi', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($last->kode_transaksi, -4);
        $nextNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }
}

