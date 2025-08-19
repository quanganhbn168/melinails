@extends('layouts.admin')
@section('title','Chỉnh sửa sản phẩm')
@section('content_header_title', 'Chỉnh sửa sản phẩm')
@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <strong>Đã có lỗi xảy ra:</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@php
    $activeTab = old('active_tab', 'general');
@endphp
<form action="{{ route('admin.products.update', $product->id ?? 0) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="active_tab" id="active_tab" value="{{ $activeTab }}">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'general' ? 'active' : '' }}"
                               id="tab-general-tab"
                               data-toggle="tab"
                               href="#tab-general"
                               role="tab"
                               aria-controls="tab-general"
                               aria-selected="{{ $activeTab === 'general' ? 'true' : 'false' }}">
                               Thông tin chung
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'variants' ? 'active' : '' }}"
                               id="tab-variants-tab"
                               data-toggle="tab"
                               href="#tab-variants"
                               role="tab"
                               aria-controls="tab-variants"
                               aria-selected="{{ $activeTab === 'variants' ? 'true' : 'false' }}">
                               Biến thể
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'attributes' ? 'active' : '' }}"
                               id="tab-attributes-tab"
                               data-toggle="tab"
                               href="#tab-attributes"
                               role="tab"
                               aria-controls="tab-attributes"
                               aria-selected="{{ $activeTab === 'attributes' ? 'true' : 'false' }}">
                               Thuộc tính
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}"
                             id="tab-general"
                             role="tabpanel"
                             aria-labelledby="tab-general-tab">
                            <x-form.input name="name" label="Tên sản phẩm" :value="old('name', $product->name ?? '')" required/>
                            <div class="row">
                                <div class="col-6">
                                    <x-form.input name="code" label="Mã sản phẩm" :value="old('code', $product->code ?? '')"/>
                                </div>
                                <div class="col-6">
                                    <x-form.select name="brand_id" label="Thương hiệu" :options="$brands" :selected="old('brand_id', $product->brand_id ?? '')"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <x-form.money-input name="price_discount" label="Giá bán" :value="old('price_discount', $product->price_discount ?? '')"/>
                                </div>
                                <div class="col-6">
                                    <x-form.money-input name="price" label="Giá so sánh" :value="old('price', $product->price ?? '')"/>
                                </div>
                            </div>
                            <x-form.image-input name="image" label="Ảnh đại diện" :value="old('image', $product->image ?? '')" />
                            <x-form.image-multi-input name="gallery" label="Ảnh chi tiết" :images="old('gallery', $product->images ?? [])" />
                            <x-form.ckeditor name="description" label="Mô tả" :value="old('description', $product->description ?? '')"/>
                            <x-form.ckeditor name="content" label="Nội dung" :value="old('content', $product->content ?? '')"/>
                        </div>
                        <div class="tab-pane fade {{ $activeTab === 'variants' ? 'show active' : '' }}"
                             id="tab-variants"
                             role="tabpanel"
                             aria-labelledby="tab-variants-tab">
                            <x-form.switch name="has_variants" label="Đây là sản phẩm có biến thể" :checked="old('has_variants', $product->has_variants ?? false)"/>
                            <small>Chọn tối đa 3 thuộc tính</small>
                            <div id="show_variants">
                                <hr>
                                <div class="form-group">
                                    <label for="variant_attribute_ids">Chọn thuộc tính</label>
                                    <select
                                        name="variant_attribute_ids[]"
                                        id="variant_attribute_ids"
                                        class="select2 form-control"
                                        multiple
                                        data-placeholder="--- Chọn thuộc tính biến thể ---">
                                        @foreach($variantAttributes as $attribute)
                                            <option value="{{ $attribute->id }}"
                                                    @if(in_array($attribute->id, $selectedVariantAttributeIds)) selected @endif>
                                                {{ $attribute->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="variant-values-container">

                                @foreach($variantAttributes->whereIn('id', $selectedVariantAttributeIds) as $attribute)
                                @php
            
                                $currentSelectedValues = $selectedVariantValues[$attribute->id] ?? [];
                                @endphp
                                <div class="bg-light p-3 border rounded mb-3" data-attribute-id="{{ $attribute->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-3"><label class="mb-0">{{ $attribute->name }}</label></div>
                                        <div class="col-md-8">
                                            <select name="attribute_values[{{ $attribute->id }}][]" class="form-control variant-value-select" multiple>
                                                @foreach($attribute->values as $value)
                                                <option value="{{ $value->id }}" 
                                                    @if(in_array($value->id, $currentSelectedValues)) selected @endif
                                                    >
                                                    {{ $value->value }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-variant-attribute" title="Xóa thuộc tính"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row mt-3" id="bulk-edit-container" style="display: none;">
                                <div class="col-12">
                                    <div class="card card-warning collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title">Chỉnh sửa hàng loạt</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="bulk_sku">Mã SKU</label>
                                                        <input type="text" id="bulk_sku" class="form-control" placeholder="Để trống nếu không muốn thay đổi">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="bulk_price">Giá bán</label>
                                                        <input type="number" id="bulk_price" class="form-control" placeholder="Để trống nếu không muốn thay đổi">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="bulk_compare_price">Giá so sánh</label>
                                                        <input type="number" id="bulk_compare_price" class="form-control" placeholder="Để trống nếu không muốn thay đổi">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="bulk_stock">Tồn kho</label>
                                                        <input type="number" id="bulk_stock" class="form-control" placeholder="Để trống nếu không muốn thay đổi">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" id="apply_bulk_edit" class="btn btn-warning">Áp dụng</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="variants-table-container" class="mt-4">
                            </div>
                        </div>
                        <div class="tab-pane fade {{ $activeTab === 'attributes' ? 'show active' : '' }}"
                             id="tab-attributes"
                             role="tabpanel"
                             aria-labelledby="tab-attributes-tab">
                            <div class="bg-light p-3 border rounded mt-3">
                                <em>Placeholder giá trị thuộc tính (tuỳ UI của anh, có thể là badges / inputs / Select2 values...).</em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin bổ sung</h3>
                </div>
                <div class="card-body">
                    <x-form.switch name="status" label="Trạng thái" :checked="old('status', $product->status ?? true)"/>
                    <hr>
                    <x-form.select
                        name="cate_type"
                        id="cate_type"
                        label="Loại danh mục"
                        :options="[\App\Models\Category::TYPE_PHYSICS => 'Sản phẩm', \App\Models\Category::TYPE_SERVICE => 'Dịch vụ']"
                        :selected="old('cate_type', $product->cate_type ?? \App\Models\Category::TYPE_PHYSICS)"
                        required
                    />
                    <x-form.select
                        name="category_id"
                        label="Danh mục cha"
                        :selected="old('category_id', $product->category_id ?? '')"
                        :options="$categories"
                        required
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="text-right mt-3">
        <button type="submit" name="action" value="save" class="btn btn-success">Cập nhật</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </div>
</form>
@endsection
@push('js')
<script>
$(document).ready(function() {
    // === PHẦN 1: KHAI BÁO ===
    const allVariantAttributes = @json($variantAttributes->keyBy('id'));
    const existingVariants = @json($product->variants);
    const hasVariantsCheckbox = $('#has_variants');
    const showVariantsContainer = $('#show_variants');
    const mainAttributeSelect = $('#variant_attribute_ids');
    const variantValuesContainer = $('#variant-values-container');
    const variantsTableContainer = $('#variants-table-container');
    const bulkEditContainer = $('#bulk-edit-container');
    const defaultPrice = @json(old('price_discount', $product->price_discount ?? null));
    const defaultComparePrice = @json(old('price', $product->price ?? null));
    let selectedVariantValues = @json($selectedVariantValues ?? (object)[]);
    let variantValuesMap = {};
    let currentVariantsData = {};

    // === PHẦN 2: ĐỊNH NGHĨA HÀM ===
    function slugify(text) { return text.toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '').replace(/--+/g, '-').replace(/^-+/, '').replace(/-+$/, ''); }
    
    function renderVariantValueSelects(selectedAttributeIds) {
        variantValuesContainer.empty();
        bulkEditContainer.toggle(!!(selectedAttributeIds && selectedAttributeIds.length > 0));
        if (!selectedAttributeIds || selectedAttributeIds.length === 0) {
            generateVariantsTable();
            return;
        }
        selectedAttributeIds.forEach(attributeId => {
            const attribute = allVariantAttributes[attributeId];
            if (!attribute) return;
            let optionsHtml = '';
            (attribute.values || []).forEach(value => { optionsHtml += `<option value="${value.id}">${value.value}</option>`; });
            const rowHtml = `<div class="bg-light p-3 border rounded mb-3" data-attribute-id="${attributeId}"><div class="row align-items-center"><div class="col-md-3"><label class="mb-0">${attribute.name}</label></div><div class="col-md-8"><select name="attribute_values[${attributeId}][]" class="form-control variant-value-select" multiple>${optionsHtml}</select></div><div class="col-md-1 text-center"><button type="button" class="btn btn-danger btn-sm remove-variant-attribute" title="Xóa thuộc tính"><i class="fas fa-trash"></i></button></div></div></div>`;
            variantValuesContainer.append(rowHtml);
            const selectElement = variantValuesContainer.find(`[data-attribute-id="${attributeId}"] .variant-value-select`);
            selectElement.select2({ placeholder: `--- Chọn giá trị ${attribute.name} ---`, width: '100%', closeOnSelect: false, theme: 'bootstrap4' });
            if (selectedVariantValues[attributeId]) {
                selectElement.val(selectedVariantValues[attributeId]).trigger('change.select2');
            }
        });
    }
    
    function generateVariantsTable() {
        const selectedAttributeIds = mainAttributeSelect.val() || [];
        const valueGroups = selectedAttributeIds.map(id => selectedVariantValues[id]).filter(v => v && v.length > 0);
        if (valueGroups.length < selectedAttributeIds.length || valueGroups.length === 0) { variantsTableContainer.empty(); return; }
        const getCombinations = (arrays) => {
            if (arrays.length === 0) return [];
            let result = arrays[0].map(item => [item]);
            for (let i = 1; i < arrays.length; i++) { let nextResult = []; result.forEach(combination => { arrays[i].forEach(item => nextResult.push([...combination, item])); }); result = nextResult; }
            return result;
        };
        const valueIdCombinations = getCombinations(valueGroups);
        let tableHtml = `<h4>Bảng biến thể (${valueIdCombinations.length} phiên bản)</h4><div class="table-responsive"><table class="table table-bordered table-striped" id="variants-table"><thead><tr><th>Tên biến thể</th><th>Mã SKU</th><th>Giá bán</th><th>Giá so sánh</th><th>Tồn kho</th></tr></thead><tbody>`;
        valueIdCombinations.forEach(combo => {
            const sortedComboIds = [...combo].sort((a, b) => String(a).localeCompare(String(b)));
            const variantKey = sortedComboIds.join('_');
            const variantName = sortedComboIds.map(id => variantValuesMap[id]).join(' / ');
            const existingVariant = currentVariantsData[variantKey] || {};
            const productCode = $('input[name="code"]').val().trim() || 'SP';
            const generatedSku = `${productCode}-${slugify(variantName)}`;
            tableHtml += `<tr data-key="${variantKey}"><td>${variantName}<input type="hidden" name="variants[${variantKey}][attribute_values]" value="${sortedComboIds.join(',')}" /></td><td><input type="text" name="variants[${variantKey}][sku]" class="form-control form-control-sm" value="${existingVariant.sku || generatedSku}"></td><td><input type="number" name="variants[${variantKey}][price]" class="form-control form-control-sm" value="${existingVariant.price !== undefined ? existingVariant.price : defaultPrice || ''}"></td><td><input type="number" name="variants[${variantKey}][compare_price]" class="form-control form-control-sm" value="${existingVariant.compare_price !== undefined ? existingVariant.compare_price : defaultComparePrice || ''}"></td><td><input type="number" name="variants[${variantKey}][stock]" class="form-control form-control-sm" value="${existingVariant.stock !== undefined ? existingVariant.stock : 1}"></td></tr>`;
        });
        tableHtml += `</tbody></table></div>`;
        variantsTableContainer.html(tableHtml);
    }

    // === PHẦN 3: KHỞI TẠO ===
    function initialize() {
        Object.values(allVariantAttributes).forEach(attr => { (attr.values || []).forEach(val => variantValuesMap[val.id] = val.value); });
        existingVariants.forEach(variant => {
            if (variant.attribute_values && variant.attribute_values.length > 0) {
                const key = variant.attribute_values.map(val => val.id).sort((a, b) => a - b).join('_');
                currentVariantsData[key] = variant;
            }
        });

        mainAttributeSelect.select2({ placeholder: '--- Chọn thuộc tính biến thể ---', width: '100%', maximumSelectionLength: 3, closeOnSelect: false, theme: 'bootstrap4' });

        $('.variant-value-select').each(function() {
            const selectElement = $(this);
            const attributeName = selectElement.closest('.bg-light').find('label').text();
            selectElement.select2({ placeholder: `--- Chọn giá trị ${attributeName} ---`, width: '100%', closeOnSelect: false, theme: 'bootstrap4' });
        });
        
        if (hasVariantsCheckbox.is(':checked')) { showVariantsContainer.show(); bulkEditContainer.show(); } else { showVariantsContainer.hide(); }
        
        generateVariantsTable();
    }

    // === PHẦN 4: GÁN SỰ KIỆN ===
    hasVariantsCheckbox.on('change', function() { if ($(this).is(':checked')) { showVariantsContainer.show(); } else { showVariantsContainer.hide(); } });
    // Thay thế đoạn mã mainAttributeSelect.on('change',...) cũ bằng đoạn này

mainAttributeSelect.on('change', function() {
    const selectedIds = $(this).val() || [];
    
    // Tạo một đối tượng mới để lưu các giá trị được giữ lại
    let newSelectedValues = {};

    // Lặp qua các ID thuộc tính vừa được chọn
    selectedIds.forEach(id => {
        // Nếu thuộc tính này đã tồn tại trong danh sách cũ,
        // thì sao chép giá trị của nó qua đối tượng mới.
        if (selectedVariantValues[id]) {
            newSelectedValues[id] = selectedVariantValues[id];
        }
    });

    // Gán lại biến chính bằng đối tượng mới đã được lọc
    selectedVariantValues = newSelectedValues;

    // Vẽ lại giao diện và bảng biến thể
    renderVariantValueSelects(selectedIds);
    generateVariantsTable();
});
    variantValuesContainer.on('change', '.variant-value-select', function() { const attributeId = $(this).closest('[data-attribute-id]').data('attribute-id').toString(); selectedVariantValues[attributeId] = $(this).val() || []; generateVariantsTable(); });
    variantValuesContainer.on('click', '.remove-variant-attribute', function() { const attributeIdToRemove = $(this).closest('[data-attribute-id]').data('attribute-id').toString(); const currentSelectedIds = mainAttributeSelect.val(); const newSelectedIds = currentSelectedIds.filter(id => id !== attributeIdToRemove); mainAttributeSelect.val(newSelectedIds).trigger('change'); });
    $('#apply_bulk_edit').on('click', function() {
        const bulkSku = $('#bulk_sku').val().trim(), bulkPrice = $('#bulk_price').val(), bulkComparePrice = $('#bulk_compare_price').val(), bulkStock = $('#bulk_stock').val();
        $('#variants-table tbody tr').each(function() {
            const row = $(this);
            if (bulkSku) { const variantName = row.find('td:first').text().trim(), variantSlug = slugify(variantName); row.find('input[name*="[sku]"]').val(`${bulkSku}-${variantSlug}`); }
            if (bulkPrice) row.find('input[name*="[price]"]').val(bulkPrice);
            if (bulkComparePrice) row.find('input[name*="[compare_price]"]').val(bulkComparePrice);
            if (bulkStock) row.find('input[name*="[stock]"]').val(bulkStock);
        });
    });
    
    // === CHẠY SCRIPT ===
    initialize();
});
</script>
@endpush