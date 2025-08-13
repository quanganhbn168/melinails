<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand-link">
    <img src="<?php echo e(asset('favicon/icon-192.png')); ?>" alt="<?php echo e($setting->name); ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?php echo e($setting->name); ?></span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <?php if (isset($component)) { $__componentOriginal4088d25bd82bc725cd179ab6ddc6f4b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4088d25bd82bc725cd179ab6ddc6f4b8 = $attributes; } ?>
<?php $component = App\View\Components\SidebarMenu::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SidebarMenu::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4088d25bd82bc725cd179ab6ddc6f4b8)): ?>
<?php $attributes = $__attributesOriginal4088d25bd82bc725cd179ab6ddc6f4b8; ?>
<?php unset($__attributesOriginal4088d25bd82bc725cd179ab6ddc6f4b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4088d25bd82bc725cd179ab6ddc6f4b8)): ?>
<?php $component = $__componentOriginal4088d25bd82bc725cd179ab6ddc6f4b8; ?>
<?php unset($__componentOriginal4088d25bd82bc725cd179ab6ddc6f4b8); ?>
<?php endif; ?>
      <!--end::Sidebar Menu-->
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/partials/admin/sidebar.blade.php ENDPATH**/ ?>