<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'thumbnail',
        'input_type',
        'provider_code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) ($this->approvedReviews()->avg('rating') ?: 5.0), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return (int) $this->approvedReviews()->count();
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->thumbnail)) {
                    return null;
                }

                return filter_var($this->thumbnail, FILTER_VALIDATE_URL)
                    ? $this->thumbnail
                    : asset('storage/'.$this->thumbnail);
            }
        );
    }
}
