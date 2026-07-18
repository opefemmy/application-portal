<?php $__env->startSection('title', 'About Us'); ?>

<?php $__env->startSection('content'); ?>
<section class="section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="page-title">About <?php echo e($settings['institution_name'] ?? 'Our Institution'); ?></h2>
                <p class="lead">Welcome to <?php echo e($settings['portal_name'] ?? 'Online Application Portal'); ?>, your gateway to new opportunities.</p>
                <p>We are committed to providing a seamless, transparent, and efficient application process for all candidates. Our online portal allows you to apply from anywhere in the world, at any time.</p>
                <p>Our mission is to make the application process simple, secure, and accessible to everyone. We continuously improve our system to ensure a positive experience for all applicants.</p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/frontend/about.blade.php ENDPATH**/ ?>