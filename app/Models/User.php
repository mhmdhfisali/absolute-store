<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'discord_tag',
        'bio',
        'notification_preferences',
        'webhook_url',
        'role',
        'balance',
        'tier',
        'referral_code',
        'referred_by_id',
        'is_banned',
        'ban_reason',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'is_banned' => 'boolean',
            'notification_preferences' => 'array',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function savedAccounts(): HasMany
    {
        return $this->hasMany(SavedAccount::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function affiliateEarnings(): HasMany
    {
        return $this->hasMany(AffiliateEarning::class, 'referrer_id');
    }

    public function downlines(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->userNotifications()->where('is_read', false)->count();
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = 'AS'.strtoupper(Str::random(6));
            }
        });
    }

    public function getReferralCodeAttribute($value): string
    {
        if (empty($value)) {
            $value = 'AS'.strtoupper(Str::random(6));
            $this->attributes['referral_code'] = $value;
            if ($this->exists) {
                $this->saveQuietly();
            }
        }

        return $value;
    }

    public function isBanned(): bool
    {
        return (bool) $this->is_banned;
    }

    /**
     * Memeriksa apakah user memiliki hak akses Admin / Superadmin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Memeriksa apakah user adalah Superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Memeriksa apakah user merupakan Reseller atau VIP
     */
    public function isReseller(): bool
    {
        return in_array($this->tier, ['reseller', 'vip']);
    }
}
