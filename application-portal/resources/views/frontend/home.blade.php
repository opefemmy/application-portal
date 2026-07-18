@extends('layouts.frontend')

@section('title', 'Home')
@section('content')
<?php
use App\Models\Setting;
use App\Models\Application;

// Get dynamic content
$heroTitle = Setting::get('page_home_hero_title', 'Welcome to ' . ($settings['portal_name'] ?? 'Online Application Portal'));
$heroSubtitle = Setting::get('page_home_hero_subtitle', $settings['institution_name'] ?? 'Begin your journey with us');
$heroButtonText = Setting::get('page_home_hero_button_text', 'Apply Now');
$heroButtonLink = Setting::get('page_home_hero_button_link', '/apply');

$statsApplications = Setting::get('page_home_stats_applications', Application::count() . '+');
$statsSuccessful = Setting::get('page_home_stats_successful', Application::where('status', 'accepted')->count() . '+');
$statsPartners = Setting::get('page_home_stats_partners', '50+');
$statsQuality = Setting::get('page_home_stats_quality', '100%');

$featureTitle = Setting::get('page_home_feature_title', 'Why Apply Through Our Portal?');
$featureSubtitle = Setting::get('page_home_feature_subtitle', 'Experience a seamless, secure, and fast application process');

$feature1Title = Setting::get('page_home_feature_1_title', 'Secure & Private');
$feature1Desc = Setting::get('page_home_feature_1_desc', 'Your data is protected with industry-standard security measures and encryption.');

$feature2Title = Setting::get('page_home_feature_2_title', 'Fast Processing');
$feature2Desc = Setting::get('page_home_feature_2_desc', 'Quick application submission with instant confirmation and tracking.');

$feature3Title = Setting::get('page_home_feature_3_title', 'Mobile Friendly');
$feature3Desc = Setting::get('page_home_feature_3_desc', 'Apply from any device - desktop, tablet, or mobile phone.');

$stepsTitle = Setting::get('page_home_steps_title', 'How to Apply');
$stepsSubtitle = Setting::get('page_home_steps_subtitle', 'Simple steps to complete your application');

$step1Title = Setting::get('page_home_step_1_title', 'Click Apply Now');
$step1Desc = Setting::get('page_home_step_1_desc', 'Visit our portal and click the Apply Now button to start your application.');

$step2Title = Setting::get('page_home_step_2_title', 'Fill the Form');
$step2Desc = Setting::get('page_home_step_2_desc', 'Complete all required information and upload necessary documents.');

$step3Title = Setting::get('page_home_step_3_title', 'Submit & Track');
$step3Desc = Setting::get('page_home_step_3_desc', 'Submit your application and receive instant confirmation with tracking details.');

$ctaTitle = Setting::get('page_home_cta_title', 'Ready to Get Started?');
$ctaSubtitle = Setting::get('page_home_cta_subtitle', 'Take the first step towards your future career today');
$ctaButtonText = Setting::get('page_home_cta_button_text', 'Apply Now');

$footerText = Setting::get('page_home_footer_text', '© ' . date('Y') . ' ' . ($settings['portal_name'] ?? 'Online Application Portal') . '. All rights reserved.');
$contactEmail = Setting::get('page_home_contact_email', $settings['contact_email'] ?? 'admin@ekscotech.edu.ng');
$contactPhone = Setting::get('page_home_contact_phone', $settings['phone_number'] ?? '+2341234567890');
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">{{ $heroTitle }}</h1>
                <p class="lead mb-4">{{ $heroSubtitle }}</p>
                @if(Setting::isPortalOpen())
                <a href="{{ route('apply') }}" class="btn btn-primary-custom btn-lg">
                    <i class="bi bi-send me-2"></i>{{ $heroButtonText }}
                </a>
                @else
                <button class="btn btn-secondary btn-lg" disabled>
                    <i class="bi bi-clock me-2"></i>Portal Closed
                </button>
                @endif
                <a href="{{ route('track') }}" class="btn btn-outline-light btn-lg ms-2">
                    <i class="bi bi-search me-2"></i>Track Application
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="section bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="icon bg-primary text-white mx-auto mb-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="mb-1">{{ $statsApplications }}</h3>
                    <p class="text-muted mb-0">Applications Received</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="icon bg-success text-white mx-auto mb-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h3 class="mb-1">{{ $statsSuccessful }}</h3>
                    <p class="text-muted mb-0">Successful Applicants</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="icon bg-info text-white mx-auto mb-3">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="mb-1">{{ $statsPartners }}</h3>
                    <p class="text-muted mb-0">Partner Institutions</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="icon bg-warning text-white mx-auto mb-3">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3 class="mb-1">{{ $statsQuality }}</h3>
                    <p class="text-muted mb-0">Support Quality</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Apply Section -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">{{ $featureTitle }}</h2>
            <p class="text-muted">{{ $featureSubtitle }}</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon bg-primary text-white mx-auto mb-3">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h5 class="mb-3">{{ $feature1Title }}</h5>
                    <p class="text-muted mb-0">{{ $feature1Desc }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon bg-success text-white mx-auto mb-3">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h5 class="mb-3">{{ $feature2Title }}</h5>
                    <p class="text-muted mb-0">{{ $feature2Desc }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon bg-info text-white mx-auto mb-3">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h5 class="mb-3">{{ $feature3Title }}</h5>
                    <p class="text-muted mb-0">{{ $feature3Desc }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How to Apply -->
<section class="section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">{{ $stepsTitle }}</h2>
            <p class="text-muted">{{ $stepsSubtitle }}</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="step-number mx-auto mb-3">1</div>
                    <h5 class="mb-3">{{ $step1Title }}</h5>
                    <p class="text-muted mb-0">{{ $step1Desc }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="step-number mx-auto mb-3">2</div>
                    <h5 class="mb-3">{{ $step2Title }}</h5>
                    <p class="text-muted mb-0">{{ $step2Desc }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="step-number mx-auto mb-3">3</div>
                    <h5 class="mb-3">{{ $step3Title }}</h5>
                    <p class="text-muted mb-0">{{ $step3Desc }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2 class="mb-3">{{ $ctaTitle }}</h2>
        <p class="mb-4">{{ $ctaSubtitle }}</p>
        @if(Setting::isPortalOpen())
        <a href="{{ route('apply') }}" class="btn btn-primary-custom btn-lg">
            <i class="bi bi-send me-2"></i>{{ $ctaButtonText }}
        </a>
        @else
        <button class="btn btn-secondary btn-lg" disabled>Portal Closed</button>
        @endif
    </div>
</section>
@endsection