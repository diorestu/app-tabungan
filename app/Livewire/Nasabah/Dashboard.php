<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.nasabah')]
#[Title('Dashboard Nasabah - TabunganKu')]
class Dashboard extends Component
{
    public function render()
    {
        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();

        // Refresh nasabah model from db for fresh balance
        $nasabah->refresh();

        $recentTransactions = Transaksi::where('nasabah_id', $nasabah->id)
            ->latest()
            ->take(5)
            ->get();

        $totalSetor = Transaksi::where('nasabah_id', $nasabah->id)
            ->where('jenis_transaksi', 'setor')
            ->sum('nominal');

        $totalTarik = Transaksi::where('nasabah_id', $nasabah->id)
            ->where('jenis_transaksi', 'tarik')
            ->sum('nominal');

        $countTransaksi = Transaksi::where('nasabah_id', $nasabah->id)->count();

        $targetTabungans = $nasabah->targetTabungans()->take(3)->get();
        $totalTargetTerkumpul = $nasabah->targetTabungans()->sum('terkumpul_nominal');

        return view('livewire.nasabah.dashboard', [
            'nasabah' => $nasabah,
            'recentTransactions' => $recentTransactions,
            'totalSetor' => $totalSetor,
            'totalTarik' => $totalTarik,
            'countTransaksi' => $countTransaksi,
            'targetTabungans' => $targetTabungans,
            'totalTargetTerkumpul' => $totalTargetTerkumpul,
        ]);
    }
}
