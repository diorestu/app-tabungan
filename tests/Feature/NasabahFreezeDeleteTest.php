<?php

namespace Tests\Feature;

use App\Livewire\Admin\NasabahManager;
use App\Livewire\Admin\SetorTunai;
use App\Livewire\Admin\TarikTunai;
use App\Livewire\Nasabah\Login;
use App\Models\Nasabah;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NasabahFreezeDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'email' => 'admin@tabungan.test',
        ]);
    }

    public function test_admin_can_freeze_and_unfreeze_nasabah(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'status' => 'aktif',
            'saldo' => 500000,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(NasabahManager::class)
            ->call('toggleFreeze', $nasabah->id);

        $this->assertEquals('dibekukan', $nasabah->fresh()->status);

        Livewire::test(NasabahManager::class)
            ->call('toggleFreeze', $nasabah->id);

        $this->assertEquals('aktif', $nasabah->fresh()->status);
    }

    public function test_frozen_nasabah_cannot_login_to_portal(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0002',
            'nama' => 'Siti Rahma',
            'no_hp' => '081298765432',
            'status' => 'dibekukan',
            'saldo' => 100000,
        ]);

        Livewire::test(Login::class)
            ->set('nomor_nasabah', 'NAS-2026-0002')
            ->set('no_hp', '081298765432')
            ->call('login')
            ->assertHasErrors(['nomor_nasabah'])
            ->assertSee('DIBEKUKAN');

        $this->assertFalse(auth()->guard('nasabah')->check());
    }

    public function test_frozen_nasabah_rejected_in_setor_and_tarik(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0003',
            'nama' => 'Ahmad Fauzi',
            'no_hp' => '081345678901',
            'status' => 'dibekukan',
            'saldo' => 200000,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(SetorTunai::class)
            ->call('selectNasabah', $nasabah->id)
            ->assertHasErrors(['nasabah_id']);

        Livewire::test(TarikTunai::class)
            ->call('selectNasabah', $nasabah->id)
            ->assertHasErrors(['nasabah_id']);
    }

    public function test_admin_can_delete_nasabah_with_confirmation(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0004',
            'nama' => 'Dewi Lestari',
            'no_hp' => '081456789012',
            'status' => 'aktif',
            'saldo' => 150000,
        ]);

        Transaksi::create([
            'kode_transaksi' => 'TRX-SETOR-0001',
            'nasabah_id' => $nasabah->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 150000,
            'saldo_awal' => 0,
            'saldo_akhir' => 150000,
            'keterangan' => 'Setoran awal',
        ]);

        $this->actingAs($this->admin);

        Livewire::test(NasabahManager::class)
            ->call('openDeleteModal', $nasabah->id)
            ->assertSet('showDeleteModal', true)
            ->call('confirmDelete')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('nasabahs', ['id' => $nasabah->id]);
        $this->assertDatabaseMissing('transaksis', ['nasabah_id' => $nasabah->id]);
    }

    public function test_9_digit_account_numbering_format(): void
    {
        // Jawa = 2, Year = 26, Month = 08 -> 226080001 (9 digits)
        $nomorJawa = Nasabah::generateNomorNasabah('2');
        $this->assertEquals(9, strlen($nomorJawa));
        $this->assertStringStartsWith('2' . date('ym'), $nomorJawa);
        $this->assertStringEndsWith('0001', $nomorJawa);

        // Sumatera = 1 -> 126080001
        $nomorSumatera = Nasabah::generateNomorNasabah('1');
        $this->assertEquals(9, strlen($nomorSumatera));
        $this->assertStringStartsWith('1' . date('ym'), $nomorSumatera);

        // Create nasabah with 9 digits
        $nasabah = Nasabah::create([
            'nomor_nasabah' => $nomorJawa,
            'nama' => 'Nasabah Jawa',
            'no_hp' => '081233445566',
            'saldo' => 0,
        ]);

        $this->assertEquals('Jawa', $nasabah->wilayah_nama);

        // Next number for Jawa should increment to 0002
        $nextJawa = Nasabah::generateNomorNasabah('2');
        $this->assertStringEndsWith('0002', $nextJawa);
        $this->assertEquals(9, strlen($nextJawa));
    }

    public function test_admin_can_register_nasabah_fully_automatically(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(NasabahManager::class)
            ->call('openCreateModal')
            ->set('wilayah_code', '3') // Bali
            ->set('nama', 'I Wayan Sudirta')
            ->set('no_hp', '081399887766')
            ->set('setoran_awal', 250000)
            ->call('saveNasabah');

        $this->assertDatabaseHas('nasabahs', [
            'nama' => 'I Wayan Sudirta',
            'no_hp' => '081399887766',
            'saldo' => 250000,
        ]);

        $nasabah = Nasabah::where('nama', 'I Wayan Sudirta')->first();
        $this->assertNotNull($nasabah);
        $this->assertEquals(9, strlen($nasabah->nomor_nasabah));
        $this->assertStringStartsWith('3' . date('ym'), $nasabah->nomor_nasabah);
        $this->assertEquals('Bali', $nasabah->wilayah_nama);
    }
}
