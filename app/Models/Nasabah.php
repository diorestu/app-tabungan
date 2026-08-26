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

    public function targetTabungans(): HasMany
    {
        return $this->hasMany(TargetTabungan::class, 'nasabah_id')->latest();
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

    public const WILAYAH = [
        '1' => 'Sumatera',
        '2' => 'Jawa',
        '3' => 'Bali',
        '4' => 'Kalimantan',
        '5' => 'Sulawesi',
        '6' => 'Nusa Tenggara',
        '7' => 'Maluku',
        '8' => 'Papua',
    ];

    public function getWilayahNamaAttribute(): string
    {
        $code = substr($this->nomor_nasabah, 0, 1);
        return self::WILAYAH[$code] ?? 'Lainnya';
    }

    public static function generateNomorNasabah(string $wilayah = '2'): string
    {
        // Pastikan kode wilayah valid (1-8), default Jawa (2)
        if (!array_key_exists($wilayah, self::WILAYAH)) {
            $wilayah = '2';
        }

        $year = date('y');  // 2 digit tahun aktif (YY) -> e.g. '26'
        $month = date('m'); // 2 digit bulan aktif (MM) -> e.g. '08'

        // Prefix 5 digit: {A}{BCDE} = 1 digit wilayah + 2 digit tahun + 2 digit bulan
        $prefix = $wilayah . $year . $month;

        // Cari nomor urut terakhir pada prefix yang sama
        $last = self::where('nomor_nasabah', 'LIKE', $prefix . '%')
            ->orderBy('nomor_nasabah', 'desc')
            ->first();

        if (!$last) {
            // 5 digit prefix + 4 digit urutan = 9 digit total (contoh: 226080001)
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($last->nomor_nasabah, -4);
        $nextNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }
}

