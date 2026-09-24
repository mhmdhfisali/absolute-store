<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\DigiflazzService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerServicePanel extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all'; // 'all', 'failed', 'processing', 'unpaid'

    // State Modal Force-Complete
    public bool $showCompleteModal = false;

    public ?int $selectedTransactionId = null;

    public string $manualSerialNumber = '';

    public string $completeAdminNote = '';

    // State Modal Force-Refund
    public bool $showRefundModal = false;

    public ?int $refundTransactionId = null;

    public string $refundInvoice = '';

    public float $refundAmount = 0;

    public string $refundTargetUser = '';

    public string $refundReason = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    // --- Force Complete Action ---
    public function openCompleteModal(int $txId): void
    {
        $tx = Transaction::findOrFail($txId);
        $this->selectedTransactionId = $tx->id;
        $this->manualSerialNumber = $tx->serial_number ?? '';
        $this->completeAdminNote = '';
        $this->showCompleteModal = true;
    }

    public function executeForceComplete(): void
    {
        $this->validate([
            'manualSerialNumber' => 'required|string|min:3|max:255',
            'completeAdminNote' => 'required|string|max:255',
        ]);

        $tx = Transaction::findOrFail($this->selectedTransactionId);

        $tx->update([
            'payment_status' => 'paid',
            'delivery_status' => 'success',
            'serial_number' => $this->manualSerialNumber,
        ]);

        AuditLog::log(
            'FORCE_COMPLETE_ORDER',
            "Force-Complete pesanan #{$tx->invoice_number} oleh Admin. SN: {$this->manualSerialNumber}. Catatan: {$this->completeAdminNote}",
            ['transaction_id' => $tx->id, 'invoice' => $tx->invoice_number, 'sn' => $this->manualSerialNumber]
        );

        $this->showCompleteModal = false;
        session()->flash('success', "Pesanan #{$tx->invoice_number} berhasil diselesaikan secara manual.");
    }

    // --- Force Refund Action ---
    public function openRefundModal(int $txId): void
    {
        $tx = Transaction::with('user')->findOrFail($txId);
        $this->refundTransactionId = $tx->id;
        $this->refundInvoice = $tx->invoice_number;
        $this->refundAmount = (float) $tx->total_amount;
        $this->refundTargetUser = $tx->user?->name ?? 'Guest/Non-Member';
        $this->refundReason = '';
        $this->showRefundModal = true;
    }

    public function executeForceRefund(): void
    {
        $this->validate([
            'refundReason' => 'required|string|min:5|max:255',
        ]);

        $tx = Transaction::with('user')->findOrFail($this->refundTransactionId);

        if (! $tx->user) {
            $this->addError('refundReason', 'Transaksi ini milik tamu (Guest). Pengembalian dana otomatis hanya didukung untuk akun member terdaftar.');

            return;
        }

        DB::transaction(function () use ($tx) {
            $user = User::where('id', $tx->user_id)->lockForUpdate()->firstOrFail();
            $refundAmount = (float) $tx->total_amount;

            $before = (float) $user->balance;
            $after = $before + $refundAmount;

            $user->update(['balance' => $after]);

            $tx->update([
                'delivery_status' => 'failed',
            ]);

            $ref = 'REFUND-'.strtoupper(Str::random(10));

            WalletTransaction::create([
                'user_id' => $user->id,
                'reference_id' => $ref,
                'type' => 'credit',
                'amount' => $refundAmount,
                'balance_before' => $before,
                'balance_after' => $after,
                'category' => 'refund',
                'description' => "Refund pesanan gagal #{$tx->invoice_number}: {$this->refundReason}",
            ]);

            AuditLog::log(
                'FORCE_REFUND_ORDER',
                "Refund dana pesanan #{$tx->invoice_number} sebesar Rp ".number_format($refundAmount, 0, ',', '.')." ke wallet {$user->name}. Alasan: {$this->refundReason}",
                ['transaction_id' => $tx->id, 'invoice' => $tx->invoice_number, 'amount' => $refundAmount]
            );
        });

        $this->showRefundModal = false;
        session()->flash('success', "Dana pesanan #{$tx->invoice_number} berhasil direfund ke dompet pelanggan.");
    }

    // --- Re-Check Status Provider API ---
    public function recheckStatus(int $txId, DigiflazzService $digiflazz): void
    {
        $tx = Transaction::findOrFail($txId);

        try {
            // Cek status ke provider
            $response = $digiflazz->checkStatus($tx->invoice_number);

            if (isset($response['status'])) {
                if ($response['status'] === 'Sukses') {
                    $tx->update([
                        'delivery_status' => 'success',
                        'serial_number' => $response['sn'] ?? $tx->serial_number,
                    ]);
                    session()->flash('success', "Status #{$tx->invoice_number} berhasil disinkron: SUKSES (SN: {$tx->serial_number}).");
                } elseif ($response['status'] === 'Gagal') {
                    $tx->update(['delivery_status' => 'failed']);
                    session()->flash('success', "Status #{$tx->invoice_number} tersinkron: GAGAL.");
                } else {
                    session()->flash('info', "Status pesanan di provider: {$response['status']}.");
                }
            } else {
                session()->flash('info', 'Pengecekan selesai. Tidak ada pembaruan status dari provider.');
            }
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal re-check status: '.$e->getMessage());
        }
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->with(['user', 'item.product', 'paymentMethod'])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%'.$this->search.'%')
                        ->orWhere('target_account', 'like', '%'.$this->search.'%')
                        ->orWhere('contact_email_or_phone', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function ($uq) {
                            $uq->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->statusFilter === 'failed', function ($query) {
                $query->where('delivery_status', 'failed');
            })
            ->when($this->statusFilter === 'processing', function ($query) {
                $query->where('delivery_status', 'processing');
            })
            ->when($this->statusFilter === 'unpaid', function ($query) {
                $query->where('payment_status', 'unpaid');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.customer-service-panel', compact('transactions'))->layout('layouts.app');
    }
}
