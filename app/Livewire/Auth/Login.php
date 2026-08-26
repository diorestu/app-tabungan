<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login Petugas / Admin - TabunganKu')]
class Login extends Component
{
    #[Validate('required|email', message: 'Email tidak valid.')]
    public string $email = 'admin@tabungan.test';

    #[Validate('required', message: 'Password wajib diisi.')]
    public string $password = 'password';

    public bool $remember = true;
    public ?string $errorMessage = null;

    public function mount(): void
    {
        if (Auth::guard('web')->check()) {
            $this->redirectRoute('admin.dashboard', navigate: true);
        }
    }

    public function login(): void
    {
        $this->validate();
        $this->errorMessage = null;

        if (!Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->errorMessage = 'Email atau password petugas tidak sesuai.';
            return;
        }

        $user = Auth::guard('web')->user();
        if ($user && $user->status !== 'aktif') {
            Auth::guard('web')->logout();
            $this->errorMessage = 'Akun petugas Anda telah dinonaktifkan oleh administrator. Silakan hubungi admin utama.';
            return;
        }

        session()->regenerate();
        session()->flash('success', 'Selamat datang di Panel Petugas Tabungan.');
        $this->redirectRoute('admin.dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
