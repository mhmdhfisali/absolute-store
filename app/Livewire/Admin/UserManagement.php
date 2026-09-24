<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = 'all';

    public string $tierFilter = 'all';

    // State Modal Ubah Role & Tier
    public bool $showRoleModal = false;

    public ?int $selectedUserId = null;

    public string $editRole = 'member';

    public string $editTier = 'member';

    // State Modal Penyesuaian Saldo Manual (Double-Entry Ledger)
    public bool $showBalanceModal = false;

    public ?int $balanceUserId = null;

    public string $balanceUserName = '';

    public float $currentBalance = 0;

    public string $balanceAdjustmentType = 'credit'; // 'credit' (tambah) / 'debit' (kurang)

    public string $adjustmentAmount = '';

    public string $adjustmentNote = '';

    // State Modal Ban / Suspend
    public bool $showBanModal = false;

    public ?int $banUserId = null;

    public string $banUserName = '';

    public bool $isCurrentlyBanned = false;

    public string $banReason = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => 'all'],
        'tierFilter' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTierFilter(): void
    {
        $this->resetPage();
    }

    // --- Ubah Role & Tier ---
    public function openRoleModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->selectedUserId = $user->id;
        $this->editRole = $user->role ?? 'member';
        $this->editTier = $user->tier ?? 'member';
        $this->showRoleModal = true;
    }

    public function saveRoleAndTier(): void
    {
        $this->validate([
            'editRole' => 'required|in:member,admin,superadmin',
            'editTier' => 'required|in:member,reseller,vip',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $oldRole = $user->role;
        $oldTier = $user->tier;

        $user->update([
            'role' => $this->editRole,
            'tier' => $this->editTier,
        ]);

        AuditLog::log(
            'CHANGE_USER_ROLE_TIER',
            "Mengubah Role & Tier user {$user->name} (#{$user->id}) dari [Role: {$oldRole}, Tier: {$oldTier}] ke [Role: {$this->editRole}, Tier: {$this->editTier}]",
            ['user_id' => $user->id, 'old_role' => $oldRole, 'new_role' => $this->editRole, 'old_tier' => $oldTier, 'new_tier' => $this->editTier]
        );

        $this->showRoleModal = false;
        session()->flash('success', "Role & Tier pengguna {$user->name} berhasil diperbarui.");
    }

    // --- Penyesuaian Saldo Manual (Double-Entry Ledger) ---
    public function openBalanceModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->balanceUserId = $user->id;
        $this->balanceUserName = $user->name;
        $this->currentBalance = (float) $user->balance;
        $this->balanceAdjustmentType = 'credit';
        $this->adjustmentAmount = '';
        $this->adjustmentNote = '';
        $this->showBalanceModal = true;
    }

    public function applyBalanceAdjustment(): void
    {
        $this->validate([
            'adjustmentAmount' => 'required|numeric|min:1',
            'balanceAdjustmentType' => 'required|in:credit,debit',
            'adjustmentNote' => 'required|string|max:255',
        ]);

        $amount = (float) $this->adjustmentAmount;

        DB::transaction(function () use ($amount) {
            $user = User::where('id', $this->balanceUserId)->lockForUpdate()->firstOrFail();
            $before = (float) $user->balance;

            if ($this->balanceAdjustmentType === 'debit' && $amount > $before) {
                $this->addError('adjustmentAmount', 'Nominal potongan melebihi sisa saldo user (Rp '.number_format($before, 0, ',', '.').').');

                return;
            }

            $after = $this->balanceAdjustmentType === 'credit'
                ? $before + $amount
                : $before - $amount;

            $user->update(['balance' => $after]);

            $ref = 'ADJ-'.strtoupper(Str::random(10));

            WalletTransaction::create([
                'user_id' => $user->id,
                'reference_id' => $ref,
                'type' => $this->balanceAdjustmentType,
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'category' => 'adjustment',
                'description' => "Penyesuaian saldo manual oleh Admin: {$this->adjustmentNote}",
            ]);

            AuditLog::log(
                'MANUAL_BALANCE_ADJUSTMENT',
                "Penyesuaian saldo {$this->balanceAdjustmentType} sebesar Rp ".number_format($amount, 0, ',', '.')." untuk {$user->name} (#{$user->id}). Alasan: {$this->adjustmentNote}",
                ['user_id' => $user->id, 'reference' => $ref, 'type' => $this->balanceAdjustmentType, 'amount' => $amount, 'before' => $before, 'after' => $after]
            );
        });

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $this->showBalanceModal = false;
        session()->flash('success', "Saldo pengguna {$this->balanceUserName} berhasil disesuaikan.");
    }

    // --- Ban / Suspend Akun ---
    public function openBanModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->banUserId = $user->id;
        $this->banUserName = $user->name;
        $this->isCurrentlyBanned = (bool) $user->is_banned;
        $this->banReason = $user->ban_reason ?? '';
        $this->showBanModal = true;
    }

    public function toggleBanStatus(): void
    {
        $user = User::findOrFail($this->banUserId);

        if ($this->isCurrentlyBanned) {
            // Unban
            $user->update([
                'is_banned' => false,
                'ban_reason' => null,
            ]);

            AuditLog::log('USER_UNBANNED', "Membuka blokir akun pengguna {$user->name} (#{$user->id})");
            session()->flash('success', "Blokir akun {$user->name} telah dicabut.");
        } else {
            // Ban
            $this->validate([
                'banReason' => 'required|string|min:5|max:500',
            ]);

            $user->update([
                'is_banned' => true,
                'ban_reason' => $this->banReason,
            ]);

            AuditLog::log('USER_BANNED', "Memblokir akun pengguna {$user->name} (#{$user->id}). Alasan: {$this->banReason}");
            session()->flash('success', "Akun {$user->name} berhasil ditangguhkan/diblokir.");
        }

        $this->showBanModal = false;
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->roleFilter !== 'all', function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->tierFilter !== 'all', function ($query) {
                $query->where('tier', $this->tierFilter);
            })
            ->withCount(['transactions', 'walletTransactions'])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.user-management', compact('users'))->layout('layouts.app');
    }
}
