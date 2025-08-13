


<?php $__env->startSection('title', 'Danh sách sản phẩm'); ?>
<?php $__env->startSection('content_header', 'Danh sách sản phẩm'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-tools">
                <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm sản phẩm
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php echo $dataTable->table(); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <?php echo $dataTable->scripts(); ?>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/products/index.blade.php ENDPATH**/ ?>