<?php

namespace App\Livewire\Admin;

use App\Models\Transaksi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Riwayat Transaksi Tabungan - TabunganKu')]
class TransaksiManager extends Component
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

    public ?Transaksi $selectedReceipt = null;
    public bool $showReceiptModal = false;

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

    public function openReceipt(int $id): void
    {
        $this->selectedReceipt = Transaksi::with(['nasabah', 'user'])->findOrFail($id);
        $this->showReceiptModal = true;
    }

    public function closeReceipt(): void
    {
        $this->showReceiptModal = false;
        $this->selectedReceipt = null;
    }

    public function exportCsv()
    {
        $query = Transaksi::with(['nasabah', 'user']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('kode_transaksi', 'like', '%' . $this->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('nasabah', function ($nq) {
                      $nq->where('nama', 'like', '%' . $this->search . '%')
                         ->orWhere('nomor_nasabah', 'like', '%' . $this->search . '%')
                         ->orWhere('no_hp', 'like', '%' . $this->search . '%');
                  });
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

        $transaksis = $query->latest()->get();
        $filename = 'laporan-transaksi-tabungan-' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($transaksis) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header Row
            fputcsv($handle, [
                'No',
                'Kode Transaksi',
                'Tanggal & Waktu',
                'ID Nasabah',
                'Nama Nasabah',
                'No. Handphone',
                'Jenis Transaksi',
                'Nominal (Rp)',
                'Saldo Awal (Rp)',
                'Saldo Akhir (Rp)',
                'Petugas / Teller',
                'Keterangan',
            ]);

            $no = 1;
            foreach ($transaksis as $trx) {
                fputcsv($handle, [
                    $no++,
                    $trx->kode_transaksi,
                    $trx->created_at->format('d/m/Y H:i:s'),
                    $trx->nasabah?->nomor_nasabah ?? '-',
                    $trx->nasabah?->nama ?? '-',
                    $trx->nasabah?->no_hp ?? '-',
                    strtoupper($trx->jenis_transaksi),
                    number_format((float)$trx->nominal, 0, ',', '.'),
                    number_format((float)$trx->saldo_awal, 0, ',', '.'),
                    number_format((float)$trx->saldo_akhir, 0, ',', '.'),
                    $trx->user?->name ?? 'Sistem',
                    $trx->keterangan ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function render()
    {
        $query = Transaksi::with(['nasabah', 'user']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('kode_transaksi', 'like', '%' . $this->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('nasabah', function ($nq) {
                      $nq->where('nama', 'like', '%' . $this->search . '%')
                         ->orWhere('nomor_nasabah', 'like', '%' . $this->search . '%')
                         ->orWhere('no_hp', 'like', '%' . $this->search . '%');
                  });
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

        $totalSetor = (clone $query)->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik = (clone $query)->where('jenis_transaksi', 'tarik')->sum('nominal');

        $transaksis = $query->latest()->paginate(15);

        return view('livewire.admin.transaksi-manager', [
            'transaksis' => $transaksis,
            'totalSetor' => $totalSetor,
            'totalTarik' => $totalTarik,
        ]);
    }
}
