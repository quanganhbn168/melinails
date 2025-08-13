

<?php $__env->startSection('title', 'Chỉnh sửa Đơn hàng #' . $order->code); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Chỉnh sửa Đơn hàng #<?php echo e($order->code); ?></h5>
    </div>
    <div class="card-body">
        <?php if (isset($component)) { $__componentOriginale064690ba8c23d027d850cedab635484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale064690ba8c23d027d850cedab635484 = $attributes; } ?>
<?php $component = App\View\Components\AlertErrors::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AlertErrors::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale064690ba8c23d027d850cedab635484)): ?>
<?php $attributes = $__attributesOriginale064690ba8c23d027d850cedab635484; ?>
<?php unset($__attributesOriginale064690ba8c23d027d850cedab635484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale064690ba8c23d027d850cedab635484)): ?>
<?php $component = $__componentOriginale064690ba8c23d027d850cedab635484; ?>
<?php unset($__componentOriginale064690ba8c23d027d850cedab635484); ?>
<?php endif; ?>
        <form action="<?php echo e(route('admin.orders.update', $order)); ?>" method="POST">
            <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('admin.orders._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/orders/edit.blade.php ENDPATH**/ ?>