<div class="product_item">
    <div class="product_item-img">
        <a href="{{ route('frontend.slug.handle', $product->slugValue) }}">
            <img class="main-image" src="{{ !empty($product->image) ? asset($product->image) : (optional($product->mainImage())->url() ?: asset('images/setting/no-image.png')) }}" alt="{{ $product->name }}">
        </a>
    </div>
    <div class="product_item-name">
        <a href="{{ route('frontend.slug.handle', $product->slugValue) }}">
            {{ $product->name }}
        </a>
    </div>
    <div class="product_item-price">
        @if($product->price > 0)
            <p>Giá: <span class="text-danger text-bold">{{ number_format($product->price) }}₫</span></p>
        @else
            <p>Giá: Liên hệ</p>
        @endif
        <div class="product_item-arrow">
            <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.5 11L6.5 6L1.5 1" stroke="#00427A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>
</div>