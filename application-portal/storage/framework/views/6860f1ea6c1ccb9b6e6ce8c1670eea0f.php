<?php $__env->startSection('title', 'Application Details'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.applications.index')); ?>">Applications</a></li>
<li class="breadcrumb-item active"><?php echo e($application->application_number); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Application: <?php echo e($application->application_number); ?></h4>
        <span class="text-muted">Submitted on <?php echo e($application->created_at->format('F j, Y g:i A')); ?></span>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.applications.print', $application->id)); ?>" class="btn btn-outline-primary" target="_blank">
            <i class="bi bi-printer me-2"></i>Print
        </a>
        <form method="POST" action="<?php echo e(route('admin.applications.destroy', $application->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                <i class="bi bi-trash me-2"></i>Delete
            </button>
        </form>
    </div>
</div>

<div class="row">
    <!-- Status Update -->
    <div class="col-lg-4">
        <div class="stat-card mb-4">
            <h5 class="mb-3">Update Status</h5>
            <form method="POST" action="<?php echo e(route('admin.applications.status', $application->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php $__currentLoopData = \App\Models\Application::getStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e($application->status == $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"><?php echo e($application->notes); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Update Status</button>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="stat-card mb-4">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#shortlistModal">
                    <i class="bi bi-check-circle me-2"></i>Send Shortlist Email
                </button>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-2"></i>Send Rejection Email
                </button>
            </div>
        </div>

        <!-- Documents -->
        <div class="stat-card">
            <h5 class="mb-3">Uploaded Documents</h5>
            <?php $__empty_1 = true; $__currentLoopData = $application->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                <span><i class="bi bi-file-earmark me-2"></i><?php echo e($doc->document_type); ?></span>
                <div class="btn-group btn-group-sm">
                    <a href="<?php echo e(route('admin.documents.preview', $doc->id)); ?>" class="btn btn-outline-primary" target="_blank">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?php echo e(route('admin.documents.download', $doc->id)); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted mb-0">No documents uploaded</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Application Details -->
    <div class="col-lg-8">
        <div class="stat-card mb-4">
            <h5 class="mb-3">Personal Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Name:</strong> <?php echo e($application->full_name); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Gender:</strong> <?php echo e(ucfirst($application->gender)); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Date of Birth:</strong> <?php echo e($application->personal_info['date_of_birth'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Marital Status:</strong> <?php echo e(ucfirst($application->personal_info['marital_status'] ?? 'N/A')); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Nationality:</strong> <?php echo e($application->personal_info['nationality'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>State of Origin:</strong> <?php echo e($application->state); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Local Government:</strong> <?php echo e($application->personal_info['local_government'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Email:</strong> <?php echo e($application->email); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Phone:</strong> <?php echo e($application->phone); ?>

                </div>
                <div class="col-12 mb-2">
                    <strong>Address:</strong> <?php echo e($application->personal_info['residential_address'] ?? 'N/A'); ?>

                </div>
            </div>
        </div>

        <div class="stat-card mb-4">
            <h5 class="mb-3">Academic Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Qualification:</strong> <?php echo e($application->qualification); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Institution:</strong> <?php echo e($application->academic_info['institution_attended'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Course:</strong> <?php echo e($application->academic_info['course_studied'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Grade:</strong> <?php echo e($application->academic_info['grade_class'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Graduation Year:</strong> <?php echo e($application->academic_info['graduation_year'] ?? 'N/A'); ?>

                </div>
            </div>
        </div>

        <?php if($application->employment_info && ($application->employment_info['employer'] ?? null)): ?>
        <div class="stat-card mb-4">
            <h5 class="mb-3">Employment Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Employer:</strong> <?php echo e($application->employment_info['employer'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Position:</strong> <?php echo e($application->employment_info['position'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Years of Experience:</strong> <?php echo e($application->employment_info['years_experience'] ?? 'N/A'); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="stat-card">
            <h5 class="mb-3">Application Details</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Position:</strong> <?php echo e($application->application_details['position_applying_for'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Programme:</strong> <?php echo e($application->application_details['programme_applying_for'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Department:</strong> <?php echo e($application->application_details['department'] ?? 'N/A'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Category:</strong> <?php echo e($application->application_details['category'] ?? 'N/A'); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shortlist Modal -->
<div class="modal fade" id="shortlistModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Shortlist Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.applications.shortlist', $application->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Interview Date</label>
                        <input type="date" name="interview_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interview Time</label>
                        <input type="time" name="interview_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Link (Optional)</label>
                        <input type="url" name="meeting_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Rejection Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.applications.reject', $application->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Message (Optional)</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Add a personalized message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Send Rejection Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/applications/show.blade.php ENDPATH**/ ?>