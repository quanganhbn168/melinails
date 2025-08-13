
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'options' => [], 'selected' => '', 'required' => false, 'placeholder' => '--- Chọn ---']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'label', 'options' => [], 'selected' => '', 'required' => false, 'placeholder' => '--- Chọn ---']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $selected = old($name, $selected);
?>


<div class="form-group">
    <label for="<?php echo e($name); ?>">
        <?php echo e($label); ?> <?php if($required): ?><span class="text-danger">*</span><?php endif; ?>
    </label>
        <select
            name="<?php echo e($name); ?>"
            id="<?php echo e($name); ?>"
            <?php echo e($attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')])); ?>

        >
            <option value="0"><?php echo e($placeholder); ?></option>
            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>" <?php if($key == $selected): echo 'selected'; endif; ?>> <?php echo e($text); ?> </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/form/select.blade.php ENDPATH**/ ?>