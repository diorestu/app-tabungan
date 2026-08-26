<?php

namespace App\Livewire\Admin;

use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Data Nasabah - TabunganKu')]
class NasabahManager extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    // Modal Create / Edit / Delete / Buku Tabungan states
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDetailModal = false;
    public bool $showDeleteModal = false;
    public bool $showBukuTabunganModal = false;

    // Delete state
    public ?Nasabah $deleteNasabah = null;

    // Buku Tabungan / Statement State
    public ?Nasabah $bukuNasabah = null;
    public string $bukuStartDate = '';
    public string $bukuEndDate = '';

    // Form fields for create/edit
    public ?int $selectedNasabahId = null;
    public string $wilayah_code = '2';
    public string $nomor_nasabah = '';
    public string $nama = '';
    public string $no_hp = '';
    public string $nik = '';
    public string $alamat = '';
    public string $status = 'aktif';
    public float|string $setoran_awal = 0;

    // Detail view object
    public ?Nasabah $detailNasabah = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedWilayahCode(): void
    {
        if ($this->showCreateModal) {
            $this->nomor_nasabah = Nasabah::generateNomorNasabah($this->wilayah_code);
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->wilayah_code = '2';
        $this->nomor_nasabah = Nasabah::generateNomorNasabah($this->wilayah_code);
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal(int $id): void
    {
        $nasabah = Nasabah::findOrFail($id);
        $this->selectedNasabahId = $nasabah->id;
        $this->nomor_nasabah = $nasabah->nomor_nasabah;
        $this->nama = $nasabah->nama;
        $this->no_hp = $nasabah->no_hp;
        $this->nik = $nasabah->nik ?? '';
        $this->alamat = $nasabah->alamat ?? '';
        $this->status = $nasabah->status;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDetailModal(int $id): void
    {
        $this->detailNasabah = Nasabah::with(['transaksis' => function ($q) {
            $q->latest()->take(10);
        }])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->detailNasabah = null;
    }

    public function openBukuTabungan(int $id): void
    {
        $this->bukuNasabah = Nasabah::with(['transaksis' => function ($q) {
            $q->oldest();
        }])->findOrFail($id);
        $this->bukuStartDate = '';
        $this->bukuEndDate = '';
        $this->showBukuTabunganModal = true;
    }

    public function closeBukuTabungan(): void
    {
        $this->showBukuTabunganModal = false;
        $this->bukuNasabah = null;
    }

    public function exportBukuCsv()
    {
        if (!$this->bukuNasabah) {
            return;
        }

        $query = $this->bukuNasabah->transaksis()->oldest();

        if (!empty($this->bukuStartDate)) {
            $query->whereDate('created_at', '>=', $this->bukuStartDate);
        }

        if (!empty($this->bukuEndDate)) {
            $query->whereDate('created_at', '<=', $this->bukuEndDate);
        }

        $transaksis = $query->get();
        $totalSetor = $transaksis->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik = $transaksis->where('jenis_transaksi', 'tarik')->sum('nominal');

        $namaLembaga = Setting::get('nama_lembaga', 'TabunganKu Digital');
        $alamatLembaga = Setting::get('alamat_lembaga', 'Jl. Jenderal Sudirman No. 123, Jakarta Pusat');
        $teleponLembaga = Setting::get('telepon_lembaga', '(021) 555-0199');

        $periode = 'Semua Transaksi';
        if ($this->bukuStartDate && $this->bukuEndDate) {
            $periode = date('d/m/Y', strtotime($this->bukuStartDate)) . ' s/d ' . date('d/m/Y', strtotime($this->bukuEndDate));
        } elseif ($this->bukuStartDate) {
            $periode = 'Mulai ' . date('d/m/Y', strtotime($this->bukuStartDate));
        } elseif ($this->bukuEndDate) {
            $periode = 'Hingga ' . date('d/m/Y', strtotime($this->bukuEndDate));
        }

        $filename = 'rekening_koran_' . $this->bukuNasabah->nomor_nasabah . '_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($transaksis, $namaLembaga, $alamatLembaga, $teleponLembaga, $periode, $totalSetor, $totalTarik) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            // Header Lembaga & Dokumen
            fputcsv($handle, [$namaLembaga]);
            fputcsv($handle, [$alamatLembaga . ' | Telp: ' . $teleponLembaga]);
            fputcsv($handle, ['REKENING KORAN / LAPORAN MUTASI TABUNGAN (FORMAT AKUNTANSI)']);
            fputcsv($handle, []);

            // Identity Header
            fputcsv($handle, ['Nomor Rekening', ': ' . $this->bukuNasabah->nomor_nasabah]);
            fputcsv($handle, ['Nama Nasabah', ': ' . $this->bukuNasabah->nama]);
            fputcsv($handle, ['No. Handphone', ': ' . $this->bukuNasabah->no_hp]);
            fputcsv($handle, ['Periode Mutasi', ': ' . $periode]);
            fputcsv($handle, ['Mata Uang', ': IDR (Rupiah)']);
            fputcsv($handle, ['Total Kredit (Setor)', ': Rp ' . number_format($totalSetor, 0, ',', '.')]);
            fputcsv($handle, ['Total Debit (Tarik)', ': Rp ' . number_format($totalTarik, 0, ',', '.')]);
            fputcsv($handle, ['Saldo Tabungan', ': Rp ' . number_format((float)$this->bukuNasabah->saldo, 0, ',', '.')]);
            fputcsv($handle, []);

            // Column Header
            fputcsv($handle, [
                'No',
                'Tanggal & Waktu',
                'Kode Transaksi',
                'Uraian Transaksi',
                'Debit / Penarikan (IDR)',
                'Kredit / Setoran (IDR)',
                'Saldo Akhir (IDR)',
                'Petugas',
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
                    $trx->user?->name ?? 'Teller',
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
                number_format((float)$this->bukuNasabah->saldo, 0, ',', '.'),
                '',
            ]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteNasabah = Nasabah::withCount('transaksis')->findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteNasabah = null;
    }

    public function confirmDelete(): void
    {
        if (!$this->deleteNasabah) {
            return;
        }

        $nasabahName = $this->deleteNasabah->nama;
        $this->deleteNasabah->delete();

        session()->flash('success', 'Nasabah "' . $nasabahName . '" beserta riwayat transaksinya telah berhasil dihapus.');
        $this->closeDeleteModal();
        if ($this->showDetailModal && $this->detailNasabah?->id === $this->deleteNasabah?->id) {
            $this->closeDetailModal();
        }
    }

    public function setStatus(int $id, string $status): void
    {
        if (!in_array($status, ['aktif', 'dibekukan', 'nonaktif'], true)) {
            return;
        }

        $nasabah = Nasabah::findOrFail($id);
        $nasabah->update(['status' => $status]);

        $statusLabel = match ($status) {
            'aktif' => 'diaktifkan kembali',
            'dibekukan' => 'dibekukan (dilarang bertransaksi)',
            'nonaktif' => 'dinonaktifkan',
        };

        session()->flash('success', 'Status rekening nasabah ' . $nasabah->nama . ' berhasil ' . $statusLabel . '.');

        if ($this->detailNasabah && $this->detailNasabah->id === $id) {
            $this->detailNasabah->status = $status;
        }
    }

    public function toggleFreeze(int $id): void
    {
        $nasabah = Nasabah::findOrFail($id);
        $newStatus = $nasabah->status === 'dibekukan' ? 'aktif' : 'dibekukan';
        $this->setStatus($id, $newStatus);
    }

    public function saveNasabah(): void
    {
        $this->validate([
            'nama' => 'required|min:3|max:150',
            'no_hp' => 'required|min:9|max:20',
            'nik' => 'nullable|numeric|digits_between:10,20',
            'alamat' => 'nullable|max:500',
            'setoran_awal' => 'nullable|numeric|min:0',
            'wilayah_code' => 'required|in:1,2,3,4,5,6,7,8',
        ], [
            'nama.required' => 'Nama lengkap nasabah wajib diisi.',
            'no_hp.required' => 'Nomor Handphone wajib diisi untuk akses login nasabah.',
        ]);

        $generatedNomor = '';

        DB::transaction(function () use (&$generatedNomor) {
            $generatedNomor = Nasabah::generateNomorNasabah($this->wilayah_code);
            $initialAmount = (float) ($this->setoran_awal ?: 0);

            $nasabah = Nasabah::create([
                'nomor_nasabah' => $generatedNomor,
                'nama' => trim($this->nama),
                'no_hp' => trim($this->no_hp),
                'nik' => $this->nik ? trim($this->nik) : null,
                'alamat' => $this->alamat ? trim($this->alamat) : null,
                'status' => 'aktif',
                'saldo' => $initialAmount,
            ]);

            if ($initialAmount > 0) {
                Transaksi::create([
                    'kode_transaksi' => Transaksi::generateKodeTransaksi('setor'),
                    'nasabah_id' => $nasabah->id,
                    'user_id' => Auth::guard('web')->id(),
                    'jenis_transaksi' => 'setor',
                    'nominal' => $initialAmount,
                    'saldo_awal' => 0,
                    'saldo_akhir' => $initialAmount,
                    'keterangan' => 'Setoran awal pembukaan rekening',
                ]);
            }
        });

        session()->flash('success', 'Nasabah baru "' . $this->nama . '" berhasil didaftarkan dengan Nomor Rekening: ' . $generatedNomor);
        $this->closeCreateModal();
    }

    public function updateNasabah(): void
    {
        $this->validate([
            'nama' => 'required|min:3|max:150',
            'no_hp' => 'required|min:9|max:20',
            'nik' => 'nullable|numeric|digits_between:10,20',
            'alamat' => 'nullable|max:500',
            'status' => 'required|in:aktif,dibekukan,nonaktif',
        ]);

        $nasabah = Nasabah::findOrFail($this->selectedNasabahId);
        $nasabah->update([
            'nama' => trim($this->nama),
            'no_hp' => trim($this->no_hp),
            'nik' => $this->nik ? trim($this->nik) : null,
            'alamat' => $this->alamat ? trim($this->alamat) : null,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Data nasabah "' . $nasabah->nama . '" berhasil diperbarui.');
        $this->closeEditModal();
    }

    public function toggleStatus(int $id): void
    {
        $nasabah = Nasabah::findOrFail($id);
        $newStatus = $nasabah->status === 'aktif' ? 'nonaktif' : 'aktif';
        $nasabah->update(['status' => $newStatus]);

        session()->flash('success', 'Status nasabah ' . $nasabah->nama . ' diubah menjadi ' . $newStatus . '.');
    }

    public function resetForm(): void
    {
        $this->selectedNasabahId = null;
        $this->wilayah_code = '2';
        $this->nomor_nasabah = '';
        $this->nama = '';
        $this->no_hp = '';
        $this->nik = '';
        $this->alamat = '';
        $this->status = 'aktif';
        $this->setoran_awal = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Nasabah::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_nasabah', 'like', '%' . $this->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $nasabahs = $query->latest()->paginate(10);

        return view('livewire.admin.nasabah-manager', [
            'nasabahs' => $nasabahs,
        ]);
    }
}
