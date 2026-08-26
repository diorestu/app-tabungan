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
#[Title('Tarik Tunai Tabungan - TabunganKu')]
class TarikTunai extends Component
{
    #[Url]
    public ?int $nasabah_id = null;

    public string $nasabahSearch = '';
    public ?Nasabah $selectedNasabah = null;

    #[Validate('required|numeric|min:5000', message: 'Nominal penarikan minimal Rp 5.000')]
    public float|string $nominal = '';

    public string $keterangan = 'Tarik tunai tabungan';

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
        $this->selectedNasabah = Nasabah::where('id', $id)->where('status', 'aktif')->first();
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

    public function setPresetAmount(int $amount): void
    {
        $this->nominal = $amount;
    }

    public function setAllBalance(): void
    {
        if ($this->selectedNasabah) {
            $this->nominal = (float) $this->selectedNasabah->saldo;
        }
    }

    public function processTarik(): void
    {
        if (!$this->selectedNasabah) {
            $this->addError('nasabah_id', 'Silakan pilih nasabah terlebih dahulu.');
            return;
        }

        $this->validate();

        $nominalFloat = (float) $this->nominal;

        if ($nominalFloat > (float) $this->selectedNasabah->saldo) {
            $this->addError('nominal', 'Saldo tidak mencukupi. Saldo saat ini: ' . $this->selectedNasabah->formatted_saldo);
            return;
        }

        DB::transaction(function () use ($nominalFloat) {
            $nasabah = Nasabah::lockForUpdate()->find($this->selectedNasabah->id);
            $saldoAwal = (float) $nasabah->saldo;

            if ($nominalFloat > $saldoAwal) {
                throw new \Exception('Saldo tidak mencukupi untuk melakukan penarikan.');
            }

            $saldoAkhir = $saldoAwal - $nominalFloat;

            // Create Transaction record
            $this->lastTransaction = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKodeTransaksi('tarik'),
                'nasabah_id' => $nasabah->id,
                'user_id' => Auth::guard('web')->id(),
                'jenis_transaksi' => 'tarik',
                'nominal' => $nominalFloat,
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAkhir,
                'keterangan' => $this->keterangan ?: 'Tarik tunai tabungan',
            ]);

            // Update Saldo
            $nasabah->update(['saldo' => $saldoAkhir]);
            $this->selectedNasabah = $nasabah;
        });

        $this->showSuccessModal = true;
        $this->nominal = '';
        $this->keterangan = 'Tarik tunai tabungan';
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

        return view('livewire.admin.tarik-tunai', [
            'searchResults' => $searchResults,
        ]);
    }
}
