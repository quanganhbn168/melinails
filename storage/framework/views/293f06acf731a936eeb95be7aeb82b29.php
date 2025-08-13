
<?php $__env->startSection('title', 'Thêm bài viết'); ?>
<?php $__env->startSection('content_header', 'Thêm bài viết'); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.posts.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="card">
        <div class="card-body">
            <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'title','label' => 'Tiêu đề','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => 'Tiêu đề','required' => true]); ?>
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
            <?php if (isset($component)) { $__componentOriginalf9bd38000c95fd989f962c53d2580587 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf9bd38000c95fd989f962c53d2580587 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.category-select','data' => ['name' => 'post_category_id','label' => 'Danh mục','options' => $categories,'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.category-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'post_category_id','label' => 'Danh mục','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf9bd38000c95fd989f962c53d2580587)): ?>
<?php $attributes = $__attributesOriginalf9bd38000c95fd989f962c53d2580587; ?>
<?php unset($__attributesOriginalf9bd38000c95fd989f962c53d2580587); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf9bd38000c95fd989f962c53d2580587)): ?>
<?php $component = $__componentOriginalf9bd38000c95fd989f962c53d2580587; ?>
<?php unset($__componentOriginalf9bd38000c95fd989f962c53d2580587); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala9c02590cd582d41347c840796552ccc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9c02590cd582d41347c840796552ccc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image-input','data' => ['name' => 'image','label' => 'Ảnh đại diện','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','label' => 'Ảnh đại diện','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image-input','data' => ['name' => 'banner','label' => 'Banner']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'banner','label' => 'Banner']); ?>
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

            <?php if (isset($component)) { $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.textarea','data' => ['name' => 'description','label' => 'Mô tả ngắn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Mô tả ngắn']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab)): ?>
<?php $attributes = $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab; ?>
<?php unset($__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd97a59301ba78d56b3ed60dd41409ab)): ?>
<?php $component = $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab; ?>
<?php unset($__componentOriginalcd97a59301ba78d56b3ed60dd41409ab); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal88297ccebadcf777caa8c0f4be26c88c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal88297ccebadcf777caa8c0f4be26c88c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.ckeditor','data' => ['name' => 'content','label' => 'Nội dung']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.ckeditor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'content','label' => 'Nội dung']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal88297ccebadcf777caa8c0f4be26c88c)): ?>
<?php $attributes = $__attributesOriginal88297ccebadcf777caa8c0f4be26c88c; ?>
<?php unset($__attributesOriginal88297ccebadcf777caa8c0f4be26c88c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal88297ccebadcf777caa8c0f4be26c88c)): ?>
<?php $component = $__componentOriginal88297ccebadcf777caa8c0f4be26c88c; ?>
<?php unset($__componentOriginal88297ccebadcf777caa8c0f4be26c88c); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal3393560a65f36031a8fc2de39b5ab719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3393560a65f36031a8fc2de39b5ab719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.switch','data' => ['name' => 'is_featured','label' => 'Nổi bật','checked' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_featured','label' => 'Nổi bật','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.switch','data' => ['name' => 'status','label' => 'Hiển thị','checked' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','label' => 'Hiển thị','checked' => true]); ?>
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
            <button type="submit" name="save" class="btn btn-primary">Lưu</button>
            <button type="submit" name="save_new" class="btn btn-success">Lưu & Thêm mới</button>
            <a href="<?php echo e(route('admin.posts.index')); ?>" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/posts/create.blade.php ENDPATH**/ ?>