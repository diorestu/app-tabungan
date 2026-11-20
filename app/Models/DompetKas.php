<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DompetKas extends Model
{
    use HasFactory;

    protected $table = 'dompet_kas';

    protected $fillable = [
        'nama',
        'nomor_rekening',
        'saldo',
        'status',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'dompet_kas_id');
    }

    public function getFormattedSaldoAttribute(): string
    {
        return 'Rp '.number_format((float) $this->saldo, 0, ',', '.');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nama.' ('.$this->nomor_rekening.')';
    }
}
