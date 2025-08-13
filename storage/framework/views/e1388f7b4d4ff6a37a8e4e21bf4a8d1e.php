<?php $__env->startSection('title', 'Bảng điều khiển'); ?>


<?php $__env->startSection('content_header'); ?>
    Bảng điều khiển
            <?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo e($totalProducts ?? 0); ?></h3>
                        <p>Tổng số Sản phẩm</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo e($totalPosts ?? 0); ?></h3>
                        <p>Tổng số Bài viết</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <a href="" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo e($totalContacts ?? 0); ?></h3>
                        <p>Tổng số Liên hệ</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <a href="" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        

    </div><?php $__env->stopSection(); ?>


<?php $__env->startPush('css'); ?>
    
<?php $__env->stopPush(); ?>


<?php $__env->startPush('js'); ?>
    <script> console.log('Hi!'); </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>