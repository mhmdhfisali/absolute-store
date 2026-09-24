<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_amount',
        'max_discount',
        'min_transaction',
        'usage_limit',
        'used_count',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_until' => 'datetime',
        'discount_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_transaction' => 'decimal:2',
    ];

    /**
     * Hitung nilai potongan berdasarkan subtotal pesanan
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->discount_amount) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                return (float) $this->max_discount;
            }

            return (float) $discount;
        }

        // Tipe 'flat' (potongan rupiah pasti)
        return min((float) $this->discount_amount, $subtotal);
    }
}
