<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = [
        'section',
        'field_name',
        'field_label',
        'field_type',
        'options',
        'is_visible',
        'is_required',
        'sort_order',
        'validation_rules',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'is_visible' => 'boolean',
        'is_required' => 'boolean',
    ];

    public static function getBySection($section)
    {
        return self::where('section', $section)
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();
    }

    public static function getAllSections()
    {
        return self::distinct()
            ->pluck('section')
            ->toArray();
    }
}