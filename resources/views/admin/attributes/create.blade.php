@extends('layouts.admin')

@section('title', 'Thêm thuộc tính')
@section('content_header_title', 'Thêm thuộc tính mới')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @include('admin.attributes._form', ['buttonText' => 'Tạo mới'])
            </form>
        </div>
    </div>
@endsection