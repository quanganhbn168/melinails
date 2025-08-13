<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'value' => '', 'required' => false, 'defaultImage' => 'images/setting/no-image.png']));

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

foreach (array_filter((['name', 'label', 'value' => '', 'required' => false, 'defaultImage' => 'images/setting/no-image.png']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $imageUrl = old($name, $value) ?: $defaultImage;
    $inputId = 'input_' . $name;
    $previewId = 'preview_' . $name;
?>

<div class="form-group">
    <label for="<?php echo e($inputId); ?>">
        <?php echo e($label); ?> <?php if($required): ?><span class="text-danger">*</span><?php endif; ?>
    </label>
    
        <input
            type="file"
            name="<?php echo e($name); ?>"
            id="<?php echo e($inputId); ?>"
            accept="image/*"
            <?php echo e($attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')])); ?>

            onchange="previewImage('<?php echo e($inputId); ?>', '<?php echo e($previewId); ?>')"
        >
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

        <div class="mt-2">
            <img
                id="<?php echo e($previewId); ?>"
                src="<?php echo e(asset($imageUrl)); ?>"
                alt="Preview"
                style="max-height: 150px; border: 1px solid #ddd; padding: 4px; background-color: #f8f8f8;"
            >
        </div>
    
</div>

<?php $__env->startPush('js'); ?>
<script>
    function previewImage(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/form/image-input.blade.php ENDPATH**/ ?>