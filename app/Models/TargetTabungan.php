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
        'qurban' => ['nama' => 'Qurban & Ibadah', 'icon' => 'heart', 'color' => 'emerald', 'warna' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border-emerald-500/20'],
        'pendidikan' => ['nama' => 'Pendidikan', 'icon' => 'academic-cap', 'color' => 'blue', 'warna' => 'bg-blue-500/10 text-blue-700 dark:text-blue-300 border-blue-500/20'],
        'liburan' => ['nama' => 'Liburan & Traveling', 'icon' => 'globe-alt', 'color' => 'amber', 'warna' => 'bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-500/20'],
        'darurat' => ['nama' => 'Dana Darurat', 'icon' => 'shield-check', 'color' => 'rose', 'warna' => 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-500/20'],
        'elektronik' => ['nama' => 'Gadget & Elektronik', 'icon' => 'device-phone-mobile', 'color' => 'indigo', 'warna' => 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border-indigo-500/20'],
        'kendaraan' => ['nama' => 'Kendaraan', 'icon' => 'truck', 'color' => 'purple', 'warna' => 'bg-purple-500/10 text-purple-700 dark:text-purple-300 border-purple-500/20'],
        'rumah' => ['nama' => 'Properti & Rumah', 'icon' => 'home', 'color' => 'teal', 'warna' => 'bg-teal-500/10 text-teal-700 dark:text-teal-300 border-teal-500/20'],
        'lainnya' => ['nama' => 'Lain-lain', 'icon' => 'sparkles', 'color' => 'zinc', 'warna' => 'bg-zinc-500/10 text-zinc-700 dark:text-zinc-300 border-zinc-500/20'],
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
        return 'Rp '.number_format((float) $this->target_nominal, 0, ',', '.');
    }

    public function getFormattedTerkumpulNominalAttribute(): string
    {
        return 'Rp '.number_format((float) $this->terkumpul_nominal, 0, ',', '.');
    }

    public function getSisaNominalAttribute(): float
    {
        return max(0, (float) $this->target_nominal - (float) $this->terkumpul_nominal);
    }

    public function getFormattedSisaNominalAttribute(): string
    {
        return 'Rp '.number_format($this->sisa_nominal, 0, ',', '.');
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
