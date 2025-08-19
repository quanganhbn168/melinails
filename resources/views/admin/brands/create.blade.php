@extends('layouts.admin')

@section('title', 'Thêm thương hiệu')
@section('content_header', 'Thêm thương hiệu')

@section('content')
<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <x-form.input name="name" label="Tên thương hiệu" required />
            <x-form.input name="slug" label="Slug (nếu không nhập sẽ tự tạo)" />
            <x-form.image-input name="image" label="Logo" />
            <x-form.switch name="status" label="Hiển thị" :checked="true" />
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection
