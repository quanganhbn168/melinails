

<?php $__env->startSection('title', 'Chỉnh sửa danh mục'); ?>
<?php $__env->startSection('content_header', 'Chỉnh sửa danh mục'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa danh mục</h3>
    </div>

    <form action="<?php echo e(route('admin.categories.update', $category)); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="card-body">
            <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'name','label' => 'Tên danh mục','value' => old('name', $category->name)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'name','label' => 'Tên danh mục','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name', $category->name))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'slug','label' => 'Slug (URL thân thiện)','value' => old('slug', $category->slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'slug','label' => 'Slug (URL thân thiện)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('slug', $category->slug))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => 'parent_id','label' => 'Danh mục cha','options' => $categories,'selected' => old('parent_id', $category->parent_id),'placeholder' => '-- Không có danh mục cha --']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'parent_id','label' => 'Danh mục cha','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('parent_id', $category->parent_id)),'placeholder' => '-- Không có danh mục cha --']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $attributes = $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $component = $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala9c02590cd582d41347c840796552ccc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9c02590cd582d41347c840796552ccc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image-input','data' => ['name' => 'image','label' => 'Ảnh đại diện','value' => $category->image]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','label' => 'Ảnh đại diện','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category->image)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9c02590cd582d41347c840796552ccc)): ?>
<?php $attributes = $__attributesOriginala9c02590cd582d41347c840796552ccc; ?>
<?php unset($__attributesOriginala9c02590cd582d41347c840796552ccc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9c02590cd582d41347c840796552ccc)): ?>
<?php $component = $__componentOriginala9c02590cd582d41347c840796552ccc; ?>
<?php unset($__componentOriginala9c02590cd582d41347c840796552ccc); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala9c02590cd582d41347c840796552ccc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9c02590cd582d41347c840796552ccc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image-input','data' => ['name' => 'banner','label' => 'Banner (tuỳ chọn)','value' => $category->banner]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'banner','label' => 'Banner (tuỳ chọn)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category->banner)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9c02590cd582d41347c840796552ccc)): ?>
<?php $attributes = $__attributesOriginala9c02590cd582d41347c840796552ccc; ?>
<?php unset($__attributesOriginala9c02590cd582d41347c840796552ccc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9c02590cd582d41347c840796552ccc)): ?>
<?php $component = $__componentOriginala9c02590cd582d41347c840796552ccc; ?>
<?php unset($__componentOriginala9c02590cd582d41347c840796552ccc); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal3393560a65f36031a8fc2de39b5ab719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3393560a65f36031a8fc2de39b5ab719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.switch','data' => ['name' => 'status','label' => 'Trạng thái','checked' => old('status', $category->status)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','label' => 'Trạng thái','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('status', $category->status))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3393560a65f36031a8fc2de39b5ab719)): ?>
<?php $attributes = $__attributesOriginal3393560a65f36031a8fc2de39b5ab719; ?>
<?php unset($__attributesOriginal3393560a65f36031a8fc2de39b5ab719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3393560a65f36031a8fc2de39b5ab719)): ?>
<?php $component = $__componentOriginal3393560a65f36031a8fc2de39b5ab719; ?>
<?php unset($__componentOriginal3393560a65f36031a8fc2de39b5ab719); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal3393560a65f36031a8fc2de39b5ab719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3393560a65f36031a8fc2de39b5ab719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.switch','data' => ['name' => 'is_menu','label' => 'Trạng thái','checked' => old('is_menu', $category->is_menu)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_menu','label' => 'Trạng thái','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('is_menu', $category->is_menu))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3393560a65f36031a8fc2de39b5ab719)): ?>
<?php $attributes = $__attributesOriginal3393560a65f36031a8fc2de39b5ab719; ?>
<?php unset($__attributesOriginal3393560a65f36031a8fc2de39b5ab719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3393560a65f36031a8fc2de39b5ab719)): ?>
<?php $component = $__componentOriginal3393560a65f36031a8fc2de39b5ab719; ?>
<?php unset($__componentOriginal3393560a65f36031a8fc2de39b5ab719); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal3393560a65f36031a8fc2de39b5ab719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3393560a65f36031a8fc2de39b5ab719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.switch','data' => ['name' => 'is_footer','label' => 'Trạng thái','checked' => old('is_footer', $category->is_footer)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_footer','label' => 'Trạng thái','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('is_footer', $category->is_footer))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3393560a65f36031a8fc2de39b5ab719)): ?>
<?php $attributes = $__attributesOriginal3393560a65f36031a8fc2de39b5ab719; ?>
<?php unset($__attributesOriginal3393560a65f36031a8fc2de39b5ab719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3393560a65f36031a8fc2de39b5ab719)): ?>
<?php $component = $__componentOriginal3393560a65f36031a8fc2de39b5ab719; ?>
<?php unset($__componentOriginal3393560a65f36031a8fc2de39b5ab719); ?>
<?php endif; ?>
            
        </div>

        <div class="card-footer">
            <button type="submit" name="action" value="update" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-outline-dark">Quay lại</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/categories/edit.blade.php ENDPATH**/ ?>