
<?php $__env->startSection('title', 'Tra cứu thông tin bảo hành'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center mb-0">TRA CỨU THÔNG TIN BẢO HÀNH</h3>
                </div>
                <div class="card-body">
                    <p class="text-center text-muted">
                        Tra cứu bằng <strong>Số điện thoại</strong> hoặc <strong>Mã đơn</strong> (ví dụ: TP-250813-0001).
                    </p>

                    
                    <form action="<?php echo e(route('order.tracking')); ?>" method="GET" class="row g-2 mb-4">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="phone"
                                   placeholder="Nhập số điện thoại..."
                                   value="<?php echo e($phone_searched ?? ''); ?>">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="code"
                                   placeholder="Nhập mã đơn (bỏ dấu #)..."
                                   value="<?php echo e($code_searched ?? ''); ?>">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary" type="submit">Tra cứu</button>
                        </div>
                    </form>

                    <hr>

                    <?php
                        $hasQuery = (isset($phone_searched) && $phone_searched !== '') || (isset($code_searched) && $code_searched !== '');
                    ?>

                    <?php if($hasQuery): ?>
                        <?php if(isset($orders) && $orders->count() > 0): ?>
                            <?php if(isset($user) && ($phone_searched ?? '') !== ''): ?>
                                <p>Khách hàng: <strong><?php echo e($user->name); ?></strong> — SĐT: <strong><?php echo e($phone_searched); ?></strong></p>
                            <?php endif; ?>

                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $cid = 'order-'.$order->id; ?>
                                <div class="card mb-3">
                                    <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center">
                                        <div class="mb-2 mb-md-0">
                                            <span class="me-3">Mã đơn: <strong><?php echo e($order->code); ?></strong></span>
                                            <span class="me-3">
                                                Ngày lắp đặt: <?php echo e($order->installed_date_for_view); ?>

                                            </span>
                                            <span>Trạng thái: <span class="badge bg-success text-white"><?php echo e($order->status); ?></span></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo e(route('warranty.code.qr', $order->code)); ?>" alt="QR" width="48" height="48" class="mr-2">
                                            
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                type="button"
                                                data-toggle="collapse"
                                                data-target="#<?php echo e($cid); ?>"
                                                aria-expanded="false"
                                                aria-controls="<?php echo e($cid); ?>"
                                            >
                                                Xem chi tiết
                                            </button>
                                        </div>
                                    </div>

                                    <div id="<?php echo e($cid); ?>" class="collapse">
                                        <div class="card-body">
                                            <h6 class="mb-3">Sản phẩm trong đơn</h6>
                                            <ul class="list-group">
                                                <?php $__empty_1 = true; $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <li class="list-group-item">
                                                        <div class="fw-bold">
                                                            <?php echo e($item->product->name ?? $item->product_name); ?>

                                                            <span class="text-muted">× <?php echo e($item->quantity); ?></span>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <?php if($item->warranty_expires_at): ?>
                                                                Ngày hết hạn: <?php echo e($item->warranty_expires_at_for_view); ?>

                                                                (<?php echo e($item->warranty_remaining_text); ?>)
                                                            <?php else: ?>
                                                                Không áp dụng bảo hành
                                                            <?php endif; ?>
                                                            <?php if($item->warranty_months): ?>
                                                                — Thời hạn: <?php echo e($item->warranty_months); ?> tháng
                                                            <?php endif; ?>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <li class="list-group-item text-muted">Đơn hàng chưa có sản phẩm.</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="alert alert-danger text-center">
                                Không tìm thấy đơn hàng phù hợp với thông tin đã nhập.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/tracking/index.blade.php ENDPATH**/ ?>