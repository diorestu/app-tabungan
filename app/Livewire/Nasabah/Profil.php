<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.nasabah')]
#[Title('Profil & Rekening Nasabah - TabunganKu')]
class Profil extends Component
{
    public function render()
    {
        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();
        $nasabah->refresh();

        $countTransaksi = Transaksi::where('nasabah_id', $nasabah->id)->count();
        $totalSetor = Transaksi::where('nasabah_id', $nasabah->id)->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik = Transaksi::where('nasabah_id', $nasabah->id)->where('jenis_transaksi', 'tarik')->sum('nominal');

        $lembaga = [
            'nama' => Setting::get('nama_lembaga', 'TabunganKu Digital'),
            'slogan' => Setting::get('slogan_lembaga', 'Layanan Simpanan & Tabungan Terpercaya'),
            'alamat' => Setting::get('alamat_lembaga', 'Jl. Jenderal Sudirman No. 123, Jakarta Pusat'),
            'telepon' => Setting::get('telepon_lembaga', '(021) 555-0199'),
            'email' => Setting::get('email_lembaga', 'layanan@tabunganku.id'),
        ];

        return view('livewire.nasabah.profil', [
            'nasabah' => $nasabah,
            'countTransaksi' => $countTransaksi,
            'totalSetor' => $totalSetor,
            'totalTarik' => $totalTarik,
            'lembaga' => $lembaga,
        ]);
    }
}
