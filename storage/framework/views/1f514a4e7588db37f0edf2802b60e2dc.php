<?php $__env->startSection('title','Trang chủ - '.$setting->name); ?>
<?php $__env->startSection('meta_description',$setting->meta_description); ?>
<?php $__env->startSection('meta_keywords',$setting->meta_keywords); ?>
<?php $__env->startPush('jsonld'); ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Store",
  "name": "<?php echo e($setting->name); ?>",
  "alternateName": "<?php echo e($setting->name); ?>",
  "url": "<?php echo e(url()->current()); ?>",
  "logo": "<?php echo e(asset($setting->logo)); ?>",
  "description": "<?php echo e($setting->meta_description); ?>",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "<?php echo e($setting->address); ?>",
    "addressLocality": "Thành phố Bắc Ninh",
    "addressRegion": "Bắc Ninh",
    "postalCode": "220000",
    "addressCountry": "VN"
  },
  "telephone": "<?php echo e($setting->phone); ?>",
  "email": "<?php echo e($setting->email); ?>",
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
    "<?php echo e($setting->facebook); ?>",
    "<?php echo e($setting->youtube); ?>",
    "<?php echo e($setting->zalo); ?>"
  ]
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('vendor/glightbox/css/glightbox.min.css')); ?>?<?php echo e(time()); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection("content"); ?>
<section id="slider">
    <div class="container">
        <div class="row">
            
            <div class="col-lg-8 col-12">
                <?php echo $__env->make("partials.frontend.slide", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            
            <div class="col-lg-4 col-12 d-flex flex-column justify-content-between">
                
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="banner-home">  
                    <img src="<?php echo e(asset($banner->image)); ?>" alt="" class="img-fluid"> img-fluid để ảnh responsive --}}
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<section class="feature">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fa-solid fa-piggy-bank"></i>
                    </div>
                    <h3 class="">Tiết kiệm tối đa</h3>
                    <p class="text-gray-600">Giảm đến 90% chi phí tiền điện hàng tháng, hoàn vốn nhanh chóng chỉ từ 4-5 năm.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="">Sản Phẩm Chính Hãng</h3>
                    <p class="text-gray-600">Giảm đến 90% chi phí tiền điện hàng tháng, hoàn vốn nhanh chóng chỉ từ 4-5 năm.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="">Bảo Hành Dài Hạn</h3>
                    <p class="text-gray-600">Chính sách bảo hành hiệu suất 25 năm, bảo hành vật lý 12 năm, đảm bảo an tâm tuyệt đối.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-item">
                    <div class="text-center">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="">Chính sách rõ ràng</h3>
                    <p class="text-gray-600">Giảm đến 90% chi phí tiền điện hàng tháng, hoàn vốn nhanh chóng chỉ từ 4-5 năm.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="categories">
    <div class="container">
        <div class="section-title">
            <h3>
                <a href="#" class="text-uppercase">Danh Mục Sản Phẩm Chính</a>
            </h3>
        </div>
        <div class="categories-wrapper">
            <div class="row">
                
                <?php $__empty_1 = true; $__currentLoopData = $featuredCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="categories-item">
                            <div class="categories-item_image">
                                
                                <a href="#">
                                    <img src="<?php echo e(asset($category->image)); ?>" alt="<?php echo e($category->name); ?>">
                                </a>
                            </div>
                            <div class="categories-item_name">
                                <a href="#"><?php echo e($category->name); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="col-12">Chưa có danh mục nổi bật nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php $__currentLoopData = $categoriesWithProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<section class="product-list">
    <div class="container">
        <div class="product-widget">
            <div class="widget-title">
                <h3>
                    <a href="<?php echo e(route('products.by_category',$category->slug)); ?>"><?php echo e($category->name); ?></a>
                </h3>
                <div class="widget-tool">
                    <a href="<?php echo e(route('products.by_category',$category->slug)); ?>">Xem thêm <i class="fa-solid fa-angles-right"></i></a>
                </div>
            </div>
            <div class="product-widget_wrapper">
                
                <?php $__empty_1 = true; $__currentLoopData = $category->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php echo $__env->make('partials.frontend.product_item', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="col-12">Chưa có sản phẩm nào trong danh mục này.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <form id="contact-form" action="<?php echo e(route('contact.store')); ?>" method="POST" novalidate>
                    <?php echo csrf_field(); ?>
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
                    <img src="<?php echo e(asset($setting->logo)); ?>" alt="<?php echo e($setting->name); ?>">
                    <p class=""><?php echo e($setting->name); ?></p>
                </div>
                <p class="contact-info__text">
                    Năng lượng mặt trời Tài Nguyễn
                </p>
                <div class="contact-info__button">
                    <a href="" class="w-100 btn btn-light rounded-pill d-block mb-3"><?php echo e($setting->phone); ?></a>
                    <a href="" class="w-100 btn btn-dark rounded-pill d-block mb-3">Chat ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>      
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
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
        const counters = document.querySelectorAll('.counter-number');

        const runCounter = (el) => {
            const target = +el.getAttribute('data-target');
            const suffix = el.getAttribute('data-suffix') || '';
            let count = 0;
            const speed = 20;
            const step = Math.ceil(target / 60);

            const update = () => {
                count += step;
                if (count >= target) {
                    el.textContent = target.toLocaleString() + suffix;
                } else {
                    el.textContent = count.toLocaleString() + suffix;
                    requestAnimationFrame(update);
                }
            };

            update();
        };

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
        }, {
            threshold: 0.6
        });

        counters.forEach(counter => observer.observe(counter));
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.swiper.slider', {
        slidesPerView: 1,
        loop: true,
        speed: 600,

        // Tự chạy
        autoplay: {
            delay: 3000,
            disableOnInteraction: false
        },

        // Dấu chấm phân trang
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },

        // Nút điều hướng
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        }
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/index.blade.php ENDPATH**/ ?>