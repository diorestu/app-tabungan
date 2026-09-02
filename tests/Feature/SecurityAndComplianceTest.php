<?php

namespace Tests\Feature;

use App\Livewire\Admin\AuditLog;
use App\Livewire\Admin\NasabahManager;
use App\Livewire\Admin\SetorTunai;
use App\Livewire\Admin\TarikTunai;
use App\Livewire\Public\VerifyTransaksi;
use App\Models\ActivityLog;
use App\Models\Nasabah;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\QrCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityAndComplianceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Nasabah $nasabah;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Keamanan',
            'email' => 'security@tabungan.test',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->nasabah = Nasabah::create([
            'nomor_nasabah' => '226080001',
            'nama' => 'Ahmad Fauzi',
            'no_hp' => '081234567890',
            'status' => 'aktif',
            'saldo' => 1000000,
        ]);
    }

    public function test_transaksi_auto_generates_verification_code_and_qr(): void
    {
        $trx = Transaksi::create([
            'kode_transaksi' => 'STR-20260827-0099',
            'nasabah_id' => $this->nasabah->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 200000,
            'saldo_awal' => 1000000,
            'saldo_akhir' => 1200000,
            'keterangan' => 'Setoran tabungan',
        ]);

        $this->assertNotEmpty($trx->verification_code);
        $this->assertStringContainsString('/v/' . $trx->verification_code, $trx->verification_url);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $trx->qr_code_data_uri);
        $this->assertNotEmpty($trx->digital_signature);
    }

    public function test_public_verification_page_renders_valid_certificate(): void
    {
        $trx = Transaksi::create([
            'kode_transaksi' => 'STR-20260827-0100',
            'nasabah_id' => $this->nasabah->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 750000,
            'saldo_awal' => 1000000,
            'saldo_akhir' => 1750000,
            'keterangan' => 'Setoran validasi',
        ]);

        $response = $this->get('/v/' . $trx->verification_code);
        $response->assertStatus(200);
        $response->assertSee('TRANSAKSI RESMI TERVERIFIKASI');
        $response->assertSee('STR-20260827-0100');
        $response->assertSee('Rp 750.000');
        $response->assertSee('Digital Signature');

        // Also works by searching kode_transaksi
        $responseByKode = $this->get('/v/' . $trx->kode_transaksi);
        $responseByKode->assertStatus(200);
        $responseByKode->assertSee('TRANSAKSI RESMI TERVERIFIKASI');
    }

    public function test_public_verification_page_shows_warning_for_invalid_code(): void
    {
        $response = $this->get('/v/INVALID-FAKE-CODE-999');
        $response->assertStatus(200);
        $response->assertSee('DOKUMEN TIDAK TERVERIFIKASI');
        $response->assertSee('Peringatan Keamanan Transaksi');
    }

    public function test_activity_log_is_recorded_on_setor_and_tarik(): void
    {
        $this->actingAs($this->admin, 'web');

        // Setor Tunai
        Livewire::test(SetorTunai::class)
            ->call('selectNasabah', $this->nasabah->id)
            ->set('nominal', 300000)
            ->call('processSetor');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'setor_tunai',
            'user_type' => 'petugas',
        ]);

        // Tarik Tunai
        Livewire::test(TarikTunai::class)
            ->call('selectNasabah', $this->nasabah->id)
            ->set('nominal', 100000)
            ->call('processTarik');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tarik_tunai',
            'user_type' => 'petugas',
        ]);
    }

    public function test_activity_log_is_recorded_on_nasabah_management(): void
    {
        $this->actingAs($this->admin, 'web');

        // Tambah Nasabah
        Livewire::test(NasabahManager::class)
            ->set('nama', 'Citra Lestari')
            ->set('no_hp', '081277778888')
            ->set('wilayah_code', '2')
            ->set('setoran_awal', 50000)
            ->call('saveNasabah');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tambah_nasabah',
        ]);

        // Status Change
        Livewire::test(NasabahManager::class)
            ->call('setStatus', $this->nasabah->id, 'dibekukan');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'status_nasabah',
        ]);
    }

    public function test_admin_can_view_audit_trail_and_export_csv(): void
    {
        $this->actingAs($this->admin, 'web');

        ActivityLog::record('test_event', 'Aktivitas uji coba keamanan', null, ['key' => 'value']);

        $response = $this->get(route('admin.audit-log'));
        $response->assertStatus(200);
        $response->assertSee('Audit Trail & Log Aktivitas');
        $response->assertSee('test_event');

        // Test Livewire component
        Livewire::test(AuditLog::class)
            ->set('action', 'test_event')
            ->assertSee('Aktivitas uji coba keamanan');

        // Test CSV export
        $component = Livewire::test(AuditLog::class);
        $exportResponse = $component->call('exportCsv');
        $this->assertNotNull($exportResponse);
    }
}
