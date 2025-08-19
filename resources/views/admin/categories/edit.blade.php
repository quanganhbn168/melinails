@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Chỉnh sửa danh mục: {{ $category->name }}</h3>
        </div>
        <div class="card-body">
            @include('admin.categories._form')
        </div>
    </div>
</div>
@endsection