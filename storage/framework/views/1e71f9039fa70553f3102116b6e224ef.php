
<?php $__env->startSection('title','Danh sách Intro'); ?>
<?php $__env->startSection('content_header','Danh sách Intro'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
	<div class="card-header">
		<h3 class="card-title"></h3>
		<div class="card-tools">
			<a href="<?php echo e(route('admin.intros.create')); ?>" class="btn btn-primary">
				<i class="fas fa-plus"></i> Thêm Intro
			</a>
		</div>
	</div>
	<div class="card-body table-responsive p-0">
		<table class="table table-hover text-nowrap">
			<thead>
				<tr>
					<th>#</th>
					<th>Tiêu đề</th>
					<th>Ảnh</th>
					<th>Trạng thái</th>
					<th>Ngày tạo</th>
					<th>Cập nhật</th>
					<th>Thao tác</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $intros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $intro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($key + 1); ?></td>
					<td><?php echo e($intro->title); ?></td>
					<td>
						<img src="<?php echo e(asset($intro->image)); ?>" alt="Intro" style="height: 80px;">
					</td>
					<td>
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="status_<?php echo e($intro->id); ?>" <?php echo e($intro->status ? 'checked' : ''); ?>>
							<label for="status_<?php echo e($intro->id); ?>" class="custom-control-label">
								<?php echo e($intro->status ? 'Hiện' : 'Ẩn'); ?>

							</label>
						</div>
					</td>
					<td><?php echo e($intro->created_at->format('d/m/Y')); ?></td>
					<td><?php echo e($intro->updated_at->format('d/m/Y')); ?></td>
					<td>
						<a href="<?php echo e(route('admin.intros.edit', $intro)); ?>" class="btn btn-warning">
							<i class="far fa-edit"></i>
						</a>

						<form action="<?php echo e(route('admin.intros.destroy', $intro)); ?>" method="POST" style="display:inline-block;" class="form-delete">
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/intros/index.blade.php ENDPATH**/ ?>