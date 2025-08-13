

<?php $__env->startSection('title', 'Chỉnh sửa Người dùng'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Chỉnh sửa Người dùng: <?php echo e($user->name); ?></h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.users.update', $user)); ?>" method="POST">
            <?php echo method_field('PUT'); ?>
            
            <?php echo $__env->make('admin.users._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>