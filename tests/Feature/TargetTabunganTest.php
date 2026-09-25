<?php

namespace Tests\Feature;

use App\Livewire\Nasabah\TargetTabungan;
use App\Models\Nasabah;
use App\Models\TargetTabungan as TargetTabunganModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TargetTabunganTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Nasabah $nasabah;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@tabungan.test',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->nasabah = Nasabah::create([
            'nomor_nasabah' => '226080001',
            'nama' => 'Rahmat Hidayat',
            'no_hp' => '081298765432',
            'nik' => '3201123456780001',
            'alamat' => 'Jl. Merdeka No. 45',
            'status' => 'aktif',
            'saldo' => 1000000,
        ]);
    }

    public function test_nasabah_can_view_target_tabungan_page(): void
    {
        $this->actingAs($this->nasabah, 'nasabah');

        $response = $this->get(route('nasabah.target'));
        $response->assertStatus(200);
        $response->assertSee('Kantong Target Tabungan');
    }

    public function test_nasabah_can_create_new_target_tabungan(): void
    {
        $this->actingAs($this->nasabah, 'nasabah');

        Livewire::test(TargetTabungan::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('nama_target', 'Tabungan Kambing Qurban')
            ->set('kategori', 'qurban')
            ->set('target_nominal', '3500000')
            ->set('catatan', 'Rencana beli kambing jantan')
            ->call('saveTarget')
            ->assertHasNoErrors()
            ->assertSet('showCreateModal', false);

        $this->assertDatabaseHas('target_tabungans', [
            'nasabah_id' => $this->nasabah->id,
            'nama_target' => 'Tabungan Kambing Qurban',
            'kategori' => 'qurban',
            'target_nominal' => 3500000,
            'terkumpul_nominal' => 0,
            'status' => 'berjalan',
        ]);
    }

    public function test_nasabah_can_allocate_funds_to_target(): void
    {
        $target = TargetTabunganModel::create([
            'nasabah_id' => $this->nasabah->id,
            'nama_target' => 'Dana Darurat',
            'kategori' => 'darurat',
            'target_nominal' => 500000,
            'terkumpul_nominal' => 0,
            'status' => 'berjalan',
        ]);

        $this->actingAs($this->nasabah, 'nasabah');

        Livewire::test(TargetTabungan::class)
            ->call('openAlokasiModal', $target->id)
            ->assertSet('showAlokasiModal', true)
            ->set('alokasi_nominal', '200000')
            ->call('prosesAlokasi')
            ->assertHasNoErrors()
            ->assertSet('showAlokasiModal', false);

        $this->nasabah->refresh();
        $target->refresh();

        // Nasabah main balance decreased
        $this->assertEquals(800000, (float) $this->nasabah->saldo);
        // Target balance increased
        $this->assertEquals(200000, (float) $target->terkumpul_nominal);

        // History recorded
        $this->assertDatabaseHas('target_tabungan_histories', [
            'target_tabungan_id' => $target->id,
            'nasabah_id' => $this->nasabah->id,
            'tipe' => 'alokasi',
            'nominal' => 200000,
        ]);
    }

    public function test_nasabah_can_withdraw_funds_from_target_back_to_main_balance(): void
    {
        $target = TargetTabunganModel::create([
            'nasabah_id' => $this->nasabah->id,
            'nama_target' => 'Beli Laptop',
            'kategori' => 'elektronik',
            'target_nominal' => 1000000,
            'terkumpul_nominal' => 400000,
            'status' => 'berjalan',
        ]);

        $this->actingAs($this->nasabah, 'nasabah');

        Livewire::test(TargetTabungan::class)
            ->call('openTarikModal', $target->id)
            ->assertSet('showTarikModal', true)
            ->set('tarik_nominal', '150000')
            ->call('prosesTarik')
            ->assertHasNoErrors()
            ->assertSet('showTarikModal', false);

        $this->nasabah->refresh();
        $target->refresh();

        // Main balance increased
        $this->assertEquals(1150000, (float) $this->nasabah->saldo);
        // Target balance decreased
        $this->assertEquals(250000, (float) $target->terkumpul_nominal);

        $this->assertDatabaseHas('target_tabungan_histories', [
            'target_tabungan_id' => $target->id,
            'tipe' => 'penarikan',
            'nominal' => 150000,
        ]);
    }

    public function test_deleting_target_automatically_refunds_funds_to_main_balance(): void
    {
        $target = TargetTabunganModel::create([
            'nasabah_id' => $this->nasabah->id,
            'nama_target' => 'Liburan Jogja',
            'kategori' => 'liburan',
            'target_nominal' => 2000000,
            'terkumpul_nominal' => 300000,
            'status' => 'berjalan',
        ]);

        $this->actingAs($this->nasabah, 'nasabah');

        Livewire::test(TargetTabungan::class)
            ->call('openDeleteModal', $target->id)
            ->assertSet('showDeleteModal', true)
            ->call('confirmDelete')
            ->assertHasNoErrors()
            ->assertSet('showDeleteModal', false);

        $this->nasabah->refresh();

        // Refunded to main balance
        $this->assertEquals(1300000, (float) $this->nasabah->saldo);
        $this->assertDatabaseMissing('target_tabungans', [
            'id' => $target->id,
        ]);
    }

    public function test_nasabah_modal_open_close_and_validation(): void
    {
        $this->actingAs($this->nasabah, 'nasabah');

        // Test open and close create modal
        Livewire::test(TargetTabungan::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->call('closeCreateModal')
            ->assertSet('showCreateModal', false);

        // Test validation errors on create
        Livewire::test(TargetTabungan::class)
            ->call('openCreateModal')
            ->set('nama_target', '')
            ->set('target_nominal', '')
            ->call('saveTarget')
            ->assertHasErrors(['nama_target', 'target_nominal']);
    }

    public function test_nasabah_cannot_allocate_more_than_main_balance(): void
    {
        $target = TargetTabunganModel::create([
            'nasabah_id' => $this->nasabah->id,
            'nama_target' => 'Target Besar',
            'kategori' => 'rumah',
            'target_nominal' => 50000000,
            'terkumpul_nominal' => 0,
            'status' => 'berjalan',
        ]);

        $this->actingAs($this->nasabah, 'nasabah');

        // Nasabah balance is 1,000,000. Try to allocate 5,000,000
        Livewire::test(TargetTabungan::class)
            ->call('openAlokasiModal', $target->id)
            ->set('alokasi_nominal', '5000000')
            ->call('prosesAlokasi')
            ->assertHasErrors(['alokasi_nominal']);
    }
}
