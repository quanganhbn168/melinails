<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/product.css')); ?>">
<?php $__env->stopPush(); ?>

<?php if (isset($component)) { $__componentOriginale214a3a58030101f39558445753d359d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale214a3a58030101f39558445753d359d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema.product','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema.product'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale214a3a58030101f39558445753d359d)): ?>
<?php $attributes = $__attributesOriginale214a3a58030101f39558445753d359d; ?>
<?php unset($__attributesOriginale214a3a58030101f39558445753d359d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale214a3a58030101f39558445753d359d)): ?>
<?php $component = $__componentOriginale214a3a58030101f39558445753d359d; ?>
<?php unset($__componentOriginale214a3a58030101f39558445753d359d); ?>
<?php endif; ?>

<?php $__env->startSection("content"); ?>
<section id="product-detail-page" class="py-4">
    <div class="container">
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent px-0 py-2">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('products.by_category', $product->category->slug)); ?>"><?php echo e($product->category->name); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo e(Str::limit($product->name, 50)); ?></li>
            </ol>
        </nav>

        <div class="product-main-content bg-white p-3 p-md-4">
            <div class="row">
                
                <div class="col-lg-5">
                    <div class="product-gallery">
                        <div class="swiper main-slider mb-3">
                            <div class="swiper-wrapper">
                                <?php if($product->images->isNotEmpty()): ?>
                                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="swiper-slide"><img src="<?php echo e(asset($image->image)); ?>" class="img-fluid" alt="<?php echo e($image->name); ?>"></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="swiper-slide"><img src="<?php echo e(asset($product->image)); ?>" class="img-fluid" alt="<?php echo e($product->name); ?>"></div>
                                <?php endif; ?>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="swiper thumbnail-slider">
                            <div class="swiper-wrapper">
                                <?php if($product->images->isNotEmpty()): ?>
                                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="swiper-slide"><img src="<?php echo e(asset($image->image)); ?>" class="img-fluid" alt="<?php echo e($image->name); ?>"></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="swiper-slide"><img src="<?php echo e(asset($product->image)); ?>" class="img-fluid" alt="<?php echo e($product->name); ?>"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-lg-4">
                    <div class="product-info">
                        <h1 class="product-title"><?php echo e($product->name); ?></h1>
                        <div class="product-meta">
                            <span>Mã: <strong><?php echo e($product->code ?? 'Đang cập nhật'); ?></strong></span>
                            <span class="mx-2">|</span>
                            <span>Tình trạng: <strong class="text-success">Còn hàng</strong></span>
                        </div>

                        <div class="price-area">
                            <?php
                                $hasDiscount = $product->price_discount && $product->price > $product->price_discount;
                            ?>
                            <span class="price-new"><?php echo e(number_format($hasDiscount ? $product->price_discount : $product->price)); ?>đ</span>
                            <?php if($hasDiscount): ?>
                                <del class="price-old"><?php echo e(number_format($product->price)); ?>đ</del>
                                <span class="discount-badge">
                                    -<?php echo e(round((($product->price - $product->price_discount) / $product->price) * 100)); ?>%
                                </span>
                            <?php endif; ?>
                        </div>

                        
                        <?php
                            $displayableVariants = $product->variants->filter(fn($v) => $v->attributeValues->isNotEmpty());
                        ?>
                        <?php if($displayableVariants->isNotEmpty()): ?>
                            <div class="variants-container">
                                <?php $__currentLoopData = $displayableVariants->groupBy('attributeValues.first.attribute.name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attributeName => $variants): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="variant-group">
                                    <label class="variant-label"><?php echo e($attributeName); ?>:</label>
                                    <div class="variant-options">
                                        <?php $__currentLoopData = $variants->pluck('attributeValues.first')->unique('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attributeValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="variant-item">
                                                <input type="radio" id="variant_<?php echo e($attributeValue->id); ?>" name="attribute_<?php echo e(Str::slug($attributeName)); ?>" value="<?php echo e($attributeValue->id); ?>" class="variant-selector">
                                                <label for="variant_<?php echo e($attributeValue->id); ?>"><?php echo e($attributeValue->value); ?></label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                        
                        
                        <div class="quantity-area">
                            <label class="quantity-label">Số lượng:</label>
                            <div class="quantity-input">
                                <button type="button" class="quantity-btn" data-action="decrease">-</button>
                                <input type="number" id="quantity" value="1" min="1">
                                <button type="button" class="quantity-btn" data-action="increase">+</button>
                            </div>
                        </div>

                        
                        <div class="action-buttons">
                            <button id="add-to-cart-btn" class="btn btn-add-to-cart"
                                data-id="<?php echo e($product->id); ?>"
                                data-name="<?php echo e($product->name); ?>"
                                data-price="<?php echo e($product->price_discount ?? $product->price); ?>"
                                data-image="<?php echo e(asset($product->image)); ?>"
                                data-url="<?php echo e(route('frontend.product.show', $product->slug)); ?>"
                                data-quantity="1">
                                Thêm vào giỏ
                            </button>
                            <button id="buy-now-btn" class="btn btn-buy-now">Mua ngay</button>
                        </div>
                        <form id="buy-now-form" class="d-none" method="POST" action="<?php echo e(route('cart.buy_now')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                            <input type="hidden" name="variant_id" value="">
                            <input type="hidden" name="quantity" value="1">
                        </form>
                    </div>
                </div>

                
                <div class="col-lg-3">
                    <div class="policy-commitment">
                        <ul class="list-unstyled">
                            <li class="policy-item">
                                <div class="policy-icon"><i class="fa fa-check-circle"></i></div>
                                <div class="policy-text">
                                    <strong>Cam kết chuẩn chất lượng</strong>
                                    <span>Đảm bảo chất lượng như mô tả</span>
                                </div>
                            </li>
                            <li class="policy-item">
                                <div class="policy-icon"><i class="fa fa-truck"></i></div>
                                <div class="policy-text">
                                    <strong>Giao hàng nhanh toàn quốc</strong>
                                    <span>Vận chuyển an toàn, nhanh chóng</span>
                                </div>
                            </li>
                             <li class="policy-item">
                                <div class="policy-icon"><i class="fa fa-sync-alt"></i></div>
                                <div class="policy-text">
                                    <strong>Đổi trả hàng trong 7 ngày</strong>
                                    <span>Nếu có lỗi từ nhà sản xuất</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        


<div class="product-details-tabs mt-4">
    
    <ul class="nav nav-tabs" id="productTab" role="tablist">
        <li class="nav-item">
            
            <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Mô tả sản phẩm</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab" aria-controls="specifications" aria-selected="false">Thông số kỹ thuật</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Đánh giá</a>
        </li>
    </ul>

    
    <div class="tab-content bg-white p-3 p-md-4" id="productTabContent">
        
        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
            <?php if($product->content): ?>
                <?php echo $product->content; ?>

            <?php else: ?>
                <p>Nội dung đang được cập nhật...</p>
            <?php endif; ?>
        </div>
        
        <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
            <?php if($product->specifications): ?>
                <?php echo $product->specifications; ?>

            <?php else: ?>
                <p>Thông số kỹ thuật đang được cập nhật...</p>
            <?php endif; ?>
        </div>
        
        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
            <p>Tính năng đánh giá sản phẩm sẽ sớm được ra mắt.</p>
        </div>
    </div>
</div>


<?php if(isset($relatedProducts) && $relatedProducts->isNotEmpty()): ?>
<div class="related-products mt-5">
    <h3 class="section-title">Sản phẩm liên quan</h3>
    <div class="row">
        <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-md-4 col-lg-3">
            
            
            <?php echo $__env->make('partials.frontend.product_item',['product'=>$related], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>




    </div>
</section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // =================================================================
    // KHỞI TẠO SWIPER GALLERY
    // =================================================================
    var thumbnailSlider = new Swiper(".thumbnail-slider", {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var mainSlider = new Swiper(".main-slider", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: thumbnailSlider,
        },
    });

    // =================================================================
    // KHAI BÁO BIẾN CHUNG
    // =================================================================
    const quantityInput = document.getElementById('quantity');
    const quantityButtons = document.querySelectorAll('.quantity-btn');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const variantSelectors = document.querySelectorAll('.variant-selector');

    // =================================================================
    // LOGIC XỬ LÝ SỐ LƯỢNG (ĐÃ TỐI ƯU)
    // =================================================================
    // Hàm cập nhật data-quantity trên nút "Thêm vào giỏ"
        // ============================
    // NÚT MUA NGAY
    // ============================
    const buyNowBtn  = document.getElementById('buy-now-btn');
    const buyNowForm = document.getElementById('buy-now-form');

    function allVariantGroupsSelected() {
        const groups = document.querySelectorAll('.variant-group');
        if (!groups.length) return true;
        const selected = document.querySelectorAll('.variant-selector:checked');
        return selected.length === groups.length;
    }

    if (buyNowBtn && buyNowForm) {
        buyNowBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Yêu cầu chọn đủ biến thể (nếu có)
            if (!allVariantGroupsSelected()) {
                alert('Vui lòng chọn đầy đủ tùy chọn sản phẩm.');
                return;
            }

            // Lấy ID đang gắn trên nút add-to-cart:
            // - Nếu có biến thể và đã chọn, anh đang set dataset.id = variant_id trong logic phía trên
            // - Nếu không có biến thể, dataset.id = product_id
            const chosenId = addToCartBtn ? addToCartBtn.dataset.id : "<?php echo e($product->id); ?>";
            const baseProductId = "<?php echo e($product->id); ?>";
            const qty = Math.max(1, parseInt(quantityInput?.value || '1', 10));

            // Gán vào form ẩn rồi submit
            buyNowForm.querySelector('input[name="product_id"]').value = baseProductId;
            buyNowForm.querySelector('input[name="variant_id"]').value = (chosenId !== baseProductId) ? chosenId : '';
            buyNowForm.querySelector('input[name="quantity"]').value   = qty;

            buyNowForm.submit();
        });
    }

    function updateCartButtonQuantity() {
        if (addToCartBtn && quantityInput) {
            const newQuantity = parseInt(quantityInput.value, 10);
            if (newQuantity > 0) {
                addToCartBtn.dataset.quantity = newQuantity;
            }
        }
    }
    
    // Lắng nghe sự kiện click trên nút +/-
    quantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            // 1. Thay đổi giá trị trong ô input
            const action = this.dataset.action;
            let currentValue = parseInt(quantityInput.value, 10);
            if (action === 'increase') {
                quantityInput.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
            // 2. Cập nhật ngay lập tức data-quantity của nút cart
            updateCartButtonQuantity();
        });
    });

    // Lắng nghe sự kiện khi người dùng tự nhập số lượng
    if (quantityInput) {
        quantityInput.addEventListener('change', updateCartButtonQuantity);
    }
    

    // =================================================================
    // LOGIC XỬ LÝ BIẾN THỂ (GIỮ NGUYÊN)
    // =================================================================
    if (variantSelectors.length > 0) {
        const variants = <?php echo json_encode($product->variants->filter(fn($v) => $v->attributeValues->isNotEmpty())->mapWithKeys(fn($v) => [$v->attributeValues->pluck('id')->sort()->implode('-') => ['id' => $v->id, 'price' => $v->price, 'price_discount' => $v->price_discount]])) ?>;
        const priceContainer = document.querySelector('.price-area');
        const originalPriceHTML = priceContainer.innerHTML;
        const variantError = document.getElementById('variant-error');

        variantSelectors.forEach(selector => selector.addEventListener('change', updateProductDetails));

        function updateProductDetails() {
            const selectedOptions = document.querySelectorAll('.variant-selector:checked');
            const totalOptionGroups = document.querySelectorAll('.variant-group').length;

            if (selectedOptions.length < totalOptionGroups) {
                if(addToCartBtn) addToCartBtn.disabled = true;
                if(variantError) variantError.classList.remove('d-none');
                return;
            }

            if(addToCartBtn) addToCartBtn.disabled = false;
            if(variantError) variantError.classList.add('d-none');

            const selectedIds = Array.from(selectedOptions).map(input => input.value).sort().join('-');
            const selectedVariant = variants[selectedIds];
            
            if (selectedVariant) {
                let newPriceHTML = '';
                const hasDiscount = selectedVariant.price_discount && selectedVariant.price > selectedVariant.price_discount;
                newPriceHTML += `<span class="price-new">${Number(hasDiscount ? selectedVariant.price_discount : selectedVariant.price).toLocaleString('vi-VN')}đ</span>`;
                if(hasDiscount) {
                    newPriceHTML += `<del class="price-old">${Number(selectedVariant.price).toLocaleString('vi-VN')}đ</del>`;
                    newPriceHTML += `<span class="discount-badge">-${Math.round(((selectedVariant.price - selectedVariant.price_discount) / selectedVariant.price) * 100)}%</span>`;
                }
                priceContainer.innerHTML = newPriceHTML;

                if(addToCartBtn) {
                    addToCartBtn.dataset.id = selectedVariant.id;
                    addToCartBtn.dataset.price = selectedVariant.price_discount ?? selectedVariant.price;
                }
            } else {
                priceContainer.innerHTML = originalPriceHTML;
                if(addToCartBtn) addToCartBtn.disabled = true;
            }
        }
        
        if(addToCartBtn) addToCartBtn.disabled = true;
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/products/detail.blade.php ENDPATH**/ ?>