<?php $__env->startSection('title', 'Application Types'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.settings.index')); ?>">Settings</a></li>
<li class="breadcrumb-item active">Application Types</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <!-- Create New Type Form -->
        <div class="stat-card mb-4">
            <h5 class="mb-4">Create New Application Type</h5>
            <form method="POST" action="<?php echo e(route('admin.settings.application-types.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Type Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Employment Application" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" checked>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom">Create Type</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Existing Types -->
        <div class="stat-card">
            <h5 class="mb-4">Existing Application Types</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($type->name); ?></strong>
                                <br><small class="text-muted"><?php echo e($type->slug); ?></small>
                            </td>
                            <td><?php echo e($type->description ?? '-'); ?></td>
                            <td>
                                <?php if($type->is_active): ?>
                                <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.settings.application-types.fields', $type->id)); ?>" class="btn btn-sm btn-outline-primary" title="Configure Fields">
                                    <i class="bi bi-gear"></i> Fields
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($type->id); ?>" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="<?php echo e(route('admin.settings.application-types.destroy', $type->id)); ?>" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo e($type->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit: <?php echo e($type->name); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('admin.settings.application-types.update', $type->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Type Name</label>
                                                <input type="text" name="name" class="form-control" value="<?php echo e($type->name); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2"><?php echo e($type->description); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control" value="<?php echo e($type->sort_order); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="is_active" class="form-check-input" id="editActive<?php echo e($type->id); ?>" value="1" <?php echo e($type->is_active ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="editActive<?php echo e($type->id); ?>">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No application types created yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/settings/application-types.blade.php ENDPATH**/ ?>