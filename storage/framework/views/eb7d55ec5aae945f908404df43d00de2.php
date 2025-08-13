
<?php $__env->startSection('title','Danh sách danh mục'); ?>
<?php $__env->startSection('content_header','Danh sách danh mục'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
	<div class="card-header">
		<h3 class="card-title"></h3>
		<div class="card-tools">
			<a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
				<i class="fas fa-plus"></i> Thêm Danh mục
			</a>
		</div>
	</div>
	<div class="card-body table-responsive p-0">
		<table class="table table-hover text-nowrap">
			<thead>
				<tr>
					<th>#</th>
					<th>Tên danh mục</th>
					<th>Ảnh</th>
					<th>Danh mục cha</th>
					<th>Trạng thái</th>
					<th>H.Trang chủ</th>
					<th>H.Menu</th>
					<th>Ngày tạo</th>
					<th>Thao tác</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($key + 1); ?></td>
					<td><?php echo e($category->name); ?></td>
					<td>
						<img src="<?php echo e(asset($category->image)); ?>" alt="Image" style="height: 60px;">
					</td>
					<td>
						<?php echo e($category->parent?->name ?? '---'); ?>

					</td>
					<td>
						<?php if (isset($component)) { $__componentOriginal56f80b0519631baa268a6fa6976cb527 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56f80b0519631baa268a6fa6976cb527 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'Category','record' => $category,'field' => 'status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'Category','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category),'field' => 'status']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56f80b0519631baa268a6fa6976cb527)): ?>
<?php $attributes = $__attributesOriginal56f80b0519631baa268a6fa6976cb527; ?>
<?php unset($__attributesOriginal56f80b0519631baa268a6fa6976cb527); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56f80b0519631baa268a6fa6976cb527)): ?>
<?php $component = $__componentOriginal56f80b0519631baa268a6fa6976cb527; ?>
<?php unset($__componentOriginal56f80b0519631baa268a6fa6976cb527); ?>
<?php endif; ?>
					</td>
					<td>
						<?php if (isset($component)) { $__componentOriginal56f80b0519631baa268a6fa6976cb527 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56f80b0519631baa268a6fa6976cb527 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'Category','record' => $category,'field' => 'is_home']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'Category','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category),'field' => 'is_home']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56f80b0519631baa268a6fa6976cb527)): ?>
<?php $attributes = $__attributesOriginal56f80b0519631baa268a6fa6976cb527; ?>
<?php unset($__attributesOriginal56f80b0519631baa268a6fa6976cb527); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56f80b0519631baa268a6fa6976cb527)): ?>
<?php $component = $__componentOriginal56f80b0519631baa268a6fa6976cb527; ?>
<?php unset($__componentOriginal56f80b0519631baa268a6fa6976cb527); ?>
<?php endif; ?>
					</td>
					<td>
						<?php if (isset($component)) { $__componentOriginal56f80b0519631baa268a6fa6976cb527 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56f80b0519631baa268a6fa6976cb527 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'Category','record' => $category,'field' => 'is_menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'Category','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category),'field' => 'is_menu']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56f80b0519631baa268a6fa6976cb527)): ?>
<?php $attributes = $__attributesOriginal56f80b0519631baa268a6fa6976cb527; ?>
<?php unset($__attributesOriginal56f80b0519631baa268a6fa6976cb527); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56f80b0519631baa268a6fa6976cb527)): ?>
<?php $component = $__componentOriginal56f80b0519631baa268a6fa6976cb527; ?>
<?php unset($__componentOriginal56f80b0519631baa268a6fa6976cb527); ?>
<?php endif; ?>
					</td>
					
					<td><?php echo e($category->created_at->format('d/m/Y')); ?></td>
					<td>
						<a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn btn-warning">
							<i class="far fa-edit"></i>
						</a>

						<form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" method="POST" class="d-inline form-delete">
							<?php echo csrf_field(); ?>
							<?php echo method_field('DELETE'); ?>
							<button type="submit" class="btn btn-danger delete">
								<i class="far fa-trash-alt"></i>
							</button>
						</form>
					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	const forms = document.querySelectorAll('.form-delete');

	forms.forEach(function(form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();

			Swal.fire({
				title: 'Bạn chắc chắn?',
				text: 'Hành động này không thể hoàn tác!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Xoá',
				cancelButtonText: 'Huỷ'
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});
	});
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>