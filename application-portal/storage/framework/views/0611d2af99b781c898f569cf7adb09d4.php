<?php $__env->startSection('title', 'Edit ' . $pageName); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.settings.index')); ?>">Settings</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.settings.pages')); ?>">Page Builder</a></li>
<li class="breadcrumb-item active">Edit <?php echo e($pageName); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('admin.settings.pages.update', $page)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <!-- Hero Section -->
            <?php if(in_array($page, ['home', 'about'])): ?>
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-card-heading me-2"></i>Hero Section</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hero Title</label>
                        <input type="text" name="hero_title" class="form-control" value="<?php echo e($content['hero_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" class="form-control" value="<?php echo e($content['hero_subtitle'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="hero_button_text" class="form-control" value="<?php echo e($content['hero_button_text'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Button Link</label>
                        <input type="text" name="hero_button_link" class="form-control" value="<?php echo e($content['hero_button_link'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Stats Section (Home) -->
            <?php if($page === 'home'): ?>
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Statistics Section</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Applications Received</label>
                        <input type="text" name="stats_applications" class="form-control" value="<?php echo e($content['stats_applications'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Successful Applicants</label>
                        <input type="text" name="stats_successful" class="form-control" value="<?php echo e($content['stats_successful'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Partner Institutions</label>
                        <input type="text" name="stats_partners" class="form-control" value="<?php echo e($content['stats_partners'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Support Quality</label>
                        <input type="text" name="stats_quality" class="form-control" value="<?php echo e($content['stats_quality'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-stars me-2"></i>Why Apply Section</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Section Title</label>
                        <input type="text" name="feature_title" class="form-control" value="<?php echo e($content['feature_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Section Subtitle</label>
                        <input type="text" name="feature_subtitle" class="form-control" value="<?php echo e($content['feature_subtitle'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Feature 1 Title</label>
                        <input type="text" name="feature_1_title" class="form-control" value="<?php echo e($content['feature_1_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Feature 1 Description</label>
                        <textarea name="feature_1_desc" class="form-control" rows="2"><?php echo e($content['feature_1_desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Feature 2 Title</label>
                        <input type="text" name="feature_2_title" class="form-control" value="<?php echo e($content['feature_2_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Feature 2 Description</label>
                        <textarea name="feature_2_desc" class="form-control" rows="2"><?php echo e($content['feature_2_desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Feature 3 Title</label>
                        <input type="text" name="feature_3_title" class="form-control" value="<?php echo e($content['feature_3_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Feature 3 Description</label>
                        <textarea name="feature_3_desc" class="form-control" rows="2"><?php echo e($content['feature_3_desc'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- How to Apply Steps -->
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-list-ol me-2"></i>How to Apply Steps</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Step 1 Title</label>
                        <input type="text" name="step_1_title" class="form-control" value="<?php echo e($content['step_1_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Step 1 Description</label>
                        <input type="text" name="step_1_desc" class="form-control" value="<?php echo e($content['step_1_desc'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Step 2 Title</label>
                        <input type="text" name="step_2_title" class="form-control" value="<?php echo e($content['step_2_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Step 2 Description</label>
                        <input type="text" name="step_2_desc" class="form-control" value="<?php echo e($content['step_2_desc'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Step 3 Title</label>
                        <input type="text" name="step_3_title" class="form-control" value="<?php echo e($content['step_3_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Step 3 Description</label>
                        <input type="text" name="step_3_desc" class="form-control" value="<?php echo e($content['step_3_desc'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-megaphone me-2"></i>Call to Action Section</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">CTA Title</label>
                        <input type="text" name="cta_title" class="form-control" value="<?php echo e($content['cta_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">CTA Subtitle</label>
                        <input type="text" name="cta_subtitle" class="form-control" value="<?php echo e($content['cta_subtitle'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CTA Button Text</label>
                        <input type="text" name="cta_button_text" class="form-control" value="<?php echo e($content['cta_button_text'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Contact Page -->
            <?php if($page === 'contact'): ?>
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-envelope me-2"></i>Contact Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo e($content['contact_email'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?php echo e($content['contact_phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="contact_address" class="form-control" rows="3"><?php echo e($content['contact_address'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Footer Section -->
            <div class="stat-card mb-4">
                <h5 class="mb-4"><i class="bi bi-layout-footer me-2"></i>Footer Section</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Footer Copyright Text</label>
                        <input type="text" name="footer_text" class="form-control" value="<?php echo e($content['footer_text'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo e($content['contact_email'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?php echo e($content['contact_phone'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom mb-4">
                <i class="bi bi-save me-2"></i>Save Changes
            </button>
        </div>

        <div class="col-md-4">
            <!-- Preview -->
            <div class="stat-card mb-4 sticky-top" style="top: 20px;">
                <h5 class="mb-3">Preview</h5>
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('home')); ?>" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Live Page
                    </a>
                </div>
                <hr>
                <h6 class="mb-3">Quick Tips</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><i class="bi bi-check text-success me-1"></i>Changes save automatically</li>
                    <li class="mb-2"><i class="bi bi-check text-success me-1"></i>Use clear, short text</li>
                    <li class="mb-2"><i class="bi bi-info text-info me-1"></i>Images can be changed in Branding</li>
                </ul>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/page-builder/edit.blade.php ENDPATH**/ ?>