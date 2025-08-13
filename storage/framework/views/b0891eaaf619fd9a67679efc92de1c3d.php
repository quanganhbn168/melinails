<?php echo csrf_field(); ?>

<div class="row">
    <div class="col-md-4">
        <h5>1. Thông tin khách hàng</h5>
        <div class="mb-3">
            <label for="user_id" class="form-label">Chọn khách hàng (Thợ)</label>
            <select class="form-control" id="user_id" name="user_id">
                <option value="">-- Chọn khách hàng có sẵn --</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>" <?php if(old('user_id', $order->user_id ?? '') == $user->id): ?> selected <?php endif; ?>
                        data-name="<?php echo e($user->name); ?>" data-email="<?php echo e($user->email); ?>" data-phone="<?php echo e($user->phone); ?>" data-address="<?php echo e($user->address); ?>">
                        <?php echo e($user->name); ?> - <?php echo e($user->phone); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="form-text">Hoặc điền thông tin khách vãng lai ở dưới.</div>
        </div>
        <div id="guest-info">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Tên khách vãng lai</label>
                <input type="text" class="form-control" name="customer_name" id="customer_name" value="<?php echo e(old('customer_name', $order->customer_name ?? '')); ?>">
            </div>
            <div class="mb-3">
                <label for="customer_phone" class="form-label">SĐT khách vãng lai</label>
                <input type="text" class="form-control" name="customer_phone" id="customer_phone" value="<?php echo e(old('customer_phone', $order->customer_phone ?? '')); ?>">
            </div>
             <div class="mb-3">
                <label for="customer_email" class="form-label">Email khách vãng lai</label>
                <input type="email" class="form-control" name="customer_email" id="customer_email" value="<?php echo e(old('customer_email', $order->customer_email ?? '')); ?>">
            </div>
            <div class="mb-3">
                <label for="customer_address" class="form-label">Địa chỉ khách vãng lai</label>
                <input type="text" class="form-control" name="customer_address" id="customer_address" value="<?php echo e(old('customer_address', $order->customer_address ?? '')); ?>">
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <h5>2. Thông tin đơn hàng</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control form-control" id="status" name="status">
                    <option value="pending" <?php if(old('status', $order->status ?? 'pending') == 'pending'): ?> selected <?php endif; ?>>Chờ xử lý</option>
                    <option value="processing" <?php if(old('status', $order->status ?? '') == 'processing'): ?> selected <?php endif; ?>>Đang xử lý</option>
                    <option value="completed" <?php if(old('status', $order->status ?? '') == 'completed'): ?> selected <?php endif; ?>>Hoàn thành</option>
                    <option value="cancelled" <?php if(old('status', $order->status ?? '') == 'cancelled'): ?> selected <?php endif; ?>>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="technician_id" class="form-label">Gán thợ lắp đặt</label>
                <select class="form-control" id="technician_id" name="technician_id">
                    <option value="">-- Không gán --</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php if(old('technician_id', $order->technician_id ?? '') == $user->id): ?> selected <?php endif; ?>>
                            <?php echo e($user->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="note" class="form-label">Ghi chú</label>
            <textarea class="form-control" id="note" name="note" rows="4"><?php echo e(old('note', $order->note ?? '')); ?></textarea>
        </div>
    </div>
</div>
<hr>


<h5>3. Chi tiết sản phẩm</h5>
<div class="row align-items-end mb-3">
    <div class="col-md-6">
        <label for="product-selector" class="form-label">Chọn sản phẩm để thêm vào đơn</label>
        <select id="product-selector" class="form-control">
            <option value="">-- Chọn sản phẩm --</option>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php
                    $variantPrice = $product->variants->first()->price ?? $product->price_discount ?? $product->price;
                ?>
                <option value="<?php echo e($product->id); ?>" data-name="<?php echo e($product->name); ?>" data-price="<?php echo e($variantPrice); ?>">
                    <?php echo e($product->name); ?> (<?php echo e(number_format($variantPrice, 0, ',', '.')); ?> đ)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-success" id="add-item-btn">Thêm sản phẩm</button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Sản phẩm</th>
                <th style="width: 15%;">Thời gian BH</th>
                <th style="width: 15%;">Số lượng</th>
                <th style="width: 20%;">Đơn giá (Tùy chỉnh)</th>
                <th style="width: 20%;" class="text-end">Thành tiền</th>
                <th style="width: 5%;">Xóa</th>
            </tr>
        </thead>
        <tbody id="order-items-table">
            <?php if(isset($order) && $order->orderItems): ?>
                <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-product-id="<?php echo e($item->product_id); ?>">
                    <td>
                        <?php echo e($item->product_name); ?>

                        <input type="hidden" name="items[<?php echo e($index); ?>][product_id]" value="<?php echo e($item->product_id); ?>">
                        <input type="hidden" name="items[<?php echo e($index); ?>][product_name]" value="<?php echo e($item->product_name); ?>">
                    </td>
                    <td>
                        <select class="form-control item-warranty" name="items[<?php echo e($index); ?>][warranty_months]">
                            <option value="0" <?php if($item->warranty_months == 0): ?> selected <?php endif; ?>>Không có</option>
                            <option value="6" <?php if($item->warranty_months == 6): ?> selected <?php endif; ?>>6 tháng</option>
                            <option value="12" <?php if($item->warranty_months == 12): ?> selected <?php endif; ?>>1 năm</option>
                            <option value="24" <?php if($item->warranty_months == 24): ?> selected <?php endif; ?>>2 năm</option>
                            <option value="36" <?php if($item->warranty_months == 36): ?> selected <?php endif; ?>>3 năm</option>
                            <option value="60" <?php if($item->warranty_months == 60): ?> selected <?php endif; ?>>5 năm</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control item-quantity" name="items[<?php echo e($index); ?>][quantity]" value="<?php echo e($item->quantity); ?>" min="1"></td>
                    <td><input type="number" class="form-control item-price" name="items[<?php echo e($index); ?>][price]" value="<?php echo e($item->product_price); ?>" min="0"></td>
                    <td class="text-end item-subtotal"><?php echo e(number_format($item->subtotal, 0, ',', '.')); ?> đ</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item-btn"><i class="bi bi-trash"></i></button></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Tổng cộng:</th>
                <th class="text-end" id="grand-total"><?php echo e(number_format($order->total_price ?? 0, 0, ',', '.')); ?> đ</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary btn-lg"><?php echo e(isset($order) ? 'Cập nhật đơn hàng' : 'Tạo đơn hàng'); ?></button>
</div>



<?php $__env->startPush('js'); ?> 
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const guestInfoDiv = document.getElementById('guest-info');
    const addBtn = document.getElementById('add-item-btn');
    const productSelect = document.getElementById('product-selector');
    const itemsTableBody = document.getElementById('order-items-table');

    function toggleGuestInfo() {
        if (userSelect.value) {
            guestInfoDiv.style.display = 'none';
        } else {
            guestInfoDiv.style.display = 'block';
        }
    }

    userSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('customer_name').value = selectedOption.dataset.name;
            document.getElementById('customer_phone').value = selectedOption.dataset.phone;
            document.getElementById('customer_address').value = selectedOption.dataset.address;
            document.getElementById('customer_email').value = selectedOption.dataset.email;
        } else {
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_phone').value = '';
            document.getElementById('customer_address').value = '';
            document.getElementById('customer_email').value = '';
        }
        toggleGuestInfo();
    });

    addBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (!selectedOption.value) { return; }

        const productId = selectedOption.value;
        const productName = selectedOption.dataset.name;
        const productPrice = selectedOption.dataset.price;

        // KIỂM TRA SẢN PHẨM ĐÃ TỒN TẠI CHƯA
        const existingRow = itemsTableBody.querySelector(`tr[data-product-id="${productId}"]`);

        if (existingRow) {
            // NẾU ĐÃ TỒN TẠI -> TĂNG SỐ LƯỢNG
            const quantityInput = existingRow.querySelector('.item-quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
            // Kích hoạt sự kiện 'input' để tự động cập nhật lại thành tiền
            quantityInput.dispatchEvent(new Event('input'));
        } else {
            // NẾU CHƯA TỒN TẠI -> THÊM DÒNG MỚI
            const itemIndex = Date.now();
            const newRowHTML = `
                            <tr data-product-id="${productId}">
                                <td>
                                    ${productName}
                                    <input type="hidden" name="items[${itemIndex}][product_id]" value="${productId}">
                                    <input type="hidden" name="items[${itemIndex}][product_name]" value="${productName}">
                                </td>
                                <td>
                                    <select class="form-control item-warranty" name="items[${itemIndex}][warranty_months]">
                                        <option value="0">Không có</option>
                                        <option value="6">6 tháng</option>
                                        <option value="12" selected>1 năm</option>
                                        <option value="24">2 năm</option>
                                        <option value="36">3 năm</option>
                                        <option value="60">5 năm</option>
                                    </select>
                                </td>
                                <td><input type="number" class="form-control item-quantity" name="items[${itemIndex}][quantity]" value="1" min="1"></td>
                                <td><input type="number" class="form-control item-price" name="items[${itemIndex}][price]" value="${productPrice}" min="0"></td>
                                <td class="text-end item-subtotal">${formatCurrency(productPrice)}</td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-item-btn"><i class="bi bi-trash"></i></button></td>
                            </tr>
            `;
            itemsTableBody.insertAdjacentHTML('beforeend', newRowHTML);
        }
        
        productSelect.value = ''; // Reset ô chọn sản phẩm
        updateGrandTotal();
    });

    itemsTableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-quantity') || e.target.classList.contains('item-price')) {
            updateRowSubtotal(e.target.closest('tr'));
        }
    });

    itemsTableBody.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-item-btn');
        if (removeBtn) {
            removeBtn.closest('tr').remove();
            updateGrandTotal();
        }
    });

    function updateRowSubtotal(row) {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const subtotal = quantity * price;
        row.querySelector('.item-subtotal').textContent = formatCurrency(subtotal);
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('#order-items-table tr').forEach(row => {
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            grandTotal += quantity * price;
        });
        document.getElementById('grand-total').textContent = formatCurrency(grandTotal);
    }

    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    }

    // Initial setup
    toggleGuestInfo();
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/orders/_form.blade.php ENDPATH**/ ?>