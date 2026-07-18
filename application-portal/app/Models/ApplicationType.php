<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicationType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the form fields associated with this application type.
     */
    public function formFields(): BelongsToMany
    {
        return $this->belongsToMany(FormField::class, 'application_type_fields')
            ->withPivot('is_enabled', 'is_required', 'sort_order')
            ->orderBy('pivot_sort_order');
    }

    /**
     * Get enabled form fields for this application type.
     */
    public function getEnabledFields()
    {
        return $this->formFields()->wherePivot('is_enabled', true)->get();
    }

    /**
     * Get fields grouped by section.
     */
    public function getFieldsBySection()
    {
        $fields = $this->formFields()->wherePivot('is_enabled', true)->get();
        return $fields->groupBy('section');
    }

    /**
     * Scope to get only active application types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Check if this application type is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}