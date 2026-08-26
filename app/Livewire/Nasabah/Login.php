<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login Nasabah - TabunganKu')]
class Login extends Component
{
    #[Validate('required', message: 'Nomor ID Nasabah wajib diisi.')]
    public string $nomor_nasabah = '';

    #[Validate('required', message: 'Nomor Handphone wajib diisi.')]
    public string $no_hp = '';

    public bool $remember = true;
    public ?string $errorMessage = null;

    public function mount(): void
    {
        if (Auth::guard('nasabah')->check()) {
            $this->redirectRoute('nasabah.dashboard', navigate: true);
        }
    }

    public function fillSample(string $nomor, string $hp): void
    {
        $this->nomor_nasabah = $nomor;
        $this->no_hp = $hp;
        $this->errorMessage = null;
    }

    public function login(): void
    {
        $this->validate();
        $this->errorMessage = null;

        // Clean input: remove accidental leading/trailing spaces
        $nomorNasabah = trim($this->nomor_nasabah);
        $noHp = trim($this->no_hp);

        // Normalize phone number (handle 08... or +62...)
        $nasabah = Nasabah::where('nomor_nasabah', $nomorNasabah)
            ->where(function ($query) use ($noHp) {
                $query->where('no_hp', $noHp)
                      ->orWhere('no_hp', preg_replace('/^0/', '62', $noHp))
                      ->orWhere('no_hp', preg_replace('/^\+?62/', '0', $noHp));
            })
            ->first();

        if (!$nasabah) {
            $this->errorMessage = 'ID Nasabah atau Nomor Handphone tidak sesuai dengan data kami.';
            $this->addError('nomor_nasabah', $this->errorMessage);
            return;
        }

        if ($nasabah->status !== 'aktif') {
            $this->errorMessage = 'Akun tabungan Anda berstatus non-aktif. Silakan hubungi customer service / teller.';
            $this->addError('nomor_nasabah', $this->errorMessage);
            return;
        }

        Auth::guard('nasabah')->login($nasabah, $this->remember);
        session()->regenerate();

        session()->flash('success', 'Selamat datang kembali, ' . $nasabah->nama . '!');
        $this->redirectRoute('nasabah.dashboard', navigate: true);
    }

    public function render()
    {
        $sampleNasabahs = Nasabah::where('status', 'aktif')->take(3)->get();

        return view('livewire.nasabah.login', [
            'sampleNasabahs' => $sampleNasabahs,
        ]);
    }
}
