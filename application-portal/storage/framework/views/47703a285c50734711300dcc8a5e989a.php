<?php $__env->startSection('title', 'Programmes & Positions'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.settings.index')); ?>">Settings</a></li>
<li class="breadcrumb-item active">Programmes</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <div class="stat-card">
            <h5 class="mb-4">Add New Programme</h5>
            <form method="POST" action="<?php echo e(route('admin.settings.programmes.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Programme Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Computer Science" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Programme Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g., CSC" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" placeholder="e.g., Science" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."></textarea>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" checked>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="bi bi-plus-circle me-2"></i>Add Programme
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Programmes & Positions List</h4>
                <span class="badge bg-primary"><?php echo e(count($programmes)); ?> Total</span>
            </div>

            <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if(count($programmes) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $programmes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $programme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($programme['code']); ?></strong></td>
                            <td><?php echo e($programme['name']); ?></td>
                            <td><?php echo e($programme['department']); ?></td>
                            <td>
                                <?php if($programme['is_active'] ?? true): ?>
                                <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($index); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="<?php echo e(route('admin.settings.programmes.destroy', $index)); ?>" class="d-inline" onsubmit="return confirm('Delete this programme?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo e($index); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit: <?php echo e($programme['name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('admin.settings.programmes.update', $index)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Programme Name</label>
                                                <input type="text" name="name" class="form-control" value="<?php echo e($programme['name']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Programme Code</label>
                                                <input type="text" name="code" class="form-control" value="<?php echo e($programme['code']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Department</label>
                                                <input type="text" name="department" class="form-control" value="<?php echo e($programme['department']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2"><?php echo e($programme['description'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="is_active" class="form-check-input" id="editActive<?php echo e($index); ?>" value="1" <?php echo e(($programme['is_active'] ?? true) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="editActive<?php echo e($index); ?>">Active</label>
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-folder-plus fs-1 text-muted mb-3"></i>
                <p class="text-muted">No programmes added yet.</p>
                <p class="text-muted">Add programmes above that applicants can select when applying.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/settings/programmes.blade.php ENDPATH**/ ?>