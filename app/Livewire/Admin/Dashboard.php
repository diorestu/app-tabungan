<?php

namespace App\Livewire\Admin;

use App\Models\Nasabah;
use App\Models\Transaksi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard Petugas - TabunganKu')]
class Dashboard extends Component
{
    public function render()
    {
        $totalKas = (float) Nasabah::sum('saldo');
        $totalNasabah = Nasabah::count();
        $totalNasabahAktif = Nasabah::where('status', 'aktif')->count();

        $setorHariIni = Transaksi::where('jenis_transaksi', 'setor')
            ->whereDate('created_at', today())
            ->sum('nominal');

        $tarikHariIni = Transaksi::where('jenis_transaksi', 'tarik')
            ->whereDate('created_at', today())
            ->sum('nominal');

        $totalSetorAll = Transaksi::where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarikAll = Transaksi::where('jenis_transaksi', 'tarik')->sum('nominal');

        $recentTransactions = Transaksi::with('nasabah')
            ->latest()
            ->take(8)
            ->get();

        $topNasabahs = Nasabah::orderByDesc('saldo')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalKas' => $totalKas,
            'totalNasabah' => $totalNasabah,
            'totalNasabahAktif' => $totalNasabahAktif,
            'setorHariIni' => $setorHariIni,
            'tarikHariIni' => $tarikHariIni,
            'totalSetorAll' => $totalSetorAll,
            'totalTarikAll' => $totalTarikAll,
            'recentTransactions' => $recentTransactions,
            'topNasabahs' => $topNasabahs,
        ]);
    }
}
