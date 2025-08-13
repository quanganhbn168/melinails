<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['items' => []]));

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

foreach (array_filter((['items' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<nav aria-label="breadcrumb" class="">
  <ol class="breadcrumb bg-transparent">
    <li class="breadcrumb-item">
      <a href="<?php echo e(url('/')); ?>">Trang chủ</a>
    </li>

    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if($loop->last): ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo e($item['label']); ?></li>
      <?php else: ?>
        <li class="breadcrumb-item">
          <a href="<?php echo e($item['url']); ?>"><?php echo e($item['label']); ?></a>
        </li>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ol>
</nav>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/frontend/breadcrumb.blade.php ENDPATH**/ ?>