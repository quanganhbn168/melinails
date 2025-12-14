<header class="header">
    <div class="main-header">
        <div class="container-fluid">
            <div class="main-header-inner">
                <div class="header-col-left">
                    <div class="mobile-menu-toggle d-lg-none">
                        <a href="#" aria-label="Toggle Menu"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="logo d-none d-lg-block">
                        <a href="{{ url('/') }}">
                            <img src="{{asset($setting->logo)}}" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="header-col-center">
                    <div class="logo d-lg-none">
                        <a href="{{ url('/') }}">
                            <img src="{{asset($setting->logo)}}" alt="Logo">
                        </a>
                    </div>
                    {{-- Trong file header.blade.php --}}
                    <ul class="main-menu-desktop d-none d-lg-flex" id="main-menu-desktop-source">
    @foreach($headerMenu as $menuItem)
        <li class="{{ $menuItem->children->count() > 0 ? 'menu-item-has-children' : '' }}">
            
            {{-- CHỖ NÀY DÙNG LOGIC MODEL ĐỂ LẤY LINK CHUẨN --}}
            <a href="{{ $menuItem->link }}">
                {{ $menuItem->title }}
            </a>

            {{-- MENU CẤP 2 --}}
            @if($menuItem->children->count() > 0)
                <span class="submenu-toggle"><i class="fa fa-angle-down"></i></span>
                <ul class="sub-menu">
                    @foreach($menuItem->children as $childItem)
                        <li class="{{ $childItem->children->count() > 0 ? 'menu-item-has-children' : '' }}">
                            <a href="{{ $childItem->link }}">{{ $childItem->title }}</a>

                            {{-- MENU CẤP 3 --}}
                            @if($childItem->children->count() > 0)
                                <span class="submenu-toggle"><i class="fa fa-angle-right"></i></span>
                                <ul class="sub-menu">
                                    @foreach($childItem->children as $grandChild)
                                        <li><a href="{{ $grandChild->link }}">{{ $grandChild->title }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
                </div>
                <div class="header-col-right">
                    <div class="d-lg-none">
                        {{-- Custom Dropdown linked to GTranslate --}}
                        <div class="dropdown">
                            <button class="btn btn-light rounded-circle shadow-sm dropdown-toggle p-0 d-flex align-items-center justify-content-center" type="button" data-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px; border: 1px solid #eee;">
                                <img src="{{ asset('images/flags/vn.png') }}" class="current-flag-img rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" style="min-width: 180px; border-radius: 16px; padding: 8px; margin-top: 10px;">
                                <a class="dropdown-item d-flex align-items-center px-3 py-2 rounded mb-1" href="#" onclick="doGTranslate('vi|vi'); updateFlag('{{ asset('images/flags/vn.png') }}'); return false;" style="transition: all 0.2s;">
                                    <img src="{{ asset('images/flags/vn.png') }}" class="me-3 mr-3 rounded-circle shadow-sm" style="width: 24px; height: 24px; object-fit: cover;"> 
                                    <span style="font-weight: 600; font-size: 14px; color: #333;">Tiếng Việt</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center px-3 py-2 rounded mb-1" href="#" onclick="doGTranslate('vi|en'); updateFlag('{{ asset('images/flags/us.png') }}'); return false;" style="transition: all 0.2s;">
                                    <img src="{{ asset('images/flags/us.png') }}" class="me-3 mr-3 rounded-circle shadow-sm" style="width: 24px; height: 24px; object-fit: cover;"> 
                                    <span style="font-weight: 600; font-size: 14px; color: #333;">English</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center px-3 py-2 rounded mb-1" href="#" onclick="doGTranslate('vi|zh-CN'); updateFlag('{{ asset('images/flags/cn.png') }}'); return false;" style="transition: all 0.2s;">
                                    <img src="{{ asset('images/flags/cn.png') }}" class="me-3 mr-3 rounded-circle shadow-sm" style="width: 24px; height: 24px; object-fit: cover;"> 
                                    <span style="font-weight: 600; font-size: 14px; color: #333;">中文 (Chinese)</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center px-3 py-2 rounded" href="#" onclick="doGTranslate('vi|ko'); updateFlag('{{ asset('images/flags/kr.png') }}'); return false;" style="transition: all 0.2s;">
                                    <img src="{{ asset('images/flags/kr.png') }}" class="me-3 mr-3 rounded-circle shadow-sm" style="width: 24px; height: 24px; object-fit: cover;"> 
                                    <span style="font-weight: 600; font-size: 14px; color: #333;">한국어 (Korean)</span>
                                </a>
                            </div>
                        </div>
                        {{-- Hidden GTranslate Widget --}}
                        <div class="gtranslate_wrapper" style="display:none;"></div>
                    </div>
                    <div class="d-none d-lg-block">
                        <button class="btn btn-primary rounded-pill">
                            <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
        
        {{-- Icon nằm bên trong, có màu của nút --}}
        <i class="fa-solid fa-phone text-primary"></i>

    </span>
                            <a href="tel:{{$setting->phone}}">
                                {{$setting->phone}}
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- HIDDEN: Old Mobile Search --}}
    <div class="mobile-search-container d-lg-none d-none">
        <div class="search-box">
            <form action="/search" method="get">
                <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    <nav class="main-nav-container d-none"></nav>
</header>
<div class="offcanvas-menu-wrapper">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">MENU</h5>
        <a href="#" class="offcanvas-close"><i class="fa fa-times"></i></a>
    </div>
    <div class="offcanvas-menu-content">
        {{-- ADDED: Mobile Search in Offcanvas (Styled) --}}
        <div class="offcanvas-search p-3 border-bottom">
            <form action="/search" method="get">
                <div class="position-relative">
                    <input type="text" class="form-control rounded-pill bg-light border-0 pl-4 pr-5" name="keyword" placeholder="Tìm kiếm sản phẩm..." style="height: 45px; padding-left: 20px; font-size: 14px;">
                    <button class="btn position-absolute text-muted" type="submit" style="right: 5px; top: 2px; height: 40px; border: none; background: transparent;">
                        <i class="fa fa-search" style="font-size: 18px;"></i>
                    </button>
                </div>
            </form>
        </div>
        {{-- Menu items will be appended here by JS --}}
        </div>
</div>
<div class="cart-offcanvas-wrapper">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Giỏ Hàng Của Bạn</h5>
        <a href="#" class="offcanvas-close js-close-cart"><i class="fa fa-times"></i></a>
    </div>
    @auth('web')
        <div class="offcanvas-body">
            @forelse($cartItems as $item)
            <div class="cart-item cart-item-auth">
                <div class="cart-item_image">
                    <img src="{{ asset($item->product->image ?? 'https://placehold.co/100x100') }}" alt="{{ $item->product->name }}">
                </div>
                <div class="cart-item_info">
                    <a href="{{ route('frontend.product.show', $item->product->slug) }}" class="item-name">{{ $item->product->name }}</a>
                    <div class="item-meta">
                        <span class="item-price">{{ number_format($item->product->price) }}đ</span>
                        <span class="item-quantity">x {{ $item->quantity }}</span>
                    </div>
                </div>
                <a href="#" class="item-remove" title="Xóa sản phẩm" data-item-id="{{ $item->id }}">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
            @empty
            <p class="text-center p-4">Giỏ hàng của bạn đang trống.</p>
            @endforelse
        </div>
        <div class="offcanvas-footer">
            <div class="cart-summary">
                <span>Tổng cộng:</span>
                <span class="total-price">{{ number_format($cartTotal ?? 0) }}đ</span>
            </div>
            <a href="/cart" class="btn btn-dark w-100">Xem Giỏ Hàng</a>
            <a href="/checkout" class="btn btn-primary w-100 mt-2">Thanh Toán</a>
        </div>
    @else
        <div id="guest-cart-body" class="offcanvas-body">
            <p class="text-center p-4">Giỏ hàng của bạn đang trống.</p>
        </div>
        <div id="guest-cart-footer" class="offcanvas-footer" style="display: none;">
            <div class="cart-summary">
                <span>Tổng cộng:</span>
                <span id="guest-cart-total" class="total-price">0đ</span>
            </div>
            <a href="/cart" class="btn btn-dark w-100">Xem Giỏ Hàng</a>
            <a href="/checkout" class="btn bg-main w-100 mt-2">Thanh Toán</a>
        </div>
    @endauth   
</div>
<template id="guest-cart-item-template">
    <div class="cart-item">
        <div class="cart-item_image">
            <img src="__IMAGE__" alt="__NAME__">
        </div>
        <div class="cart-item_info">
            <a href="__URL__" class="item-name">__NAME__</a>
            <div class="item-variant text-muted small">__VARIANT__</div>
            <div class="item-meta">
                <span class="item-price">__PRICE__đ</span>
                <span class="item-quantity">x __QUANTITY__</span>
            </div>
        </div>
        <a href="#" class="item-remove" title="Xóa sản phẩm" data-item-id="__ID__">
            <i class="fa fa-trash"></i>
        </a>
    </div>
</template>
<div class="offcanvas-overlay"></div>
@push('js')
<script>
    $(document).ready(function() {
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            const scrollPosition = window.scrollY;
            if (scrollPosition > 50) { 
                header.classList.add('header-scrolled');
                header.classList.remove('is-unsticking'); 
            } else {
                if (header.classList.contains('header-scrolled')) {
                    header.classList.remove('header-scrolled');
                    header.classList.add('is-unsticking');
                    setTimeout(function() {
                        header.classList.remove('is-unsticking');
                    }, 20);
                }
            }
        });
        if ($('.offcanvas-menu-content #main-menu-desktop-source').length === 0) {
    // Chỉ clone menu gốc có ID là "main-menu-desktop-source"
                $('#main-menu-desktop-source').clone()
            .removeAttr('id') // Xóa ID để tránh bị trùng lặp
            .removeClass('d-none d-lg-flex') // Xóa class ẩn/hiện của desktop
            .appendTo('.offcanvas-menu-content');
        }
        $('.mobile-menu-toggle a').on('click', function(e) {
            e.preventDefault();
            $('body').addClass('show-offcanvas');
        });
        $('.offcanvas-menu-content').on('click', '.submenu-toggle', function(e) {
            e.preventDefault();
            $(this).parent('.menu-item-has-children').toggleClass('open');
            $(this).siblings('.sub-menu').slideToggle(300);
        });
        $('.cart-action > a').on('click', function(e) {
            e.preventDefault(); 
            $('body').addClass('show-cart-offcanvas');
        });
        $('.offcanvas-menu-wrapper .offcanvas-close').on('click', function(e) {
            e.preventDefault();
            $('body').removeClass('show-offcanvas');
        });
        $('.cart-offcanvas-wrapper .js-close-cart').on('click', function(e) {
            e.preventDefault();
            $('body').removeClass('show-cart-offcanvas');
        });
        $('.offcanvas-overlay').on('click', function(e) {
            e.preventDefault();
            $('body').removeClass('show-offcanvas show-cart-offcanvas');
        });
    });
    $('.header-actions .frame-fix').on('click', function(event) {
    // Ngăn sự kiện click lan ra ngoài, tránh việc tự đóng ngay lập tức
        event.stopPropagation(); 
    // Thêm/xóa class 'active' trên chính nó để bật/tắt menu
        $(this).toggleClass('active'); 
    });
// Bấm ra ngoài khu vực menu thì sẽ đóng menu lại
    $(document).on('click', function() {
        $('.header-actions .frame-fix').removeClass('active');
    });
    function updateFlag(flagSrc) {
        $('.current-flag-img').attr('src', flagSrc);
    }
    // Set active flag on load based on GTranslate cookie
    function getCookie(name) {
        var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
    }
    var langCookie = getCookie('googtrans'); // Format: /vi/en
    if (langCookie) {
        var lang = langCookie.split('|').pop().split('/').pop(); 
        // Logic might need adjustment based on exact cookie value from GTranslate Widget
        // Usually /vi/en
        var flag = '{{ asset('images/flags/vn.png') }}';
        if (lang == 'en') flag = '{{ asset('images/flags/us.png') }}';
        else if (lang == 'zh-CN') flag = '{{ asset('images/flags/cn.png') }}';
        else if (lang == 'ko') flag = '{{ asset('images/flags/kr.png') }}';
        $('.current-flag-img').attr('src', flag);
    }
</script>
@endpush