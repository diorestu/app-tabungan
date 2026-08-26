<?php

namespace App\Livewire\Admin;

use App\Models\Nasabah;
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

    // Modal Create / Edit states
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDetailModal = false;

    // Form fields for create/edit
    public ?int $selectedNasabahId = null;
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

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->nomor_nasabah = Nasabah::generateNomorNasabah();
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

    public function saveNasabah(): void
    {
        $this->validate([
            'nomor_nasabah' => 'required|unique:nasabahs,nomor_nasabah',
            'nama' => 'required|min:3|max:150',
            'no_hp' => 'required|min:9|max:20',
            'nik' => 'nullable|numeric|digits_between:10,20',
            'alamat' => 'nullable|max:500',
            'setoran_awal' => 'nullable|numeric|min:0',
        ], [
            'nomor_nasabah.required' => 'Nomor ID Nasabah wajib diisi.',
            'nomor_nasabah.unique' => 'Nomor ID Nasabah sudah digunakan.',
            'nama.required' => 'Nama lengkap nasabah wajib diisi.',
            'no_hp.required' => 'Nomor Handphone wajib diisi untuk akses login nasabah.',
        ]);

        DB::transaction(function () {
            $initialAmount = (float) ($this->setoran_awal ?: 0);

            $nasabah = Nasabah::create([
                'nomor_nasabah' => trim($this->nomor_nasabah),
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

        session()->flash('success', 'Nasabah baru "' . $this->nama . '" berhasil didaftarkan.');
        $this->closeCreateModal();
    }

    public function updateNasabah(): void
    {
        $this->validate([
            'nama' => 'required|min:3|max:150',
            'no_hp' => 'required|min:9|max:20',
            'nik' => 'nullable|numeric|digits_between:10,20',
            'alamat' => 'nullable|max:500',
            'status' => 'required|in:aktif,nonaktif',
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
