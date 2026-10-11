<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
        'type',
    ];

    /**
     * Get setting value by key with caching.
     */
    public static function get(string $key, $default = null)
    {
        $all = Cache::rememberForever('aczet_site_settings_map', function () {
            return static::pluck('value', 'key')->toArray();
        });

        if (array_key_exists($key, $all) && !is_null($all[$key]) && $all[$key] !== '') {
            return $all[$key];
        }

        return $default;
    }

    /**
     * Set or update a setting and invalidate cache.
     */
    public static function set(string $key, $value, string $group = 'general', ?string $label = null, string $type = 'text'): self
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->group = $group;
        if ($label) {
            $setting->label = $label;
        }
        if ($type) {
            $setting->type = $type;
        }
        $setting->save();

        static::clearCache();

        return $setting;
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('aczet_site_settings_map');
    }

    /**
     * Get all settings grouped.
     */
    public static function getGrouped()
    {
        return static::all()->groupBy('group');
    }
}

