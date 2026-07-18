<?php $__env->startSection('title', 'Branding & Rebranding'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.settings.index')); ?>">Settings</a></li>
<li class="breadcrumb-item active">Branding</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="stat-card">
            <h5 class="mb-4"><i class="bi bi-palette me-2"></i>Branding & Rebranding Settings</h5>
            <p class="text-muted mb-4">Customize the look and feel of your application portal. Upload your institution's logo and choose colors.</p>

            <form method="POST" action="<?php echo e(route('admin.settings.branding.update')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <!-- Logo Upload -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Main Logo</label>
                        <div class="border rounded p-2 text-center" style="min-height: 100px;" id="logo-preview-container">
                            <?php if($settings['logo'] ?? false): ?>
                                <img src="<?php echo e(asset($settings['logo'])); ?>" alt="Logo" class="img-fluid" id="logo-preview" style="max-height: 80px;">
                            <?php else: ?>
                                <span class="text-muted" id="logo-placeholder">No logo uploaded</span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="logo" class="form-control mt-2" accept="image/*" onchange="previewFile('logo')">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Favicon</label>
                        <div class="border rounded p-2 text-center" style="min-height: 100px;" id="favicon-preview-container">
                            <?php if($settings['favicon'] ?? false): ?>
                                <img src="<?php echo e(asset($settings['favicon'])); ?>" alt="Favicon" class="img-fluid" id="favicon-preview" style="max-height: 80px;">
                            <?php else: ?>
                                <span class="text-muted" id="favicon-placeholder">No favicon uploaded</span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="favicon" class="form-control mt-2" accept="image/*" onchange="previewFile('favicon')">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Footer Logo</label>
                        <div class="border rounded p-2 text-center" style="min-height: 100px;" id="footer_logo-preview-container">
                            <?php if($settings['footer_logo'] ?? false): ?>
                                <img src="<?php echo e(asset($settings['footer_logo'])); ?>" alt="Footer Logo" class="img-fluid" id="footer_logo-preview" style="max-height: 80px;">
                            <?php else: ?>
                                <span class="text-muted" id="footer_logo-placeholder">No footer logo</span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="footer_logo" class="form-control mt-2" accept="image/*" onchange="previewFile('footer_logo')">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Login Background Image</label>
                        <div class="border rounded p-2 text-center" style="min-height: 150px;" id="login_background-preview-container">
                            <?php if($settings['login_background'] ?? false): ?>
                                <img src="<?php echo e(asset($settings['login_background'])); ?>" alt="Background" class="img-fluid" id="login_background-preview" style="max-height: 130px;">
                            <?php else: ?>
                                <span class="text-muted" id="login_background-placeholder">No background uploaded</span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="login_background" class="form-control mt-2" accept="image/*" onchange="previewFile('login_background')">
                    </div>
                </div>

                <hr class="my-4">

                <!-- Color Settings -->
                <h6 class="mb-3">Color Scheme</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Primary Color</label>
                        <div class="input-group">
                            <input type="color" name="primary_color" class="form-control form-control-color" value="<?php echo e($settings['primary_color'] ?? '#38488e'); ?>">
                            <input type="text" class="form-control" value="<?php echo e($settings['primary_color'] ?? '#38488e'); ?>" readonly>
                        </div>
                        <small class="text-muted">Main brand color (headers, buttons)</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Secondary Color</label>
                        <div class="input-group">
                            <input type="color" name="secondary_color" class="form-control form-control-color" value="<?php echo e($settings['secondary_color'] ?? '#4052a0'); ?>">
                            <input type="text" class="form-control" value="<?php echo e($settings['secondary_color'] ?? '#4052a0'); ?>" readonly>
                        </div>
                        <small class="text-muted">Secondary elements</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Accent Color</label>
                        <div class="input-group">
                            <input type="color" name="accent_color" class="form-control form-control-color" value="<?php echo e($settings['accent_color'] ?? '#fcb900'); ?>">
                            <input type="text" class="form-control" value="<?php echo e($settings['accent_color'] ?? '#fcb900'); ?>" readonly>
                        </div>
                        <small class="text-muted">Highlights, buttons</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Header Text Color</label>
                        <div class="input-group">
                            <input type="color" name="header_text_color" class="form-control form-control-color" value="<?php echo e($settings['header_text_color'] ?? '#ffffff'); ?>">
                            <input type="text" class="form-control" value="<?php echo e($settings['header_text_color'] ?? '#ffffff'); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Footer Text Color</label>
                        <div class="input-group">
                            <input type="color" name="footer_text_color" class="form-control form-control-color" value="<?php echo e($settings['footer_text_color'] ?? '#ffffff'); ?>">
                            <input type="text" class="form-control" value="<?php echo e($settings['footer_text_color'] ?? '#ffffff'); ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Quick Presets -->
                <h6 class="mb-3">Quick Presets</h6>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-outline-primary me-2" onclick="setPreset('ekscotech')">EKSCOTECH Preset</button>
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="setPreset('blue')">Blue Theme</button>
                        <button type="button" class="btn btn-outline-success me-2" onclick="setPreset('green')">Green Theme</button>
                        <button type="button" class="btn btn-outline-danger me-2" onclick="setPreset('red')">Red Theme</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-save me-2"></i>Save Branding Settings
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Preview -->
        <div class="stat-card mb-4">
            <h5 class="mb-3">Preview</h5>
            <div class="border rounded p-3" style="background: linear-gradient(135deg, <?php echo e($settings['primary_color'] ?? '#38488e'); ?> 0%, <?php echo e($settings['secondary_color'] ?? '#4052a0'); ?> 100%);">
                <div class="text-white p-3 rounded" style="background: rgba(255,255,255,0.1);">
                    <h5 class="mb-1">
                        <?php if($settings['logo'] ?? false): ?>
                            <img src="<?php echo e(asset($settings['logo'])); ?>" alt="Logo" height="30">
                        <?php else: ?>
                            <i class="bi bi-mortarboard-fill"></i> Portal Name
                        <?php endif; ?>
                    </h5>
                    <small>Your Institution Name</small>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm" style="background: <?php echo e($settings['accent_color'] ?? '#fcb900'); ?>; color: #000;">Primary Button</button>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="stat-card">
            <h5 class="mb-3">Instructions</h5>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Upload PNG or JPG logos</li>
                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Use hex color codes (#38488e)</li>
                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Click presets for quick themes</li>
                <li class="mb-2"><i class="bi bi-info-circle text-info me-2"></i>Changes apply immediately</li>
                <li class="mb-2"><i class="bi bi-image text-warning me-2"></i>Preview shows after upload</li>
            </ul>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function setPreset(preset) {
    const presets = {
        'ekscotech': { primary: '#38488e', secondary: '#4052a0', accent: '#fcb900' },
        'blue': { primary: '#1e3a5f', secondary: '#2c5282', accent: '#3498db' },
        'green': { primary: '#27ae60', secondary: '#2ecc71', accent: '#f39c12' },
        'red': { primary: '#c0392b', secondary: '#e74c3c', accent: '#f39c12' }
    };

    const colors = presets[preset];
    document.querySelector('input[name="primary_color"]').value = colors.primary;
    document.querySelector('input[name="primary_color"]').nextElementSibling.value = colors.primary;
    document.querySelector('input[name="secondary_color"]').value = colors.secondary;
    document.querySelector('input[name="secondary_color"]').nextElementSibling.value = colors.secondary;
    document.querySelector('input[name="accent_color"]').value = colors.accent;
    document.querySelector('input[name="accent_color"]').nextElementSibling.value = colors.accent;
}

// Update text input when color picker changes
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
    });
});

// File preview function
function previewFile(fieldName) {
    const input = document.querySelector(`input[name="${fieldName}"]`);
    const preview = document.getElementById(`${fieldName}-preview`);
    const placeholder = document.getElementById(`${fieldName}-placeholder`);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.id = `${fieldName}-preview`;
                img.className = 'img-fluid';
                img.style.maxHeight = fieldName === 'login_background' ? '130px' : '80px';

                const container = document.getElementById(`${fieldName}-preview-container`);
                container.innerHTML = '';
                container.appendChild(img);
            }

            if (placeholder) {
                placeholder.style.display = 'none';
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/settings/branding.blade.php ENDPATH**/ ?>