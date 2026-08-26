<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login Petugas / Admin - TabunganKu')]
class Login extends Component
{
    #[Validate('required|email|max:100', message: 'Email tidak valid.')]
    public string $email = '';

    #[Validate('required|string|min:4|max:100', message: 'Password wajib diisi.')]
    public string $password = '';

    public bool $remember = false;
    public ?string $errorMessage = null;

    public function mount(): void
    {
        if (Auth::guard('web')->check()) {
            $this->redirectRoute('admin.dashboard', navigate: true);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate('login:admin:' . Str::lower(trim($this->email)) . '|' . request()->ip());
    }

    public function login(): void
    {
        $this->validate();
        $this->errorMessage = null;

        // Security: Check Rate Limiter (Max 5 attempts per 60 seconds)
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            $this->errorMessage = "Terlalu banyak percobaan login yang gagal. Akun diamankan sementara, silakan coba lagi dalam {$seconds} detik.";
            $this->addError('email', $this->errorMessage);
            return;
        }

        $email = trim($this->email);

        if (!Auth::guard('web')->attempt(['email' => $email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey(), 60);
            $this->errorMessage = 'Email atau password petugas tidak sesuai.';
            $this->addError('email', $this->errorMessage);
            return;
        }

        $user = Auth::guard('web')->user();
        if ($user && $user->status !== 'aktif') {
            RateLimiter::hit($this->throttleKey(), 60);
            Auth::guard('web')->logout();
            $this->errorMessage = 'Akun petugas Anda telah dinonaktifkan oleh administrator. Silakan hubungi admin utama.';
            $this->addError('email', $this->errorMessage);
            return;
        }

        // Clear rate limiter on successful authentication
        RateLimiter::clear($this->throttleKey());

        session()->regenerate();
        session()->flash('success', 'Selamat datang di Panel Petugas Tabungan.');
        $this->redirectRoute('admin.dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
