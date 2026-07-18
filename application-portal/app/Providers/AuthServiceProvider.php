<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for permissions
        Gate::define('manage-applications', function ($user) {
            return $user->hasPermission('manage-applications');
        });

        Gate::define('manage-settings', function ($user) {
            return $user->hasPermission('manage-settings');
        });

        Gate::define('view-reports', function ($user) {
            return $user->hasPermission('view-reports');
        });
    }
}