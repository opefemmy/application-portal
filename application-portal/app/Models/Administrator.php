<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrator extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'avatar',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isSuperAdmin()
    {
        return $this->role && $this->role->slug === 'super-admin';
    }

    public function hasPermission($permission)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $rolePermissions = $this->role ? $this->role->permissions : [];

        return in_array($permission, $rolePermissions ?? []);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'user');
    }
}