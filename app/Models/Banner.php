<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'image_url', 'target_url', 'is_active'];

    /**
     * Resolusi URL gambar: file storage lokal atau URL eksternal
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => filter_var($value, FILTER_VALIDATE_URL)
                ? $value
                : asset('storage/' . $value),
        );
    }
}
