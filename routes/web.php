<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\NasabahManager;
use App\Livewire\Admin\SetorTunai;
use App\Livewire\Admin\TarikTunai;
use App\Livewire\Admin\TransaksiManager;
use App\Livewire\Auth\Login as AdminLogin;
use App\Livewire\Nasabah\Dashboard as NasabahDashboard;
use App\Livewire\Nasabah\Login as NasabahLogin;
use App\Livewire\Nasabah\Mutasi as NasabahMutasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Theme Switcher Session Store
Route::post('/theme', function (\Illuminate\Http\Request $request) {
    $theme = $request->input('theme', 'dark');
    if (in_array($theme, ['light', 'dark'])) {
        session(['theme' => $theme]);
    }
    return response()->json(['status' => 'success', 'theme' => session('theme')]);
})->name('theme.update');

// ==========================================
// PORTAL NASABAH (ID Nasabah + No HP Auth)
// ==========================================
Route::middleware('guest:nasabah')->group(function () {
    Route::get('/nasabah/login', NasabahLogin::class)->name('nasabah.login');
});

Route::post('/nasabah/logout', function () {
    Auth::guard('nasabah')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('nasabah.login');
})->name('nasabah.logout');

Route::middleware(['auth.nasabah'])->prefix('nasabah')->name('nasabah.')->group(function () {
    Route::get('/dashboard', NasabahDashboard::class)->name('dashboard');
    Route::get('/mutasi', NasabahMutasi::class)->name('mutasi');
});

// ==========================================
// PORTAL PETUGAS / ADMIN (Email + Password)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', AdminLogin::class)->name('login');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

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
    });
});

