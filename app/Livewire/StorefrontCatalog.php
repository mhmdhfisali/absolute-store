<?php

namespace App\Livewire;

use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\Wishlist;
use Livewire\Component;
use Livewire\WithPagination;

class StorefrontCatalog extends Component
{
    use WithPagination;

    public string $search = '';

    public string $selectedCategory = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectCategory(string $slug): void
    {
        $this->selectedCategory = $slug;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedCategory = 'all';
        $this->resetPage();
    }

    public function toggleWishlist(int $productId): void
    {
        if (! auth()->check()) {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'title' => 'Perhatian',
                'message' => 'Silakan masuk / login terlebih dahulu untuk menyimpan ke Wishlist.',
            ]);

            return;
        }

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $this->dispatch('show-toast', [
                'type' => 'info',
                'title' => 'Wishlist',
                'message' => 'Produk dihapus dari Wishlist favorit Anda.',
            ]);
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
            ]);
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Wishlist Disimpan',
                'message' => 'Produk berhasil ditambahkan ke Wishlist favorit!',
            ]);
        }
    }

    public function render()
    {
        $categories = Category::withCount('products')->get();
        $banners = Banner::where('is_active', true)->latest()->get();
        $announcements = Announcement::where('is_active', true)->latest()->get();
        $userWishlistProductIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        $productsQuery = Product::query()
            ->where('is_active', true)
            ->with(['category', 'approvedReviews', 'items' => function ($query) {
                $query->where('is_available', true);
            }])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('provider_code', 'like', '%'.$this->search.'%')
                        ->orWhereHas('category', function ($cq) {
                            $cq->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->selectedCategory !== 'all', function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->selectedCategory);
                });
            })
            ->latest();

        $products = $productsQuery->get();

        // Flash Sale items: Ambil item dengan harga menarik atau populer
        $flashSaleItems = ProductItem::query()
            ->where('is_available', true)
            ->whereHas('product', fn ($q) => $q->where('is_active', true))
            ->with('product.category')
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->map(function ($item, $index) {
                $discountPercent = match ($index % 4) {
                    0 => 25,
                    1 => 18,
                    2 => 32,
                    default => 15,
                };

                $sellingPrice = (float) $item->selling_price;
                $originalPrice = $sellingPrice > 0
                    ? ceil($sellingPrice / (1 - ($discountPercent / 100)))
                    : 15000;

                $soldPercentage = match ($index % 4) {
                    0 => 88,
                    1 => 94,
                    2 => 76,
                    default => 82,
                };

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'product_name' => $item->product->name,
                    'product_slug' => $item->product->slug,
                    'thumbnail' => $item->product->thumbnail_url,
                    'category' => $item->product->category?->name ?? 'Game',
                    'selling_price' => $sellingPrice,
                    'original_price' => $originalPrice,
                    'discount_percent' => $discountPercent,
                    'sold_percentage' => $soldPercentage,
                    'stock_left' => rand(3, 14),
                ];
            });

        return view('livewire.storefront-catalog', compact(
            'categories',
            'banners',
            'announcements',
            'products',
            'flashSaleItems',
            'userWishlistProductIds'
        ));
    }
}
