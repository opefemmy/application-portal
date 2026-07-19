<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getSettings()
    {
        $settings = self::all();
        return $settings->pluck('value', 'key')->toArray();
    }

    public static function isPortalOpen()
    {
        // Always return true to keep portal open for applications
        // Remove or comment the date-based logic if you want to control via dates
        return true;
    }
}