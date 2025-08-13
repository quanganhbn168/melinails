<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label',
    'checked' => false,
    'onText' => 'Hiện',
    'offText' => 'Ẩn',
]));

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

foreach (array_filter(([
    'name',
    'label',
    'checked' => false,
    'onText' => 'Hiện',
    'offText' => 'Ẩn',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="form-group">
    <label for="<?php echo e($name); ?>"><?php echo e($label); ?></label>
    
        <div class="d-flex align-items-center gap-3">
            <div class="custom-control custom-switch">
                <input type="hidden" name="<?php echo e($name); ?>" value="0">
                <input
                    type="checkbox"
                    id="<?php echo e($name); ?>"
                    name="<?php echo e($name); ?>"
                    value="1"
                    class="custom-control-input <?php echo e($errors->has($name) ? 'is-invalid' : ''); ?>"
                    <?php echo e($checked ? 'checked' : ''); ?>

                    onchange="updateSwitchLabel('<?php echo e($name); ?>', '<?php echo e($onText); ?>', '<?php echo e($offText); ?>')"
                >
                <label class="custom-control-label" for="<?php echo e($name); ?>"></label>
            </div>
            <span id="label_<?php echo e($name); ?>">
                <span class="badge <?php echo e($checked ? 'badge-success' : 'badge-secondary'); ?>">
                    <?php echo e($checked ? $onText : $offText); ?>

                </span>
            </span>
        </div>
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
    
</div>

<?php $__env->startPush('js'); ?>
<script>
    function updateSwitchLabel(name, onText, offText) {
        const input = document.getElementById(name);
        const label = document.getElementById('label_' + name);
        if (!input || !label) return;

        const isChecked = input.checked;
        label.innerHTML = `<span class="badge ${isChecked ? 'badge-success' : 'badge-secondary'}">${isChecked ? onText : offText}</span>`;
    }
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/form/switch.blade.php ENDPATH**/ ?>