


<?php $__env->startSection('title','Danh mục dịch vụ'); ?>
<?php $__env->startSection('content_header','Danh mục dịch vụ'); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.service_categories.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm danh mục
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Ảnh</th>
                            <th>Trạng thái</th>
                            <th>Hiện Menu</th>
                            <th>Hiện Footer</th>
                            <th>Hiện Trang chủ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($item->name); ?></td>
                            <td><img src="<?php echo e(asset($item->image)); ?>" alt="Image" style="height: 60px;"></td>
                            <td>
                                <?php if (isset($component)) { $__componentOriginal56f80b0519631baa268a6fa6976cb527 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56f80b0519631baa268a6fa6976cb527 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'ServiceCategory','record' => $item,'field' => 'status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'ServiceCategory','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'field' => 'status']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'ServiceCategory','record' => $item,'field' => 'is_menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'ServiceCategory','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'field' => 'is_menu']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'ServiceCategory','record' => $item,'field' => 'is_footer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'ServiceCategory','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'field' => 'is_footer']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'ServiceCategory','record' => $item,'field' => 'is_home']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'ServiceCategory','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'field' => 'is_home']); ?>
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
                                <a href="<?php echo e(route('admin.service_categories.edit', $item)); ?>" class="btn btn-sm btn-warning"><i class="far fa-edit"></i></a>
                                <form action="<?php echo e(route('admin.service_categories.destroy', $item)); ?>" method="POST" class="d-inline-block form-delete">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger delete"><i class="far fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-3">
            <?php if (isset($component)) { $__componentOriginal7ac7969dc163cbea6f00ddbb93341121 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7ac7969dc163cbea6f00ddbb93341121 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.categories.tree-card','data' => ['categories' => $categories,'routeName' => 'admin.service_categories.edit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('categories.tree-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'routeName' => 'admin.service_categories.edit']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7ac7969dc163cbea6f00ddbb93341121)): ?>
<?php $attributes = $__attributesOriginal7ac7969dc163cbea6f00ddbb93341121; ?>
<?php unset($__attributesOriginal7ac7969dc163cbea6f00ddbb93341121); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7ac7969dc163cbea6f00ddbb93341121)): ?>
<?php $component = $__componentOriginal7ac7969dc163cbea6f00ddbb93341121; ?>
<?php unset($__componentOriginal7ac7969dc163cbea6f00ddbb93341121); ?>
<?php endif; ?>
        </div>
    </div>

    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.querySelectorAll('.form-delete').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn chắc chắn?',
                    text: 'Hành động này không thể hoàn tác!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ'
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
    <?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/service_categories/index.blade.php ENDPATH**/ ?>