
<?php $__env->startSection('title', 'Đặt hàng thành công'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5 text-center">
    <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
    <h2>Đặt hàng thành công!</h2>
    <p>Cảm ơn bạn đã mua hàng. Mã đơn hàng của bạn là <strong>#<?php echo e($order->id); ?></strong>.</p>
    <p>Chúng tôi sẽ liên hệ với bạn để xác nhận đơn hàng trong thời gian sớm nhất.</p>

    
    <?php if($order->payment_method == 'bank_transfer'): ?>
        <div class="mt-5 p-4 border rounded" style="max-width: 450px; margin: auto;">
            <?php
                $bankId = "970436"; // Ví dụ: Vietcombank
                $accountNo = "YOUR_ACCOUNT_NO"; // SỐ TÀI KHOẢN CỦA BẠN
                $accountName = "YOUR_ACCOUNT_NAME"; // TÊN CHỦ TK CỦA BẠN
                $amount = $order->total_price;
                $note = "NLMT " . $order->id; // Nội dung chuyển khoản ngắn gọn
                $qrCodeUrl = "https://api.vietqr.io/image/{$bankId}-{$accountNo}-print.png?amount={$amount}&addInfo=" . urlencode($note) . "&accountName=" . urlencode($accountName);
            ?>
            <h4>Quét mã QR để thanh toán</h4>
            <p class="mb-1"><strong>Nội dung:</strong> <span class="text-danger"><?php echo e($note); ?></span></p>
            <p><strong>Số tiền:</strong> <span class="text-danger"><?php echo e(number_format($amount)); ?>đ</span></p>
            <img src="<?php echo e($qrCodeUrl); ?>" alt="Mã QR thanh toán" class="img-fluid">
        </div>
    <?php endif; ?>

    <a href="/" class="btn btn-primary mt-4">Tiếp tục mua sắm</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/checkout/success.blade.php ENDPATH**/ ?>