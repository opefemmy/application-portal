<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_applications' => Application::count(),
            'today_applications' => Application::whereDate('created_at', $today)->count(),
            'week_applications' => Application::whereDate('created_at', '>=', $startOfWeek)->count(),
            'month_applications' => Application::whereDate('created_at', '>=', $startOfMonth)->count(),
            'shortlisted' => Application::where('status', 'shortlisted')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
            'pending' => Application::where('status', 'pending')->count(),
            'male_applicants' => Application::where('personal_info->gender', 'male')->count(),
            'female_applicants' => Application::where('personal_info->gender', 'female')->count(),
        ];

        // Monthly applications for the last 12 months
        $monthlyApplications = Application::selectRaw('COUNT(*) as count, MONTH(created_at) as month, YEAR(created_at) as year')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Applications by state
        $applicationsByState = Application::selectRaw('personal_info->>"$.state_of_origin" as state, COUNT(*) as count')
            ->groupBy('state')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Applications by qualification
        $applicationsByQualification = Application::selectRaw('academic_info->>"$.highest_qualification" as qualification, COUNT(*) as count')
            ->groupBy('qualification')
            ->orderByDesc('count')
            ->get();

        // Recent applications
        $recentApplications = Application::with('documents')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Unread notifications
        $unreadNotifications = Notification::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyApplications',
            'applicationsByState',
            'applicationsByQualification',
            'recentApplications',
            'unreadNotifications'
        ));
    }
}