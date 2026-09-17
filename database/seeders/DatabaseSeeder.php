<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@absolutestore.id'],
            [
                'name'     => 'Admin Absolute',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Kategori Layanan
        $catGames     = Category::firstOrCreate(['slug' => 'games'], ['name' => 'Game Populer', 'icon' => 'gamepad']);
        $catPulsa     = Category::firstOrCreate(['slug' => 'pulsa-data'], ['name' => 'Pulsa & Paket Data', 'icon' => 'smartphone']);
        $catPln       = Category::firstOrCreate(['slug' => 'pln'], ['name' => 'Token & Tagihan PLN', 'icon' => 'zap']);
        $catEwallet   = Category::firstOrCreate(['slug' => 'ewallet'], ['name' => 'Top Up E-Wallet', 'icon' => 'wallet']);
        $catStreaming = Category::firstOrCreate(['slug' => 'streaming'], ['name' => 'Voucher Streaming', 'icon' => 'tv']);
        $catVoucher   = Category::firstOrCreate(['slug' => 'voucher-game'], ['name' => 'PC & Console Voucher', 'icon' => 'gift']);

        // 3. Katalog Produk dengan Thumbnail Gambar Resmi

        // --- Mobile Legends ---
        $ml = Product::updateOrCreate(
            ['slug' => 'mobile-legends'],
            [
                'category_id' => $catGames->id,
                'name'        => 'Mobile Legends: Bang Bang',
                'thumbnail'   => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'id_and_zone',
                'is_active'   => true,
            ]
        );
        $this->createItems($ml->id, [
            ['name' => '86 Diamonds (78+8)', 'sku' => 'MLBB-86', 'cost' => 19000, 'price' => 20500],
            ['name' => '172 Diamonds (156+16)', 'sku' => 'MLBB-172', 'cost' => 38000, 'price' => 41000],
            ['name' => '257 Diamonds (234+23)', 'sku' => 'MLBB-257', 'cost' => 57000, 'price' => 61000],
            ['name' => 'Weekly Diamond Pass', 'sku' => 'MLBB-WDP', 'cost' => 27000, 'price' => 28500],
            ['name' => 'Twilight Pass', 'sku' => 'MLBB-TP', 'cost' => 135000, 'price' => 143000],
        ]);

        // --- Free Fire Max ---
        $ff = Product::updateOrCreate(
            ['slug' => 'free-fire'],
            [
                'category_id' => $catGames->id,
                'name'        => 'Free Fire Max',
                'thumbnail'   => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'id_only',
                'is_active'   => true,
            ]
        );
        $this->createItems($ff->id, [
            ['name' => '70 Diamonds', 'sku' => 'FF-70', 'cost' => 9000, 'price' => 10000],
            ['name' => '140 Diamonds', 'sku' => 'FF-140', 'cost' => 18000, 'price' => 19500],
            ['name' => '355 Diamonds', 'sku' => 'FF-355', 'cost' => 45000, 'price' => 48000],
            ['name' => 'Member Mingguan', 'sku' => 'FF-WM', 'cost' => 28000, 'price' => 30000],
        ]);

        // --- Genshin Impact ---
        $genshin = Product::updateOrCreate(
            ['slug' => 'genshin-impact'],
            [
                'category_id' => $catGames->id,
                'name'        => 'Genshin Impact (Genesis)',
                'thumbnail'   => 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'id_and_zone',
                'is_active'   => true,
            ]
        );
        $this->createItems($genshin->id, [
            ['name' => 'Blessing of the Welkin Moon', 'sku' => 'GI-WELKIN', 'cost' => 65000, 'price' => 71000],
            ['name' => '300+30 Genesis Crystals', 'sku' => 'GI-330', 'cost' => 65000, 'price' => 71000],
            ['name' => '980+110 Genesis Crystals', 'sku' => 'GI-1090', 'cost' => 195000, 'price' => 215000],
        ]);

        // --- Telkomsel Reguler & Data ---
        $tsel = Product::updateOrCreate(
            ['slug' => 'telkomsel-reguler'],
            [
                'category_id' => $catPulsa->id,
                'name'        => 'Telkomsel Reguler & Data',
                'thumbnail'   => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'phone_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($tsel->id, [
            ['name' => 'Pulsa Telkomsel 10.000', 'sku' => 'TSEL-10K', 'cost' => 10200, 'price' => 11500],
            ['name' => 'Pulsa Telkomsel 25.000', 'sku' => 'TSEL-25K', 'cost' => 25000, 'price' => 26000],
            ['name' => 'Pulsa Telkomsel 50.000', 'sku' => 'TSEL-50K', 'cost' => 49500, 'price' => 51000],
            ['name' => 'Data MAXstream 10GB 30Hr', 'sku' => 'TSEL-MAX10', 'cost' => 42000, 'price' => 45000],
        ]);

        // --- Indosat IM3 ---
        $isat = Product::updateOrCreate(
            ['slug' => 'indosat-im3'],
            [
                'category_id' => $catPulsa->id,
                'name'        => 'Indosat IM3 Ooredoo',
                'thumbnail'   => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'phone_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($isat->id, [
            ['name' => 'Pulsa IM3 25.000', 'sku' => 'ISAT-25K', 'cost' => 25000, 'price' => 26000],
            ['name' => 'Pulsa IM3 50.000', 'sku' => 'ISAT-50K', 'cost' => 49600, 'price' => 51000],
            ['name' => 'Freedom Internet 15GB 30Hr', 'sku' => 'ISAT-F15', 'cost' => 48000, 'price' => 52000],
        ]);

        // --- Token Listrik PLN ---
        $pln = Product::updateOrCreate(
            ['slug' => 'pln-prepaid'],
            [
                'category_id' => $catPln->id,
                'name'        => 'Token Listrik PLN Prabayar',
                'thumbnail'   => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'meter_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($pln->id, [
            ['name' => 'Token PLN 20.000', 'sku' => 'PLN-20K', 'cost' => 20000, 'price' => 21500],
            ['name' => 'Token PLN 50.000', 'sku' => 'PLN-50K', 'cost' => 50000, 'price' => 51500],
            ['name' => 'Token PLN 100.000', 'sku' => 'PLN-100K', 'cost' => 100000, 'price' => 102000],
            ['name' => 'Token PLN 200.000', 'sku' => 'PLN-200K', 'cost' => 200000, 'price' => 202500],
        ]);

        // --- DANA ---
        $dana = Product::updateOrCreate(
            ['slug' => 'topup-dana'],
            [
                'category_id' => $catEwallet->id,
                'name'        => 'Saldo DANA Dompet Digital',
                'thumbnail'   => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'phone_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($dana->id, [
            ['name' => 'DANA Rp 25.000', 'sku' => 'DANA-25', 'cost' => 25200, 'price' => 26500],
            ['name' => 'DANA Rp 50.000', 'sku' => 'DANA-50', 'cost' => 50200, 'price' => 51500],
            ['name' => 'DANA Rp 100.000', 'sku' => 'DANA-100', 'cost' => 100200, 'price' => 101800],
        ]);

        // --- GoPay ---
        $gopay = Product::updateOrCreate(
            ['slug' => 'topup-gopay'],
            [
                'category_id' => $catEwallet->id,
                'name'        => 'Saldo GoPay Driver & Customer',
                'thumbnail'   => 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'phone_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($gopay->id, [
            ['name' => 'GoPay Rp 25.000', 'sku' => 'GOPAY-25', 'cost' => 25300, 'price' => 26500],
            ['name' => 'GoPay Rp 50.000', 'sku' => 'GOPAY-50', 'cost' => 50300, 'price' => 51500],
        ]);

        // --- Spotify ---
        $spotify = Product::updateOrCreate(
            ['slug' => 'spotify-premium'],
            [
                'category_id' => $catStreaming->id,
                'name'        => 'Spotify Premium Individual',
                'thumbnail'   => 'https://images.unsplash.com/photo-1614680376593-902f749f7ffc?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'phone_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($spotify->id, [
            ['name' => 'Spotify 1 Bulan Individual', 'sku' => 'SPOT-1M', 'cost' => 52000, 'price' => 55000],
            ['name' => 'Spotify 3 Bulan Individual', 'sku' => 'SPOT-3M', 'cost' => 150000, 'price' => 162000],
        ]);

        // --- Netflix ---
        $netflix = Product::updateOrCreate(
            ['slug' => 'netflix-voucher'],
            [
                'category_id' => $catStreaming->id,
                'name'        => 'Netflix Gift Card IDR',
                'thumbnail'   => 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'phone_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($netflix->id, [
            ['name' => 'Voucher Netflix Rp 65.000', 'sku' => 'NFLX-65', 'cost' => 65000, 'price' => 68000],
            ['name' => 'Voucher Netflix Rp 186.000', 'sku' => 'NFLX-186', 'cost' => 186000, 'price' => 193000],
        ]);

        // --- Steam Wallet ---
        $steam = Product::updateOrCreate(
            ['slug' => 'steam-wallet-code'],
            [
                'category_id' => $catVoucher->id,
                'name'        => 'Steam Wallet Code (IDR)',
                'thumbnail'   => 'https://images.unsplash.com/photo-1612287233261-26c7104f32c3?auto=format&fit=crop&w=800&q=80',
                'input_type'  => 'phone_number',
                'is_active'   => true,
            ]
        );
        $this->createItems($steam->id, [
            ['name' => 'Steam Wallet IDR 45.000', 'sku' => 'STEAM-45K', 'cost' => 46000, 'price' => 49500],
            ['name' => 'Steam Wallet IDR 90.000', 'sku' => 'STEAM-90K', 'cost' => 92000, 'price' => 98000],
            ['name' => 'Steam Wallet IDR 250.000', 'sku' => 'STEAM-250K', 'cost' => 253000, 'price' => 268000],
        ]);

        // 4. Payment Methods
        PaymentMethod::firstOrCreate(
            ['code' => 'QRIS'],
            [
                'name'             => 'QRIS (Semua E-Wallet / Mobile Banking)',
                'channel_category' => 'qris',
                'fee_flat'         => 750,
                'fee_percent'      => 0.7,
                'is_active'        => true,
            ]
        );

        PaymentMethod::firstOrCreate(
            ['code' => 'BCAVA'],
            [
                'name'             => 'BCA Virtual Account',
                'channel_category' => 'va',
                'fee_flat'         => 2500,
                'fee_percent'      => 0,
                'is_active'        => true,
            ]
        );

        PaymentMethod::firstOrCreate(
            ['code' => 'BRIVA'],
            [
                'name'             => 'BRI Virtual Account',
                'channel_category' => 'va',
                'fee_flat'         => 2500,
                'fee_percent'      => 0,
                'is_active'        => true,
            ]
        );

        PaymentMethod::firstOrCreate(
            ['code' => 'MANDIRIVA'],
            [
                'name'             => 'Mandiri Livin VA',
                'channel_category' => 'va',
                'fee_flat'         => 2500,
                'fee_percent'      => 0,
                'is_active'        => true,
            ]
        );

        // 5. Banner Promo & Announcement
        Banner::firstOrCreate(
            ['title' => 'Promo Spesial Weekly Diamond Pass MLBB'],
            [
                'image_url'  => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1600&q=80',
                'target_url' => url('/order/mobile-legends'),
                'is_active'  => true,
            ]
        );

        Banner::firstOrCreate(
            ['title' => 'Flash Sale Token PLN & Saldo E-Wallet'],
            [
                'image_url'  => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1600&q=80',
                'target_url' => url('/order/pln-prepaid'),
                'is_active'  => true,
            ]
        );

        Announcement::firstOrCreate(
            ['content' => '🔥 Layanan Top Up Diamond MLBB, Free Fire, Token PLN & Pulsa 24 Jam Otomatis Instan via QRIS & Virtual Account!'],
            [
                'is_active' => true,
            ]
        );
    }

    private function createItems(int $productId, array $items): void
    {
        foreach ($items as $item) {
            ProductItem::firstOrCreate(
                ['sku_code' => $item['sku']],
                [
                    'product_id'     => $productId,
                    'name'           => $item['name'],
                    'original_price' => $item['cost'],
                    'selling_price'  => $item['price'],
                    'is_available'   => true,
                ]
            );
        }
    }
}
