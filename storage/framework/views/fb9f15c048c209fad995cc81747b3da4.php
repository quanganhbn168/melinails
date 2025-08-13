

<?php $__env->startSection('title', 'Danh sách bài viết'); ?>
<?php $__env->startSection('content_header', 'Danh sách bài viết'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <a href="<?php echo e(route('admin.posts.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm bài viết
        </a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Ảnh</th>
                    <th>Danh mục</th>
                    <th>Trạng thái</th>
                    <th>Cập nhật</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($post->title); ?></td>
                        <td><img src="<?php echo e(asset($post->image)); ?>" height="40"></td>
                        <td><?php echo e($post->category->name ?? ''); ?></td>
                        <td>
                            <?php if (isset($component)) { $__componentOriginal56f80b0519631baa268a6fa6976cb527 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56f80b0519631baa268a6fa6976cb527 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'post','record' => $post,'field' => 'status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'post','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post),'field' => 'status']); ?>
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
                        <td><?php echo e($post->updated_at->format('d/m/Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.posts.edit', $post)); ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('admin.posts.destroy', $post)); ?>" method="POST" class="d-inline-block" onsubmit="return confirmDelete(this)">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
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

<?php $__env->startPush('js'); ?>
<script>
    function confirmDelete(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn xoá?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/posts/index.blade.php ENDPATH**/ ?>