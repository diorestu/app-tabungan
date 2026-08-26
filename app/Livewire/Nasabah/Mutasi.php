<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;
use App\Models\Setting;
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

    public function exportCsv()
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

        $transaksis = $query->oldest()->get();
        $totalSetor = $transaksis->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik = $transaksis->where('jenis_transaksi', 'tarik')->sum('nominal');

        $namaLembaga = Setting::get('nama_lembaga', 'TabunganKu Digital');
        $alamatLembaga = Setting::get('alamat_lembaga', 'Jl. Jenderal Sudirman No. 123, Jakarta Pusat');
        $teleponLembaga = Setting::get('telepon_lembaga', '(021) 555-0199');

        $periode = 'Semua Transaksi';
        if ($this->startDate && $this->endDate) {
            $periode = date('d/m/Y', strtotime($this->startDate)) . ' s/d ' . date('d/m/Y', strtotime($this->endDate));
        } elseif ($this->startDate) {
            $periode = 'Mulai ' . date('d/m/Y', strtotime($this->startDate));
        } elseif ($this->endDate) {
            $periode = 'Hingga ' . date('d/m/Y', strtotime($this->endDate));
        }

        $filename = 'rekening_koran_' . $nasabah->nomor_nasabah . '_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($nasabah, $transaksis, $namaLembaga, $alamatLembaga, $teleponLembaga, $periode, $totalSetor, $totalTarik) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Lembaga & Dokumen
            fputcsv($handle, [$namaLembaga]);
            fputcsv($handle, [$alamatLembaga . ' | Telp: ' . $teleponLembaga]);
            fputcsv($handle, ['REKENING KORAN / LAPORAN MUTASI TABUNGAN (FORMAT AKUNTANSI)']);
            fputcsv($handle, []);

            // Data Nasabah & Rekening
            fputcsv($handle, ['Nomor Rekening', ': ' . $nasabah->nomor_nasabah]);
            fputcsv($handle, ['Nama Nasabah', ': ' . $nasabah->nama]);
            fputcsv($handle, ['Periode Mutasi', ': ' . $periode]);
            fputcsv($handle, ['Mata Uang', ': IDR (Rupiah)']);
            fputcsv($handle, ['Waktu Unduh', ': ' . now()->format('d/m/Y H:i:s') . ' WIB']);
            fputcsv($handle, ['Total Kredit (Setor)', ': Rp ' . number_format($totalSetor, 0, ',', '.')]);
            fputcsv($handle, ['Total Debit (Tarik)', ': Rp ' . number_format($totalTarik, 0, ',', '.')]);
            fputcsv($handle, ['Saldo Akhir', ': Rp ' . number_format((float)$nasabah->saldo, 0, ',', '.')]);
            fputcsv($handle, []);

            // Tabel Mutasi Akuntansi
            fputcsv($handle, [
                'No',
                'Tanggal & Waktu',
                'Kode Transaksi',
                'Uraian Transaksi',
                'Debit / Penarikan (IDR)',
                'Kredit / Setoran (IDR)',
                'Saldo Akhir (IDR)',
            ]);

            $no = 1;
            foreach ($transaksis as $trx) {
                fputcsv($handle, [
                    $no++,
                    $trx->created_at->format('d/m/Y H:i:s'),
                    $trx->kode_transaksi,
                    $trx->keterangan ?: ($trx->jenis_transaksi === 'setor' ? 'Setor Tunai' : 'Penarikan Tunai'),
                    $trx->jenis_transaksi === 'tarik' ? number_format((float)$trx->nominal, 0, ',', '.') : '0',
                    $trx->jenis_transaksi === 'setor' ? number_format((float)$trx->nominal, 0, ',', '.') : '0',
                    number_format((float)$trx->saldo_akhir, 0, ',', '.'),
                ]);
            }

            // Summary Footer
            fputcsv($handle, []);
            fputcsv($handle, [
                '',
                '',
                '',
                'TOTAL MUTASI',
                number_format($totalTarik, 0, ',', '.'),
                number_format($totalSetor, 0, ',', '.'),
                number_format((float)$nasabah->saldo, 0, ',', '.'),
            ]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

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
