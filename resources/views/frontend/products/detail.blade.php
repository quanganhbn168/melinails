@extends('layouts.master')
@section('title', $product->name)
@push('css')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush
<x-schema.product :product="$product" />
@section('content')
    <section class="layout-product">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0 py-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('products.by_category', $product->category->slug) }}">{{ $product->category->name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 50) }}</li>
                </ol>
            </nav>
            <div class="details-product bg-white p-3 p-md-4">
                <div class="row">
                    <div class="col-lg-5 product-images">
                        <div class="product-image-block">
                            <div class="gallery-top">
                                <div class="swiper main-slider">
                                    <div class="swiper-wrapper">
                                        @forelse($product->images as $image)
                                            <div class="swiper-slide">
                                                <img src="{{ asset($image->image) }}" class="img-fluid"
                                                    alt="{{ $image->name }}">
                                            </div>
                                        @empty
                                            <div class="swiper-slide">
                                                <img src="{{ asset($product->image) }}" class="img-fluid"
                                                    alt="{{ $product->name }}">
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                            <div class="swiper gallery-thumbs">
                                <div class="swiper-wrapper">
                                    @forelse($product->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ asset($image->image) }}" class="img-fluid"
                                                alt="{{ $image->name }}">
                                        </div>
                                    @empty
                                        <div class="swiper-slide">
                                            <img src="{{ asset($product->image) }}" class="img-fluid"
                                                alt="{{ $product->name }}">
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="details-pro">
                            <h1 class="title-product">{{ $product->name }}</h1>
                            <div class="product-top">
                                <div class="sku-product">
                                    <span>Mã: <strong class="a-sku">{{ $product->code ?? 'N/A' }}</strong></span>
                                </div>
                            </div>
                            <div class="inventory_quantity">
                                <span>Tình trạng: <strong class="a-stock text-success">Còn hàng</strong></span>
                            </div>
                            <div class="price-box">
                                @php
                                $displayPrice = $product->price ?? 0;
                                $comparePrice = $product->compare_at_price ?? 0;
                                $hasDiscount = $comparePrice > $displayPrice;
                                @endphp
                                <span class="special-price">{{ number_format($displayPrice) }}đ</span>
                                @if ($hasDiscount)
                                <del class="old-price">{{ number_format($comparePrice) }}đ</del>
                                <span class="sale-off">
                                    -{{ round((($comparePrice - $displayPrice) / $comparePrice) * 100) }}%
                                </span>
                                @endif
                            </div>
                            @if ($product->has_variants && !empty($variantAttributes))
                            <div class="select-swatch">
                                @foreach ($variantAttributes as $attributeName => $values)
                                <div class="swatch">
                                    <div class="header">{{ $attributeName }}:</div>
                                    @foreach ($values as $id => $value)
                                    <div class="swatch-element">
                                        <input type="radio" id="variant_{{ $id }}"
                                        name="attribute_{{ Str::slug($attributeName) }}"
                                        value="{{ $id }}" class="variant-selector">
                                        <label for="variant_{{ $id }}">{{ $value }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            <div id="variant-error" class="text-danger my-2 d-none">
                                Rất tiếc, tùy chọn này hiện không có sẵn.
                            </div>
                            @endif
                            <div class="flex-quantity">
                                <span>Số lượng:</span>
                                <div class="input_number_product">
                                    <button type="button" class="btn_num" data-action="decrease">-</button>
                                    <input type="number" id="quantity" value="1" min="1" readonly>
                                    <button type="button" class="btn_num" data-action="increase">+</button>
                                </div>
                            </div>
                            <div class="button_actions">
                                <button id="add-to-cart-btn" class="btn btn-add-to-cart"
                                    {{ $product->has_variants && !empty($variantAttributes) ? 'disabled' : '' }}
                                    data-id="{{ $product->id }}"
                                    data-variant-id=""
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price_discount ?? $product->price }}"
                                    data-image="{{ asset($product->image) }}"
                                    data-slug="{{ $product->slug }}"
                                    data-quantity="1">
                                    Thêm vào giỏ
                                </button>
                                <button id="buy-now-btn" class="btn btn-buyNow">Mua ngay</button>
                            </div>
                            <form id="buy-now-form" class="d-none" method="POST" action="{{ route('cart.buy_now') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" value="">
                                <input type="hidden" name="quantity" value="1">
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3 product-col-right">
                        <div class="block-policy">
                            <div class="policy-item">
                                <div class="policy-icon"><i class="fa fa-check-circle fa-2x"></i></div>
                                <div class="info">
                                    <h3>Cam kết chuẩn chất lượng</h3>
                                </div>
                            </div>
                            <div class="policy-item">
                                <div class="policy-icon"><i class="fa fa-truck fa-2x"></i></div>
                                <div class="info">
                                    <h3>Giao hàng nhanh toàn quốc</h3>
                                </div>
                            </div>
                            <div class="policy-item">
                                <div class="policy-icon"><i class="fa fa-sync-alt fa-2x"></i></div>
                                <div class="info">
                                    <h3>Đổi trả hàng trong 7 ngày</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-review-details mt-4">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">
                            Mô tả sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab" aria-controls="specifications" aria-selected="false">
                            Thông số kỹ thuật
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">
                            Đánh giá
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="productTabContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        {!! $product->content ?: '<p>Nội dung đang được cập nhật...</p>' !!}
                    </div>
                    <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                        {!! $product->specifications ?: '<p>Thông số kỹ thuật đang được cập nhật...</p>' !!}
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <p>Tính năng đánh giá sản phẩm sẽ sớm được ra mắt.</p>
                    </div>
                </div>
            </div>
            @if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
                <div class="related-products mt-5">
                    <h3 class="section-title">Sản phẩm liên quan</h3>
                    <div class="row">
                        @foreach ($relatedProducts as $related)
                            <div class="col-6 col-md-4 col-lg-3">
                                @include('partials.frontend.product_item', ['product' => $related])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === KHÔI PHỤC SWIPER NGUYÊN BẢN CỦA ANH ===
    var thumbnailSlider = new Swiper(".gallery-thumbs", {
        spaceBetween: 10, slidesPerView: 4, freeMode: true, watchSlidesProgress: true,
    });
    var mainSlider = new Swiper(".main-slider", {
        spaceBetween: 10,
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        thumbs: { swiper: thumbnailSlider },
    });

    /**
     * Module quản lý logic trang chi tiết sản phẩm.
     */
    const productDetailManager = {
        data: {
            variants: @json($variantMap),
            hasVariants: {{ $product->has_variants && !empty($variantAttributes) ? 'true' : 'false' }},
        },
        elements: {
            quantityInput: null,
            addToCartBtn: null,
            priceContainer: null,
            skuContainer: null,
            stockContainer: null,
            variantError: null,
            buyNowForm: null,
            quantityButtons: [],
            variantSelectors: [],
        },
        state: {
            originalPriceHTML: '',
            originalSku: 'N/A',
            originalStockHTML: '',
        },

        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.initState();
        },

        cacheElements: function() {
            this.elements.quantityInput = document.getElementById('quantity');
            this.elements.addToCartBtn = document.getElementById('add-to-cart-btn');
            this.elements.priceContainer = document.querySelector('.price-box');
            this.elements.skuContainer = document.querySelector('.a-sku');
            this.elements.stockContainer = document.querySelector('.a-stock');
            this.elements.variantError = document.getElementById('variant-error');
            this.elements.buyNowForm = document.getElementById('buy-now-form');
            this.elements.quantityButtons = document.querySelectorAll('.btn_num');
            this.elements.variantSelectors = document.querySelectorAll('.variant-selector');
        },

        bindEvents: function() {
            this.elements.quantityButtons.forEach(btn => {
                btn.addEventListener('click', (e) => this.methods.handleQuantityChange(e.currentTarget.dataset.action));
            });
            this.elements.variantSelectors.forEach(selector => {
                selector.addEventListener('change', () => this.methods.updateVariantState());
            });
             // Gắn lại sự kiện cho nút Mua Ngay
            const buyNowBtn = document.getElementById('buy-now-btn');
            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', (e) => this.methods.handleBuyNow(e));
            }
        },

        initState: function() {
            if (this.elements.priceContainer) this.state.originalPriceHTML = this.elements.priceContainer.innerHTML;
            if (this.elements.skuContainer) this.state.originalSku = this.elements.skuContainer.textContent;
            if (this.elements.stockContainer) this.state.originalStockHTML = this.elements.stockContainer.innerHTML;
            if (this.data.hasVariants && this.elements.addToCartBtn) {
                this.elements.addToCartBtn.disabled = true;
            }
        },

        methods: {
            /**
             * Xử lý thay đổi số lượng.
             */
            handleQuantityChange: function(action) {
                const { quantityInput, addToCartBtn } = productDetailManager.elements;
                if (!quantityInput) return;

                let currentValue = parseInt(quantityInput.value, 10);
                if (action === 'increase') {
                    currentValue++;
                } else if (action === 'decrease' && currentValue > 1) {
                    currentValue--;
                }
                quantityInput.value = currentValue;

                // === SỬA LỖI SỐ LƯỢNG TẠI ĐÂY ===
                // Cập nhật lại thuộc tính data-quantity của nút "Thêm vào giỏ"
                // để script giỏ hàng của anh có thể đọc được số lượng mới.
                if (addToCartBtn) {
                    addToCartBtn.dataset.quantity = currentValue;
                }
            },

            /**
             * Cập nhật giao diện khi chọn thuộc tính.
             */
            updateVariantState: function() {
                const { elements, data, state, methods } = productDetailManager;
                if (!data.hasVariants) return;

                const selectedOptions = document.querySelectorAll('.variant-selector:checked');
                const attributeGroups = document.querySelectorAll('.swatch');
                
                const resetUI = () => {
                    elements.priceContainer.innerHTML = state.originalPriceHTML;
                    elements.skuContainer.textContent = state.originalSku;
                    elements.stockContainer.innerHTML = state.originalStockHTML;
                    elements.addToCartBtn.disabled = true;
                    elements.addToCartBtn.dataset.variantId = '';
                    if (elements.buyNowForm) elements.buyNowForm.querySelector('input[name="variant_id"]').value = '';
                };

                if (selectedOptions.length < attributeGroups.length) {
                    resetUI();
                    return;
                }

                const selectedKey = Array.from(selectedOptions).map(input => input.value).sort().join('-');
                const selectedVariant = data.variants[selectedKey];

                if (selectedVariant) {
                    elements.variantError.style.display = 'none';
                    let priceHTML = `<span class="special-price">${methods.formatCurrency(selectedVariant.price)}</span>`;
                    if (selectedVariant.compare_at_price > selectedVariant.price) {
                        const discount = Math.round(((selectedVariant.compare_at_price - selectedVariant.price) / selectedVariant.compare_at_price) * 100);
                        priceHTML += `<del class="old-price">${methods.formatCurrency(selectedVariant.compare_at_price)}</del>`;
                        priceHTML += `<span class="sale-off">-${discount}%</span>`;
                    }
                    elements.priceContainer.innerHTML = priceHTML;
                    elements.skuContainer.textContent = selectedVariant.sku || state.originalSku;

                    if (selectedVariant.stock > 0) {
                        elements.stockContainer.innerHTML = 'Còn hàng';
                        elements.stockContainer.className = 'a-stock text-success';
                        elements.addToCartBtn.disabled = false;
                    } else {
                        elements.stockContainer.innerHTML = 'Hết hàng';
                        elements.stockContainer.className = 'a-stock text-danger';
                        elements.addToCartBtn.disabled = true;
                    }
                    
                    elements.addToCartBtn.dataset.variantId = selectedVariant.id;
                    elements.addToCartBtn.dataset.price = selectedVariant.price;
                    if (elements.buyNowForm) elements.buyNowForm.querySelector('input[name="variant_id"]').value = selectedVariant.id;
                } else {
                    resetUI();
                    elements.variantError.style.display = 'block';
                }
            },
            
            /**
             * Xử lý sự kiện "Mua ngay".
             */
            handleBuyNow: function(e) {
                e.preventDefault();
                const { elements, data, methods } = productDetailManager;
                
                if (data.hasVariants && !methods.allVariantGroupsSelected()) {
                    alert('Vui lòng chọn đầy đủ tùy chọn sản phẩm.');
                    return;
                }
                
                const quantity = elements.quantityInput?.value || '1';
                const variantId = elements.addToCartBtn?.dataset.variantId || '';
                
                elements.buyNowForm.querySelector('input[name="product_id"]').value = data.productId;
                elements.buyNowForm.querySelector('input[name="variant_id"]').value = variantId;
                elements.buyNowForm.querySelector('input[name="quantity"]').value = quantity;
                
                elements.buyNowForm.submit();
            },

            // --- HÀM HỖ TRỢ ---
            formatCurrency: (number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number || 0).replace(/\s/g, ''),
            allVariantGroupsSelected: () => {
                const groups = document.querySelectorAll('.swatch');
                return !groups.length || document.querySelectorAll('.variant-selector:checked').length === groups.length;
            },
        }
    };

    productDetailManager.init();
});
</script>
@endpush