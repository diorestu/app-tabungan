<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pengaturan Aplikasi - TabunganKu')]
class Pengaturan extends Component
{
    // Institution Settings
    public string $nama_lembaga = '';
    public string $slogan_lembaga = '';
    public string $alamat_lembaga = '';
    public string $telepon_lembaga = '';
    public string $pesan_struk = '';

    // Admin Profile & Password Change
    public string $admin_name = '';
    public string $admin_email = '';
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $settings = Setting::getAllSettings();

        $this->nama_lembaga = $settings['nama_lembaga'] ?? '';
        $this->slogan_lembaga = $settings['slogan_lembaga'] ?? '';
        $this->alamat_lembaga = $settings['alamat_lembaga'] ?? '';
        $this->telepon_lembaga = $settings['telepon_lembaga'] ?? '';
        $this->pesan_struk = $settings['pesan_struk'] ?? '';

        $user = Auth::guard('web')->user();
        if ($user) {
            $this->admin_name = $user->name;
            $this->admin_email = $user->email;
        }
    }

    public function saveInstitutionSettings(): void
    {
        $this->validate([
            'nama_lembaga' => 'required|min:3|max:100',
            'slogan_lembaga' => 'nullable|max:150',
            'alamat_lembaga' => 'nullable|max:250',
            'telepon_lembaga' => 'nullable|max:50',
            'pesan_struk' => 'nullable|max:300',
        ]);

        Setting::set('nama_lembaga', trim($this->nama_lembaga), 'Nama Lembaga / Koperasi / Sekolah');
        Setting::set('slogan_lembaga', trim($this->slogan_lembaga), 'Slogan atau Tagline');
        Setting::set('alamat_lembaga', trim($this->alamat_lembaga), 'Alamat Kantor / Lokasi');
        Setting::set('telepon_lembaga', trim($this->telepon_lembaga), 'Nomor Kontak / WhatsApp');
        Setting::set('pesan_struk', trim($this->pesan_struk), 'Catatan Kaki Struk Transaksi');

        session()->flash('success_institution', 'Profil lembaga dan pengaturan struk berhasil disimpan.');
    }

    public function updateAdminProfile(): void
    {
        $user = Auth::guard('web')->user();

        $this->validate([
            'admin_name' => 'required|min:3|max:100',
            'admin_email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => trim($this->admin_name),
            'email' => trim($this->admin_email),
        ]);

        session()->flash('success_profile', 'Profil admin berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $user = Auth::guard('web')->user();

        $this->validate([
            'current_password' => ['required', 'current_password:web'],
            'new_password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.current_password' => 'Password saat ini yang Anda masukkan tidak sesuai.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('success_password', 'Password akun admin berhasil diubah.');
    }

    public function render()
    {
        return view('livewire.admin.pengaturan');
    }
}
