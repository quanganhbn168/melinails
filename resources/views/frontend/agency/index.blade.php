@extends('layouts.master')
@section('title', 'Hợp tác Đại lý & Đối tác')
@section('content')
@push('css')
<style>
    /* CSS REUSED FROM RECRUITMENT PAGE */
    /* 2. Khối màu xanh chứa nội dung */
.thongdiep-text {
    background-color: #1072ba; /* Màu xanh chủ đạo của box */
    color: #ffffff;
    padding: 40px 30px 60px 30px; /* Padding dưới to hơn để chừa chỗ cho ảnh */
    position: relative; /* Để căn chỉnh dấu nháy kép */
    border-radius: 0; /* Vuông vức theo thiết kế */
}

/* Tạo dấu nháy kép to đùng ở góc */
.thongdiep-text::before {
    content: '“';
    font-family: Georgia, serif; /* Font có chân để dấu nháy đẹp */
    font-size: 180px;
    color: #ffffff;
    position: absolute;
    top: -30px;
    left: 30px;
    line-height: 1;
    opacity: 0.8;
    pointer-events: none; /* Để không che mất nội dung khi click */
}

/* Ảnh banner nhỏ bên trong box xanh */
.thongdiep-text-banner {
    margin-bottom: 20px;
    position: relative;
    z-index: 2; /* Đè lên dấu nháy kép */
}

.thongdiep-text-banner img {
    /* width: 60%; */
    height: auto;
    display: block;
    border: 1px solid rgba(255, 255, 255, 0.2); /* Viền mờ nhẹ cho ảnh */
}

/* Định dạng nội dung văn bản bên trong */
.thongdiep-text p, 
.thongdiep-text h3,
.thongdiep-text h4,
.thongdiep-text ul, 
.thongdiep-text li {
    color: #ffffff !important;
    position: relative;
    z-index: 2;
}

.thongdiep-text h3, 
.thongdiep-text strong {
    font-size: 22px;
    line-height: 1.4;
    font-weight: 700;
    display: block;
    margin-bottom: 20px;
}

/* 3. Ảnh chân dung */
.thongdiep-image {
    margin-top: -130px; 
    text-align: right; 
    position: relative;
    z-index: 10; 
    margin-bottom: 20px; 
}

.thongdiep-image img {
    height: 420px; 
    width: auto;
    object-fit: contain;
    display: inline-block;
    filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2)); 
}

/* Form Container Style matching the clean aesthetic */
.agency-form-container {
    background-color: #fff;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    border-radius: 4px;
    border-top: 4px solid #1072ba;
}
</style>
@endpush

<div class="banner">
    {{-- Banner ảnh --}}
    <img src="{{ !empty($setting->banner_image) ? asset($setting->banner_image) : asset($setting->banner) }}" alt="Hợp tác đại lý" style="width: 100%; height: auto; object-fit: cover;">
</div>

<div class="container mt-5">
    
    {{-- MESSAGE BLOCK (STRUCTURE FROM RECRUITMENT) --}}
    <div class="thongdiep">
        <h2 class="custom-section-title text-uppercase font-weight-bold mb-4">Chính sách hợp tác</h2>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="thongdiep-text">
                    {{-- Nội dung chính sách (Giả lập HTML content) --}}
                    <h3>Cùng CNETPOS kiến tạo thành công!</h3>
                    <p style="margin-bottom: 15px;">Chúng tôi cam kết mang lại giá trị bền vững cho đối tác với chính sách ưu việt:</p>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <li style="margin-bottom: 10px;"><strong>Chiết khấu hấp dẫn:</strong> Lợi nhuận lên đến 40% tùy theo doanh số cam kết.</li>
                        <li style="margin-bottom: 10px;"><strong>Hỗ trợ Marketing:</strong> Cung cấp biển hiệu, catalogue và data khách hàng khu vực.</li>
                        <li style="margin-bottom: 10px;"><strong>Đào tạo chuyên sâu:</strong> Hướng dẫn kỹ thuật và kỹ năng bán hàng bài bản.</li>
                        <li><strong>Bảo hành uy tín:</strong> Cơ chế bảo hành 1 đổi 1 nhanh chóng.</li>
                    </ul>
                    <br>
                    <p><em>"Thành công của bạn là sứ mệnh của chúng tôi."</em></p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="thongdiep-image">
                    {{-- Dùng ảnh bắt tay hoặc ảnh đại diện --}}
                    <img src="{{ asset('images/setting/bat-tay.png') }}" onerror="this.src='https://placehold.co/400x420/png?text=Partner'" alt="Hợp tác đại lý">
                </div>
            </div>
        </div>
    </div>

    {{-- REGISTRATION FORM (REPLACING CAREER CARDS) --}}
    <div class="tuyendung mt-5 mb-5">
        <h2 class="custom-section-title text-uppercase font-weight-bold mb-4">Đăng ký đại lý</h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="agency-form-container">
                    <p class="text-center mb-4 text-muted">Vui lòng điền thông tin doanh nghiệp, chúng tôi sẽ gửi chính sách chi tiết qua Email.</p>
                    
                    <form action="{{ route('agency.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="Nhập họ tên...">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" required placeholder="Nhập số điện thoại...">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tên Cửa hàng / Công ty</label>
                                    <input type="text" name="shop_name" class="form-control" placeholder="Tên cửa hàng (nếu có)...">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Khu vực đăng ký</label>
                                    <input type="text" name="area" class="form-control" placeholder="Tỉnh/Thành phố...">
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Địa chỉ chi tiết</label>
                                    <input type="text" name="address" class="form-control" placeholder="Địa chỉ cửa hàng/văn phòng...">
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Ghi chú thêm</label>
                                    <textarea name="details" class="form-control" rows="4" placeholder="Giới thiệu đôi nét về kinh nghiệm kinh doanh của bạn..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5 font-weight-bold text-uppercase" style="background-color: #1072ba; border-color: #1072ba; border-radius: 0;">
                                Gửi Đăng Ký Ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
