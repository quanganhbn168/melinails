<?php echo csrf_field(); ?>
<div class="row">
    <div class="col-md-8">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', $user->name ?? '')); ?>" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6 mb-3">
                
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', $user->email ?? '')); ?>">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        
        <div class="row">
             <div class="col-md-6 mb-3">
                
                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone" name="phone" value="<?php echo e(old('phone', $user->phone ?? '')); ?>" required>
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" value="<?php echo e(old('address', $user->address ?? '')); ?>">
                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        
        
        
        
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" name="password" autocomplete="new-password">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="generate-password-btn" title="Tạo mật khẩu ngẫu nhiên">
                            <i class="fas fa-random"></i>
                        </button>
                        <span class="input-group-text" id="toggle-password-span" style="cursor: pointer;" title="Hiện/Ẩn mật khẩu">
                            <i class="fas fa-eye" id="toggle-password-icon"></i>
                        </span>
                    </div>
                </div>
                <?php if(isset($user)): ?>
                    <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu.</small>
                <?php endif; ?>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    <div class="input-group-append">
                         <span class="input-group-text" id="toggle-password-confirmation-span" style="cursor: pointer;" title="Hiện/Ẩn mật khẩu">
                            <i class="fas fa-eye" id="toggle-password-confirmation-icon"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        
        <div class="mb-3">
            <label class="form-label fw-bold">Gán vai trò</label>
             <?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger small mb-2"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="p-3 border rounded" style="max-height: 300px; overflow-y: auto;">
                <?php if(isset($roles) && !$roles->isEmpty()): ?>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="<?php echo e($roleName); ?>" id="role-<?php echo e($roleName); ?>"
                                   <?php if(is_array(old('roles', $userRoles ?? [])) && in_array($roleName, old('roles', $userRoles ?? []))): ?> checked <?php endif; ?>>
                            <label class="form-check-label" for="role-<?php echo e($roleName); ?>">
                                <?php echo e($roleName); ?>

                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                     <p class="text-muted">Không có vai trò nào (thuộc guard web) để gán.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-primary"><?php echo e(isset($user) ? 'Cập nhật' : 'Tạo mới'); ?></button>
    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Hủy bỏ</a>
</div>


<?php $__env->startPush('js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const generateBtn = document.getElementById('generate-password-btn');

    // Sửa lại ID của nút toggle cho khớp với cấu trúc mới
    const toggleSpan = document.getElementById('toggle-password-span');
    const toggleIcon = document.getElementById('toggle-password-icon');
    const toggleConfirmationSpan = document.getElementById('toggle-password-confirmation-span');
    const toggleConfirmationIcon = document.getElementById('toggle-password-confirmation-icon');

    // 1. Chức năng tạo mật khẩu ngẫu nhiên (Không đổi)
    if (generateBtn) {
        generateBtn.addEventListener('click', function () {
            const randomPassword = generateStrongPassword();
            passwordInput.value = randomPassword;
            passwordConfirmationInput.value = randomPassword;
        });
    }

    // 2. Chức năng hiện/ẩn mật khẩu (Sửa lại để lắng nghe sự kiện trên thẻ span)
    if (toggleSpan) {
        toggleSpan.addEventListener('click', function () {
            togglePasswordVisibility(passwordInput, toggleIcon);
        });
    }
    
    if (toggleConfirmationSpan) {
        toggleConfirmationSpan.addEventListener('click', function () {
            togglePasswordVisibility(passwordConfirmationInput, toggleConfirmationIcon);
        });
    }

    // Hàm helper để tạo mật khẩu mạnh (Không đổi)
    function generateStrongPassword(length = 12) {
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let password = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }
        return password;
    }

    // Hàm helper để thay đổi trạng thái hiện/ẩn (Đổi class cho Font Awesome)
    function togglePasswordVisibility(inputField, iconElement) {
        if (inputField.type === "password") {
            inputField.type = "text";
            iconElement.classList.remove("fa-eye");
            iconElement.classList.add("fa-eye-slash");
        } else {
            inputField.type = "password";
            iconElement.classList.remove("fa-eye-slash");
            iconElement.classList.add("fa-eye");
        }
    }
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/admin/users/_form.blade.php ENDPATH**/ ?>