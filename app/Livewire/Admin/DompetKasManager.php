<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\DompetKas;
use App\Models\Transaksi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Dompet Kas - TabunganKu')]
class DompetKasManager extends Component
{
    use WithPagination;

    public string $search = '';

    // Form Modal State
    public bool $showFormModal = false;

    public ?int $selectedId = null;

    // Strict input fields as requested: "input dompet hanya berisi mandiri dan nomor rekening"
    public string $nama = 'Mandiri';

    public string $nomor_rekening = '';

    public string $status = 'aktif';

    // Delete Modal State
    public bool $showDeleteModal = false;

    public ?DompetKas $targetDelete = null;

    // Detail Transactions Modal
    public bool $showDetailModal = false;

    public ?DompetKas $detailDompet = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->selectedId = null;
        $this->nama = 'Mandiri';
        $this->nomor_rekening = '';
        $this->status = 'aktif';
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $dompet = DompetKas::findOrFail($id);
        $this->selectedId = $dompet->id;
        $this->nama = $dompet->nama;
        $this->nomor_rekening = $dompet->nomor_rekening;
        $this->status = $dompet->status;
        $this->showFormModal = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'nama' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|min:4|max:50',
        ], [
            'nama.required' => 'Nama bank wajib diisi.',
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nomor_rekening.min' => 'Nomor rekening minimal 4 karakter.',
        ]);

        if ($this->selectedId) {
            $dompet = DompetKas::findOrFail($this->selectedId);
            $dompet->update([
                'nama' => trim($this->nama),
                'nomor_rekening' => trim($this->nomor_rekening),
                'status' => $this->status,
            ]);

            ActivityLog::record(
                'edit_dompet_kas',
                'Mengubah data dompet kas '.$dompet->nama.' ('.$dompet->nomor_rekening.')',
                $dompet
            );

            session()->flash('success', 'Dompet kas berhasil diperbarui.');
        } else {
            $dompet = DompetKas::create([
                'nama' => trim($this->nama),
                'nomor_rekening' => trim($this->nomor_rekening),
                'saldo' => 0,
                'status' => 'aktif',
            ]);

            ActivityLog::record(
                'tambah_dompet_kas',
                'Menambahkan dompet kas baru '.$dompet->nama.' ('.$dompet->nomor_rekening.')',
                $dompet
            );

            session()->flash('success', 'Dompet kas baru berhasil ditambahkan.');
        }

        $this->closeFormModal();
    }

    public function toggleStatus(int $id): void
    {
        $dompet = DompetKas::findOrFail($id);
        $newStatus = $dompet->status === 'aktif' ? 'nonaktif' : 'aktif';
        $dompet->update(['status' => $newStatus]);

        ActivityLog::record(
            'toggle_dompet_kas',
            'Mengubah status dompet kas '.$dompet->nama.' menjadi '.$newStatus,
            $dompet
        );

        session()->flash('success', 'Status dompet kas diubah menjadi '.$newStatus.'.');
    }

    public function confirmDelete(int $id): void
    {
        $this->targetDelete = DompetKas::withCount('transaksis')->findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (! $this->targetDelete) {
            return;
        }

        if ($this->targetDelete->transaksis()->count() > 0) {
            $this->targetDelete->update(['status' => 'nonaktif']);
            session()->flash('warning', 'Dompet kas memiliki riwayat transaksi, status diubah menjadi non-aktif alih-alih dihapus permanen demi keutuhan audit.');
        } else {
            $nama = $this->targetDelete->nama.' ('.$this->targetDelete->nomor_rekening.')';
            $this->targetDelete->delete();

            ActivityLog::record(
                'hapus_dompet_kas',
                'Menghapus dompet kas '.$nama
            );

            session()->flash('success', 'Dompet kas berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->targetDelete = null;
    }

    public function viewDetail(int $id): void
    {
        $this->detailDompet = DompetKas::with(['transaksis' => function ($query) {
            $query->with('nasabah')->latest()->take(20);
        }])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function render()
    {
        $query = DompetKas::withCount(['transaksis as total_transaksi_masuk' => function ($q) {
            $q->where('jenis_transaksi', 'setor');
        }]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nomor_rekening', 'like', '%'.$this->search.'%')
                    ->orWhere('nama', 'like', '%'.$this->search.'%');
            });
        }

        $dompets = $query->latest()->paginate(10);

        $totalSaldoAll = DompetKas::sum('saldo');
        $totalDompetAktif = DompetKas::where('status', 'aktif')->count();
        $totalUangMasuk = Transaksi::whereNotNull('dompet_kas_id')->where('jenis_transaksi', 'setor')->sum('nominal');

        return view('livewire.admin.dompet-kas-manager', [
            'dompets' => $dompets,
            'totalSaldoAll' => $totalSaldoAll,
            'totalDompetAktif' => $totalDompetAktif,
            'totalUangMasuk' => $totalUangMasuk,
        ]);
    }
}
