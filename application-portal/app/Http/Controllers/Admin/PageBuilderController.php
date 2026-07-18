<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageBuilderController extends Controller
{
    public function index()
    {
        $pages = [
            'home' => 'Home Page',
            'about' => 'About Page',
            'requirements' => 'Requirements Page',
            'how-to-apply' => 'How to Apply Page',
            'faq' => 'FAQ Page',
            'contact' => 'Contact Page',
        ];

        return view('admin.page-builder.index', compact('pages'));
    }

    public function edit($page)
    {
        $pageName = [
            'home' => 'Home Page',
            'about' => 'About Page',
            'requirements' => 'Requirements Page',
            'how-to-apply' => 'How to Apply Page',
            'faq' => 'FAQ Page',
            'contact' => 'Contact Page',
        ];

        // Get existing content or defaults
        $content = [];
        $savedContent = Setting::get('page_content_' . $page);
        if ($savedContent) {
            $content = is_array($savedContent) ? $savedContent : json_decode($savedContent, true);
        }

        // Default content for home page
        if ($page === 'home' && empty($content)) {
            $content = [
                'hero_title' => 'Welcome to EKSCOTECH Online Portal',
                'hero_subtitle' => 'Ekiti State College of Technology',
                'hero_button_text' => 'Apply Now',
                'hero_button_link' => '/apply',
                'stats_applications' => '1+',
                'stats_successful' => '0+',
                'stats_partners' => '50+',
                'stats_quality' => '100%',
                'feature_title' => 'Why Apply Through Our Portal?',
                'feature_subtitle' => 'Experience a seamless, secure, and fast application process',
                'feature_1_title' => 'Secure & Private',
                'feature_1_desc' => 'Your data is protected with industry-standard security measures and encryption.',
                'feature_2_title' => 'Fast Processing',
                'feature_2_desc' => 'Quick application submission with instant confirmation and tracking.',
                'feature_3_title' => 'Mobile Friendly',
                'feature_3_desc' => 'Apply from any device - desktop, tablet, or mobile phone.',
                'steps_title' => 'How to Apply',
                'steps_subtitle' => 'Simple steps to complete your application',
                'step_1_title' => 'Click Apply Now',
                'step_1_desc' => 'Visit our portal and click the Apply Now button to start your application.',
                'step_2_title' => 'Fill the Form',
                'step_2_desc' => 'Complete all required information and upload necessary documents.',
                'step_3_title' => 'Submit & Track',
                'step_3_desc' => 'Submit your application and receive instant confirmation with tracking details.',
                'cta_title' => 'Ready to Get Started?',
                'cta_subtitle' => 'Take the first step towards your future career today',
                'cta_button_text' => 'Apply Now',
                'footer_text' => '© 2026 EKSCOTECH Online Portal. All rights reserved.',
                'contact_email' => 'admin@ekscotech.edu.ng',
                'contact_phone' => '+2341234567890',
            ];
        }

        return view('admin.page-builder.edit', [
            'page' => $page,
            'pageName' => $pageName[$page] ?? $page,
            'content' => $content
        ]);
    }

    public function update(Request $request, $page)
    {
        $validated = $request->except(['_token', '_method']);

        // Save each field
        foreach ($validated as $key => $value) {
            Setting::set('page_' . $page . '_' . $key, $value);
        }

        // Save as JSON as well
        Setting::set('page_content_' . $page, json_encode($validated));

        ActivityLog::log('page_update', "Updated {$page} page content");

        return back()->with('success', 'Page content updated successfully!');
    }

    public function preview($page)
    {
        // Get saved content
        $savedContent = Setting::get('page_content_' . $page);
        $content = $savedContent ? json_decode($savedContent, true) : [];

        return view('frontend.custom-page', compact('page', 'content'));
    }
}