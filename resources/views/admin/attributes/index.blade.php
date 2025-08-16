@extends('layouts.admin')

@section('title', 'Danh sách thuộc tính')
@section('content_header_title', 'Danh sách thuộc tính')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-tools">
                <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm thuộc tính
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Tên thuộc tính</th>
                        <th>Kiểu hiển thị</th>
                        <th>Các giá trị</th>
                        <th style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attributes as $key => $attribute)
                    <tr>
                        <td>{{ $attributes->firstItem() + $key }}</td>
                        <td>{{ $attribute->name }}</td>
                        <td><span class="badge badge-secondary">{{ $attribute->type }}</span></td>
                        <td>
                            {{-- Hiển thị 5 giá trị đầu tiên --}}
                            @foreach($attribute->values->take(5) as $value)
                                <span class="badge badge-info">{{ $value->value }}</span>
                            @endforeach
                            @if($attribute->values->count() > 5)
                                <span>...</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-sm btn-warning">
                                <i class="far fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="far fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có dữ liệu.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($attributes->hasPages())
        <div class="card-footer">
            {{ $attributes->links() }}
        </div>
        @endif
    </div>
@endsection