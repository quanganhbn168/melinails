<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product']));

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

foreach (array_filter((['product']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product']));

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

foreach (array_filter((['product']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php $__env->startPush('jsonld'); ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "<?php echo e($product->name); ?>",
  "image": [
    "<?php echo e(asset($product->image)); ?>"
  ],
  "description": "<?php echo e(e(strip_tags($product->description))); ?>",
  "sku": "<?php echo e($product->id); ?>",
  "mpn": "<?php echo e($product->sku ?? 'SAMAN-' . $product->id); ?>",
  "brand": {
    "@type": "Brand",
    "name": "<?php echo e($setting->name ?? 'WebApp Bắc Ninh'); ?>"
  },
  <?php if($product->category): ?>
  "category": {
    "@type": "Thing",
    "name": "<?php echo e($product->category->name); ?>"
  },
  <?php endif; ?>
  "offers": {
    "@type": "Offer",
    "url": "<?php echo e(request()->url()); ?>",
    "priceCurrency": "VND",
    "price": "<?php echo e($product->price_discount > 0 ? $product->price_discount : $product->price); ?>",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "https://schema.org/InStock",
    "priceValidUntil": "<?php echo e(now()->addYear()->toDateString()); ?>"
  }
}
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/schema/product.blade.php ENDPATH**/ ?>