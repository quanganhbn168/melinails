

<?php $__env->startSection('title', 'Lịch sử đơn hàng'); ?>

<?php $__env->startSection('dashboard_content'); ?>
    <h3 class="mb-4">Lịch sử đơn hàng</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#<?php echo e($order->id); ?></td>
                        <td><?php echo e($order->created_at->format('d/m/Y')); ?></td>
                        <td><?php echo e(number_format($order->total)); ?>đ</td>
                        <td><span class="badge bg-info text-white"><?php echo e($order->status); ?></span></td>
                        <td>
                            <a href="<?php echo e(route('user.order.detail', $order->id)); ?>" class="btn btn-sm btn-primary">
                                Xem chi tiết
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Bạn chưa có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($orders->links()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.dashboard.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/dashboard/orders.blade.php ENDPATH**/ ?>