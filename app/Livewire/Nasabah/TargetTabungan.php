<?php

namespace App\Livewire\Nasabah;

use App\Models\ActivityLog;
use App\Models\Nasabah;
use App\Models\TargetTabungan as TargetTabunganModel;
use App\Models\TargetTabunganHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.nasabah')]
#[Title('Target Tabungan & Kantong Impian - TabunganKu')]
class TargetTabungan extends Component
{
    #[Url]
    public string $filterStatus = '';

    #[Url]
    public string $search = '';

    // Modal Create / Edit
    public bool $showCreateModal = false;

    public ?int $editTargetId = null;

    public string $nama_target = '';

    public string $kategori = 'qurban';

    public string $target_nominal = '';

    public string $tenggat_waktu = '';

    public string $catatan = '';

    // Modal Alokasi (Top Up Target)
    public bool $showAlokasiModal = false;

    public ?int $activeTargetId = null;

    public ?TargetTabunganModel $activeTarget = null;

    public string $alokasi_nominal = '';

    // Modal Tarik ke Saldo Utama
    public bool $showTarikModal = false;

    public string $tarik_nominal = '';

    // Modal Detail & Riwayat
    public bool $showDetailModal = false;

    // Modal Hapus
    public bool $showDeleteModal = false;

    public ?int $deleteTargetId = null;

    public ?TargetTabunganModel $deleteTarget = null;

    protected function rules(): array
    {
        return [
            'nama_target' => 'required|min:3|max:100',
            'kategori' => 'required|in:qurban,pendidikan,liburan,darurat,elektronik,kendaraan,rumah,lainnya',
            'target_nominal' => 'required|numeric|min:10000',
            'tenggat_waktu' => 'nullable|date',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'nama_target.required' => 'Nama target impian wajib diisi.',
        'nama_target.min' => 'Nama target minimal 3 karakter.',
        'target_nominal.required' => 'Target nominal wajib diisi.',
        'target_nominal.min' => 'Target nominal minimal Rp 10.000.',
    ];

    public function openCreateModal(): void
    {
        $this->reset(['editTargetId', 'nama_target', 'target_nominal', 'tenggat_waktu', 'catatan']);
        $this->kategori = 'qurban';
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetValidation();
    }

    public function openEditModal(int $id): void
    {
        $nasabah = Auth::guard('nasabah')->user();
        $target = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($id);

        $this->editTargetId = $target->id;
        $this->nama_target = $target->nama_target;
        $this->kategori = $target->kategori;
        $this->target_nominal = (string) (int) $target->target_nominal;
        $this->tenggat_waktu = $target->tenggat_waktu ? $target->tenggat_waktu->format('Y-m-d') : '';
        $this->catatan = $target->catatan ?? '';
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function saveTarget(): void
    {
        $this->validate();
        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();

        $data = [
            'nama_target' => trim($this->nama_target),
            'kategori' => $this->kategori,
            'target_nominal' => (float) $this->target_nominal,
            'tenggat_waktu' => $this->tenggat_waktu ?: null,
            'catatan' => $this->catatan ? trim($this->catatan) : null,
        ];

        if ($this->editTargetId) {
            $target = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($this->editTargetId);
            $target->update($data);
            if ($target->terkumpul_nominal >= $target->target_nominal) {
                $target->update(['status' => 'tercapai']);
            } elseif ($target->status === 'tercapai') {
                $target->update(['status' => 'berjalan']);
            }

            ActivityLog::record('target_tabungan_edit', 'Mengubah kantong target: '.$target->nama_target.' (Target: Rp '.number_format($target->target_nominal, 0, ',', '.').')', $target);
            session()->flash('success', 'Target tabungan "'.$target->nama_target.'" berhasil diperbarui.');
        } else {
            $data['nasabah_id'] = $nasabah->id;
            $data['terkumpul_nominal'] = 0;
            $data['status'] = 'berjalan';
            $target = TargetTabunganModel::create($data);

            ActivityLog::record('target_tabungan_buat', 'Membuat kantong target baru: '.$target->nama_target.' (Target: Rp '.number_format($target->target_nominal, 0, ',', '.').')', $target);
            session()->flash('success', 'Kantong target tabungan baru berhasil dibuat! Anda dapat mulai menabung ke kantong ini.');
        }

        $this->showCreateModal = false;
        $this->reset(['editTargetId', 'nama_target', 'target_nominal', 'tenggat_waktu', 'catatan']);
    }

    public function openAlokasiModal(int $id): void
    {
        $nasabah = Auth::guard('nasabah')->user();
        $this->activeTargetId = $id;
        $this->activeTarget = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($id);
        $this->alokasi_nominal = '';
        $this->resetValidation();
        $this->showAlokasiModal = true;
    }

    public function closeAlokasiModal(): void
    {
        $this->showAlokasiModal = false;
        $this->activeTarget = null;
        $this->activeTargetId = null;
        $this->alokasi_nominal = '';
        $this->resetValidation();
    }

    public function setQuickAlokasi(int $amount): void
    {
        $this->alokasi_nominal = (string) $amount;
    }

    public function prosesAlokasi(): void
    {
        $this->validate([
            'alokasi_nominal' => 'required|numeric|min:1000',
        ], [
            'alokasi_nominal.required' => 'Nominal alokasi wajib diisi.',
            'alokasi_nominal.min' => 'Nominal alokasi minimal Rp 1.000.',
        ]);

        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();
        $nasabah->refresh();

        if (! $this->activeTargetId) {
            $this->closeAlokasiModal();

            return;
        }

        $this->activeTarget = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($this->activeTargetId);
        $nominal = (float) $this->alokasi_nominal;

        if ($nominal > (float) $nasabah->saldo) {
            $this->addError('alokasi_nominal', 'Saldo utama Anda tidak mencukupi (Tersedia: '.$nasabah->formatted_saldo.').');

            return;
        }

        $targetName = $this->activeTarget->nama_target;

        DB::transaction(function () use ($nasabah, $nominal) {
            $target = TargetTabunganModel::where('nasabah_id', $nasabah->id)
                ->lockForUpdate()
                ->findOrFail($this->activeTargetId);

            $saldoSebelum = (float) $target->terkumpul_nominal;
            $saldoSesudah = $saldoSebelum + $nominal;

            // Update Target
            $target->terkumpul_nominal = $saldoSesudah;
            if ($saldoSesudah >= (float) $target->target_nominal) {
                $target->status = 'tercapai';
            }
            $target->save();

            // Potong Saldo Utama
            $nasabah->saldo = (float) $nasabah->saldo - $nominal;
            $nasabah->save();

            // Catat History Alokasi
            TargetTabunganHistory::create([
                'target_tabungan_id' => $target->id,
                'nasabah_id' => $nasabah->id,
                'tipe' => 'alokasi',
                'nominal' => $nominal,
                'saldo_target_sebelum' => $saldoSebelum,
                'saldo_target_sesudah' => $saldoSesudah,
                'keterangan' => 'Alokasi tabungan dari Saldo Utama',
            ]);

            ActivityLog::record('target_tabungan_topup', 'Menabung Rp '.number_format($nominal, 0, ',', '.').' ke kantong "'.$target->nama_target.'"', $target);
        });

        session()->flash('success', 'Berhasil menabung Rp '.number_format($nominal, 0, ',', '.').' ke kantong "'.$targetName.'".');
        $this->closeAlokasiModal();
    }

    public function openTarikModal(int $id): void
    {
        $nasabah = Auth::guard('nasabah')->user();
        $this->activeTargetId = $id;
        $this->activeTarget = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($id);
        $this->tarik_nominal = '';
        $this->resetValidation();
        $this->showTarikModal = true;
    }

    public function closeTarikModal(): void
    {
        $this->showTarikModal = false;
        $this->activeTarget = null;
        $this->activeTargetId = null;
        $this->tarik_nominal = '';
        $this->resetValidation();
    }

    public function setTarikSemua(): void
    {
        if ($this->activeTarget) {
            $this->tarik_nominal = (string) (int) $this->activeTarget->terkumpul_nominal;
        }
    }

    public function prosesTarik(): void
    {
        $this->validate([
            'tarik_nominal' => 'required|numeric|min:1000',
        ], [
            'tarik_nominal.required' => 'Nominal penarikan wajib diisi.',
            'tarik_nominal.min' => 'Nominal penarikan minimal Rp 1.000.',
        ]);

        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();
        $nasabah->refresh();

        if (! $this->activeTargetId) {
            $this->closeTarikModal();

            return;
        }

        $this->activeTarget = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($this->activeTargetId);
        $nominal = (float) $this->tarik_nominal;

        if ($nominal > (float) $this->activeTarget->terkumpul_nominal) {
            $this->addError('tarik_nominal', 'Nominal penarikan melebihi saldo di kantong ini (Tersedia: '.$this->activeTarget->formatted_terkumpul_nominal.').');

            return;
        }

        $targetName = $this->activeTarget->nama_target;

        DB::transaction(function () use ($nasabah, $nominal) {
            $target = TargetTabunganModel::where('nasabah_id', $nasabah->id)
                ->lockForUpdate()
                ->findOrFail($this->activeTargetId);

            $saldoSebelum = (float) $target->terkumpul_nominal;
            $saldoSesudah = max(0, $saldoSebelum - $nominal);

            // Update Target
            $target->terkumpul_nominal = $saldoSesudah;
            if ($saldoSesudah < (float) $target->target_nominal && $target->status === 'tercapai') {
                $target->status = 'berjalan';
            }
            $target->save();

            // Tambahkan Saldo Utama
            $nasabah->saldo = (float) $nasabah->saldo + $nominal;
            $nasabah->save();

            // Catat History
            TargetTabunganHistory::create([
                'target_tabungan_id' => $target->id,
                'nasabah_id' => $nasabah->id,
                'tipe' => 'penarikan',
                'nominal' => $nominal,
                'saldo_target_sebelum' => $saldoSebelum,
                'saldo_target_sesudah' => $saldoSesudah,
                'keterangan' => 'Pemindahan dana kembali ke Saldo Utama',
            ]);

            ActivityLog::record('target_tabungan_tarik', 'Tarik saldo Rp '.number_format($nominal, 0, ',', '.').' dari kantong "'.$target->nama_target.'" ke saldo utama', $target);
        });

        session()->flash('success', 'Berhasil memindahkan Rp '.number_format($nominal, 0, ',', '.').' dari kantong "'.$targetName.'" kembali ke Saldo Tabungan Utama.');
        $this->closeTarikModal();
    }

    public function openDetailModal(int $id): void
    {
        $nasabah = Auth::guard('nasabah')->user();
        $this->activeTargetId = $id;
        $this->activeTarget = TargetTabunganModel::with('histories')
            ->where('nasabah_id', $nasabah->id)
            ->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->activeTarget = null;
        $this->activeTargetId = null;
    }

    public function openDeleteModal(int $id): void
    {
        $nasabah = Auth::guard('nasabah')->user();
        $this->deleteTargetId = $id;
        $this->deleteTarget = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteTarget = null;
        $this->deleteTargetId = null;
    }

    public function confirmDelete(): void
    {
        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();

        if (! $this->deleteTargetId) {
            $this->closeDeleteModal();

            return;
        }

        $this->deleteTarget = TargetTabunganModel::where('nasabah_id', $nasabah->id)->findOrFail($this->deleteTargetId);
        $targetName = $this->deleteTarget->nama_target;
        $refundAmount = (float) $this->deleteTarget->terkumpul_nominal;

        DB::transaction(function () use ($nasabah, $refundAmount) {
            if ($refundAmount > 0) {
                $nasabah->saldo = (float) $nasabah->saldo + $refundAmount;
                $nasabah->save();
            }

            $this->deleteTarget->delete();
            ActivityLog::record('target_tabungan_hapus', 'Menghapus kantong target: '.$this->deleteTarget->nama_target.' (Pengembalian: Rp '.number_format($refundAmount, 0, ',', '.').')');
        });

        $msg = 'Kantong target tabungan "'.$targetName.'" telah dihapus.';
        if ($refundAmount > 0) {
            $msg .= ' Sisa dana sebesar Rp '.number_format($refundAmount, 0, ',', '.').' telah otomatis dikembalikan ke Saldo Utama Anda.';
        }

        session()->flash('success', $msg);
        $this->closeDeleteModal();
    }

    public function render()
    {
        /** @var Nasabah $nasabah */
        $nasabah = Auth::guard('nasabah')->user();
        $nasabah->refresh();

        $query = TargetTabunganModel::where('nasabah_id', $nasabah->id);

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama_target', 'like', '%'.$this->search.'%')
                    ->orWhere('catatan', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $targets = $query->latest()->get();

        $totalTerkumpulSemua = TargetTabunganModel::where('nasabah_id', $nasabah->id)->sum('terkumpul_nominal');
        $totalTargetSemua = TargetTabunganModel::where('nasabah_id', $nasabah->id)->sum('target_nominal');
        $countTercapai = TargetTabunganModel::where('nasabah_id', $nasabah->id)->where('status', 'tercapai')->count();

        return view('livewire.nasabah.target-tabungan', [
            'nasabah' => $nasabah,
            'targets' => $targets,
            'totalTerkumpulSemua' => $totalTerkumpulSemua,
            'totalTargetSemua' => $totalTargetSemua,
            'countTercapai' => $countTercapai,
            'kategoriOptions' => TargetTabunganModel::KATEGORI_OPTIONS,
        ]);
    }
}
