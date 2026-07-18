<?php $__env->startSection('title', 'FAQ'); ?>

<?php $__env->startSection('content'); ?>
<section class="section bg-white">
    <div class="container">
        <h2 class="page-title">Frequently Asked Questions</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Do I need to create an account to apply?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">No, you do not need to create an account. Simply click "Apply Now" and fill out the application form. You will receive a confirmation email after submission.</div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What documents do I need to upload?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">You will need to upload your passport photograph, birth certificate, local government certificate, and other relevant documents. All documents should be in PDF, JPG, JPEG, or PNG format.</div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How can I track my application?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Use the "Track Application" page and enter your application number. You can also check your email for status updates.</div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                What is the maximum file size for uploads?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">The maximum file size is 10MB per document. Please ensure your files are properly compressed if they exceed this limit.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/frontend/faq.blade.php ENDPATH**/ ?>