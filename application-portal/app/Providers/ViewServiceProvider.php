<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share unread notifications count with admin layouts
        View::composer(['layouts.admin', 'admin.*'], function ($view) {
            $unreadNotifications = [];
            if (auth()->check()) {
                $unreadNotifications = Notification::where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            }
            $view->with('unreadNotifications', $unreadNotifications);
        });
    }
}