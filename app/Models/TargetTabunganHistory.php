<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TargetTabunganHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_tabungan_id',
        'nasabah_id',
        'tipe',
        'nominal',
        'saldo_target_sebelum',
        'saldo_target_sesudah',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'saldo_target_sebelum' => 'decimal:2',
            'saldo_target_sesudah' => 'decimal:2',
        ];
    }

    public function targetTabungan(): BelongsTo
    {
        return $this->belongsTo(TargetTabungan::class);
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->nominal, 0, ',', '.');
    }
}
