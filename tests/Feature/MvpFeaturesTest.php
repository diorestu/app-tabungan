<?php

namespace Tests\Feature;

use App\Livewire\Admin\NasabahManager;
use App\Livewire\Admin\Pengaturan;
use App\Livewire\Admin\TransaksiManager;
use App\Livewire\Auth\Login as AdminLogin;
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
            'name' => 'Admin Utama',
            'email' => 'admin@tabungan.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
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

    public function test_admin_can_view_and_export_passbook_statement(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => 'NAS-2026-0002',
            'nama' => 'Siti Nurhaliza',
            'no_hp' => '081299887766',
            'status' => 'aktif',
            'saldo' => 750000,
        ]);

        Transaksi::create([
            'kode_transaksi' => 'TRX-SETOR-0002',
            'nasabah_id' => $nasabah->id,
            'user_id' => $this->admin->id,
            'jenis_transaksi' => 'setor',
            'nominal' => 750000,
            'saldo_awal' => 0,
            'saldo_akhir' => 750000,
            'keterangan' => 'Setor tabungan',
        ]);

        $this->actingAs($this->admin);

        $component = Livewire::test(NasabahManager::class)
            ->call('openBukuTabungan', $nasabah->id)
            ->assertSet('showBukuTabunganModal', true)
            ->assertSee('LEMBAR BUKU TABUNGAN')
            ->assertSee('Siti Nurhaliza');

        $csvResponse = $component->call('exportBukuCsv');
        $csvResponse->assertFileDownloaded();
    }

    public function test_admin_can_manage_petugas_teller_crud(): void
    {
        $this->actingAs($this->admin);

        // 1. Create new Teller
        Livewire::test(Pengaturan::class)
            ->call('openCreatePetugasModal')
            ->set('petugas_name', 'Rina Anggraini')
            ->set('petugas_email', 'rina.teller@tabungan.test')
            ->set('petugas_role', 'teller')
            ->set('petugas_password', 'secret123')
            ->set('petugas_password_confirmation', 'secret123')
            ->call('savePetugas');

        $this->assertDatabaseHas('users', [
            'email' => 'rina.teller@tabungan.test',
            'role' => 'teller',
            'status' => 'aktif',
        ]);

        $teller = User::where('email', 'rina.teller@tabungan.test')->first();

        // 2. Toggle Status to non-aktif
        Livewire::test(Pengaturan::class)
            ->call('togglePetugasStatus', $teller->id);

        $this->assertEquals('nonaktif', $teller->fresh()->status);

        // 3. Edit Petugas
        Livewire::test(Pengaturan::class)
            ->call('openEditPetugasModal', $teller->id)
            ->set('petugas_name', 'Rina Anggraini S.E.')
            ->set('petugas_status', 'aktif')
            ->call('updatePetugas');

        $this->assertEquals('Rina Anggraini S.E.', $teller->fresh()->name);
        $this->assertEquals('aktif', $teller->fresh()->status);

        // 4. Delete Petugas
        Livewire::test(Pengaturan::class)
            ->call('openDeletePetugasModal', $teller->id)
            ->call('confirmDeletePetugas');

        $this->assertDatabaseMissing('users', ['id' => $teller->id]);
    }

    public function test_inactive_petugas_cannot_login(): void
    {
        User::create([
            'name' => 'Petugas Nonaktif',
            'email' => 'nonaktif@tabungan.test',
            'password' => Hash::make('password123'),
            'role' => 'teller',
            'status' => 'nonaktif',
        ]);

        Livewire::test(AdminLogin::class)
            ->set('email', 'nonaktif@tabungan.test')
            ->set('password', 'password123')
            ->call('login')
            ->assertSee('dinonaktifkan');

        $this->assertFalse(auth()->guard('web')->check());
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

    public function test_nasabah_can_view_profile_page(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => '226080001',
            'nama' => 'Rahmat Hidayat',
            'no_hp' => '081298765432',
            'nik' => '3201123456780001',
            'alamat' => 'Jl. Merdeka No. 45',
            'status' => 'aktif',
            'saldo' => 750000,
        ]);

        $this->actingAs($nasabah, 'nasabah');

        $response = $this->get(route('nasabah.profil'));
        $response->assertStatus(200);
        $response->assertSee('Rahmat Hidayat');
        $response->assertSee('226080001');
        $response->assertSee('3201123456780001');
        $response->assertSee('Data Rekening');
    }

    public function test_nasabah_can_export_rekening_koran_csv_accounting_format(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => '226080001',
            'nama' => 'Rahmat Hidayat',
            'no_hp' => '081298765432',
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
            'keterangan' => 'Setoran Tunai Awal',
        ]);

        $this->actingAs($nasabah, 'nasabah');

        $response = Livewire::test(\App\Livewire\Nasabah\Mutasi::class)
            ->call('exportCsv');

        $response->assertFileDownloaded();
    }

    public function test_landing_page_redirect_buttons_for_guest(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Login Nasabah');
        $response->assertDontSee('Login Petugas');
    }

    public function test_landing_page_redirect_buttons_for_authenticated_nasabah(): void
    {
        $nasabah = Nasabah::create([
            'nomor_nasabah' => '226080001',
            'nama' => 'Rahmat Hidayat',
            'no_hp' => '081298765432',
            'status' => 'aktif',
            'saldo' => 500000,
        ]);

        $this->actingAs($nasabah, 'nasabah');

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Saya');
        $response->assertSee('Buka Dashboard Nasabah Saya');
        $response->assertSee(route('nasabah.dashboard'));
        $response->assertDontSee('500.000');
        $response->assertDontSee('226080001');
    }

    public function test_landing_page_redirect_buttons_for_authenticated_petugas(): void
    {
        $this->actingAs($this->admin, 'web');

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Petugas');
        $response->assertSee(route('admin.dashboard'));
    }

    public function test_nasabah_login_rate_limiting_after_repeated_failures(): void
    {
        \Illuminate\Support\Facades\RateLimiter::clear(
            \Illuminate\Support\Str::transliterate('login:nasabah:999999999|127.0.0.1')
        );

        $component = Livewire::test(\App\Livewire\Nasabah\Login::class)
            ->set('nomor_nasabah', '999999999')
            ->set('no_hp', '081299999999');

        for ($i = 0; $i < 5; $i++) {
            $component->call('login');
        }

        // The 6th attempt should trigger rate limiting message
        $component->call('login')
            ->assertHasErrors(['nomor_nasabah'])
            ->assertSee('Terlalu banyak percobaan login');
    }

    public function test_admin_login_rate_limiting_after_repeated_failures(): void
    {
        \Illuminate\Support\Facades\RateLimiter::clear(
            \Illuminate\Support\Str::transliterate('login:admin:wrong@admin.test|127.0.0.1')
        );

        $component = Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', 'wrong@admin.test')
            ->set('password', 'wrongpassword');

        for ($i = 0; $i < 5; $i++) {
            $component->call('login');
        }

        // The 6th attempt should trigger rate limiting message
        $component->call('login')
            ->assertHasErrors(['email'])
            ->assertSee('Terlalu banyak percobaan login');
    }
}
