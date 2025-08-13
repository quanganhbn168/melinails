<div class="page-pills">
    <div class="contact-pills d-none d-md-flex flex-column gap-2">
        <a href="<?php echo e($setting->zalo); ?>" target="_blank" class="contact-pill" aria-label="Liên hệ Zalo">
            <img src="<?php echo e(asset('images/setting/Icon_of_Zalo.svg')); ?>" alt="zalo icon">
        </a>
        <a href="<?php echo e($setting->mess); ?>" target="_blank" class="contact-pill" aria-label="Liên hệ Messenger">
            <img src="<?php echo e(asset('images/setting/Facebook_Messenger_logo_2020.svg')); ?>" alt="messenger icon">
        </a>
        <a href="tel:<?php echo e($setting->phone); ?>" class="contact-pill" aria-label="Gọi điện thoại">
            <img src="<?php echo e(asset('images/setting/phone.svg')); ?>" alt="phone icon">
        </a>
    </div>
    <a href="#" class="gotop d-none d-md-flex" aria-label="Lên đầu trang">
        <i class="fa-solid fa-arrow-up"></i>
    </a>
</div>
<nav class="mobile-bottom-nav d-md-none">
    <a href="#danh-muc" class="mobile-nav-item">
        <i class="fa-solid fa-list"></i>
        <span class="mobile-nav-label">Danh mục</span>
    </a>
    <a href="tel:<?php echo e($setting->phone ?? ''); ?>" class="mobile-nav-item">
        <i class="fa-solid fa-phone"></i>
        <span class="mobile-nav-label">Gọi</span>
    </a>
    <a href="<?php echo e(url('/')); ?>" class="mobile-nav-item">
        <i class="fa-solid fa-home"></i>
        <span class="mobile-nav-label">Trang chủ</span>
    </a>
    <a href="<?php echo e($setting->zalo ?? '#'); ?>" target="_blank" class="mobile-nav-item">
        <i class="fa-solid fa-comments"></i>
        <span class="mobile-nav-label">Zalo</span>
    </a>
    <a href="#" class="mobile-nav-item gotop-mobile">
        <i class="fa-solid fa-arrow-up"></i>
        <span class="mobile-nav-label">Top</span>
    </a>
</nav>
<footer class="footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5 col-md-12">
                <div class="footer__item">
                    <h4 class="footer__title">Thông tin liên hệ</h4>
                    <div class="footer__logo mb-3">
                        <img src="<?php echo e(asset($setting->logo)); ?>" alt="<?php echo e($setting->name); ?>">
                    </div>
                    <ul class="footer__list">
                        <li>
                            <h5 class="company-name"><?php echo e($setting->name); ?></h5>
                        </li>
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span><strong>Trụ sở chính:</strong> <?php echo e($setting->address); ?></span>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <span><strong>Điện thoại:</strong> <a href="tel:<?php echo e($setting->phone); ?>"><?php echo e($setting->phone); ?></a></span>
                        </li>
                        <li>
                            <i class="fa-regular fa-envelope"></i>
                            <span><strong>Email:</strong> <a href="mailto:<?php echo e($setting->email); ?>"><?php echo e($setting->email); ?></a></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer__item">
                    <h4 class="footer__title">Chính sách</h4>
                    <ul class="footer__list">
                        <li><a href="/">Trang chủ</a></li>
                        <li><a href="/gioi-thieu">Giới thiệu</a></li>
                        <li><a href="/chinh-sach-bao-hanh">Chính sách bảo hành</a></li>
                        <li><a href="/chinh-sach-doi-tra">Chính sách đổi trả</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="footer__item">
                    <h4 class="footer__title">Đăng ký nhận tư vấn</h4>
                    <form action="<?php echo e(route('contact.store')); ?>" method="POST" id="footer-contact-form">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Họ và tên" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" class="form-control" placeholder="Số điện thoại" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" class="form-control" placeholder="Nội dung cần tư vấn..."></textarea>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Gửi yêu cầu</button>
                    </form>
                    <div id="form-message" class="mt-3"></div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container text-center">
            <p>© Bản quyền thuộc về <strong><?php echo e($setting->name); ?></strong></p>
            <p class="tht-credit">Thiết kế và phát triển bởi <a href="https://truyenthongtht.com" target="_blank" rel="noopener">THT Media</a></p>
        </div>
    </div>
</footer>
<?php $__env->startPush('js'); ?>
<script>
$(document).ready(function () {
    const gotopDesktop = $('.gotop'); 
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 200) {
            gotopDesktop.addClass('show');
        } else {
            gotopDesktop.removeClass('show');
        }
    });
    $('.gotop, .gotop-mobile').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 600); 
    });
    $('#footer-contact-form').on('submit', function(e) {
        e.preventDefault(); 
        var form = $(this);
        var submitButton = form.find('button[type="submit"]');
        var formMessage = $('#form-message');
        submitButton.prop('disabled', true).text('Đang gửi...');
        formMessage.html(''); 
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                formMessage.html('<div class="alert alert-success mt-2">Yêu cầu của bạn đã được gửi. Chúng tôi sẽ liên hệ lại sớm!</div>');
                form[0].reset(); 
            },
            error: function(xhr) {
                formMessage.html('<div class="alert alert-danger mt-2">Đã có lỗi xảy ra. Vui lòng thử lại.</div>');
            },
            complete: function() {
                setTimeout(function() {
                    submitButton.prop('disabled', false).text('Gửi yêu cầu');
                }, 2000);
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/partials/frontend/footer.blade.php ENDPATH**/ ?>