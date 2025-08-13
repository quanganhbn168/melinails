<?php
    // Logic tính toán giảm giá, đưa vào đây để tái sử dụng
    $hasDiscount = $product->price_discount && $product->price > $product->price_discount;
    if ($hasDiscount) {
        $discountPercentage = round((($product->price - $product->price_discount) / $product->price) * 100);
    }
?>

<div class="product-card">
    <div class="product-card__image-wrapper">
        <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>">
            <img src="<?php echo e(asset($product->image ?? 'images/setting/no-image.png')); ?>" class="product-card__image" alt="<?php echo e($product->name); ?>">
        </a>

        <?php if($hasDiscount): ?>
            <div class="product-card__discount-badge">-<?php echo e($discountPercentage); ?>%</div>
        <?php endif; ?>
        
        <div class="product-card__actions">
            <button class="action-btn btn-add-to-cart"
                    data-id="<?php echo e($product->id); ?>"
                    data-name="<?php echo e($product->name); ?>"
                    data-price="<?php echo e($hasDiscount ? $product->price_discount : $product->price); ?>"
                    data-image="<?php echo e(asset($product->image ?? 'images/setting/no-image.png')); ?>"
                    data-slug="<?php echo e($product->slug); ?>" 
                    data-quantity="1"
                    title="Thêm vào giỏ hàng">
                <i class="fa fa-cart-plus"></i>
            </button>
            <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="action-btn" title="Xem chi tiết">
                <i class="fa fa-eye"></i>
            </a>
        </div>
    </div>
    <div class="product-card__body">
        <h3 class="product-card__title">
            <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" title="<?php echo e($product->name); ?>"><?php echo e($product->name); ?></a>
        </h3>
        <div class="product-card__price">
            <?php if($product->price > 0): ?>
                <?php if($hasDiscount): ?>
                    <span class="price-new"><?php echo e(number_format($product->price_discount)); ?>đ</span>
                    <del class="price-old"><?php echo e(number_format($product->price)); ?>đ</del>
                <?php else: ?>
                    <span class="price-new"><?php echo e(number_format($product->price)); ?>đ</span>
                <?php endif; ?>
            <?php else: ?>
                <span class="price-contact">Liên hệ</span>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/partials/frontend/product_item.blade.php ENDPATH**/ ?>