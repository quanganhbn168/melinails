<?php $__env->startSection('title','Liên hệ'); ?>
<?php $__env->startPush('css'); ?>
<style>
	.map iframe{
		width: 100%!important;
	}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div id="contact">
	<div class="container">
		<?php if (isset($component)) { $__componentOriginal579baf6ff71824f65670a6f9c3a54aa9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal579baf6ff71824f65670a6f9c3a54aa9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.frontend.breadcrumb','data' => ['items' => [
			['label' => 'Liên hệ'],
		]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('frontend.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
			['label' => 'Liên hệ'],
		])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal579baf6ff71824f65670a6f9c3a54aa9)): ?>
<?php $attributes = $__attributesOriginal579baf6ff71824f65670a6f9c3a54aa9; ?>
<?php unset($__attributesOriginal579baf6ff71824f65670a6f9c3a54aa9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal579baf6ff71824f65670a6f9c3a54aa9)): ?>
<?php $component = $__componentOriginal579baf6ff71824f65670a6f9c3a54aa9; ?>
<?php unset($__componentOriginal579baf6ff71824f65670a6f9c3a54aa9); ?>
<?php endif; ?>
		<h2 class="text-center mt-3">Liên hệ</h2>
		<div class="row">
			<div class="col-12 col-md-6 col-sm-12 order-2 order-md-1">
				<h3>
					<strong>Liên hệ với <?php echo e($setting->name); ?></strong>
				</h3>
				<ul>
					<li class="mt-3"><strong>Địa chỉ:</strong> <?php echo e($setting->address); ?></li>
					<?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li class="mt-3"> <strong><?php echo e($branch->name); ?></strong> : <?php echo e($branch->address); ?></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<li class="mt-3">
						<a href="tel:<?php echo e(preg_replace('/\s+/', '', $setting->phone)); ?>">
							<strong>Điện thoại:</strong> <?php echo e($setting->phone); ?></a>
					</li>
					<li class="mt-3">
						<a href="mailto:<?php echo e(trim($setting->email)); ?>">
							<strong>Email:</strong> <?php echo e($setting->email); ?>

						</a>
					</li>
				</ul>
			</div>
			<div class="col-12 col-md-6 col-sm-12 order-1 order-md-2">
				<div class="card text-bg-light">
					<div class="card-header">
						<div class="card-title">
							<h3 class="strong text-uppercase">Liên hệ ngay với chúng tôi</h3>
						</div>
					</div>
					<div class="card-body bg-light">
						<form id="contact-form" action="<?php echo e(route('contact.store')); ?>" method="POST">
							<?php echo csrf_field(); ?>
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="name">Họ và tên *</label>
										<input type="text" class="form-control" id="name" name="name" autocomplete>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="address">Địa chỉ</label>
										<input type="text" class="form-control" id="address" name="address" autocomplete>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="phone">Số điện thoại *</label>
										<input type="text" class="form-control" id="phone" name="phone" autocomplete>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="email">Email</label>
										<input type="text" class="form-control" id="email" name="email" autocomplete>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="message">Ý kiến</label>
								<textarea name="message" id="message" class="form-control" autocomplete></textarea>
							</div>
							<button class="btn btn-dark d-block w-100">Gửi ý kiến</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="map text-center py-4">
					<?php echo $setting->map; ?>

				</div>
			</div>			
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    // Custom rule kiểm tra số điện thoại Việt Nam
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
                	required: true,
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
                	required: "Vui lòng nhập nội dung",
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/contact.blade.php ENDPATH**/ ?>