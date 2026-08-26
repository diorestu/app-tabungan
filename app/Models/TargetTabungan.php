<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TargetTabungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nasabah_id',
        'nama_target',
        'kategori',
        'target_nominal',
        'terkumpul_nominal',
        'tenggat_waktu',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'target_nominal' => 'decimal:2',
            'terkumpul_nominal' => 'decimal:2',
            'tenggat_waktu' => 'date',
        ];
    }

    public const KATEGORI_OPTIONS = [
        'qurban' => ['nama' => 'Qurban & Ibadah', 'icon' => 'heart', 'color' => 'emerald'],
        'pendidikan' => ['nama' => 'Pendidikan', 'icon' => 'academic-cap', 'color' => 'blue'],
        'liburan' => ['nama' => 'Liburan & Traveling', 'icon' => 'globe-alt', 'color' => 'amber'],
        'darurat' => ['nama' => 'Dana Darurat', 'icon' => 'shield-check', 'color' => 'rose'],
        'elektronik' => ['nama' => 'Gadget & Elektronik', 'icon' => 'device-phone-mobile', 'color' => 'indigo'],
        'kendaraan' => ['nama' => 'Kendaraan', 'icon' => 'truck', 'color' => 'purple'],
        'rumah' => ['nama' => 'Properti & Rumah', 'icon' => 'home', 'color' => 'teal'],
        'lainnya' => ['nama' => 'Lain-lain', 'icon' => 'sparkles', 'color' => 'zinc'],
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TargetTabunganHistory::class)->latest();
    }

    public function getFormattedTargetNominalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->target_nominal, 0, ',', '.');
    }

    public function getFormattedTerkumpulNominalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->terkumpul_nominal, 0, ',', '.');
    }

    public function getSisaNominalAttribute(): float
    {
        return max(0, (float) $this->target_nominal - (float) $this->terkumpul_nominal);
    }

    public function getFormattedSisaNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->sisa_nominal, 0, ',', '.');
    }

    public function getProgressPercentageAttribute(): int
    {
        if ((float) $this->target_nominal <= 0) {
            return 0;
        }
        $pct = ((float) $this->terkumpul_nominal / (float) $this->target_nominal) * 100;
        return (int) min(100, round($pct));
    }

    public function getKategoriMetaAttribute(): array
    {
        return self::KATEGORI_OPTIONS[$this->kategori] ?? self::KATEGORI_OPTIONS['lainnya'];
    }

    public function getKategoriNamaAttribute(): string
    {
        return $this->kategori_meta['nama'];
    }
}
