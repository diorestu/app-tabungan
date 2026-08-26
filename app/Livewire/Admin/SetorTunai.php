<?php

namespace App\Livewire\Admin;

use App\Models\Nasabah;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Setor Tunai Tabungan - TabunganKu')]
class SetorTunai extends Component
{
    #[Url]
    public ?int $nasabah_id = null;

    public string $nasabahSearch = '';
    public ?Nasabah $selectedNasabah = null;

    #[Validate('required|numeric|min:5000', message: 'Nominal setoran minimal Rp 5.000')]
    public float|string $nominal = '';

    public string $keterangan = 'Setor tunai tabungan';

    public ?Transaksi $lastTransaction = null;
    public bool $showSuccessModal = false;

    public function mount(): void
    {
        if ($this->nasabah_id) {
            $this->selectNasabah($this->nasabah_id);
        }
    }

    public function selectNasabah(int $id): void
    {
        $nasabah = Nasabah::find($id);
        if ($nasabah && $nasabah->status === 'dibekukan') {
            $this->addError('nasabah_id', 'Rekening nasabah ini sedang DIBEKUKAN. Buka blokir terlebih dahulu di menu Data Nasabah untuk dapat menyetor.');
            return;
        }
        if ($nasabah && $nasabah->status !== 'aktif') {
            $this->addError('nasabah_id', 'Rekening nasabah ini berstatus non-aktif.');
            return;
        }

        $this->selectedNasabah = $nasabah;
        if ($this->selectedNasabah) {
            $this->nasabah_id = $this->selectedNasabah->id;
            $this->nasabahSearch = $this->selectedNasabah->nomor_nasabah . ' - ' . $this->selectedNasabah->nama;
        }
    }

    public function clearSelectedNasabah(): void
    {
        $this->selectedNasabah = null;
        $this->nasabah_id = null;
        $this->nasabahSearch = '';
    }

    public function addPresetAmount(int $amount): void
    {
        $current = (float) ($this->nominal ?: 0);
        $this->nominal = $current + $amount;
    }

    public function setPresetAmount(int $amount): void
    {
        $this->nominal = $amount;
    }

    public function processSetor(): void
    {
        if (!$this->selectedNasabah) {
            $this->addError('nasabah_id', 'Silakan pilih nasabah terlebih dahulu.');
            return;
        }

        $this->validate();

        $nominalFloat = (float) $this->nominal;

        DB::transaction(function () use ($nominalFloat) {
            $nasabah = Nasabah::lockForUpdate()->find($this->selectedNasabah->id);
            $saldoAwal = (float) $nasabah->saldo;
            $saldoAkhir = $saldoAwal + $nominalFloat;

            // Create Transaction record
            $this->lastTransaction = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKodeTransaksi('setor'),
                'nasabah_id' => $nasabah->id,
                'user_id' => Auth::guard('web')->id(),
                'jenis_transaksi' => 'setor',
                'nominal' => $nominalFloat,
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAkhir,
                'keterangan' => $this->keterangan ?: 'Setor tunai tabungan',
            ]);

            // Update Saldo
            $nasabah->update(['saldo' => $saldoAkhir]);
            $this->selectedNasabah = $nasabah;
        });

        $this->showSuccessModal = true;
        $this->nominal = '';
        $this->keterangan = 'Setor tunai tabungan';
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
    }

    public function render()
    {
        $searchResults = collect();

        if (strlen($this->nasabahSearch) >= 2 && !$this->selectedNasabah) {
            $searchResults = Nasabah::where('status', 'aktif')
                ->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->nasabahSearch . '%')
                      ->orWhere('nomor_nasabah', 'like', '%' . $this->nasabahSearch . '%')
                      ->orWhere('no_hp', 'like', '%' . $this->nasabahSearch . '%');
                })
                ->take(6)
                ->get();
        }

        return view('livewire.admin.setor-tunai', [
            'searchResults' => $searchResults,
        ]);
    }
}
