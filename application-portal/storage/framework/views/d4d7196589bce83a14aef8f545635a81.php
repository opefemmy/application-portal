<?php $__env->startSection('title', 'How to Apply'); ?>

<?php $__env->startSection('content'); ?>
<section class="section bg-white">
    <div class="container">
        <h2 class="page-title">How to Apply</h2>
        <div class="row">
            <div class="col-lg-12">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon"><i class="bi bi-1-circle"></i></div>
                        <div class="timeline-content">
                            <h4>Step 1: Visit the Portal</h4>
                            <p>Navigate to our application portal and click "Apply Now" to begin your application.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon"><i class="bi bi-2-circle"></i></div>
                        <div class="timeline-content">
                            <h4>Step 2: Fill the Form</h4>
                            <p>Complete all required sections: Personal Information, Academic Details, and Application Details.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon"><i class="bi bi-3-circle"></i></div>
                        <div class="timeline-content">
                            <h4>Step 3: Upload Documents</h4>
                            <p>Upload all required documents in the specified formats (PDF, JPG, PNG).</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon"><i class="bi bi-4-circle"></i></div>
                        <div class="timeline-content">
                            <h4>Step 4: Review & Submit</h4>
                            <p>Review all information, accept the declaration, and submit your application.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon"><i class="bi bi-5-circle"></i></div>
                        <div class="timeline-content">
                            <h4>Step 5: Confirmation</h4>
                            <p>Receive your application number via email and on-screen. Use it to track your application status.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.timeline { position: relative; padding-left: 30px; }
.timeline-item { position: relative; padding-bottom: 30px; }
.timeline-item::before {
    content: ''; position: absolute; left: 15px; top: 30px; bottom: 0; width: 2px; background: #e9ecef;
}
.timeline-item:last-child::before { display: none; }
.timeline-icon {
    position: absolute; left: 0; width: 32px; height: 32px; background: var(--primary); color: white;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
.timeline-content { padding-left: 20px; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/frontend/how-to-apply.blade.php ENDPATH**/ ?>