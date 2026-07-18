<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $settings = Setting::getSettings();
            $view->with('settings', $settings);
        });
    }
}