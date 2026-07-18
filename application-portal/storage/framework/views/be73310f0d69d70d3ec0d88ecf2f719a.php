<?php $__env->startSection('title', 'Applications'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item active">Applications</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Applications</h4>
    <div>
        <button class="btn btn-outline-primary" onclick="exportData('csv')">
            <i class="bi bi-download me-2"></i>Export CSV
        </button>
        <button class="btn btn-outline-primary" onclick="exportData('excel')">
            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
        </button>
    </div>
</div>

<!-- Filters -->
<div class="stat-card mb-4">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, app number..." value="<?php echo e(request('search')); ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="reviewed" <?php echo e(request('status') == 'reviewed' ? 'selected' : ''); ?>>Reviewed</option>
                <option value="shortlisted" <?php echo e(request('status') == 'shortlisted' ? 'selected' : ''); ?>>Shortlisted</option>
                <option value="interview_scheduled" <?php echo e(request('status') == 'interview_scheduled' ? 'selected' : ''); ?>>Interview Scheduled</option>
                <option value="accepted" <?php echo e(request('status') == 'accepted' ? 'selected' : ''); ?>>Accepted</option>
                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="gender" class="form-select">
                <option value="">All Gender</option>
                <option value="male" <?php echo e(request('gender') == 'male' ? 'selected' : ''); ?>>Male</option>
                <option value="female" <?php echo e(request('gender') == 'female' ? 'selected' : ''); ?>>Female</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" placeholder="Date From" value="<?php echo e(request('date_from')); ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" placeholder="Date To" value="<?php echo e(request('date_to')); ?>">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="stat-card mb-4">
    <form id="bulkActionForm" method="POST">
        <?php echo csrf_field(); ?>
        <div class="d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="selectAll">
                <label class="form-check-label" for="selectAll">Select All</label>
            </div>
            <div class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Bulk Actions</option>
                    <option value="reviewed">Mark as Reviewed</option>
                    <option value="shortlisted">Mark as Shortlisted</option>
                    <option value="rejected">Mark as Rejected</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary-custom">Apply</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkDelete()">
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Applications Table -->
<div class="table-container">
    <div class="table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th width="50"></th>
                    <th>App Number</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <input type="checkbox" name="application_ids[]" value="<?php echo e($application->id); ?>" class="form-check-input">
                    </td>
                    <td><span class="fw-bold"><?php echo e($application->application_number); ?></span></td>
                    <td><?php echo e($application->full_name); ?></td>
                    <td><?php echo e($application->email); ?></td>
                    <td><?php echo e($application->phone); ?></td>
                    <td><?php echo e($application->application_details['position_applying_for'] ?? 'N/A'); ?></td>
                    <td><?php echo e(ucfirst($application->gender)); ?></td>
                    <td>
                        <?php switch($application->status):
                            case ('pending'): ?>
                                <span class="badge badge-pending">Pending</span>
                                <?php break; ?>
                            <?php case ('reviewed'): ?>
                                <span class="badge badge-reviewed">Reviewed</span>
                                <?php break; ?>
                            <?php case ('shortlisted'): ?>
                                <span class="badge badge-shortlisted">Shortlisted</span>
                                <?php break; ?>
                            <?php case ('interview_scheduled'): ?>
                                <span class="badge badge-interview">Interview</span>
                                <?php break; ?>
                            <?php case ('accepted'): ?>
                                <span class="badge badge-accepted">Accepted</span>
                                <?php break; ?>
                            <?php case ('rejected'): ?>
                                <span class="badge badge-rejected">Rejected</span>
                                <?php break; ?>
                            <?php case ('completed'): ?>
                                <span class="badge badge-completed">Completed</span>
                                <?php break; ?>
                        <?php endswitch; ?>
                    </td>
                    <td><?php echo e($application->created_at->format('M j, Y')); ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="<?php echo e(route('admin.applications.show', $application->id)); ?>" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.applications.print', $application->id)); ?>" class="btn btn-sm btn-outline-secondary" title="Print" target="_blank">
                                <i class="bi bi-printer"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.applications.destroy', $application->id)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No applications found
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <?php echo e($applications->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Select all checkbox
    $('#selectAll').change(function() {
        $('input[name="application_ids[]"]').prop('checked', $(this).prop('checked'));
    });

    // Bulk status update
    $('#bulkActionForm').submit(function(e) {
        e.preventDefault();
        const selected = $('input[name="application_ids[]"]:checked').length;
        if (selected === 0) {
            alert('Please select at least one application');
            return;
        }
        if (confirm(`Update status for ${selected} application(s)?`)) {
            $.ajax({
                url: '<?php echo e(route("admin.applications.bulk-status")); ?>',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('An error occurred');
                }
            });
        }
    });

    // Bulk delete
    function bulkDelete() {
        const selected = $('input[name="application_ids[]"]:checked').length;
        if (selected === 0) {
            alert('Please select at least one application');
            return;
        }
        if (confirm(`Delete ${selected} application(s)? This cannot be undone.`)) {
            $.ajax({
                url: '<?php echo e(route("admin.applications.bulk-delete")); ?>',
                method: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    application_ids: $('input[name="application_ids[]"]:checked').map(function() { return this.value; }).get()
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('An error occurred');
                }
            });
        }
    }

    // Export
    function exportData(format) {
        const params = new URLSearchParams(window.location.search);
        params.set('format', format);
        window.location.href = '<?php echo e(route("admin.applications.export")); ?>?' + params.toString();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/applications/index.blade.php ENDPATH**/ ?>