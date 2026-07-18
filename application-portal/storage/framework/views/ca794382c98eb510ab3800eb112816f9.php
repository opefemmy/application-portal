<?php $__env->startSection('title', 'Home'); ?>
<?php $__env->startSection('content'); ?>
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
                <h1 class="display-4 fw-bold mb-4"><?php echo e($heroTitle); ?></h1>
                <p class="lead mb-4"><?php echo e($heroSubtitle); ?></p>
                <?php if(Setting::isPortalOpen()): ?>
                <a href="<?php echo e(route('apply')); ?>" class="btn btn-primary-custom btn-lg">
                    <i class="bi bi-send me-2"></i><?php echo e($heroButtonText); ?>

                </a>
                <?php else: ?>
                <button class="btn btn-secondary btn-lg" disabled>
                    <i class="bi bi-clock me-2"></i>Portal Closed
                </button>
                <?php endif; ?>
                <a href="<?php echo e(route('track')); ?>" class="btn btn-outline-light btn-lg ms-2">
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
                    <h3 class="mb-1"><?php echo e($statsApplications); ?></h3>
                    <p class="text-muted mb-0">Applications Received</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="icon bg-success text-white mx-auto mb-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h3 class="mb-1"><?php echo e($statsSuccessful); ?></h3>
                    <p class="text-muted mb-0">Successful Applicants</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="icon bg-info text-white mx-auto mb-3">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="mb-1"><?php echo e($statsPartners); ?></h3>
                    <p class="text-muted mb-0">Partner Institutions</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="icon bg-warning text-white mx-auto mb-3">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3 class="mb-1"><?php echo e($statsQuality); ?></h3>
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
            <h2 class="mb-3"><?php echo e($featureTitle); ?></h2>
            <p class="text-muted"><?php echo e($featureSubtitle); ?></p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon bg-primary text-white mx-auto mb-3">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h5 class="mb-3"><?php echo e($feature1Title); ?></h5>
                    <p class="text-muted mb-0"><?php echo e($feature1Desc); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon bg-success text-white mx-auto mb-3">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h5 class="mb-3"><?php echo e($feature2Title); ?></h5>
                    <p class="text-muted mb-0"><?php echo e($feature2Desc); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon bg-info text-white mx-auto mb-3">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h5 class="mb-3"><?php echo e($feature3Title); ?></h5>
                    <p class="text-muted mb-0"><?php echo e($feature3Desc); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How to Apply -->
<section class="section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3"><?php echo e($stepsTitle); ?></h2>
            <p class="text-muted"><?php echo e($stepsSubtitle); ?></p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="step-number mx-auto mb-3">1</div>
                    <h5 class="mb-3"><?php echo e($step1Title); ?></h5>
                    <p class="text-muted mb-0"><?php echo e($step1Desc); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="step-number mx-auto mb-3">2</div>
                    <h5 class="mb-3"><?php echo e($step2Title); ?></h5>
                    <p class="text-muted mb-0"><?php echo e($step2Desc); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="step-number mx-auto mb-3">3</div>
                    <h5 class="mb-3"><?php echo e($step3Title); ?></h5>
                    <p class="text-muted mb-0"><?php echo e($step3Desc); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2 class="mb-3"><?php echo e($ctaTitle); ?></h2>
        <p class="mb-4"><?php echo e($ctaSubtitle); ?></p>
        <?php if(Setting::isPortalOpen()): ?>
        <a href="<?php echo e(route('apply')); ?>" class="btn btn-primary-custom btn-lg">
            <i class="bi bi-send me-2"></i><?php echo e($ctaButtonText); ?>

        </a>
        <?php else: ?>
        <button class="btn btn-secondary btn-lg" disabled>Portal Closed</button>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/frontend/home.blade.php ENDPATH**/ ?>