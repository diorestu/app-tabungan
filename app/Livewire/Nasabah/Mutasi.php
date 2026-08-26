<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.nasabah')]
#[Title('Riwayat Mutasi Tabungan - TabunganKu')]
class Mutasi extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $jenis = '';

    #[Url]
    public string $startDate = '';

    #[Url]
    public string $endDate = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingJenis(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->jenis = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
    }

    public function render()
    {
        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();

        $query = Transaksi::where('nasabah_id', $nasabah->id);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('kode_transaksi', 'like', '%' . $this->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->jenis)) {
            $query->where('jenis_transaksi', $this->jenis);
        }

        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $filteredSetor = (clone $query)->where('jenis_transaksi', 'setor')->sum('nominal');
        $filteredTarik = (clone $query)->where('jenis_transaksi', 'tarik')->sum('nominal');

        $transaksis = $query->latest()->paginate(10);

        return view('livewire.nasabah.mutasi', [
            'nasabah' => $nasabah,
            'transaksis' => $transaksis,
            'filteredSetor' => $filteredSetor,
            'filteredTarik' => $filteredTarik,
        ]);
    }
}
