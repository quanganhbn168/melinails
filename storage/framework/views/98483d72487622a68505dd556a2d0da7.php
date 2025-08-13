<?php $__env->startSection('title', 'Giới thiệu'); ?>
<?php $__env->startSection('meta_image',$setting->share_image); ?>
<?php $__env->startSection('content'); ?>
<section class="section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="section-title text-uppercase">Về <?php echo e($setting->name); ?></h1>
        </div>
        <div class="content">
            <div class="row">
                <div class="col-12 col-md-9">
                    <?php echo $intro->content; ?>

                    <h2 class="contact">Liên hệ ngay cho <?php echo e($setting->name); ?></h2>
                    
                </div>
                <div class="col-12 col-md-3">
                    <?php echo $__env->make('partials.frontend.contact_register', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/intro.blade.php ENDPATH**/ ?>