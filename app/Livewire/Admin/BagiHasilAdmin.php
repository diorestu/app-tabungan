<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Otomasi Biaya Admin & Bagi Hasil Bulanan - TabunganKu')]
class BagiHasilAdmin extends Component
{
    public string $activeTab = 'admin_fee'; // admin_fee, bagi_hasil, riwayat

    // Admin Fee Properties
    public float|string $admin_fee_amount = 5000;
    public float|string $admin_min_balance = 50000;
    public string $admin_fee_period = '';
    public array $admin_fee_simulation = [];

    // Bagi Hasil / Profit Share Properties
    public float|string $bagi_hasil_rate = 0.5; // in %
    public float|string $bagi_hasil_min_balance = 100000;
    public string $bagi_hasil_period = '';
    public array $bagi_hasil_simulation = [];

    // Confirmation & Result State
    public bool $showConfirmAdminModal = false;
    public bool $showConfirmBagiHasilModal = false;
    public ?array $lastExecutionResult = null;

    public function mount(): void
    {
        $this->admin_fee_amount = (float) Setting::get('biaya_admin_bulanan', 5000);
        $this->admin_min_balance = (float) Setting::get('min_saldo_biaya_admin', 50000);
        $this->admin_fee_period = date('F Y');

        $this->bagi_hasil_rate = (float) Setting::get('rate_bagi_hasil', 0.5);
        $this->bagi_hasil_min_balance = (float) Setting::get('min_saldo_bagi_hasil', 100000);
        $this->bagi_hasil_period = date('F Y');

        $this->simulateAdminFee();
        $this->simulateBagiHasil();
    }

    public function simulateAdminFee(): void
    {
        $fee = (float) $this->admin_fee_amount;
        $minBalance = (float) $this->admin_min_balance;

        $eligibleNasabahs = Nasabah::where('status', 'aktif')
            ->where('saldo', '>=', $minBalance)
            ->get();

        $totalNasabah = $eligibleNasabahs->count();
        $totalNominal = $totalNasabah * $fee;

        $this->admin_fee_simulation = [
            'total_nasabah' => $totalNasabah,
            'fee_per_nasabah' => $fee,
            'total_potongan' => $totalNominal,
            'preview_list' => $eligibleNasabahs->take(10)->map(function ($n) use ($fee) {
                return [
                    'id' => $n->id,
                    'nama' => $n->nama,
                    'nomor' => $n->nomor_nasabah,
                    'saldo_awal' => $n->saldo,
                    'potongan' => $fee,
                    'saldo_akhir' => max(0, $n->saldo - $fee),
                ];
            })->toArray(),
        ];
    }

    public function simulateBagiHasil(): void
    {
        $ratePercent = (float) $this->bagi_hasil_rate;
        $rateDecimal = $ratePercent / 100;
        $minBalance = (float) $this->bagi_hasil_min_balance;

        $eligibleNasabahs = Nasabah::where('status', 'aktif')
            ->where('saldo', '>=', $minBalance)
            ->get();

        $totalNominal = 0;
        $previewList = [];

        foreach ($eligibleNasabahs as $n) {
            $bonus = round($n->saldo * $rateDecimal, 2);
            $totalNominal += $bonus;

            if (count($previewList) < 10) {
                $previewList[] = [
                    'id' => $n->id,
                    'nama' => $n->nama,
                    'nomor' => $n->nomor_nasabah,
                    'saldo_awal' => $n->saldo,
                    'bonus' => $bonus,
                    'saldo_akhir' => $n->saldo + $bonus,
                ];
            }
        }

        $this->bagi_hasil_simulation = [
            'total_nasabah' => $eligibleNasabahs->count(),
            'rate' => $ratePercent,
            'total_bagi_hasil' => $totalNominal,
            'preview_list' => $previewList,
        ];
    }

    public function openConfirmAdminModal(): void
    {
        $this->simulateAdminFee();
        $this->showConfirmAdminModal = true;
    }

    public function closeConfirmAdminModal(): void
    {
        $this->showConfirmAdminModal = false;
    }

    public function executeAdminFee(): void
    {
        $user = Auth::guard('web')->user();
        if ($user->role !== 'admin') {
            session()->flash('error_batch', 'Hanya administrator yang berwenang mengeksekusi pemotongan biaya admin massal.');
            $this->closeConfirmAdminModal();
            return;
        }

        $fee = (float) $this->admin_fee_amount;
        $minBalance = (float) $this->admin_min_balance;
        $period = $this->admin_fee_period ?: date('F Y');

        $processedCount = 0;
        $totalDeducted = 0;

        DB::transaction(function () use ($fee, $minBalance, $period, $user, &$processedCount, &$totalDeducted) {
            $eligibleNasabahs = Nasabah::where('status', 'aktif')
                ->where('saldo', '>=', $minBalance)
                ->lockForUpdate()
                ->get();

            foreach ($eligibleNasabahs as $nasabah) {
                $saldoAwal = (float) $nasabah->saldo;
                $saldoAkhir = max(0, $saldoAwal - $fee);

                Transaksi::create([
                    'kode_transaksi' => Transaksi::generateKodeTransaksi('tarik'),
                    'nasabah_id' => $nasabah->id,
                    'user_id' => $user->id,
                    'jenis_transaksi' => 'tarik',
                    'nominal' => $fee,
                    'saldo_awal' => $saldoAwal,
                    'saldo_akhir' => $saldoAkhir,
                    'keterangan' => 'Biaya administrasi bulanan periode ' . $period,
                ]);

                $nasabah->update(['saldo' => $saldoAkhir]);

                $processedCount++;
                $totalDeducted += $fee;
            }

            // Save settings
            Setting::set('biaya_admin_bulanan', (string) $fee, 'Nominal Biaya Admin Bulanan');
            Setting::set('min_saldo_biaya_admin', (string) $minBalance, 'Minimum Saldo Kena Biaya Admin');
        });

        ActivityLog::record(
            'batch_biaya_admin',
            'Eksekusi pemotongan biaya admin bulanan periode ' . $period . ': ' . $processedCount . ' nasabah, total Rp ' . number_format($totalDeducted, 0, ',', '.'),
            null,
            [
                'period' => $period,
                'total_nasabah' => $processedCount,
                'total_deducted' => $totalDeducted,
                'fee_per_nasabah' => $fee,
            ]
        );

        $this->lastExecutionResult = [
            'type' => 'admin_fee',
            'period' => $period,
            'processed_count' => $processedCount,
            'total_nominal' => $totalDeducted,
        ];

        $this->closeConfirmAdminModal();
        session()->flash('success_batch', 'Pemotongan biaya admin bulanan berhasil dieksekusi untuk ' . $processedCount . ' nasabah.');
        $this->simulateAdminFee();
    }

    public function openConfirmBagiHasilModal(): void
    {
        $this->simulateBagiHasil();
        $this->showConfirmBagiHasilModal = true;
    }

    public function closeConfirmBagiHasilModal(): void
    {
        $this->showConfirmBagiHasilModal = false;
    }

    public function executeBagiHasil(): void
    {
        $user = Auth::guard('web')->user();
        if ($user->role !== 'admin') {
            session()->flash('error_batch', 'Hanya administrator yang berwenang mengeksekusi bagi hasil massal.');
            $this->closeConfirmBagiHasilModal();
            return;
        }

        $ratePercent = (float) $this->bagi_hasil_rate;
        $rateDecimal = $ratePercent / 100;
        $minBalance = (float) $this->bagi_hasil_min_balance;
        $period = $this->bagi_hasil_period ?: date('F Y');

        $processedCount = 0;
        $totalDistributed = 0;

        DB::transaction(function () use ($ratePercent, $rateDecimal, $minBalance, $period, $user, &$processedCount, &$totalDistributed) {
            $eligibleNasabahs = Nasabah::where('status', 'aktif')
                ->where('saldo', '>=', $minBalance)
                ->lockForUpdate()
                ->get();

            foreach ($eligibleNasabahs as $nasabah) {
                $bonus = round($nasabah->saldo * $rateDecimal, 2);
                if ($bonus <= 0) {
                    continue;
                }

                $saldoAwal = (float) $nasabah->saldo;
                $saldoAkhir = $saldoAwal + $bonus;

                Transaksi::create([
                    'kode_transaksi' => Transaksi::generateKodeTransaksi('setor'),
                    'nasabah_id' => $nasabah->id,
                    'user_id' => $user->id,
                    'jenis_transaksi' => 'setor',
                    'nominal' => $bonus,
                    'saldo_awal' => $saldoAwal,
                    'saldo_akhir' => $saldoAkhir,
                    'keterangan' => 'Bagi hasil / bunga simpanan periode ' . $period . ' (' . $ratePercent . '%)',
                ]);

                $nasabah->update(['saldo' => $saldoAkhir]);

                $processedCount++;
                $totalDistributed += $bonus;
            }

            // Save settings
            Setting::set('rate_bagi_hasil', (string) $ratePercent, 'Persentase Bagi Hasil Bulanan');
            Setting::set('min_saldo_bagi_hasil', (string) $minBalance, 'Minimum Saldo Dapat Bagi Hasil');
        });

        ActivityLog::record(
            'batch_bagi_hasil',
            'Eksekusi pembagian bagi hasil simpanan periode ' . $period . ' (' . $ratePercent . '%): ' . $processedCount . ' nasabah, total Rp ' . number_format($totalDistributed, 0, ',', '.'),
            null,
            [
                'period' => $period,
                'rate' => $ratePercent,
                'total_nasabah' => $processedCount,
                'total_distributed' => $totalDistributed,
            ]
        );

        $this->lastExecutionResult = [
            'type' => 'bagi_hasil',
            'period' => $period,
            'processed_count' => $processedCount,
            'total_nominal' => $totalDistributed,
        ];

        $this->closeConfirmBagiHasilModal();
        session()->flash('success_batch', 'Pembagian bagi hasil berhasil dieksekusi untuk ' . $processedCount . ' nasabah (Total Rp ' . number_format($totalDistributed, 0, ',', '.') . ').');
        $this->simulateBagiHasil();
    }

    public function render()
    {
        $settlementLogs = ActivityLog::whereIn('action', ['batch_biaya_admin', 'batch_bagi_hasil'])
            ->latest()
            ->paginate(10);

        return view('livewire.admin.bagi-hasil-admin', [
            'settlementLogs' => $settlementLogs,
        ]);
    }
}
