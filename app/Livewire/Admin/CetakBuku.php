<?php

namespace App\Livewire\Admin;

use App\Models\Nasabah;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Cetak Buku Tabungan Fisik (Passbook Printer) - TabunganKu')]
class CetakBuku extends Component
{
    #[Url]
    public ?int $selectedNasabahId = null;

    public string $search = '';
    public int $startLine = 1;
    public int $pageNumber = 1;
    public int $linesPerPage = 20;
    public string $startDate = '';
    public string $endDate = '';

    public ?Nasabah $selectedNasabah = null;

    public function mount(): void
    {
        if ($this->selectedNasabahId) {
            $this->selectNasabah($this->selectedNasabahId);
        }
    }

    public function selectNasabah(int $id): void
    {
        $this->selectedNasabahId = $id;
        $this->selectedNasabah = Nasabah::findOrFail($id);
    }

    public function clearNasabah(): void
    {
        $this->selectedNasabahId = null;
        $this->selectedNasabah = null;
    }

    public function render()
    {
        $settings = Setting::getAllSettings();
        $nasabahs = [];

        if (!empty($this->search)) {
            $nasabahs = Nasabah::where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('nomor_nasabah', 'like', '%' . $this->search . '%')
                ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                ->take(6)
                ->get();
        }

        $transaksis = collect();
        if ($this->selectedNasabah) {
            $query = $this->selectedNasabah->transaksis()->with('user')->oldest();

            if (!empty($this->startDate)) {
                $query->whereDate('created_at', '>=', $this->startDate);
            }

            if (!empty($this->endDate)) {
                $query->whereDate('created_at', '<=', $this->endDate);
            }

            $transaksis = $query->get();
        }

        return view('livewire.admin.cetak-buku', [
            'settings' => $settings,
            'nasabahs' => $nasabahs,
            'transaksis' => $transaksis,
        ]);
    }
}
