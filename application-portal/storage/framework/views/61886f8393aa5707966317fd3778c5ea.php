<?php $__env->startSection('title', 'Track Application'); ?>

<?php $__env->startSection('content'); ?>
<div class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-custom p-5 text-center">
                    <h2 class="mb-4"><i class="bi bi-search me-2"></i>Track Your Application</h2>
                    <p class="text-muted mb-4">Enter your application number to check the status of your application.</p>

                    <form method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="application_number" class="form-control form-control-lg" placeholder="Enter Application Number (e.g., APP-2026-000001)" required>
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <?php if($application): ?>
                    <div class="alert alert-success">
                        <h5><i class="bi bi-check-circle me-2"></i>Application Found</h5>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Application Number</td>
                                <td class="fw-bold"><?php echo e($application->application_number); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Name</td>
                                <td><?php echo e($application->full_name); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    <?php switch($application->status):
                                        case ('pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                            <?php break; ?>
                                        <?php case ('reviewed'): ?>
                                            <span class="badge bg-info">Reviewed</span>
                                            <?php break; ?>
                                        <?php case ('shortlisted'): ?>
                                            <span class="badge bg-success">Shortlisted</span>
                                            <?php break; ?>
                                        <?php case ('interview_scheduled'): ?>
                                            <span class="badge bg-primary">Interview Scheduled</span>
                                            <?php break; ?>
                                        <?php case ('accepted'): ?>
                                            <span class="badge bg-success">Accepted</span>
                                            <?php break; ?>
                                        <?php case ('rejected'): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                            <?php break; ?>
                                        <?php case ('completed'): ?>
                                            <span class="badge bg-dark">Completed</span>
                                            <?php break; ?>
                                    <?php endswitch; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Submission Date</td>
                                <td><?php echo e($application->created_at->format('F j, Y')); ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php elseif(request('application_number')): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No application found with this number. Please check and try again.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/frontend/track.blade.php ENDPATH**/ ?>