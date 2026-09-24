<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogsViewer extends Component
{
    use WithPagination;

    public string $search = '';

    public string $actionFilter = 'all';

    // State Modal Payload Detail
    public bool $showPayloadModal = false;

    public ?array $selectedPayload = null;

    public string $selectedLogAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'actionFilter' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActionFilter(): void
    {
        $this->resetPage();
    }

    public function viewPayload(int $logId): void
    {
        $log = AuditLog::findOrFail($logId);
        $this->selectedPayload = $log->payload ?? [];
        $this->selectedLogAction = $log->action;
        $this->showPayloadModal = true;
    }

    public function render()
    {
        $distinctActions = AuditLog::select('action')->distinct()->pluck('action');

        $logs = AuditLog::query()
            ->with('user')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%'.$this->search.'%')
                        ->orWhere('ip_address', 'like', '%'.$this->search.'%')
                        ->orWhere('action', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($uq) {
                            $uq->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->actionFilter !== 'all', function ($query) {
                $query->where('action', $this->actionFilter);
            })
            ->latest()
            ->paginate(20);

        return view('livewire.admin.audit-logs-viewer', compact('logs', 'distinctActions'))->layout('layouts.app');
    }
}
