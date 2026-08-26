<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pengaturan Aplikasi & Petugas - TabunganKu')]
class Pengaturan extends Component
{
    // Active Tab
    public string $activeTab = 'lembaga'; // 'lembaga', 'petugas', 'keamanan'

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

    // Petugas Management State
    public bool $showCreatePetugasModal = false;
    public bool $showEditPetugasModal = false;
    public bool $showDeletePetugasModal = false;

    public ?int $selectedPetugasId = null;
    public ?User $deletePetugas = null;

    public string $petugas_name = '';
    public string $petugas_email = '';
    public string $petugas_role = 'teller';
    public string $petugas_status = 'aktif';
    public string $petugas_password = '';
    public string $petugas_password_confirmation = '';

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

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
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

    // ==========================================
    // PETUGAS / TELLER MANAGEMENT
    // ==========================================

    public function openCreatePetugasModal(): void
    {
        $this->resetPetugasForm();
        $this->showCreatePetugasModal = true;
    }

    public function closeCreatePetugasModal(): void
    {
        $this->showCreatePetugasModal = false;
        $this->resetPetugasForm();
    }

    public function savePetugas(): void
    {
        $this->validate([
            'petugas_name' => 'required|min:3|max:100',
            'petugas_email' => 'required|email|unique:users,email',
            'petugas_role' => 'required|in:admin,teller',
            'petugas_password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'petugas_name.required' => 'Nama lengkap petugas wajib diisi.',
            'petugas_email.required' => 'Email petugas wajib diisi.',
            'petugas_email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'petugas_password.required' => 'Password wajib diisi.',
            'petugas_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'petugas_password.min' => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name' => trim($this->petugas_name),
            'email' => trim($this->petugas_email),
            'role' => $this->petugas_role,
            'status' => 'aktif',
            'password' => Hash::make($this->petugas_password),
        ]);

        session()->flash('success_petugas', 'Petugas baru "' . $this->petugas_name . '" berhasil ditambahkan.');
        $this->closeCreatePetugasModal();
    }

    public function openEditPetugasModal(int $id): void
    {
        $user = User::findOrFail($id);
        $this->selectedPetugasId = $user->id;
        $this->petugas_name = $user->name;
        $this->petugas_email = $user->email;
        $this->petugas_role = $user->role ?? 'teller';
        $this->petugas_status = $user->status ?? 'aktif';
        $this->petugas_password = '';
        $this->petugas_password_confirmation = '';
        $this->showEditPetugasModal = true;
    }

    public function closeEditPetugasModal(): void
    {
        $this->showEditPetugasModal = false;
        $this->resetPetugasForm();
    }

    public function updatePetugas(): void
    {
        $user = User::findOrFail($this->selectedPetugasId);

        $rules = [
            'petugas_name' => 'required|min:3|max:100',
            'petugas_email' => 'required|email|unique:users,email,' . $user->id,
            'petugas_role' => 'required|in:admin,teller',
            'petugas_status' => 'required|in:aktif,nonaktif',
        ];

        if (!empty($this->petugas_password)) {
            $rules['petugas_password'] = ['confirmed', Password::min(6)];
        }

        $this->validate($rules, [
            'petugas_name.required' => 'Nama lengkap petugas wajib diisi.',
            'petugas_email.required' => 'Email petugas wajib diisi.',
            'petugas_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'petugas_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $updateData = [
            'name' => trim($this->petugas_name),
            'email' => trim($this->petugas_email),
            'role' => $this->petugas_role,
            'status' => $this->petugas_status,
        ];

        if (!empty($this->petugas_password)) {
            $updateData['password'] = Hash::make($this->petugas_password);
        }

        $user->update($updateData);

        session()->flash('success_petugas', 'Data petugas "' . $user->name . '" berhasil diperbarui.');
        $this->closeEditPetugasModal();
    }

    public function togglePetugasStatus(int $id): void
    {
        $currentUserId = Auth::guard('web')->id();
        if ($id === $currentUserId) {
            session()->flash('error_petugas', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan saat ini.');
            return;
        }

        $user = User::findOrFail($id);
        $newStatus = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        session()->flash('success_petugas', 'Status akun ' . $user->name . ' diubah menjadi ' . $newStatus . '.');
    }

    public function openDeletePetugasModal(int $id): void
    {
        $currentUserId = Auth::guard('web')->id();
        if ($id === $currentUserId) {
            session()->flash('error_petugas', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.');
            return;
        }

        $this->deletePetugas = User::findOrFail($id);
        $this->showDeletePetugasModal = true;
    }

    public function closeDeletePetugasModal(): void
    {
        $this->showDeletePetugasModal = false;
        $this->deletePetugas = null;
    }

    public function confirmDeletePetugas(): void
    {
        if (!$this->deletePetugas) {
            return;
        }

        if ($this->deletePetugas->id === Auth::guard('web')->id()) {
            session()->flash('error_petugas', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->closeDeletePetugasModal();
            return;
        }

        $name = $this->deletePetugas->name;
        $this->deletePetugas->delete();

        session()->flash('success_petugas', 'Akun petugas "' . $name . '" telah berhasil dihapus.');
        $this->closeDeletePetugasModal();
    }

    public function resetPetugasForm(): void
    {
        $this->selectedPetugasId = null;
        $this->petugas_name = '';
        $this->petugas_email = '';
        $this->petugas_role = 'teller';
        $this->petugas_status = 'aktif';
        $this->petugas_password = '';
        $this->petugas_password_confirmation = '';
        $this->resetValidation();
    }

    public function render()
    {
        $petugasList = User::latest()->get();

        return view('livewire.admin.pengaturan', [
            'petugasList' => $petugasList,
        ]);
    }
}
