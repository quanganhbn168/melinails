

<?php $__env->startSection('title', 'Bảng điều khiển'); ?>

<?php $__env->startPush('css'); ?>
<style>
    /* Custom style cho trang dashboard */
    .dashboard-sidebar {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
    }
    .dashboard-sidebar .list-group-item.active {
        background-color: #343a40; /* Dark color for active item */
        border-color: #343a40;
    }
    .dashboard-sidebar .list-group-item i {
        margin-right: 10px;
        width: 20px;
    }
    .dashboard-content {
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
        border: 1px solid #dee2e6;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #eee;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="wrapper" class="py-5">
    <div class="container">
        <div class="row">
            
            <div class="col-md-3">
                <aside class="dashboard-sidebar">
                    <div class="text-center mb-3">
                        <img src="<?php echo e(asset(Auth::user()->avatar ?? 'https://placehold.co/120x120/EFEFEF/AAAAAA&text=avatar')); ?>" 
                             alt="Avatar" class="profile-avatar">
                        <h5 class="mt-2 mb-0"><?php echo e(Auth::user()->name); ?></h5>
                    </div>
                    <div class="list-group">
                        <a href="<?php echo e(route('user.dashboard')); ?>" 
                           class="list-group-item list-group-item-action <?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">
                           <i class="fa-solid fa-gauge-high"></i> Bảng điều khiển
                        </a>
                        <a href="<?php echo e(route('user.profile')); ?>" 
                           class="list-group-item list-group-item-action <?php echo e(request()->routeIs('user.profile') ? 'active' : ''); ?>">
                           <i class="fa-solid fa-user-pen"></i> Thông tin cá nhân
                        </a>
                        <a href="<?php echo e(route('user.orders')); ?>" 
                           class="list-group-item list-group-item-action <?php echo e(request()->routeIs('user.orders') || request()->routeIs('user.order.detail') ? 'active' : ''); ?>">
                           <i class="fa-solid fa-box-archive"></i> Lịch sử đơn hàng
                        </a>
                        <a href="<?php echo e(route('user.wishlist')); ?>" 
                           class="list-group-item list-group-item-action <?php echo e(request()->routeIs('user.wishlist') ? 'active' : ''); ?>">
                           <i class="fa-solid fa-heart"></i> Sản phẩm yêu thích
                        </a>
                        <a href="<?php echo e(route('logout')); ?>"
                           class="list-group-item list-group-item-action"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none"><?php echo csrf_field(); ?></form>
                    </div>
                </aside>
            </div>

            
            <div class="col-md-9">
                <main class="dashboard-content">
                    <?php echo $__env->yieldContent('dashboard_content'); ?>
                </main>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/dashboard/layout.blade.php ENDPATH**/ ?>