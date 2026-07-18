<?php $__env->startSection('title', 'Contact Us'); ?>

<?php $__env->startSection('content'); ?>
<section class="section bg-white">
    <div class="container">
        <h2 class="page-title">Contact Us</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card-custom p-4 h-100">
                    <h5 class="mb-4">Get in Touch</h5>
                    <div class="mb-3">
                        <i class="bi bi-envelope text-primary me-2"></i>
                        <span><?php echo e($settings['contact_email'] ?? 'contact@example.com'); ?></span>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-telephone text-primary me-2"></i>
                        <span><?php echo e($settings['phone_number'] ?? '+1234567890'); ?></span>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        <span>Address Line, City, Country</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h5 class="mb-4">Send us a Message</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-custom">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/frontend/contact.blade.php ENDPATH**/ ?>