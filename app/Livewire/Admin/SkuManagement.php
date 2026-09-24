<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\ProductItem;
use Livewire\Component;
use Livewire\WithPagination;

class SkuManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $selectedProduct = 'all';

    public string $statusFilter = 'all'; // 'all', 'available', 'unavailable'

    // State Modal Tambah SKU
    public bool $showCreateModal = false;

    public ?int $createProductId = null;

    public string $createName = '';

    public string $createSkuCode = '';

    public string $createOriginalPrice = '';

    public string $createSellingPrice = '';

    public string $createResellerPrice = '';

    // State Modal Edit SKU
    public bool $showEditModal = false;

    public ?int $editItemId = null;

    public string $editName = '';

    public string $editSkuCode = '';

    public string $editOriginalPrice = '';

    public string $editSellingPrice = '';

    public string $editResellerPrice = '';

    public bool $editIsAvailable = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedProduct' => ['except' => 'all'],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedProduct(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    // Toggle Ketersediaan SKU
    public function toggleAvailability(int $itemId): void
    {
        $item = ProductItem::findOrFail($itemId);
        $item->is_available = ! $item->is_available;
        $item->save();

        AuditLog::log(
            'TOGGLE_SKU_AVAILABILITY',
            "Mengubah status ketersediaan SKU {$item->name} ({$item->sku_code}) menjadi ".($item->is_available ? 'Tersedia' : 'Gangguan/Habis'),
            ['item_id' => $item->id, 'is_available' => $item->is_available]
        );

        session()->flash('success', "Status ketersediaan SKU {$item->name} berhasil diperbarui.");
    }

    // Tambah SKU Baru
    public function openCreateModal(): void
    {
        $this->createProductId = Product::first()?->id;
        $this->createName = '';
        $this->createSkuCode = '';
        $this->createOriginalPrice = '';
        $this->createSellingPrice = '';
        $this->createResellerPrice = '';
        $this->showCreateModal = true;
    }

    public function saveNewSku(): void
    {
        $this->validate([
            'createProductId' => 'required|exists:products,id',
            'createName' => 'required|string|max:255',
            'createSkuCode' => 'required|string|max:100|unique:product_items,sku_code',
            'createOriginalPrice' => 'required|numeric|min:0',
            'createSellingPrice' => 'required|numeric|min:0',
            'createResellerPrice' => 'nullable|numeric|min:0',
        ]);

        $item = ProductItem::create([
            'product_id' => $this->createProductId,
            'name' => $this->createName,
            'sku_code' => strtoupper(trim($this->createSkuCode)),
            'original_price' => (float) $this->createOriginalPrice,
            'selling_price' => (float) $this->createSellingPrice,
            'reseller_price' => ! empty($this->createResellerPrice) ? (float) $this->createResellerPrice : null,
            'is_available' => true,
        ]);

        AuditLog::log(
            'CREATE_SKU',
            "Menambahkan SKU baru: {$item->name} ({$item->sku_code}) untuk produk ID #{$item->product_id}",
            ['item_id' => $item->id, 'sku' => $item->sku_code, 'price' => $item->selling_price]
        );

        $this->showCreateModal = false;
        session()->flash('success', "SKU {$item->name} berhasil ditambahkan!");
    }

    // Edit SKU
    public function openEditModal(int $itemId): void
    {
        $item = ProductItem::findOrFail($itemId);
        $this->editItemId = $item->id;
        $this->editName = $item->name;
        $this->editSkuCode = $item->sku_code;
        $this->editOriginalPrice = (string) (int) $item->original_price;
        $this->editSellingPrice = (string) (int) $item->selling_price;
        $this->editResellerPrice = (string) (int) ($item->reseller_price ?? 0);
        $this->editIsAvailable = (bool) $item->is_available;
        $this->showEditModal = true;
    }

    public function updateSku(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editSkuCode' => 'required|string|max:100|unique:product_items,sku_code,'.$this->editItemId,
            'editOriginalPrice' => 'required|numeric|min:0',
            'editSellingPrice' => 'required|numeric|min:0',
            'editResellerPrice' => 'nullable|numeric|min:0',
        ]);

        $item = ProductItem::findOrFail($this->editItemId);
        $item->update([
            'name' => $this->editName,
            'sku_code' => strtoupper(trim($this->editSkuCode)),
            'original_price' => (float) $this->editOriginalPrice,
            'selling_price' => (float) $this->editSellingPrice,
            'reseller_price' => ! empty($this->editResellerPrice) ? (float) $this->editResellerPrice : null,
            'is_available' => $this->editIsAvailable,
        ]);

        AuditLog::log(
            'UPDATE_SKU',
            "Memperbarui data SKU {$item->name} ({$item->sku_code})",
            ['item_id' => $item->id, 'selling_price' => $item->selling_price]
        );

        $this->showEditModal = false;
        session()->flash('success', "SKU {$item->name} berhasil diperbarui!");
    }

    // Hapus SKU
    public function deleteSku(int $itemId): void
    {
        $item = ProductItem::findOrFail($itemId);
        $name = $item->name;
        $sku = $item->sku_code;
        $item->delete();

        AuditLog::log('DELETE_SKU', "Menghapus SKU: {$name} ({$sku})");
        session()->flash('success', "SKU {$name} berhasil dihapus.");
    }

    public function render()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        $items = ProductItem::query()
            ->with('product.category')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('sku_code', 'like', '%'.$this->search.'%')
                        ->orWhereHas('product', function ($pq) {
                            $pq->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->selectedProduct !== 'all', function ($query) {
                $query->where('product_id', $this->selectedProduct);
            })
            ->when($this->statusFilter === 'available', function ($query) {
                $query->where('is_available', true);
            })
            ->when($this->statusFilter === 'unavailable', function ($query) {
                $query->where('is_available', false);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.sku-management', compact('items', 'products'))->layout('layouts.app');
    }
}
