<?php

namespace Tests\Feature;

use App\Livewire\Admin\BagiHasilAdmin;
use App\Livewire\Admin\CetakBuku;
use App\Livewire\Admin\TutupKas;
use App\Models\Nasabah;
use App\Models\TellerShift;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OperationalBackOfficeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Nasabah $nasabah1;
    protected Nasabah $nasabah2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Supervisor Teller',
            'email' => 'supervisor@tabungan.test',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->nasabah1 = Nasabah::create([
            'nomor_nasabah' => '226080001',
            'nama' => 'Hendra Setiawan',
            'no_hp' => '081234567891',
            'status' => 'aktif',
            'saldo' => 500000,
        ]);

        $this->nasabah2 = Nasabah::create([
            'nomor_nasabah' => '226080002',
            'nama' => 'Dewi Anggraeni',
            'no_hp' => '081234567892',
            'status' => 'aktif',
            'saldo' => 1000000,
        ]);
    }

    public function test_teller_can_open_and_close_shift_with_cash_breakdown(): void
    {
        $this->actingAs($this->admin, 'web');

        // 1. Open Shift
        Livewire::test(TutupKas::class)
            ->set('modal_awal_input', 500000)
            ->set('catatan_buka', 'Shift Pagi Teller 1')
            ->call('openShift')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('teller_shifts', [
            'user_id' => $this->admin->id,
            'modal_awal' => 500000,
            'status' => 'buka',
        ]);

        // 2. Perform a transaction during shift
        Transaksi::create([
            'kode_transaksi' => 'STR-20260827-0888',
            'nasabah_id' => $this->nasabah1->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 200000,
            'saldo_awal' => 500000,
            'saldo_akhir' => 700000,
            'keterangan' => 'Setor tabungan',
        ]);

        // Expected System Cash = 500k modal + 200k setor = 700k
        // 3. Count physical denominations (7 x 100k)
        $component = Livewire::test(TutupKas::class)
            ->set('p100k', 7) // 700,000
            ->set('catatan_tutup', 'Kas klop seimbang')
            ->call('submitTutupKas')
            ->assertSet('showBeritaAcaraModal', true);

        $this->assertDatabaseHas('teller_shifts', [
            'user_id' => $this->admin->id,
            'total_setoran' => 200000,
            'saldo_sistem' => 700000,
            'saldo_fisik' => 700000,
            'selisih' => 0,
            'status' => 'ditutup',
        ]);

        // 4. Admin approves shift
        $shift = TellerShift::where('user_id', $this->admin->id)->first();
        $component->call('approveShift', $shift->id);

        $this->assertEquals('disetujui', $shift->fresh()->status);
    }

    public function test_cetak_buku_tabungan_renders_offset_lines_and_transactions(): void
    {
        $this->actingAs($this->admin, 'web');

        Transaksi::create([
            'kode_transaksi' => 'STR-20260827-0101',
            'nasabah_id' => $this->nasabah1->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 150000,
            'saldo_awal' => 500000,
            'saldo_akhir' => 650000,
            'keterangan' => 'Setor rutin',
        ]);

        Livewire::test(CetakBuku::class, ['selectedNasabahId' => $this->nasabah1->id])
            ->set('startLine', 5)
            ->set('pageNumber', 2)
            ->assertSee('Hendra Setiawan')
            ->assertSee('STR')
            ->assertSee('150.000')
            ->assertSee('650.000');
    }

    public function test_monthly_admin_fee_batch_execution(): void
    {
        $this->actingAs($this->admin, 'web');

        // Nasabah 1 (500k), Nasabah 2 (1M). Both >= 50k min balance.
        Livewire::test(BagiHasilAdmin::class)
            ->set('admin_fee_amount', 5000)
            ->set('admin_min_balance', 50000)
            ->set('admin_fee_period', 'Agustus 2026')
            ->call('executeAdminFee')
            ->assertHasNoErrors();

        // Check balances deducted
        $this->assertEquals(495000, $this->nasabah1->fresh()->saldo);
        $this->assertEquals(995000, $this->nasabah2->fresh()->saldo);

        // Check transaction records created
        $this->assertDatabaseHas('transaksis', [
            'nasabah_id' => $this->nasabah1->id,
            'jenis_transaksi' => 'tarik',
            'nominal' => 5000,
            'saldo_akhir' => 495000,
        ]);
    }

    public function test_profit_sharing_batch_execution(): void
    {
        $this->actingAs($this->admin, 'web');

        // Nasabah 1 (500k -> 1% = 5,000), Nasabah 2 (1M -> 1% = 10,000)
        Livewire::test(BagiHasilAdmin::class)
            ->set('bagi_hasil_rate', 1.0)
            ->set('bagi_hasil_min_balance', 100000)
            ->set('bagi_hasil_period', 'Agustus 2026')
            ->call('executeBagiHasil')
            ->assertHasNoErrors();

        // Check balances credited
        $this->assertEquals(505000, $this->nasabah1->fresh()->saldo);
        $this->assertEquals(1010000, $this->nasabah2->fresh()->saldo);

        // Check transaction records created
        $this->assertDatabaseHas('transaksis', [
            'nasabah_id' => $this->nasabah2->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 10000,
            'saldo_akhir' => 1010000,
        ]);
    }
}
