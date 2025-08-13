
<?php $__env->startSection('title','Danh sách dịch vụ'); ?>
<?php $__env->startSection('content_header','Danh sách dịch vụ'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <a href="<?php echo e(route('admin.services.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm dịch vụ
        </a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên dịch vụ</th>
                    <th>Ảnh</th>
                    <th>Danh mục</th>
                    <th>Trạng thái</th>
                    <th>Hiện Menu</th>
                    <th>Hiện Footer</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td><?php echo e($service->name); ?></td>
                    <td><img src="<?php echo e(asset($service->image)); ?>" style="height:60px;"></td>
                    <td><?php echo e($service->category->name ?? '-'); ?></td>
                    <td>
                        <?php if (isset($component)) { $__componentOriginal56f80b0519631baa268a6fa6976cb527 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56f80b0519631baa268a6fa6976cb527 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'service','record' => $service,'field' => 'status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'service','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service),'field' => 'status']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'service','record' => $service,'field' => 'is_menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'service','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service),'field' => 'is_menu']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.boolean-toggle','data' => ['model' => 'service','record' => $service,'field' => 'is_footer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('boolean-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'service','record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service),'field' => 'is_footer']); ?>
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
                    
                    <td><?php echo e($service->created_at->format('d/m/Y')); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.services.edit', $service)); ?>" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
                        <form action="<?php echo e(route('admin.services.destroy', $service)); ?>" method="POST" style="display:inline-block;" class="form-delete">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/services/index.blade.php ENDPATH**/ ?>