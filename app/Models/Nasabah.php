<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Nasabah extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nasabahs';

    protected $fillable = [
        'nomor_nasabah',
        'nama',
        'no_hp',
        'nik',
        'alamat',
        'status',
        'saldo',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'nasabah_id')->latest();
    }

    public function getFormattedSaldoAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->saldo, 0, ',', '.');
    }

    public function getTotalSetoranAttribute(): float
    {
        return (float) $this->transaksis()->where('jenis_transaksi', 'setor')->sum('nominal');
    }

    public function getTotalPenarikanAttribute(): float
    {
        return (float) $this->transaksis()->where('jenis_transaksi', 'tarik')->sum('nominal');
    }

    public static function generateNomorNasabah(): string
    {
        $year = date('Y');
        $prefix = 'NAS-' . $year . '-';
        $last = self::where('nomor_nasabah', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($last->nomor_nasabah, -4);
        $nextNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }
}

