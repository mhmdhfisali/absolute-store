<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku_code',
        'original_price',
        'selling_price',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function getPriceAttribute(): float
    {
        return (float) ($this->attributes['selling_price'] ?? 0);
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['selling_price'] = $value;
    }

    public function getResellerPriceAttribute(): float
    {
        return (float) ($this->attributes['reseller_price'] ?? ($this->attributes['selling_price'] ?? 0));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
