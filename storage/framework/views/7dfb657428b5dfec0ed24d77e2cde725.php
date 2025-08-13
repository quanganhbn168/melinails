

<?php if($errors->any()): ?>
    <div class="alert alert-danger" role="alert">
        <h6 class="alert-heading">Có lỗi xảy ra, vui lòng kiểm tra lại!</h6>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/alert-errors.blade.php ENDPATH**/ ?>