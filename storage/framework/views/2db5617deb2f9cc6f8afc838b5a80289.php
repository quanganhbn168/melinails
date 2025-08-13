

<?php $__env->startSection('title', 'Chi tiết Đơn hàng #' . $order->code); ?>

<?php $__env->startPush('css'); ?>
<style>
    /* Phiếu xuất kho gọn gàng khi in */
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table th, .table td { padding: 6px 8px !important; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
    .order-header {
        display: flex; justify-content: space-between; align-items: center; gap: 16px;
    }
    .qr-box { text-align: center; }
    .qr-img { width: 128px; height: 128px; border: 1px solid #e5e7eb; border-radius: 8px; }
    .qr-code-text { font-weight: 600; margin-top: 6px; font-size: .95rem; }
    .stat-badges .badge { font-size: .85rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center no-print">
        <h5 class="card-title mb-0">Chi tiết Đơn hàng #<?php echo e($order->code); ?></h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">In phiếu</button>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-print-qr">In QR</button>
            <a href="<?php echo e(route('admin.orders.edit', $order)); ?>" class="btn btn-warning btn-sm">Sửa đơn hàng</a>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-secondary btn-sm">Quay lại</a>
        </div>
    </div>

    <div class="card-body">
        
        <div class="order-header mb-3">
            <div style="flex:1">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-2">
                        <h5 class="mb-1">ĐƠN HÀNG #<?php echo e($order->code); ?></h5>
                        <div class="stat-badges">
                            <span class="badge bg-success"><?php echo e(ucfirst($order->status)); ?></span>
                        </div>
                    </div>
                    <div class="text-end mb-2">
                        <div><strong>Ngày tạo:</strong> <?php echo e($order->created_at->format('d/m/Y H:i')); ?></div>
                        <div>
                            <strong>Ngày lắp đặt:</strong>
                            <?php echo e(optional($order->installation_date ? \Carbon\Carbon::parse($order->installation_date) : $order->created_at)->format('d/m/Y')); ?>

                        </div>
                        <div><strong>Thợ lắp đặt:</strong> <?php echo e($order->technician->name ?? 'Không có'); ?></div>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-2">Thông tin Khách hàng</h6>
                        <div>
                            <div><strong>Tên:</strong> <?php echo e($order->customer_name ?? ($order->user->name ?? '—')); ?></div>
                            <div><strong>Email:</strong> <?php echo e($order->customer_email ?? ($order->user->email ?? '—')); ?></div>
                            <div><strong>Điện thoại:</strong> <?php echo e($order->customer_phone ?? ($order->user->phone ?? '—')); ?></div>
                            <div><strong>Địa chỉ:</strong> <?php echo e($order->customer_address ?? ($order->user->address ?? '—')); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Thông tin Đơn hàng</h6>
                        <div>
                            <div><strong>Trạng thái:</strong> <?php echo e(ucfirst($order->status)); ?></div>
                            <div><strong>Phương thức:</strong> <?php echo e($order->payment_method ?? '—'); ?></div>
                            <div><strong>Tổng tiền:</strong> <?php echo e(number_format($order->total_price, 0, ',', '.')); ?> đ</div>
                        </div>
                    </div>
                    <?php if($order->note): ?>
                        <div class="col-12 mt-3">
                            <h6 class="mb-2">Ghi chú của Admin</h6>
                            <div class="border p-2 rounded bg-light"><?php echo e($order->note); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="qr-box">
                <img class="qr-img" src="<?php echo e(route('warranty.code.qr', $order->code)); ?>" alt="QR Mã đơn <?php echo e($order->code); ?>">
                <div class="qr-code-text">#<?php echo e($order->code); ?></div>
                <div class="small text-muted">Quét để tra cứu bảo hành</div>
                <div class="no-print mt-2">
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" id="btn-print-qr-2">In QR</button>
                </div>
            </div>
        </div>

        <hr>

        
        <h6>Chi tiết Sản phẩm</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="min-width:240px">Sản phẩm</th>
                        <th class="text-end" style="width:120px">Đơn giá</th>
                        <th class="text-center" style="width:90px">SL</th>
                        <th class="text-end" style="width:140px">Thành tiền</th>
                        <th class="text-center" style="width:120px">Bảo hành</th>
                        <th class="text-center" style="width:160px">Hạn bảo hành</th>
                        <th class="text-center" style="width:180px">Còn/ Hết hạn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($item->product_name ?? ($item->product->name ?? '—')); ?></div>
                            </td>
                            <td class="text-end"><?php echo e(number_format($item->product_price, 0, ',', '.')); ?> đ</td>
                            <td class="text-center"><?php echo e($item->quantity); ?></td>
                            <td class="text-end"><?php echo e(number_format($item->subtotal, 0, ',', '.')); ?> đ</td>
                            <td class="text-center">
                                <?php if($item->warranty_months): ?>
                                    <?php echo e($item->warranty_months); ?> tháng
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($item->warranty_expires_at): ?>
                                    <?php echo e(\Carbon\Carbon::parse($item->warranty_expires_at)->format('d/m/Y')); ?>

                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($item->warranty_expires_at): ?>
                                    <?php echo e($item->warranty_remaining_text); ?>

                                <?php else: ?>
                                    <span class="text-muted">Không áp dụng</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Đơn hàng chưa có sản phẩm.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end fs-6">Tổng cộng:</th>
                        <th class="text-end fs-6"><?php echo e(number_format($order->total_price, 0, ',', '.')); ?> đ</th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
(function () {
    function openAndPrintQr(url, code) {
        const w = window.open('', '_blank', 'width=400,height=460');
        const html = `
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>QR ${code}</title>
<style>
  body { margin:0; display:flex; align-items:center; justify-content:center; height:100vh; }
  .wrap { text-align:center; }
  img { width:320px; height:320px; display:block; margin:0 auto; border:1px solid #e5e7eb; border-radius:8px; }
  .code { margin-top:10px; font:600 16px/1.4 system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
  @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
</style>
</head>
<body>
  <div class="wrap">
    <img src="${url}" alt="QR ${code}">
    <div class="code">#${code}</div>
  </div>
  <script>window.onload=function(){ setTimeout(function(){ window.print(); }, 200); }<\/script>
</body>
</html>`;
        w.document.write(html);
        w.document.close();
        w.focus();
    }

    const qrUrl = <?php echo json_encode(route('warranty.code.qr', $order->code), 512) ?>;
    const code  = <?php echo json_encode($order->code, 15, 512) ?>;

    document.getElementById('btn-print-qr')?.addEventListener('click', function() {
        openAndPrintQr(qrUrl, code);
    });
    document.getElementById('btn-print-qr-2')?.addEventListener('click', function() {
        openAndPrintQr(qrUrl, code);
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>