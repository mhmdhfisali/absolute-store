<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Banner;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seeder Banner Promo Gaming (Rasio 21:9 / Landscape HD)
        Banner::firstOrCreate(
            ['title' => 'Promo Spesial Weekly Diamond Pass MLBB'],
            [
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1600&q=80',
                'target_url' => url('/order/mobile-legends'),
                'is_active' => true,
            ]
        );

        Banner::firstOrCreate(
            ['title' => 'Flash Sale Diskon Token PLN & Saldo E-Wallet'],
            [
                'image_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1600&q=80',
                'target_url' => url('/order/pln-prepaid'),
                'is_active' => true,
            ]
        );

        // 2. Seeder Running Announcement
        Announcement::firstOrCreate(
            ['content' => '🔥 Layanan Top Up Diamond MLBB, Free Fire, Token PLN & Pulsa 24 Jam Otomatis Instan via QRIS & Virtual Account!'],
            [
                'is_active' => true,
            ]
        );
    }
}
