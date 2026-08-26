<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, ?string $value, ?string $description = null): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description ?? $key,
            ]
        );
    }

    public static function getAllSettings(): array
    {
        $defaults = [
            'nama_lembaga' => 'TabunganKu Digital',
            'slogan_lembaga' => 'Layanan Simpanan & Tabungan Terpercaya',
            'alamat_lembaga' => 'Jl. Merdeka No. 45, Jakarta Pusat',
            'telepon_lembaga' => '0812-3456-7890',
            'pesan_struk' => 'Terima kasih atas kepercayaan Anda menabung bersama kami. Simpan struk ini sebagai bukti transaksi resmi.',
        ];

        $settings = static::pluck('value', 'key')->toArray();

        return array_merge($defaults, $settings);
    }
}
