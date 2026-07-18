<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::getSettings();
        $portalOpen = Setting::isPortalOpen();

        return view('frontend.home', compact('settings', 'portalOpen'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function requirements()
    {
        return view('frontend.requirements');
    }

    public function howToApply()
    {
        return view('frontend.how-to-apply');
    }

    public function faq()
    {
        return view('frontend.faq');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function track(Request $request)
    {
        $application = null;

        if ($request->has('application_number')) {
            $application = Application::where('application_number', $request->application_number)
                ->first();
        }

        return view('frontend.track', compact('application'));
    }
}