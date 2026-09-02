<?php

namespace Tests\Feature;

use App\Livewire\Admin\Pengaturan;
use App\Livewire\Admin\SetorTunai;
use App\Livewire\Admin\TarikTunai;
use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WhatsAppGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Nasabah $nasabah;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@tabungan.test',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->nasabah = Nasabah::create([
            'nomor_nasabah' => '226080099',
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'status' => 'aktif',
            'saldo' => 1000000,
        ]);
    }

    public function test_phone_number_formatting(): void
    {
        $this->assertEquals('6281234567890', WhatsAppService::formatPhoneNumber('081234567890'));
        $this->assertEquals('6281234567890', WhatsAppService::formatPhoneNumber('+62 812-3456-7890'));
        $this->assertEquals('6281234567890', WhatsAppService::formatPhoneNumber('81234567890'));
    }

    public function test_build_transaction_message_and_direct_url(): void
    {
        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-SET-001',
            'nasabah_id' => $this->nasabah->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 250000,
            'saldo_awal' => 1000000,
            'saldo_akhir' => 1250000,
            'keterangan' => 'Setor tunai tabungan',
        ]);

        $message = WhatsAppService::buildTransactionMessage($transaksi);
        $this->assertStringContainsString('Budi Santoso', $message);
        $this->assertStringContainsString('226080099', $message);
        $this->assertStringContainsString('Rp 250.000', $message);
        $this->assertStringContainsString('Rp 1.250.000', $message);

        $url = WhatsAppService::getDirectWhatsAppUrl($transaksi);
        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $url);
    }

    public function test_mock_driver_send_message(): void
    {
        Setting::set('wa_gateway_enabled', '1');
        Setting::set('wa_provider', 'mock');

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-SET-002',
            'nasabah_id' => $this->nasabah->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 100000,
            'saldo_awal' => 1000000,
            'saldo_akhir' => 1100000,
            'keterangan' => 'Setor rutin',
        ]);

        $waService = app(WhatsAppService::class);
        $result = $waService->sendTransactionReceipt($transaksi);

        $this->assertTrue($result['success']);
        $this->assertEquals('mock', $result['provider']);
    }

    public function test_admin_can_save_whatsapp_settings_and_send_test(): void
    {
        $this->actingAs($this->admin, 'web');

        Livewire::test(Pengaturan::class)
            ->set('activeTab', 'wa')
            ->set('wa_gateway_enabled', true)
            ->set('wa_provider', 'fonnte')
            ->set('wa_api_token', 'sample-fonnte-token-123')
            ->set('wa_sender_number', '081298765432')
            ->set('wa_auto_send', true)
            ->call('saveWhatsAppSettings')
            ->assertHasNoErrors();

        $this->assertEquals('1', Setting::get('wa_gateway_enabled'));
        $this->assertEquals('fonnte', Setting::get('wa_provider'));
        $this->assertEquals('sample-fonnte-token-123', Setting::get('wa_api_token'));

        // Test WhatsApp Modal
        Livewire::test(Pengaturan::class)
            ->set('wa_provider', 'mock')
            ->set('test_wa_phone', '081298765432')
            ->set('test_wa_message', 'Halo ini pesan testing')
            ->call('sendTestWhatsApp', app(WhatsAppService::class))
            ->assertSet('test_wa_success', true);
    }

    public function test_setor_tunai_dispatches_whatsapp_when_enabled(): void
    {
        $this->actingAs($this->admin, 'web');

        Setting::set('wa_gateway_enabled', '1');
        Setting::set('wa_provider', 'mock');
        Setting::set('wa_auto_send', '1');

        Livewire::test(SetorTunai::class)
            ->call('selectNasabah', $this->nasabah->id)
            ->set('nominal', 500000)
            ->set('keterangan', 'Setoran bulanan')
            ->call('processSetor')
            ->assertSet('showSuccessModal', true)
            ->assertSet('waStatusMessage', 'Struk berhasil terkirim via WhatsApp.');

        $this->assertDatabaseHas('transaksis', [
            'nasabah_id' => $this->nasabah->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 500000,
        ]);
    }

    public function test_tarik_tunai_dispatches_whatsapp_when_enabled(): void
    {
        $this->actingAs($this->admin, 'web');

        Setting::set('wa_gateway_enabled', '1');
        Setting::set('wa_provider', 'mock');
        Setting::set('wa_auto_send', '1');

        Livewire::test(TarikTunai::class)
            ->call('selectNasabah', $this->nasabah->id)
            ->set('nominal', 200000)
            ->set('keterangan', 'Tarik biaya sekolah')
            ->call('processTarik')
            ->assertSet('showSuccessModal', true)
            ->assertSet('waStatusMessage', 'Struk berhasil terkirim via WhatsApp.');

        $this->assertDatabaseHas('transaksis', [
            'nasabah_id' => $this->nasabah->id,
            'jenis_transaksi' => 'tarik',
            'nominal' => 200000,
        ]);
    }
}
