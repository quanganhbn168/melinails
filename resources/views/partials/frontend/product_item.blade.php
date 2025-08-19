{{-- resources/views/partials/frontend/product_item.blade.php --}}
@php
    $hasDiscount = $product->price && $product->compare_at_price > $product->price;
    if ($hasDiscount) {
        $discountPercentage = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
    }
@endphp

<div class="item_product_main">
    {{-- Bắt chước cấu trúc của họ, dùng form bao ngoài --}}
    <form action="/cart/add" method="post" class="variants product-action">

        @if($hasDiscount)
            <span class="flash-sale">Giảm {{ $discountPercentage }}%</span>
        @endif

        <div class="product-thumbnail">
            <a class="image_thumb scale_hover" href="{{ route('frontend.product.show', $product->slug) }}" title="{{ $product->name }}">
                <img class="lazyload image1" 
                     src="{{ asset($product->image ?? 'images/setting/no-image.png') }}" 
                     alt="{{ $product->name }}">
            </a>
            
            {{-- Nút "Yêu thích" hiện ra khi hover --}}
            <div class="action-button">
                <a href="javascript:void(0)" class="setWishlist btn-views btn-circle" data-wish="{{ $product->slug }}" title="Thêm vào yêu thích">
                    <img width="20" height="20" style="width: auto;height: auto;" src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/heart.png?1734490427077" alt="Thêm vào yêu thích">
                </a>
            </div>
        </div>
        
        {{-- Phần Swatches (biến thể màu sắc/size) - tạm để trống --}}
        <div class="product-swatches"></div>

        <div class="product-info">
            <h3 class="product-name line-clamp-2-new">
                <a href="{{ route('frontend.product.show', $product->slug) }}" title="{{ $product->name }}">{{ $product->name }}</a>
            </h3>
        </div>

        <div class="product-bottom">
            <div class="product-price-cart">
                <div class="price-box">
                    @if($product->compare_at_price > 0)
                        @if($hasDiscount)
                            <span class="price">{{ number_format($product->price) }}₫</span>
                            <span class="compare-price">{{ number_format($product->compare_at_price) }}₫</span>
                        @else
                            <span class="price">{{ number_format($product->price) }}₫</span>
                        @endif
                    @else
                        <span class="price">Liên hệ</span>
                    @endif
                </div>
            </div>
            
            <div class="button-product">
                {{-- [QUY TẮC BẤT DI BẤT DỊCH] Nút của anh được tích hợp vào đây --}}
                {{-- Em thêm class của họ và giữ nguyên data-attributes của anh --}}
                <button class="btn-cart btn-views quick-view-option btn btn-add-to-cart" 
                        title="Tùy chọn" 
                        type="button" 
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $hasDiscount ? $product->price : $product->compare_at_price }}"
                        data-image="{{ asset($product->image ?? 'images/setting/no-image.png') }}"
                        data-slug="{{ $product->slug }}"
                        data-quantity="1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/>
                    </svg>
                    <span>Tùy chọn</span>
                </button>
            </div>
        </div>
    </form>
</div>