<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login Nasabah - TabunganKu')]
class Login extends Component
{
    #[Validate('required|string|min:4|max:30|regex:/^[A-Za-z0-9\-]+$/', message: 'Nomor ID Nasabah wajib diisi dengan format yang valid.')]
    public string $nomor_nasabah = '';

    #[Validate('required|string|min:8|max:20|regex:/^[0-9\+\s\-]+$/', message: 'Nomor Handphone wajib diisi dengan format nomor telepon yang valid.')]
    public string $no_hp = '';

    public bool $remember = true;
    public ?string $errorMessage = null;

    public function mount(): void
    {
        if (Auth::guard('nasabah')->check()) {
            $this->redirectRoute('nasabah.dashboard', navigate: true);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate('login:nasabah:' . Str::lower(trim($this->nomor_nasabah)) . '|' . request()->ip());
    }

    public function login(): void
    {
        $this->validate();
        $this->errorMessage = null;

        // Security: Check Rate Limiter (Max 5 attempts per 60 seconds)
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            $this->errorMessage = "Terlalu banyak percobaan login yang gagal. Akun diamankan sementara, silakan coba lagi dalam {$seconds} detik.";
            $this->addError('nomor_nasabah', $this->errorMessage);
            return;
        }

        // Clean input: remove accidental leading/trailing spaces
        $nomorNasabah = trim($this->nomor_nasabah);
        $noHp = preg_replace('/[^\d\+]/', '', trim($this->no_hp));

        // Normalize phone number (handle 08... or +62...)
        $nasabah = Nasabah::where('nomor_nasabah', $nomorNasabah)
            ->where(function ($query) use ($noHp) {
                $query->where('no_hp', $noHp)
                      ->orWhere('no_hp', preg_replace('/^0/', '62', $noHp))
                      ->orWhere('no_hp', preg_replace('/^\+?62/', '0', $noHp));
            })
            ->first();

        if (!$nasabah) {
            RateLimiter::hit($this->throttleKey(), 60);
            $this->errorMessage = 'ID Nasabah atau Nomor Handphone tidak sesuai dengan data kami.';
            $this->addError('nomor_nasabah', $this->errorMessage);
            return;
        }

        if ($nasabah->status === 'dibekukan') {
            RateLimiter::hit($this->throttleKey(), 60);
            $this->errorMessage = 'Akun tabungan Anda sedang DIBEKUKAN / DIBLOKIR sementara. Silakan hubungi customer service / petugas teller untuk pembukaan blokir.';
            $this->addError('nomor_nasabah', $this->errorMessage);
            return;
        }

        if ($nasabah->status !== 'aktif') {
            RateLimiter::hit($this->throttleKey(), 60);
            $this->errorMessage = 'Akun tabungan Anda berstatus non-aktif. Silakan hubungi customer service / teller.';
            $this->addError('nomor_nasabah', $this->errorMessage);
            return;
        }

        // Clear rate limiter on successful authentication
        RateLimiter::clear($this->throttleKey());

        Auth::guard('nasabah')->login($nasabah, $this->remember);
        session()->regenerate();

        session()->flash('success', 'Selamat datang kembali, ' . $nasabah->nama . '!');
        $this->redirectRoute('nasabah.dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.nasabah.login');
    }
}
