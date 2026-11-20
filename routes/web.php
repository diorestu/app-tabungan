<?php

use App\Livewire\Admin\AuditLog;
use App\Livewire\Admin\BagiHasilAdmin;
use App\Livewire\Admin\CetakBuku;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\DompetKasManager;
use App\Livewire\Admin\NasabahManager;
use App\Livewire\Admin\Pengaturan;
use App\Livewire\Admin\SetorTunai;
use App\Livewire\Admin\TarikTunai;
use App\Livewire\Admin\TransaksiManager;
use App\Livewire\Admin\TutupKas;
use App\Livewire\Auth\Login as AdminLogin;
use App\Livewire\Nasabah\Dashboard as NasabahDashboard;
use App\Livewire\Nasabah\Login as NasabahLogin;
use App\Livewire\Nasabah\Mutasi as NasabahMutasi;
use App\Livewire\Nasabah\Profil as NasabahProfil;
use App\Livewire\Nasabah\TargetTabungan as NasabahTargetTabungan;
use App\Livewire\Public\VerifyTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Theme Switcher Session Store
Route::post('/theme', function (Request $request) {
    $theme = $request->input('theme', 'dark');
    if (in_array($theme, ['light', 'dark'])) {
        session(['theme' => $theme]);
    }

    return response()->json(['status' => 'success', 'theme' => session('theme')]);
})->name('theme.update');

// ==========================================
// PORTAL NASABAH (Prefix: /app)
// ==========================================
Route::prefix('app')->name('nasabah.')->group(function () {
    Route::middleware(['guest:nasabah', 'throttle:login'])->group(function () {
        Route::get('/login', NasabahLogin::class)->name('login');
    });

    Route::post('/logout', function () {
        Auth::guard('nasabah')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('nasabah.login');
    })->name('logout');

    Route::middleware(['auth.nasabah'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('nasabah.dashboard');
        });
        Route::get('/dashboard', NasabahDashboard::class)->name('dashboard');
        Route::get('/mutasi', NasabahMutasi::class)->name('mutasi');
        Route::get('/target', NasabahTargetTabungan::class)->name('target');
        Route::get('/profil', NasabahProfil::class)->name('profil');
    });
});

// ==========================================
// PORTAL PETUGAS / ADMIN (Email + Password)
// ==========================================
Route::middleware(['guest', 'throttle:login'])->group(function () {
    Route::get('/login', AdminLogin::class)->name('login');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

// Public Transaction Verification Route (Anti-Counterfeit QR Code)
Route::get('/v/{code}', VerifyTransaksi::class)->name('transaksi.verify');

// Admin PWA Web App Manifest
Route::get('/admin/manifest.json', function () {
    return response()->json([
        'name' => 'TabunganKu - Petugas',
        'short_name' => 'TabunganKu',
        'description' => 'Sistem Tabungan Nasabah - Panel Petugas & Kasir',
        'start_url' => '/admin/dashboard',
        'scope' => '/',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black-translucent',
        'background_color' => '#09090b',
        'theme_color' => '#059669',
        'icons' => [
            ['src' => '/images/icons/icon-72x72.png', 'sizes' => '72x72', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/images/icons/icon-96x96.png', 'sizes' => '96x96', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/images/icons/icon-128x128.png', 'sizes' => '128x128', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/images/icons/icon-144x144.png', 'sizes' => '144x144', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/images/icons/icon-152x152.png', 'sizes' => '152x152', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/images/icons/icon-192x192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ['src' => '/images/icons/icon-384x384.png', 'sizes' => '384x384', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/images/icons/icon-512x512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
        ],
        'shortcuts' => [
            [
                'name' => 'Setor Tunai',
                'description' => 'Pencatatan setoran tabungan',
                'url' => '/admin/setor',
                'icons' => [['src' => '/images/icons/icon-96x96.png', 'sizes' => '96x96']],
            ],
            [
                'name' => 'Tarik Tunai',
                'description' => 'Pencatatan penarikan tabungan',
                'url' => '/admin/tarik',
                'icons' => [['src' => '/images/icons/icon-96x96.png', 'sizes' => '96x96']],
            ],
            [
                'name' => 'Data Nasabah',
                'description' => 'Kelola rekening nasabah',
                'url' => '/admin/nasabah',
                'icons' => [['src' => '/images/icons/icon-96x96.png', 'sizes' => '96x96']],
            ],
            [
                'name' => 'Dompet Kas',
                'description' => 'Kelola dompet kas penerima uang masuk',
                'url' => '/admin/dompet-kas',
                'icons' => [['src' => '/images/icons/icon-96x96.png', 'sizes' => '96x96']],
            ],
        ],
    ], 200, ['Content-Type' => 'application/manifest+json; charset=UTF-8']);
})->name('admin.manifest');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/nasabah', NasabahManager::class)->name('nasabah');
        Route::get('/transaksi', TransaksiManager::class)->name('transaksi');
        Route::get('/setor', SetorTunai::class)->name('setor');
        Route::get('/tarik', TarikTunai::class)->name('tarik');
        Route::get('/cetak-buku', CetakBuku::class)->name('cetak-buku');
        Route::get('/dompet-kas', DompetKasManager::class)->name('dompet-kas');
        Route::get('/tutup-kas', TutupKas::class)->name('tutup-kas');
        Route::get('/bagi-hasil', BagiHasilAdmin::class)->name('bagi-hasil');
        Route::get('/pengaturan', Pengaturan::class)->name('pengaturan');
        Route::get('/audit-log', AuditLog::class)->name('audit-log');
    });
});
