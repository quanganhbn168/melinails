<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'model',
    'record',
    'field',
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
    'model',
    'record',
    'field',
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

<?php
    $id     = is_object($record) ? $record->id : $record;
    $value  = is_object($record) ? $record->{$field} : false;
    $uid    = 'toggle-' . $model . '-' . $id . '-' . $field;
?>

<span id="<?php echo e($uid); ?>"
      class="badge badge-<?php echo e($value ? 'success' : 'danger'); ?> boolean-toggle"
      data-model="<?php echo e($model); ?>"
      data-id="<?php echo e($id); ?>"
      data-field="<?php echo e($field); ?>"
      style="cursor: pointer;">
    <?php echo e($value ? $onText : $offText); ?>

</span>

<?php if (! $__env->hasRenderedOnce('9a2ef5d8-c5da-46ae-a37c-0d11a6d211f9')): $__env->markAsRenderedOnce('9a2ef5d8-c5da-46ae-a37c-0d11a6d211f9'); ?>
<?php $__env->startPush('js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.boolean-toggle').forEach(el => {
        el.addEventListener('click', async function () {
            const span = this;

            const payload = {
                _token: '<?php echo e(csrf_token()); ?>',
                model : span.dataset.model,
                id    : span.dataset.id,
                field : span.dataset.field,
            };

            try {
                const res = await fetch('<?php echo e(route("admin.toggle")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': payload._token
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();

                if (json.success) {
                    const newValue = json.value;
                    span.classList.remove('badge-success', 'badge-danger');
                    span.classList.add(newValue ? 'badge-success' : 'badge-danger');
                    span.textContent = newValue ? '<?php echo e($onText); ?>' : '<?php echo e($offText); ?>';
                } else {
                    alert(json.error ?? 'Đã xảy ra lỗi.');
                }

            } catch (err) {
                alert('Không kết nối được đến máy chủ.');
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/boolean-toggle.blade.php ENDPATH**/ ?>