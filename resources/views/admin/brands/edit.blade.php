@extends('layouts.admin')

@section('title', 'Cập nhật thương hiệu')
@section('content_header', 'Cập nhật thương hiệu')

@section('content')
<form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <x-form.input name="name" label="Tên thương hiệu" :value="$brand->name" required />
            <x-form.input name="slug" label="Slug" :value="$brand->slug" />
            <x-form.image-input name="image" label="Logo" :value="$brand->image" />
            <x-form.switch name="status" label="Hiển thị" :checked="$brand->status" />
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
@endsection
