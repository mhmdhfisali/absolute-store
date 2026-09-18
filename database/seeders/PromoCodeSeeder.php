<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::create([
            'code'            => 'ABSOLUTEHEMAT',
            'type'            => 'flat',
            'discount_amount' => 3000,
            'min_transaction' => 10000,
            'usage_limit'     => 100,
            'valid_until'     => now()->addMonths(3),
            'is_active'       => true,
        ]);

        PromoCode::create([
            'code'            => 'PROMO10',
            'type'            => 'percentage',
            'discount_amount' => 10, // 10%
            'max_discount'    => 5000,
            'min_transaction' => 20000,
            'usage_limit'     => 50,
            'valid_until'     => now()->addMonths(1),
            'is_active'       => true,
        ]);
    }
}
