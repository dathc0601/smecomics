<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Cache key for all settings
     */
    const CACHE_KEY = 'app_settings';

    /**
     * Cache duration in seconds (1 hour)
     */
    const CACHE_DURATION = 3600;

    /**
     * Boot the model and clear cache on save/delete
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * Get all settings as key-value array with caching
     */
    public static function getAllCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::getAllCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, string $type = 'text'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    /**
     * Check if a setting exists
     */
    public static function has(string $key): bool
    {
        $settings = self::getAllCached();
        return array_key_exists($key, $settings);
    }

    /**
     * Delete a setting image file
     */
    public static function deleteImage(string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get boolean value (convert string to bool)
     */
    public static function getBool(string $key, bool $default = false): bool
    {
        $value = self::get($key);
        if ($value === null) {
            return $default;
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
