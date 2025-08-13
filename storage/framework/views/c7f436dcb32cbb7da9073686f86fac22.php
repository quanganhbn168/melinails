
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', $setting->meta_description); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', $setting->meta_keywords); ?>">
    <meta name="robots" content="<?php echo $__env->yieldContent('meta_robots', 'index, follow'); ?>">
    
    <link rel="canonical" href="<?php echo e(url()->current()); ?>" />
    
    <meta property="og:type"        content="<?php echo $__env->yieldContent('og_type','website'); ?>" />
    <meta property="og:title"       content="<?php echo $__env->yieldContent('title', config('app.name')); ?> " />
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', $setting->meta_description); ?>" />
    <meta property="og:url"         content="<?php echo e(url()->current()); ?>" />
    <meta property="og:site_name"   content="<?php echo e($setting->name); ?>" />
    <meta property="og:image"       content="<?php echo $__env->yieldContent('meta_image', $setting->share_image); ?>" />
    
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:title"       content="<?php echo $__env->yieldContent('title', config('app.name')); ?>" />
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('meta_description'); ?>" />
    <meta name="twitter:image"       content="<?php echo $__env->yieldContent('meta_image', $setting->share_image); ?>" />
    
    <link rel="icon" href="<?php echo e(asset($setting->favicon)); ?>" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset($setting->favicon)); ?>" />
    
    <link rel="stylesheet" href="<?php echo e(asset('vendor/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/fontawesome/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/swiper/swiper-bundle.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/sweetalert2/bootstrap-4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/slide.css')); ?>?v=<?php echo e(filemtime(public_path('css/slide.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/global.css')); ?>?v=<?php echo e(filemtime(public_path('css/global.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>?v=<?php echo e(filemtime(public_path('css/style.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/responsive.css')); ?>?v=<?php echo e(filemtime(public_path('css/responsive.css'))); ?>">
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $setting->head_script; ?>

    <?php echo $__env->yieldPushContent('jsonld'); ?>
    <?php echo $__env->yieldPushContent('conversion_script'); ?>
</head>
<body class="<?php echo e(Auth::check() ? 'logged-in' : ''); ?>">
    <?php echo $setting->body_script; ?>

    <?php echo $__env->make('partials.frontend.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php echo $__env->make('frontend.modal.contact', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.modal.branch', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.frontend.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="<?php echo e(asset('/js/jquery-3.7.1.min.js')); ?>?<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('/vendor/bootstrap/popper.min.js')); ?>?<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('/vendor/bootstrap/js/bootstrap.min.js')); ?>?<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('/vendor/swiper/swiper-bundle.min.js')); ?>?<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('plugins/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/cart.js')); ?>"></script>
    <?php if(session('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: <?php echo json_encode(session('success'), 15, 512) ?>,
            confirmButtonText: 'OK'
        });
    </script>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: <?php echo json_encode(session('error'), 15, 512) ?>,
            confirmButtonText: 'OK'
        });
    </script>
    <?php endif; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".gotop").addEventListener("click", function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        });
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'vi',      
                includedLanguages: 'vi,en', 
                autoDisplay: false
            }, 'google_translate_element');
            setActiveFlag();
        }
        function changeLanguage(lang) {
            var a = document.querySelector("#google_translate_element select");
            if (a) {
                a.value = lang;
                a.dispatchEvent(new Event('change'));
            }
        }
        function setActiveFlag() {
            var currentLang = getCookie('googtrans') ? getCookie('googtrans').split('/')[2] : 'vi';
            document.querySelectorAll('.language-switcher-flags a').forEach(function(el) {
                if (el.getAttribute('data-lang') === currentLang) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });
        }
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
        var originalTranslateElementInit = window.googleTranslateElementInit;
        window.googleTranslateElementInit = function() {
            originalTranslateElementInit();
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if(mutation.type === 'attributes' && mutation.attributeName === 'class' && mutation.target.nodeName === 'BODY') {
                        if(!document.body.classList.contains('google-translating')) {
                            setActiveFlag();
                        }
                    }
                });
            });
            observer.observe(document.body, { attributes: true });
        };
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        $(document).ready(function(){
            $('#contactModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var productName = button.data('name'); 
            var modal = $(this);
            var messageContent = "Tôi đang quan tâm đến sản phẩm: " + productName + "\n\n";
            var messageTextarea = modal.find('textarea#message');
            messageTextarea.val(messageContent).focus();
            messageTextarea[0].setSelectionRange(messageContent.length, messageContent.length);
        });
        });
    </script>
    <?php echo $__env->yieldPushContent('js'); ?>
</body>
</html><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/layouts/master.blade.php ENDPATH**/ ?>