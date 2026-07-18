<?php $__env->startSection('title', 'Configure Fields - ' . $type->name); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.settings.index')); ?>">Settings</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.settings.application-types')); ?>">Application Types</a></li>
<li class="breadcrumb-item active">Configure Fields</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('admin.settings.application-types.fields.update', $type->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Configure Fields for: <?php echo e($type->name); ?></h4>
                <p class="text-muted mb-0">Select which fields to include and mark as required</p>
            </div>
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-save me-2"></i>Save Configuration
            </button>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Check the "Enabled" box to include a field in this application type. Check "Required" to make it mandatory.
        </div>

        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mb-4">
            <h5 class="text-capitalize mb-3">
                <?php echo e(str_replace('_', ' ', $section)); ?> Information
            </h5>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">Enabled</th>
                                <th>Field Label</th>
                                <th>Field Name</th>
                                <th>Type</th>
                                <th style="width: 80px;">Required</th>
                                <th style="width: 80px;">Sort Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $fieldConfig = $typeFields->get($field->id);
                            $isEnabled = $fieldConfig ? $fieldConfig->pivot->is_enabled : false;
                            $isRequired = $fieldConfig ? $fieldConfig->pivot->is_required : $field->is_required;
                            $sortOrder = $fieldConfig ? $fieldConfig->pivot->sort_order : $field->sort_order;
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           name="fields[<?php echo e($field->id); ?>][enabled]"
                                           value="1"
                                           <?php echo e($isEnabled ? 'checked' : ''); ?>

                                           class="form-check-input field-enabled"
                                           data-field-id="<?php echo e($field->id); ?>">
                                </td>
                                <td><?php echo e($field->field_label); ?></td>
                                <td><code><?php echo e($field->field_name); ?></code></td>
                                <td><span class="badge bg-secondary"><?php echo e($field->field_type); ?></span></td>
                                <td>
                                    <input type="checkbox"
                                           name="fields[<?php echo e($field->id); ?>][required]"
                                           value="1"
                                           <?php echo e($isRequired ? 'checked' : ''); ?>

                                           class="form-check-input field-required"
                                           id="required_<?php echo e($field->id); ?>">
                                </td>
                                <td>
                                    <input type="number"
                                           name="fields[<?php echo e($field->id); ?>][sort_order]"
                                           value="<?php echo e($sortOrder); ?>"
                                           class="form-control form-control-sm"
                                           style="width: 70px;">
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="d-flex justify-content-end mt-4">
            <a href="<?php echo e(route('admin.settings.application-types')); ?>" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-save me-2"></i>Save Configuration
            </button>
        </div>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-uncheck required when disabled
    document.querySelectorAll('.field-enabled').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const fieldId = this.dataset.fieldId;
            const requiredCheckbox = document.getElementById('required_' + fieldId);
            if (!this.checked) {
                requiredCheckbox.checked = false;
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/settings/application-type-fields.blade.php ENDPATH**/ ?>