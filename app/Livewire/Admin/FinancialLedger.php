<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use App\Services\DigiflazzService;
use Livewire\Component;
use Livewire\WithPagination;

class FinancialLedger extends Component
{
    use WithPagination;

    public string $search = '';

    public string $typeFilter = 'all'; // 'all', 'credit', 'debit'

    public string $period = 'today'; // 'today', 'week', 'month', 'all'

    // Status Rekonsiliasi Supplier
    public bool $isReconciling = false;

    public ?float $supplierBalance = null;

    public ?string $reconciliationStatus = null;

    public ?string $reconciliationMessage = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
        'period' => ['except' => 'today'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPeriod(): void
    {
        $this->resetPage();
    }

    public function runReconciliation(DigiflazzService $digiflazz): void
    {
        $this->isReconciling = true;

        try {
            $balance = (float) $digiflazz->checkBalance();
            $this->supplierBalance = $balance;

            if ($balance < 1000000) {
                $this->reconciliationStatus = 'warning';
                $this->reconciliationMessage = 'Peringatan: Saldo supplier menipis (Rp '.number_format($balance, 0, ',', '.').'). Segera lakukan top-up saldo deposit PPOB.';
            } else {
                $this->reconciliationStatus = 'healthy';
                $this->reconciliationMessage = 'Integritas saldo supplier optimal: Rp '.number_format($balance, 0, ',', '.').' terverifikasi online.';
            }

            AuditLog::log(
                'RUN_FINANCIAL_RECONCILIATION',
                'Audit Rekonsiliasi Keuangan: Saldo Digiflazz terverifikasi sebesar Rp '.number_format($balance, 0, ',', '.'),
                ['supplier_balance' => $balance]
            );
        } catch (\Throwable $e) {
            $this->reconciliationStatus = 'error';
            $this->reconciliationMessage = 'Gagal menghubungi API Digiflazz: '.$e->getMessage();
        } finally {
            $this->isReconciling = false;
        }
    }

    public function render()
    {
        $startDate = match ($this->period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            default => null,
        };

        // Ringkasan Finansial Periode
        $creditSum = WalletTransaction::where('type', 'credit')
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->sum('amount');

        $debitSum = WalletTransaction::where('type', 'debit')
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->sum('amount');

        $successfulOrdersTotal = Transaction::where('delivery_status', 'success')
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->sum('total_amount');

        // Query Mutasi Ledger
        $mutations = WalletTransaction::query()
            ->with('user')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('reference_id', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($uq) {
                            $uq->where('name', 'like', '%'.$this->search.'%')
                                ->orWhere('email', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->typeFilter !== 'all', function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.financial-ledger', compact(
            'mutations',
            'creditSum',
            'debitSum',
            'successfulOrdersTotal'
        ))->layout('layouts.app');
    }
}
