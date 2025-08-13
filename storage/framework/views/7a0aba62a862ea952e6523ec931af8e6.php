<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['categories' => [], 'routeName' => 'admin.categories.edit']));

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

foreach (array_filter((['categories' => [], 'routeName' => 'admin.categories.edit']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $grouped = collect($categories)->groupBy('parent_id');
    $rootItems = $grouped[0] ?? [];

    $renderTree = function ($items, $depth = 0) use (&$renderTree, $grouped, $routeName) {
        $html = '';
        foreach ($items as $item) {
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
            $icon = $depth === 0 ? '📁' : '📂';
            $url = route($routeName, $item['id']);
            $html .= "<div>{$indent}{$icon} <a href=\"{$url}\">{$item['name']}</a></div>";

            if ($grouped->has($item['id'])) {
                $html .= $renderTree($grouped[$item['id']], $depth + 1);
            }
        }
        return $html;
    };
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cấu trúc danh mục</h3>
    </div>
    <div class="card-body">
        <?php echo $renderTree($rootItems); ?>

    </div>
</div>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/categories/tree-card.blade.php ENDPATH**/ ?>