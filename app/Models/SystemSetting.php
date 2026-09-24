<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'is_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        if ($setting->is_encrypted && ! empty($setting->value)) {
            try {
                return Crypt::decryptString($setting->value);
            } catch (\Throwable) {
                return $setting->value;
            }
        }

        return $setting->value ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general', bool $encrypt = false): static
    {
        $storeValue = $value;
        if ($encrypt && ! empty($value)) {
            $storeValue = Crypt::encryptString($value);
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storeValue,
                'group' => $group,
                'is_encrypted' => $encrypt,
            ]
        );
    }
}
