<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function administrators()
    {
        return $this->hasMany(Administrator::class, 'role_id');
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }
}