@extends('layouts.admin')
@section('title','Thêm sản phẩm mới')
@section('content_header_title', 'Thêm sản phẩm mới')

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

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="row">
		<div class="col-12 col-md-8">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Thông tin chung</h3>
				</div>
				<div class="card-body">
					<x-form.input name="name" label="Tên sản phẩm" required/>
					<div class="row">
						<div class="col-6">
							<x-form.input name="code" label="Mã sản phẩm" />
						</div>
						<div class="col-6">
							<x-form.select name="brand_id" label="Thương hiệu" :options="$brands" />
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<x-form.money-input name="price_discount" label="Giá bán" />
						</div>
						<div class="col-6">
							<x-form.money-input name="price" label="Giá so sánh" />
						</div>
					</div>
					<x-form.image-input name="image" label="Ảnh đại diện" />
					<x-form.image-multi-input name="gallery" label="Ảnh chi tiết" :images="$product->gallery ?? []" />
					<x-form.ckeditor name="description" label="Mô tả" />
					<x-form.ckeditor name="content" label="Nội dung" />
				</div>
			</div>
		</div>

		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Thông tin bổ sung</h3>
				</div>
				<div class="card-body">
					<x-form.switch name="status" label="Trạng thái" />
					<hr>
					<x-form.select 
						name="cate_type" 
						label="Loại danh mục"
						:options="[\App\Models\Category::TYPE_PHYSICS => 'Sản phẩm', \App\Models\Category::TYPE_SERVICE => 'Dịch vụ']"
						required
					/>
					<x-form.select
						name="category_id" 
						label="Danh mục sản phẩm" 
						required
					/>
				</div>
			</div>
		</div>
	</div>

	{{-- Nút hành động --}}
	<div class="text-right mt-3">
		<button type="submit" name="action" value="save" class="btn btn-success">Lưu</button>
		<button type="submit" name="action" value="save_new" class="btn btn-primary">Lưu & thêm mới</button>
		<button type="reset" class="btn btn-secondary">Reset</button>
	</div>
</form>
@endsection
@push('js')
<script>
$(document).ready(function () {
    var $cateType = $('#cate_type');
    var $category = $('#category_id');
    var routeUrl  = "{{ route('admin.select.categories-by-type') }}";

    function setLoading() {
        $category.prop('disabled', true)
            .html($('<option/>', {value: '0', text: 'Đang tải...'}));
    }

    function fillCategories(items, selectedId) {
        var frag = document.createDocumentFragment();
        frag.appendChild(new Option('--- Chọn ---', '0'));
        (items || []).forEach(function (it) {
            var opt = new Option(it.name, it.id);
            frag.appendChild(opt);
        });
        $category.empty().append(frag);
        if (selectedId) {
            $category.val(String(selectedId));
        } else {
            $category.val('0');
        }
        $category.prop('disabled', false).trigger('change');
    }

    function loadCategories(type, selectedId) {
        if (!type) { fillCategories([], null); return; }
        setLoading();
        $.ajax({
            url: routeUrl,
            type: 'GET',
            dataType: 'json',
            data: { type: type }
        }).done(function (res) {
            fillCategories(res, selectedId || null);
        }).fail(function () {
            $category.empty()
                .append(new Option('Không tải được danh mục', '0'))
                .prop('disabled', false);
        });
    }

    // Khi đổi cate_type (ở create thì reload list, ở edit thì không ảnh hưởng option đã render)
    $cateType.on('change', function () {
        loadCategories($(this).val(), null);
    });

    // Nếu đang ở create (categories rỗng) thì load theo cate_type đã chọn
    @if(empty($categories) || count($categories) === 0)
        loadCategories($cateType.val(), "{{ old('category_id') }}");
    @endif
});
</script>
@endpush