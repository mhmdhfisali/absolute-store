<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'product_item_id',
        'payment_method_id',
        'target_account',
        'target_zone',
        'contact_email_or_phone',
        'amount',
        'fee_amount',
        'total_amount',
        'payment_status',
        'delivery_status',
        'serial_number',
        'provider_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'provider_response' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
}
