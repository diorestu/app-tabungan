<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Audit Trail & Log Aktivitas Keamanan - TabunganKu')]
class AuditLog extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $action = '';

    #[Url]
    public string $userType = '';

    #[Url]
    public string $startDate = '';

    #[Url]
    public string $endDate = '';

    public ?ActivityLog $selectedLog = null;
    public bool $showDetailModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAction(): void
    {
        $this->resetPage();
    }

    public function updatingUserType(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->action = '';
        $this->userType = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
    }

    public function showLogDetail(int $id): void
    {
        $this->selectedLog = ActivityLog::with('user')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedLog = null;
    }

    public function exportCsv()
    {
        $query = ActivityLog::latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('user_name', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->action)) {
            $query->where('action', $this->action);
        }

        if (!empty($this->userType)) {
            $query->where('user_type', $this->userType);
        }

        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $logs = $query->get();
        $filename = 'audit-trail-log-' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID Log',
                'Waktu',
                'Tipe Pengguna',
                'Nama Pengguna',
                'Aksi / Event',
                'Deskripsi Aktivitas',
                'IP Address',
                'Device / User Agent',
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    strtoupper($log->user_type),
                    $log->user_name,
                    $log->action,
                    $log->description,
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function render()
    {
        $query = ActivityLog::latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('user_name', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->action)) {
            $query->where('action', $this->action);
        }

        if (!empty($this->userType)) {
            $query->where('user_type', $this->userType);
        }

        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $logs = $query->paginate(20);
        $totalLogs = ActivityLog::count();
        $totalPetugasAction = ActivityLog::where('user_type', 'petugas')->count();
        $totalNasabahAction = ActivityLog::where('user_type', 'nasabah')->count();

        return view('livewire.admin.audit-log', [
            'logs' => $logs,
            'totalLogs' => $totalLogs,
            'totalPetugasAction' => $totalPetugasAction,
            'totalNasabahAction' => $totalNasabahAction,
        ]);
    }
}
