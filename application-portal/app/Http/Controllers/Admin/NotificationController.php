<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => Notification::where('is_read', false)->count()
        ]);
    }
}