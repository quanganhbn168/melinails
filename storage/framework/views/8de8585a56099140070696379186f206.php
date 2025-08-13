

<?php $__env->startSection('title', 'Quản lý Người dùng'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh sách Người dùng</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm người dùng mới
            </a>
            
            <form action="<?php echo e(route('admin.users.index')); ?>" method="GET" class="d-flex">
                <input type="text" class="form-control me-2" name="search" placeholder="Tìm kiếm tên, email..." value="<?php echo e(request('search')); ?>">
                <button type="submit" class="btn btn-secondary">Tìm</button>
            </form>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Email / Điện thoại</th>
                        <th scope="col">Vai trò</th>
                        <th scope="col" style="width: 15%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row"><?php echo e($key + 1); ?></th>
                            <td><?php echo e($user->name); ?></td>
                            <td>
                                <div><?php echo e($user->email); ?></div>
                                <small class="text-muted"><?php echo e($user->phone); ?></small>
                            </td>
                            <td>
                                <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-info"><?php echo e($roleName); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('Anh có chắc chắn muốn xóa người dùng này không?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">Không tìm thấy người dùng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($users->appends(request()->query())->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/users/index.blade.php ENDPATH**/ ?>