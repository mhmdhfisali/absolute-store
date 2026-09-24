<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'buyer_id',
        'transaction_id',
        'commission_amount',
        'commission_percent',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'commission_amount' => 'decimal:2',
            'commission_percent' => 'decimal:2',
        ];
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
