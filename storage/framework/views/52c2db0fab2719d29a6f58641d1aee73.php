

<?php $__env->startSection('title', 'Tra cứu Bảo hành'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Tra cứu thông tin bảo hành theo Mã Đơn hàng</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.warranty.search')); ?>" method="POST" class="row g-3 align-items-end">
                <?php echo csrf_field(); ?>
                <div class="col-md-6">
                    <label for="order_code" class="form-label">Nhập mã đơn hàng (ví dụ: TP-250811-0001)</label>
                    <input type="text" class="form-control" id="order_code" name="order_code" value="<?php echo e($order->code ?? old('order_code')); ?>" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tra cứu
                    </button>
                </div>
            </form>

            <?php if(session('error')): ?>
                <div class="alert alert-danger mt-3"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if(isset($order)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Kết quả cho Đơn hàng: <?php echo e($order->code); ?></h5>
        </div>
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Khách hàng của đơn:</strong> <?php echo e($order->customer_name ?? $order->user->name ?? 'N/A'); ?><br>
                    <strong>Điện thoại:</strong> <?php echo e($order->customer_phone ?? $order->user->phone ?? 'N/A'); ?>

                </div>
                <div class="col-md-6">
                    
                    <?php if($order->technician): ?>
                        <strong>Thợ lắp đặt được gán:</strong> <?php echo e($order->technician->name); ?><br>
                    <?php endif; ?>
                    <strong>Ngày tạo đơn (Bắt đầu BH):</strong> <?php echo e($order->created_at->format('d/m/Y')); ?>

                </div>
            </div>

            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Thời hạn BH</th>
                            <th>Ngày hết hạn (Dự kiến)</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->product_name); ?></td>
                                <td><?php echo e($item->warranty_months); ?> tháng</td>
                                <td>
                                    
                                    <?php if($item->warranty_months > 0): ?>
                                        <?php
                                            $expiryDate = $order->created_at->copy()->addMonths($item->warranty_months);
                                        ?>
                                        <?php echo e($expiryDate->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        Không áp dụng
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(isset($expiryDate) && now()->isBefore($expiryDate)): ?>
                                        <span class="badge bg-success">Còn bảo hành</span>
                                    <?php elseif($item->warranty_months > 0): ?>
                                        <span class="badge bg-danger">Hết bảo hành</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Không có</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/warranty/index.blade.php ENDPATH**/ ?>