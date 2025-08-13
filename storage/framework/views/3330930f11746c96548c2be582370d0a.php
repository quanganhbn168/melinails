

<?php $__env->startSection('title', 'Quản lý Đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh sách Đơn hàng</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?php echo e(route('admin.orders.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tạo đơn hàng mới
            </a>
            <form action="<?php echo e(route('admin.orders.index')); ?>" method="GET" class="d-flex">
                <input type="text" class="form-control me-2" name="search" placeholder="Tìm kiếm khách hàng..." value="<?php echo e(request('search')); ?>">
                <button type="submit" class="btn btn-secondary">Tìm</button>
            </form>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong>#<?php echo e($order->id); ?></strong></td>
                            <td>
                                <?php echo e($order->customer_name ?? $order->user->name); ?>

                                <br>
                                <small class="text-muted"><?php echo e($order->customer_phone ?? $order->user->phone); ?></small>
                            </td>
                            <td><?php echo e(number_format($order->total_price, 0, ',', '.')); ?> đ</td>
                            <td>
                                <?php
                                    $statusClass = [
                                        'pending' => 'bg-warning text-dark',
                                        'processing' => 'bg-info text-dark',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                    ][$order->status] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                            </td>
                            <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-sm btn-light" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="<?php echo e(route('admin.orders.edit', $order)); ?>" class="btn btn-sm btn-warning" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.orders.destroy', $order)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Anh có chắc muốn xóa đơn hàng này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3"><?php echo e($orders->appends(request()->query())->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>