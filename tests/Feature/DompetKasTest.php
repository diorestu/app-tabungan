<?php

namespace Tests\Feature;

use App\Livewire\Admin\DompetKasManager;
use App\Livewire\Admin\NasabahManager;
use App\Livewire\Admin\SetorTunai;
use App\Models\DompetKas;
use App\Models\Nasabah;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class DompetKasTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@tabungan.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);
    }

    public function test_guest_cannot_access_dompet_kas(): void
    {
        $response = $this->get(route('admin.dompet-kas'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_dompet_kas_page(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dompet-kas'));
        $response->assertStatus(200);
        $response->assertSee('Dompet Kas');
    }

    public function test_admin_can_create_dompet_kas_with_mandiri_and_nomor_rekening(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(DompetKasManager::class)
            ->call('openCreateModal')
            ->set('nama', 'Mandiri')
            ->set('nomor_rekening', '140-00-9988776-5')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('dompet_kas', [
            'nama' => 'Mandiri',
            'nomor_rekening' => '140-00-9988776-5',
            'saldo' => 0,
            'status' => 'aktif',
        ]);
    }

    public function test_dompet_kas_creation_requires_nomor_rekening(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(DompetKasManager::class)
            ->call('openCreateModal')
            ->set('nama', 'Mandiri')
            ->set('nomor_rekening', '')
            ->call('save')
            ->assertHasErrors(['nomor_rekening' => 'required']);
    }

    public function test_admin_can_edit_dompet_kas(): void
    {
        $this->actingAs($this->admin);

        $dompet = DompetKas::create([
            'nama' => 'Mandiri',
            'nomor_rekening' => '140-00-1111111-1',
            'saldo' => 0,
            'status' => 'aktif',
        ]);

        Livewire::test(DompetKasManager::class)
            ->call('openEditModal', $dompet->id)
            ->set('nomor_rekening', '140-00-2222222-2')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('dompet_kas', [
            'id' => $dompet->id,
            'nomor_rekening' => '140-00-2222222-2',
        ]);
    }

    public function test_admin_can_toggle_dompet_kas_status(): void
    {
        $this->actingAs($this->admin);

        $dompet = DompetKas::create([
            'nama' => 'Mandiri',
            'nomor_rekening' => '140-00-3333333-3',
            'saldo' => 0,
            'status' => 'aktif',
        ]);

        Livewire::test(DompetKasManager::class)
            ->call('toggleStatus', $dompet->id);

        $this->assertDatabaseHas('dompet_kas', [
            'id' => $dompet->id,
            'status' => 'nonaktif',
        ]);

        Livewire::test(DompetKasManager::class)
            ->call('toggleStatus', $dompet->id);

        $this->assertDatabaseHas('dompet_kas', [
            'id' => $dompet->id,
            'status' => 'aktif',
        ]);
    }

    public function test_admin_can_delete_unused_dompet_kas(): void
    {
        $this->actingAs($this->admin);

        $dompet = DompetKas::create([
            'nama' => 'Mandiri',
            'nomor_rekening' => '140-00-4444444-4',
            'saldo' => 0,
            'status' => 'aktif',
        ]);

        Livewire::test(DompetKasManager::class)
            ->call('confirmDelete', $dompet->id)
            ->call('delete');

        $this->assertDatabaseMissing('dompet_kas', [
            'id' => $dompet->id,
        ]);
    }

    public function test_setor_tunai_records_uang_masuk_to_selected_dompet_kas_and_increments_saldo(): void
    {
        $this->actingAs($this->admin);

        $dompet = DompetKas::create([
            'nama' => 'Mandiri',
            'nomor_rekening' => '140-00-5555555-5',
            'saldo' => 100000,
            'status' => 'aktif',
        ]);

        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0099',
            'nama' => 'Rina Wijaya',
            'no_hp' => '081233334444',
            'status' => 'aktif',
            'saldo' => 50000,
        ]);

        Livewire::test(SetorTunai::class)
            ->call('selectNasabah', $nasabah->id)
            ->set('dompet_kas_id', $dompet->id)
            ->set('nominal', 200000)
            ->set('keterangan', 'Setoran tabungan via Mandiri')
            ->call('processSetor')
            ->assertHasNoErrors();

        // Check Nasabah saldo updated
        $this->assertEquals(250000, (float) $nasabah->fresh()->saldo);

        // Check Transaksi created with dompet_kas_id
        $transaksi = Transaksi::where('nasabah_id', $nasabah->id)->latest('id')->first();
        $this->assertNotNull($transaksi);
        $this->assertEquals('setor', $transaksi->jenis_transaksi);
        $this->assertEquals(200000, (float) $transaksi->nominal);
        $this->assertEquals($dompet->id, $transaksi->dompet_kas_id);

        // Check DompetKas balance incremented: 100,000 + 200,000 = 300,000
        $this->assertEquals(300000, (float) $dompet->fresh()->saldo);
    }

    public function test_nasabah_registration_setoran_awal_records_uang_masuk_to_dompet_kas(): void
    {
        $this->actingAs($this->admin);

        $dompet = DompetKas::create([
            'nama' => 'Mandiri',
            'nomor_rekening' => '140-00-6666666-6',
            'saldo' => 50000,
            'status' => 'aktif',
        ]);

        Livewire::test(NasabahManager::class)
            ->call('openCreateModal')
            ->set('nama', 'Doni Pratama')
            ->set('no_hp', '081299998888')
            ->set('wilayah_code', '2')
            ->set('dompet_kas_id', $dompet->id)
            ->set('setoran_awal', 150000)
            ->call('saveNasabah')
            ->assertHasNoErrors();

        $nasabah = Nasabah::where('nama', 'Doni Pratama')->first();
        $this->assertNotNull($nasabah);
        $this->assertEquals(150000, (float) $nasabah->saldo);

        // Check initial deposit transaction linked to dompet_kas_id
        $trx = Transaksi::where('nasabah_id', $nasabah->id)->first();
        $this->assertNotNull($trx);
        $this->assertEquals($dompet->id, $trx->dompet_kas_id);
        $this->assertEquals(150000, (float) $trx->nominal);

        // Check DompetKas balance incremented: 50,000 + 150,000 = 200,000
        $this->assertEquals(200000, (float) $dompet->fresh()->saldo);
    }
}
