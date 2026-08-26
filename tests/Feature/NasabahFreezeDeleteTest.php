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
}
