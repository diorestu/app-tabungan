<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TellerShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_date',
        'modal_awal',
        'total_setoran',
        'total_penarikan',
        'saldo_sistem',
        'saldo_fisik',
        'selisih',
        'pecahan_uang',
        'catatan',
        'status',
        'supervisor_id',
        'opened_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'shift_date' => 'date',
            'modal_awal' => 'decimal:2',
            'total_setoran' => 'decimal:2',
            'total_penarikan' => 'decimal:2',
            'saldo_sistem' => 'decimal:2',
            'saldo_fisik' => 'decimal:2',
            'selisih' => 'decimal:2',
            'pecahan_uang' => 'array',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function getFormattedModalAwalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->modal_awal, 0, ',', '.');
    }

    public function getFormattedTotalSetoranAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_setoran, 0, ',', '.');
    }

    public function getFormattedTotalPenarikanAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_penarikan, 0, ',', '.');
    }

    public function getFormattedSaldoSistemAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->saldo_sistem, 0, ',', '.');
    }

    public function getFormattedSaldoFisikAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->saldo_fisik, 0, ',', '.');
    }

    public function getFormattedSelisihAttribute(): string
    {
        $selisih = (float) $this->selisih;
        $sign = $selisih > 0 ? '+ ' : ($selisih < 0 ? '- ' : '');
        return $sign . 'Rp ' . number_format(abs($selisih), 0, ',', '.');
    }
}
