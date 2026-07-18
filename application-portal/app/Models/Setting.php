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
        $now = now();
        $openDate = self::get('portal_open_date');
        $closeDate = self::get('portal_close_date');

        if (!$openDate && !$closeDate) {
            return true;
        }

        if ($openDate && $now->lt($openDate)) {
            return false;
        }

        if ($closeDate && $now->gt($closeDate)) {
            return false;
        }

        return true;
    }
}