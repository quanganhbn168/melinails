@extends('layouts.master')
@section('title','Trang chủ - '.$setting->name)
@section('meta_description',$setting->meta_description)
@section('meta_keywords',$setting->meta_keywords)
@push('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Store",
  "name": "{{$setting->name}}",
  "alternateName": "{{$setting->name}}",
  "url": "{{ url()->current() }}",
  "logo": "{{asset($setting->logo)}}",
  "description": "{{$setting->meta_description}}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{$setting->address}}",
    "addressLocality": "Thành phố Bắc Ninh",
    "addressRegion": "Bắc Ninh",
    "postalCode": "220000",
    "addressCountry": "VN"
  },
  "telephone": "{{$setting->phone}}",
  "email": "{{$setting->email}}",
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "opens": "08:00",
      "closes": "17:30"
    },
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": "Saturday",
      "opens": "08:00",
      "closes": "12:00"
    }
  ],
  "sameAs": [
    "{{$setting->facebook}}",
    "{{$setting->youtube}}",
    "{{$setting->zalo}}"
  ]
}
</script>
@endpush
@push('css')
<link rel="stylesheet" href="{{asset('vendor/glightbox/css/glightbox.min.css')}}?{{time()}}">
@endpush
@section("content")
<section id="slider">
    @include("partials.frontend.slide")
</section>
<section class="section-index section_category">
    <div class="container">
        <div class="section-title side-left has-control">
            <h2>Sản phẩm bạn đang tìm kiếm</h2>
            <div class="slider-controls">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <div class="swiper_category swiper">
            <div class="swiper-wrapper">
                
                <div class="swiper-slide">
                    <a href="/quan-ao-bao-ho" title="Quần áo bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_1.png?1734490427077" alt="Quần áo bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Quần áo bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">32 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/ao-gile-phan-quang" title="Áo gile/áo lưới" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_2.png?1734490427077" alt="Áo gile/áo lưới" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Áo gile/áo lưới</h3>
                            <div class="status">
                                <span class="total-product">10 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="/giay-bao-ho" title="Giày bảo hộ" class="cate-item">
                        <div class="bg-thumb">
                            <div class="thumb">
                                <img src="//bizweb.dktcdn.net/100/504/717/themes/933859/assets/image_cate_3.png?1734490427077" alt="Giày bảo hộ" loading="lazy">
                            </div>
                        </div>
                        <div class="cate-content">
                            <h3 class="line-clamp-2-new">Giày bảo hộ</h3>
                            <div class="status">
                                <span class="total-product">7 sản phẩm</span>
                                <span class="view-more">Xem chi tiết</span>
                            </div>
                        </div>
                    </a>
                </div>
                
                </div>
        </div>
    </div>
</section>
@foreach ($categoriesWithProducts as $category)
<section class="section-index section_product">
    <div class="container-fluid">
        <div class="row">
            <div class="block-title col-lg-3 col-xl-2">
                <div class="section-title side-left has-control">
                    <h2>
                        <a href="{{route('products.by_category',$category->slug)}}">{{ $category->name }}</a>
                    </h2>
                    <div class="slider-controls">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                <a href="{{route('products.by_category',$category->slug)}}" title="Xem tất cả" class="btn btn-primary d-none d-sm-block">
                    <span>Xem tất cả</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </a>
            </div>

            <div class="block-product-list col-lg-9 col-xl-10">
                <div class="product-slider swiper">
                    <div class="swiper-wrapper">
                        {{-- Giả sử anh có biến $products từ controller --}}
                        @foreach($category->products as $product)
                            <div class="swiper-slide">
                                {{-- Gọi partial item sản phẩm đã sửa ở Bước 1 --}}
                                @include('partials.frontend.product_item', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="block-see-more text-center d-block d-md-none mt-3">
                    <a href="{{route('products.by_category',$category->slug)}}" title="Xem tất cả" class="btn btn-primary">
                        <span>Xem tất cả</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endforeach
<div class="container-fluid">
    <div class="row" style="height: 350px;">

        <div class="col-md-6 p-3">
            <div class="bg-primary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                Ảnh lớn bên trái
            </div>
        </div>

        <div class="col-md-6">
            <div class="row h-100">

                <div class="col-12 h-50 p-3">
                    <div class="bg-success w-100 h-100 d-flex align-items-center justify-content-center text-white">
                        Ảnh trên bên phải
                    </div>
                </div>

                <div class="col-12 h-50">
                    <div class="row h-100">
                        <div class="col-6 p-3 h-100">
                            <div class="bg-warning w-100 h-100 d-flex align-items-center justify-content-center">
                                Ảnh dưới 1
                            </div>
                        </div>
                        <div class="col-6 p-3 h-100">
                            <div class="bg-danger w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                Ảnh dưới 2
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<section class="section seciton_deal">
    <div class="container-fluid">
        <div class="row">
            <div class="block-title col-lg-3 col-xl-2">
                <div class="section-title side-left has-control">
                    <h2>
                        'Bão Deal' giảm giá
                    </h2>
                    <div class="slider-controls">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                <a href="{{route('products.by_category',$category->slug)}}" title="Xem tất cả" class="btn btn-primary">
                    <span>Xem tất cả</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </a>
            </div>

            <div class="block-product-list col-lg-9 col-xl-10">
                <div class="product-slider swiper">
                    <div class="swiper-wrapper">
                        {{-- Giả sử anh có biến $products từ controller --}}
                        @foreach($saleProducts as $product)
                            <div class="swiper-slide">
                                {{-- Gọi partial item sản phẩm đã sửa ở Bước 1 --}}
                                @include('partials.frontend.product_item', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>      
<section class="news">
    <div class="container">
        <h2 class="section-title">
            <a href="">Tin tức</a>
            <div class="section-tool">
                <a href="">Xem thêm <i class="fa-solid fa-angles-right"></i></a>
            </div>
        </h2>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="big-news">
                        <div class="big-news-image">
                            <img src="https://placehold.co/400" alt="">
                        </div>
                        <h3>
                            <a href="">Tin mới nhất</a>
                        </h3>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    <div class="news-item_list">
                        <div class="item_list-img">
                            <a href="">
                                <img src="https://placehold.co/400" alt="" width="80" height="80">
                            </a>
                        </div>
                        <div class="item_list-info">
                            <a href="">Tin tức 1</a>
                        </div>
                    </div>
                    
                </div>
            </div>
    </div>
</section>
<section id="contact" class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 box-shadow bg-light mb-4">
                <h2 class="section-title text-dark text-center my-4">Liên hệ với chúng tôi</h2>
                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email (không bắt buộc)</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="message">Nội dung liên hệ</label>
                        <textarea name="message" id="message" rows="5" class="form-control" value="Tôi đang quan tâm đến sản phẩm"></textarea>
                    </div>

                    <div class="text-center mb-3">
                        <button type="submit" class="btn btn-dark">Gọi cho tôi</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <div class="contact-info__image">
                    <img src="{{asset($setting->logo)}}" alt="{{$setting->name}}">
                    <p class="">{{$setting->name}}</p>
                </div>
                <p class="contact-info__text">
                    Năng lượng mặt trời Tài Nguyễn
                </p>
                <div class="contact-info__button">
                    <a href="" class="w-100 btn btn-light rounded-pill d-block mb-3">{{$setting->phone}}</a>
                    <a href="" class="w-100 btn btn-dark rounded-pill d-block mb-3">Chat ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>      
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
        $.validator.addMethod("phoneVN", function (value, element) {
            return this.optional(element) || /^(0[3|5|7|8|9])[0-9]{8}$|^\+84[3|5|7|8|9][0-9]{8}$/.test(value);
        }, "Số điện thoại không hợp lệ");
        $(document).ready(function () {
            $('#contact-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true,
                        phoneVN: true
                    },
                    email: {
                        email: true
                    },
                    message: {
                        maxlength: 1000
                    }
                },
                messages: {
                    name: {
                        required: "Vui lòng nhập họ và tên",
                        minlength: "Tên quá ngắn"
                    },
                    phone: {
                        required: "Vui lòng nhập số điện thoại",
                        phoneVN: "Số điện thoại không hợp lệ (ví dụ: 098xxxxxxx)"
                    },
                    email: {
                        email: "Email không hợp lệ"
                    },
                    message: {
                        maxlength: "Ý kiến không vượt quá 1000 ký tự"
                    }
                },
                errorElement: 'small',
                errorClass: 'text-danger',
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // --- 1. KHỞI TẠO SLIDER CHÍNH (LUÔN CHẠY) ---
    const mainSliderEl = document.querySelector('.main-slider');
    if (mainSliderEl) {
        const mainSlider = new Swiper(mainSliderEl, {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            lazy: {
                loadPrevNext: true,
            },
            // QUAN TRỌNG: Chỉ tìm nút điều hướng bên trong chính slider này
            pagination: {
                el: mainSliderEl.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: mainSliderEl.querySelector('.swiper-button-next'),
                prevEl: mainSliderEl.querySelector('.swiper-button-prev'),
            },
        });
    }

    // --- 2. HÀM KHỞI TẠO CÁC SLIDER RESPONSIVE (chỉ chạy trên màn hình lớn) ---
    // Hàm này đã được viết tốt, giữ nguyên
    const setupResponsiveSwiper = (sectionElement, swiperSelector, options, breakpointWidth = 992) => {
        if (!sectionElement) return;

        let swiperInstance = null;
        const breakpoint = window.matchMedia(`(min-width: ${breakpointWidth}px)`);

        const initializeSwiper = () => {
            if (breakpoint.matches === true && swiperInstance === null) {
                const swiperEl = sectionElement.querySelector(swiperSelector);
                const nextEl = sectionElement.querySelector('.swiper-button-next');
                const prevEl = sectionElement.querySelector('.swiper-button-prev');
                const paginationEl = sectionElement.querySelector('.swiper-pagination');

                const finalOptions = {
                    ...options,
                    navigation: { nextEl, prevEl },
                    pagination: { el: paginationEl, clickable: true },
                };
                
                if (swiperEl) {
                    swiperInstance = new Swiper(swiperEl, finalOptions);
                }
            } else if (breakpoint.matches === false && swiperInstance !== null) {
                swiperInstance.destroy(true, true);
                swiperInstance = null;
            }
        };

        initializeSwiper();
        window.addEventListener('resize', initializeSwiper);
    };

    // --- 3. ÁP DỤNG HÀM RESPONSIVE CHO CÁC SECTION TƯƠNG ỨNG ---
    const categorySection = document.querySelector('.section_category');
    if (categorySection) {
        setupResponsiveSwiper(
            categorySection,
            '.swiper_category',
            {
                spaceBetween: 20,
                slidesPerView: 2,
                breakpoints: {
                    1200: { slidesPerView: 6 },
                    992: { slidesPerView: 5 },
                    768: { slidesPerView: 4 }
                }
            },
            768
        );
    }
    
    const productSections = document.querySelectorAll('.section_product');
    productSections.forEach(section => {
        setupResponsiveSwiper(
            section, 
            '.product-slider',
            {
                spaceBetween: 20,
                breakpoints: {
                    992: { slidesPerView: 3 },
                    1200: { slidesPerView: 4 }
                }
            },
            992
        );
    });

});
</script>
@endpush

