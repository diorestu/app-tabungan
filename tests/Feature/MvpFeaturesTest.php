<?php

namespace Tests\Feature;

use App\Livewire\Admin\Pengaturan;
use App\Livewire\Admin\TransaksiManager;
use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class MvpFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'email' => 'admin@tabungan.test',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_admin_can_export_transactions_csv(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'status' => 'aktif',
            'saldo' => 500000,
        ]);

        Transaksi::create([
            'kode_transaksi' => 'TRX-SETOR-0001',
            'nasabah_id' => $nasabah->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 500000,
            'saldo_awal' => 0,
            'saldo_akhir' => 500000,
            'keterangan' => 'Setoran awal',
        ]);

        $this->actingAs($this->admin);

        $response = Livewire::test(TransaksiManager::class)
            ->call('exportCsv');

        $response->assertFileDownloaded();
    }

    public function test_admin_can_update_institution_settings(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(Pengaturan::class)
            ->set('nama_lembaga', 'Koperasi Maju Sejahtera')
            ->set('slogan_lembaga', 'Maju Bersama Anggota')
            ->set('alamat_lembaga', 'Jl. Sudirman No. 10')
            ->set('telepon_lembaga', '0811-2233-4455')
            ->set('pesan_struk', 'Terima kasih atas kunjungannya.')
            ->call('saveInstitutionSettings');

        $this->assertEquals('Koperasi Maju Sejahtera', Setting::get('nama_lembaga'));
        $this->assertEquals('0811-2233-4455', Setting::get('telepon_lembaga'));
    }

    public function test_admin_can_change_password(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(Pengaturan::class)
            ->set('current_password', 'password123')
            ->set('new_password', 'newsecret456')
            ->set('new_password_confirmation', 'newsecret456')
            ->call('updatePassword');

        $this->assertTrue(Hash::check('newsecret456', $this->admin->fresh()->password));
    }
}
