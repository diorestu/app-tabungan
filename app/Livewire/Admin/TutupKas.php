<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\TellerShift;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Tutup Kas & Rekonsiliasi Kasir Harian - TabunganKu')]
class TutupKas extends Component
{
    use WithPagination;

    // Shift Opening State
    public float|string $modal_awal_input = '';
    public string $catatan_buka = '';

    // Cash Breakdown for Closing
    public int $p100k = 0;
    public int $p50k = 0;
    public int $p20k = 0;
    public int $p10k = 0;
    public int $p5k = 0;
    public int $p2k = 0;
    public int $p1k = 0;
    public float|string $pkoin = 0;
    public string $catatan_tutup = '';

    // Active Shift & Print Modal
    public ?TellerShift $activeShift = null;
    public ?TellerShift $selectedBeritaAcara = null;
    public bool $showBeritaAcaraModal = false;

    public function mount(): void
    {
        $this->loadActiveShift();
    }

    public function loadActiveShift(): void
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return;
        }

        $this->activeShift = TellerShift::where('user_id', $user->id)
            ->whereDate('shift_date', now()->toDateString())
            ->where('status', 'buka')
            ->latest()
            ->first();

        if ($this->activeShift) {
            $this->recalculateLiveShiftStats();
        }
    }

    public function openShift(): void
    {
        $user = Auth::guard('web')->user();

        $this->validate([
            'modal_awal_input' => 'required|numeric|min:0',
        ], [
            'modal_awal_input.required' => 'Modal awal kasir wajib diisi.',
            'modal_awal_input.min' => 'Modal awal tidak boleh negatif.',
        ]);

        $modalAwal = (float) $this->modal_awal_input;

        $shift = TellerShift::create([
            'user_id' => $user->id,
            'shift_date' => now()->toDateString(),
            'modal_awal' => $modalAwal,
            'saldo_sistem' => $modalAwal,
            'catatan' => $this->catatan_buka ? trim($this->catatan_buka) : null,
            'status' => 'buka',
            'opened_at' => now(),
        ]);

        ActivityLog::record(
            'buka_kasir',
            'Membuka sesi kasir harian dengan modal awal Rp ' . number_format($modalAwal, 0, ',', '.'),
            $shift,
            ['modal_awal' => $modalAwal]
        );

        session()->flash('success_shift', 'Sesi kasir berhasil dibuka dengan modal awal Rp ' . number_format($modalAwal, 0, ',', '.'));
        $this->modal_awal_input = '';
        $this->catatan_buka = '';
        $this->loadActiveShift();
    }

    public function recalculateLiveShiftStats(): void
    {
        if (!$this->activeShift) {
            return;
        }

        $userId = $this->activeShift->user_id;
        $shiftDate = $this->activeShift->shift_date->toDateString();

        $totalSetor = (float) Transaksi::where('user_id', $userId)
            ->where('jenis_transaksi', 'setor')
            ->whereDate('created_at', $shiftDate)
            ->sum('nominal');

        $totalTarik = (float) Transaksi::where('user_id', $userId)
            ->where('jenis_transaksi', 'tarik')
            ->whereDate('created_at', $shiftDate)
            ->sum('nominal');

        $modalAwal = (float) $this->activeShift->modal_awal;
        $saldoSistem = $modalAwal + $totalSetor - $totalTarik;

        $this->activeShift->update([
            'total_setoran' => $totalSetor,
            'total_penarikan' => $totalTarik,
            'saldo_sistem' => $saldoSistem,
        ]);
    }

    public function calculatePhysicalCash(): float
    {
        return ($this->p100k * 100000)
            + ($this->p50k * 50000)
            + ($this->p20k * 20000)
            + ($this->p10k * 10000)
            + ($this->p5k * 5000)
            + ($this->p2k * 2000)
            + ($this->p1k * 1000)
            + (float) ($this->pkoin ?: 0);
    }

    public function submitTutupKas(): void
    {
        if (!$this->activeShift) {
            return;
        }

        $this->recalculateLiveShiftStats();

        $saldoFisik = $this->calculatePhysicalCash();
        $saldoSistem = (float) $this->activeShift->saldo_sistem;
        $selisih = $saldoFisik - $saldoSistem;

        $pecahanData = [
            '100k' => $this->p100k,
            '50k' => $this->p50k,
            '20k' => $this->p20k,
            '10k' => $this->p10k,
            '5k' => $this->p5k,
            '2k' => $this->p2k,
            '1k' => $this->p1k,
            'koin' => (float) ($this->pkoin ?: 0),
        ];

        $this->activeShift->update([
            'saldo_fisik' => $saldoFisik,
            'selisih' => $selisih,
            'pecahan_uang' => $pecahanData,
            'catatan' => $this->catatan_tutup ? trim($this->catatan_tutup) : $this->activeShift->catatan,
            'status' => 'ditutup',
            'closed_at' => now(),
        ]);

        ActivityLog::record(
            'tutup_kasir',
            'Menutup sesi kasir: Fisik Rp ' . number_format($saldoFisik, 0, ',', '.') . ', Sistem Rp ' . number_format($saldoSistem, 0, ',', '.') . ', Selisih ' . ($selisih >= 0 ? '+' : '') . 'Rp ' . number_format($selisih, 0, ',', '.'),
            $this->activeShift,
            [
                'saldo_sistem' => $saldoSistem,
                'saldo_fisik' => $saldoFisik,
                'selisih' => $selisih,
            ]
        );

        $this->selectedBeritaAcara = $this->activeShift;
        $this->showBeritaAcaraModal = true;
        $this->activeShift = null;

        session()->flash('success_shift', 'Rekonsiliasi tutup kasir berhasil disimpan.');
    }

    public function openBeritaAcara(int $id): void
    {
        $this->selectedBeritaAcara = TellerShift::with(['user', 'supervisor'])->findOrFail($id);
        $this->showBeritaAcaraModal = true;
    }

    public function closeBeritaAcara(): void
    {
        $this->showBeritaAcaraModal = false;
        $this->selectedBeritaAcara = null;
    }

    public function approveShift(int $id): void
    {
        $user = Auth::guard('web')->user();
        if ($user->role !== 'admin') {
            session()->flash('error_shift', 'Hanya administrator / supervisor yang berwenang menyetujui tutup kas.');
            return;
        }

        $shift = TellerShift::findOrFail($id);
        $shift->update([
            'status' => 'disetujui',
            'supervisor_id' => $user->id,
        ]);

        ActivityLog::record('approve_tutup_kasir', 'Menyetujui berita acara tutup kas #' . $shift->id . ' untuk teller ' . $shift->user->name, $shift);
        session()->flash('success_shift', 'Berita acara tutup kasir #' . $shift->id . ' berhasil disetujui.');

        if ($this->selectedBeritaAcara && $this->selectedBeritaAcara->id === $id) {
            $this->selectedBeritaAcara->status = 'disetujui';
            $this->selectedBeritaAcara->supervisor_id = $user->id;
        }
    }

    public function render()
    {
        $settings = Setting::getAllSettings();
        $historyShifts = TellerShift::with(['user', 'supervisor'])->latest()->paginate(10);

        $liveFisik = $this->calculatePhysicalCash();
        $liveSistem = (float) ($this->activeShift->saldo_sistem ?? 0);
        $liveSelisih = $liveFisik - $liveSistem;

        return view('livewire.admin.tutup-kas', [
            'settings' => $settings,
            'historyShifts' => $historyShifts,
            'liveFisik' => $liveFisik,
            'liveSistem' => $liveSistem,
            'liveSelisih' => $liveSelisih,
        ]);
    }
}
