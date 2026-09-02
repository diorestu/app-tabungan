<?php

namespace App\Livewire\Public;

use App\Models\Setting;
use App\Models\Transaksi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Verifikasi Dokumen Transaksi Sah - TabunganKu')]
class VerifyTransaksi extends Component
{
    public string $code = '';
    public ?Transaksi $transaksi = null;
    public bool $isValid = false;

    public function mount(string $code): void
    {
        $this->code = trim($code);

        $this->transaksi = Transaksi::with(['nasabah', 'user'])
            ->where('verification_code', $this->code)
            ->orWhere('kode_transaksi', $this->code)
            ->first();

        $this->isValid = (bool) $this->transaksi;
    }

    public function render()
    {
        $settings = Setting::getAllSettings();

        return view('livewire.public.verify-transaksi', [
            'settings' => $settings,
        ]);
    }
}
