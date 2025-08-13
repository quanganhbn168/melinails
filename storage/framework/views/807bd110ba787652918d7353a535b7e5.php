<?php
    $segments = Request::segments();
    $url = '';
?>

<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item">
        <a href="<?php echo e(url('/admin/dashboard')); ?>">Trang chủ</a>
    </li>

    <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $url .= '/' . $segment;
            $is_last = $index === array_key_last($segments);
            $label = ucfirst(str_replace('-', ' ', $segment));
        ?>

        <li class="breadcrumb-item <?php echo e($is_last ? 'active' : ''); ?>" <?php echo e($is_last ? 'aria-current=page' : ''); ?>>
            <?php if(!$is_last): ?>
                <a href="<?php echo e(url($url)); ?>"><?php echo e($label); ?></a>
            <?php else: ?>
                <?php echo e($label); ?>

            <?php endif; ?>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ol>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>