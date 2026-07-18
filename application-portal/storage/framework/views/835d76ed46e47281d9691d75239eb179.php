<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item active">Settings</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general">General</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#portal">Portal</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#upload">Uploads</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenance">Maintenance</button>
    </li>
</ul>

<div class="tab-content">
    <!-- General Settings -->
    <div class="tab-pane fade show active" id="general">
        <div class="stat-card">
            <h5 class="mb-4">General Settings</h5>
            <form method="POST" action="<?php echo e(route('admin.settings.general')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Portal Name</label>
                        <input type="text" name="portal_name" class="form-control" value="<?php echo e($settings['portal_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Institution Name</label>
                        <input type="text" name="institution_name" class="form-control" value="<?php echo e($settings['institution_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo e($settings['contact_email'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" value="<?php echo e($settings['phone_number'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Application Prefix</label>
                        <input type="text" name="application_prefix" class="form-control" value="<?php echo e($settings['application_prefix'] ?? 'APP'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Portal Settings -->
    <div class="tab-pane fade" id="portal">
        <div class="stat-card">
            <h5 class="mb-4">Portal Settings</h5>
            <form method="POST" action="<?php echo e(route('admin.settings.portal')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Portal Open Date</label>
                        <input type="date" name="portal_open_date" class="form-control" value="<?php echo e($settings['portal_open_date'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Portal Close Date</label>
                        <input type="date" name="portal_close_date" class="form-control" value="<?php echo e($settings['portal_close_date'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Maximum Applications</label>
                        <input type="number" name="max_applications" class="form-control" value="<?php echo e($settings['max_applications'] ?? ''); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Upload Settings -->
    <div class="tab-pane fade" id="upload">
        <div class="stat-card">
            <h5 class="mb-4">Upload Settings</h5>
            <form method="POST" action="<?php echo e(route('admin.settings.upload')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Maximum Upload Size (KB)</label>
                        <input type="number" name="max_upload_size" class="form-control" value="<?php echo e($settings['max_upload_size'] ?? 10240); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Allowed File Types</label>
                        <input type="text" name="allowed_file_types" class="form-control" value="<?php echo e($settings['allowed_file_types'] ?? 'pdf,jpg,jpeg,png'); ?>">
                        <small class="text-muted">Comma-separated list</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Maintenance -->
    <div class="tab-pane fade" id="maintenance">
        <div class="stat-card">
            <h5 class="mb-4">Maintenance Mode</h5>
            <form method="POST" action="<?php echo e(route('admin.settings.maintenance')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="maintenance_mode" class="form-check-input" id="maintenanceMode" value="1" <?php echo e(($settings['maintenance_mode'] ?? 0) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="maintenanceMode">Enable Maintenance Mode</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Maintenance Message</label>
                        <textarea name="maintenance_message" class="form-control" rows="3"><?php echo e($settings['maintenance_message'] ?? ''); ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom">Save Changes</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>