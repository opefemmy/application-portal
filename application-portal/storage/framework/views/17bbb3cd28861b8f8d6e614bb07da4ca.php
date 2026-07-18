<?php $__env->startSection('title', 'Application Submitted'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .acknowledge-card {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--success) 0%, #2ecc71 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .application-details {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 20px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .qr-code {
        background: white;
        padding: 15px;
        border-radius: 10px;
        display: inline-block;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="section py-5">
    <div class="container">
        <div class="acknowledge-card p-5">
            <div class="text-center mb-4">
                <div class="success-icon mb-4">
                    <i class="bi bi-check-lg text-white fs-1"></i>
                </div>
                <h2 class="fw-bold text-success">Application Submitted Successfully!</h2>
                <p class="text-muted">Thank you for applying. Your application has been received.</p>
            </div>

            <div class="application-details mb-4">
                <h5 class="mb-3">Application Details</h5>
                <div class="detail-row">
                    <span class="text-muted">Application Number</span>
                    <span class="fw-bold text-primary"><?php echo e($application->application_number); ?></span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Applicant Name</span>
                    <span class="fw-bold"><?php echo e($application->full_name); ?></span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Email Address</span>
                    <span><?php echo e($application->email); ?></span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Phone Number</span>
                    <span><?php echo e($application->phone); ?></span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Position Applied</span>
                    <span><?php echo e($application->application_details['position_applying_for'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Date Submitted</span>
                    <span><?php echo e($application->created_at->format('F j, Y - g:i A')); ?></span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-warning"><?php echo e(ucfirst($application->status)); ?></span>
                </div>
            </div>

            <div class="text-center mb-4">
                <div class="qr-code mb-3" style="font-family: monospace; font-size: 12px; text-align: center;">
                    <div style="border: 2px solid #333; padding: 10px; display: inline-block; background: white;">
                        <div style="border-bottom: 1px solid #333; padding-bottom: 5px; margin-bottom: 5px;">
                            ██ ██ ██ ██ ██<br>
                            ██ ██ ██ ██ ██
                        </div>
                        <div style="font-weight: bold;"><?php echo e($application->application_number); ?></div>
                    </div>
                </div>
                <p class="small text-muted">Application Reference Code</p>
            </div>

            <div class="alert alert-info">
                <h6><i class="bi bi-envelope me-2"></i>Check Your Email</h6>
                <p class="mb-0">A confirmation email has been sent to <strong><?php echo e($application->email); ?></strong>. Please check your inbox (and spam folder) for further updates.</p>
            </div>

            <div class="d-grid gap-2">
                <button onclick="window.print()" class="btn btn-primary-custom">
                    <i class="bi bi-printer me-2"></i>Print Acknowledgement
                </button>
                <a href="<?php echo e(route('application.download', $application->id)); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Download PDF
                </a>
                <a href="<?php echo e(route('track')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-search me-2"></i>Track Application
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/frontend/acknowledge.blade.php ENDPATH**/ ?>