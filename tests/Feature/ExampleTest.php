<?php

namespace Tests\Feature;

use App\Livewire\Admin\NasabahManager;
use App\Livewire\Admin\SetorTunai;
use App\Livewire\Admin\TarikTunai;
use App\Livewire\Nasabah\Login as NasabahLogin;
use App\Models\Nasabah;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('TabunganKu');
        $response->assertSee('Portal Nasabah Mandiri');
    }

    public function test_nasabah_can_login_using_id_and_phone_number_only(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'saldo' => 500000,
            'status' => 'aktif',
        ]);

        Livewire::test(NasabahLogin::class)
            ->set('nomor_nasabah', 'NAS-2026-0001')
            ->set('no_hp', '081234567890')
            ->call('login')
            ->assertRedirect(route('nasabah.dashboard'));

        $this->assertAuthenticatedAs($nasabah, 'nasabah');
    }

    public function test_nasabah_cannot_login_with_invalid_credentials(): void
    {
        Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'saldo' => 500000,
            'status' => 'aktif',
        ]);

        Livewire::test(NasabahLogin::class)
            ->set('nomor_nasabah', 'NAS-2026-0001')
            ->set('no_hp', '089999999999')
            ->call('login')
            ->assertHasErrors(['nomor_nasabah']);
    }

    public function test_admin_can_deposit_money_into_nasabah_account(): void
    {
        $admin = User::create([
            'name' => 'Teller Admin',
            'email' => 'admin@tabungan.test',
            'password' => bcrypt('password'),
        ]);

        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'saldo' => 100000,
            'status' => 'aktif',
        ]);

        $this->actingAs($admin);

        Livewire::test(SetorTunai::class, ['nasabah_id' => $nasabah->id])
            ->set('nominal', 250000)
            ->set('keterangan', 'Setor tabungan tunai')
            ->call('processSetor')
            ->assertHasNoErrors();

        $nasabah->refresh();
        $this->assertEquals(350000, $nasabah->saldo);

        $this->assertDatabaseHas('transaksis', [
            'nasabah_id' => $nasabah->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 250000,
            'saldo_awal' => 100000,
            'saldo_akhir' => 350000,
        ]);
    }

    public function test_admin_can_withdraw_money_from_nasabah_account(): void
    {
        $admin = User::create([
            'name' => 'Teller Admin',
            'email' => 'admin@tabungan.test',
            'password' => bcrypt('password'),
        ]);

        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'saldo' => 500000,
            'status' => 'aktif',
        ]);

        $this->actingAs($admin);

        Livewire::test(TarikTunai::class, ['nasabah_id' => $nasabah->id])
            ->set('nominal', 200000)
            ->set('keterangan', 'Tarik tabungan untuk biaya sekolah')
            ->call('processTarik')
            ->assertHasNoErrors();

        $nasabah->refresh();
        $this->assertEquals(300000, $nasabah->saldo);

        $this->assertDatabaseHas('transaksis', [
            'nasabah_id' => $nasabah->id,
            'jenis_transaksi' => 'tarik',
            'nominal' => 200000,
            'saldo_awal' => 500000,
            'saldo_akhir' => 300000,
        ]);
    }

    public function test_admin_cannot_withdraw_more_than_available_balance(): void
    {
        $admin = User::create([
            'name' => 'Teller Admin',
            'email' => 'admin@tabungan.test',
            'password' => bcrypt('password'),
        ]);

        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'saldo' => 100000,
            'status' => 'aktif',
        ]);

        $this->actingAs($admin);

        Livewire::test(TarikTunai::class, ['nasabah_id' => $nasabah->id])
            ->set('nominal', 500000)
            ->call('processTarik')
            ->assertHasErrors(['nominal']);

        $nasabah->refresh();
        $this->assertEquals(100000, $nasabah->saldo);
    }
}

