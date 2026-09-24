<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\ProductItem;
use App\Services\DigiflazzService;
use Livewire\Component;

class SupplierManagement extends Component
{
    public ?float $balance = null;

    public string $balanceStatus = 'idle'; // 'idle', 'loading', 'success', 'error'

    public string $balanceError = '';

    // State Sinkronisasi Harga
    public int $marginFlat = 1500;

    public int $marginPercent = 3;

    public bool $isSyncing = false;

    public ?array $syncResult = null;

    // State Test Inquiry SKU
    public string $lookupSku = '';

    public ?array $lookupResult = null;

    public bool $isLookingUp = false;

    public function mount(DigiflazzService $digiflazz): void
    {
        $this->checkBalance($digiflazz);
    }

    public function checkBalance(DigiflazzService $digiflazz): void
    {
        $this->balanceStatus = 'loading';
        $this->balanceError = '';

        try {
            $this->balance = (float) $digiflazz->checkBalance();
            $this->balanceStatus = 'success';
        } catch (\Throwable $e) {
            $this->balanceStatus = 'error';
            $this->balanceError = $e->getMessage();
        }
    }

    public function runSync(DigiflazzService $digiflazz): void
    {
        $this->isSyncing = true;
        $this->syncResult = null;

        try {
            $result = $digiflazz->syncPriceList($this->marginFlat, $this->marginPercent);
            $this->syncResult = $result;

            AuditLog::log(
                'SYNC_DIGIFLAZZ_PRICELIST',
                "Sinkronisasi harga Digiflazz selesai. Margin Flat: Rp {$this->marginFlat}, Margin %: {$this->marginPercent}%",
                $result
            );

            session()->flash('success_sync', "Sinkronisasi berhasil! {$result['updated']} SKU diperbarui, {$result['created']} SKU baru ditambahkan.");
            $this->checkBalance($digiflazz);
        } catch (\Throwable $e) {
            session()->flash('error_sync', 'Gagal sinkronisasi harga: '.$e->getMessage());
        } finally {
            $this->isSyncing = false;
        }
    }

    public function testInquiry(DigiflazzService $digiflazz): void
    {
        if (empty($this->lookupSku)) {
            return;
        }

        $this->isLookingUp = true;
        $this->lookupResult = null;

        try {
            $response = $digiflazz->checkPriceList();
            $matched = null;

            if (isset($response['data']) && is_array($response['data'])) {
                foreach ($response['data'] as $item) {
                    if (strcasecmp($item['buyer_sku_code'] ?? '', $this->lookupSku) === 0) {
                        $matched = $item;
                        break;
                    }
                }
            }

            $this->lookupResult = $matched ?: ['error' => 'SKU tidak ditemukan dalam pricelist aktif Digiflazz.'];
        } catch (\Throwable $e) {
            $this->lookupResult = ['error' => $e->getMessage()];
        } finally {
            $this->isLookingUp = false;
        }
    }

    public function render()
    {
        $totalSkus = ProductItem::count();
        $availableSkus = ProductItem::where('is_available', true)->count();

        return view('livewire.admin.supplier-management', compact('totalSkus', 'availableSkus'))->layout('layouts.app');
    }
}
