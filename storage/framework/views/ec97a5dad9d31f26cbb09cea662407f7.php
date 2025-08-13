

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $hasSub = !empty($item['submenu']);
            $isMenuActive = $hasSub ? $component->isOpen($item) : $component->isActive($item);
            $url = $hasSub ? '#' : (isset($item['route']) ? route($item['route'], $item['params'] ?? []) : '#');
        ?>

        <li class="nav-item <?php echo e($hasSub && $isMenuActive ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="<?php echo e($url); ?>" class="nav-link <?php echo e($isMenuActive ? 'active' : ''); ?>">
                <i class="nav-icon <?php echo e($item['icon']); ?>"></i>
                <p>
                    <?php echo e($item['title']); ?>

                    <?php if($hasSub): ?>
                        <i class="right fas fa-angle-left"></i>
                    <?php endif; ?>
                </p>
            </a>

            <?php if($hasSub): ?>
                <ul class="nav nav-treeview">
                    <?php $__currentLoopData = $item['submenu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item">
                            <a href="<?php echo e(isset($sub['route']) ? route($sub['route'], $sub['params'] ?? []) : '#'); ?>"
                               
                               class="nav-link <?php echo e($component->isActive($sub) ? 'active' : ''); ?>">
                                <i class="<?php echo e($sub['icon'] ?? 'far fa-circle'); ?> nav-icon"></i>
                                <p><?php echo e($sub['title']); ?></p>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/sidebar-menu.blade.php ENDPATH**/ ?>