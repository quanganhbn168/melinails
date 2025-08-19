@extends('layouts.admin')

@section('title', 'Thêm danh mục mới')
@section('content_header_title', 'Thêm danh mục mới')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Tạo mới danh mục</h3>
        </div>
        <div class="card-body">
            @include('admin.categories._form')
        </div>
    </div>
</div>
@endsection