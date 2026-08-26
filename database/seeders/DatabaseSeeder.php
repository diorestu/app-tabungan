<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@tabungan.test'],
            [
                'name' => 'Administrator Tabungan',
                'password' => bcrypt('password'),
            ]
        );

        $nasabahsData = [
            [
                'nomor_nasabah' => 'NAS-2026-0001',
                'nama' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'nik' => '3201011205900001',
                'alamat' => 'Jl. Melati Raya No. 12, Kebayoran Baru, Jakarta Selatan',
                'status' => 'aktif',
                'saldo' => 1750000,
                'transaksis' => [
                    [
                        'kode_transaksi' => 'STR-20260801-0001',
                        'jenis_transaksi' => 'setor',
                        'nominal' => 1000000,
                        'saldo_awal' => 0,
                        'saldo_akhir' => 1000000,
                        'keterangan' => 'Setoran awal pembukaan tabungan',
                        'created_at' => now()->subDays(25),
                    ],
                    [
                        'kode_transaksi' => 'STR-20260810-0002',
                        'jenis_transaksi' => 'setor',
                        'nominal' => 1000000,
                        'saldo_awal' => 1000000,
                        'saldo_akhir' => 2000000,
                        'keterangan' => 'Setoran rutin bulanan',
                        'created_at' => now()->subDays(15),
                    ],
                    [
                        'kode_transaksi' => 'TRK-20260820-0001',
                        'jenis_transaksi' => 'tarik',
                        'nominal' => 250000,
                        'saldo_awal' => 2000000,
                        'saldo_akhir' => 1750000,
                        'keterangan' => 'Penarikan untuk keperluan darurat',
                        'created_at' => now()->subDays(5),
                    ],
                ]
            ],
            [
                'nomor_nasabah' => 'NAS-2026-0002',
                'nama' => 'Siti Nurhaliza',
                'no_hp' => '081298765432',
                'nik' => '3201014508930002',
                'alamat' => 'Jl. Cempaka Putih No. 45, Bandung',
                'status' => 'aktif',
                'saldo' => 3500000,
                'transaksis' => [
                    [
                        'kode_transaksi' => 'STR-20260805-0001',
                        'jenis_transaksi' => 'setor',
                        'nominal' => 2000000,
                        'saldo_awal' => 0,
                        'saldo_akhir' => 2000000,
                        'keterangan' => 'Setoran awal tabungan',
                        'created_at' => now()->subDays(20),
                    ],
                    [
                        'kode_transaksi' => 'STR-20260818-0003',
                        'jenis_transaksi' => 'setor',
                        'nominal' => 1500000,
                        'saldo_awal' => 2000000,
                        'saldo_akhir' => 3500000,
                        'keterangan' => 'Setoran hasil usaha',
                        'created_at' => now()->subDays(7),
                    ],
                ]
            ],
            [
                'nomor_nasabah' => 'NAS-2026-0003',
                'nama' => 'Ahmad Fauzi',
                'no_hp' => '085612345678',
                'nik' => '3201012301950003',
                'alamat' => 'Jl. Pahlawan No. 88, Surabaya',
                'status' => 'aktif',
                'saldo' => 500000,
                'transaksis' => [
                    [
                        'kode_transaksi' => 'STR-20260812-0001',
                        'jenis_transaksi' => 'setor',
                        'nominal' => 1000000,
                        'saldo_awal' => 0,
                        'saldo_akhir' => 1000000,
                        'keterangan' => 'Setoran awal nasabah baru',
                        'created_at' => now()->subDays(14),
                    ],
                    [
                        'kode_transaksi' => 'TRK-20260822-0002',
                        'jenis_transaksi' => 'tarik',
                        'nominal' => 500000,
                        'saldo_awal' => 1000000,
                        'saldo_akhir' => 500000,
                        'keterangan' => 'Tarik tunai via teller',
                        'created_at' => now()->subDays(3),
                    ],
                ]
            ],
        ];

        foreach ($nasabahsData as $nData) {
            $transaksis = $nData['transaksis'];
            unset($nData['transaksis']);

            $nasabah = \App\Models\Nasabah::create($nData);

            foreach ($transaksis as $tData) {
                $tData['nasabah_id'] = $nasabah->id;
                $tData['user_id'] = $admin->id;
                \App\Models\Transaksi::create($tData);
            }
        }
    }
}
