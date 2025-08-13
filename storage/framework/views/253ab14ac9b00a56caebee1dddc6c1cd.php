
    <div class="swiper slider h-100">
        <div class="swiper-wrapper">
            <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="swiper-slide">
                <img
                class="swiper-lazy"
                data-src="<?php echo e(asset($slide->image)); ?>"
                src="<?php echo e(asset($slide->image)); ?>"
                alt="<?php echo e($slide->name); ?>"
                title="<?php echo e($slide->name); ?>"
                >
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/partials/frontend/slide.blade.php ENDPATH**/ ?>