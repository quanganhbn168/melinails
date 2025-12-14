@extends('layouts.admin')

@section('title', 'Chi tiết yêu cầu: ' . $item->name)
@section('content_header', 'Chi tiết yêu cầu tư vấn')

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Thông tin đăng ký</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong class="text-muted d-block">Họ và tên người liên hệ:</strong>
                        <span class="h5">{{ $item->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong class="text-muted d-block">Công ty / Doanh nghiệp:</strong>
                        <span class="h5 text-primary">{{ $item->company ?? '(Chưa cung cấp)' }}</span>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong class="text-muted d-block">Số điện thoại:</strong>
                         <a href="tel:{{ $item->phone }}" class="h6"><i class="fas fa-phone mr-1"></i> {{ $item->phone }}</a>
                    </div>
                    <div class="col-md-6">
                        <strong class="text-muted d-block">Email:</strong>
                        @if($item->email)
                            <a href="mailto:{{ $item->email }}" class="h6"><i class="fas fa-envelope mr-1"></i> {{ $item->email }}</a>
                        @else
                            <span class="text-muted small">Không cung cấp</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-4">
                     <div class="col-md-6">
                        <strong class="text-muted d-block">Ngân sách dự kiến:</strong>
                        <span class="badge badge-success" style="font-size: 1rem;">{{ $item->budget ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong class="text-muted d-block">Địa chỉ triển khai:</strong>
                        <span class="">{{ $item->address ?? '-' }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <strong class="text-muted">Mô tả / Yêu cầu chi tiết:</strong>
                    <div class="p-3 bg-light rounded mt-2 border">
                        {!! nl2br(e($item->details)) !!}
                    </div>
                </div>

                <div class="form-group mt-4">
                    <strong class="text-muted d-block mb-2">File đính kèm (Bản vẽ, Hồ sơ):</strong>
                    @if($item->file_path)
                         <div class="alert alert-secondary d-flex align-items-center">
                            <i class="fas fa-file-alt fa-2x mr-3"></i>
                            <div>
                                <h6 class="mb-0">File đính kèm</h6>
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="font-weight-bold text-decoration-none">
                                    Tải về / Xem file
                                </a>
                            </div>
                         </div>
                    @else
                        <p class="text-muted font-italic">Không có file đính kèm.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hành động</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.consulting-requests.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="new" {{ $item->status == 'new' ? 'selected' : '' }}>Mới</option>
                            <option value="reviewed" {{ $item->status == 'reviewed' ? 'selected' : '' }}>Đã xem</option>
                            <option value="contacted" {{ $item->status == 'contacted' ? 'selected' : '' }}>Đã liên hệ</option>
                            <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Hủy bỏ</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-save mr-1"></i> Cập nhật
                    </button>
                </form>

                <hr>
                <div class="text-muted small">
                    <p class="mb-1"><i class="far fa-clock"></i> Gửi lúc: {{ $item->created_at->format('H:i d/m/Y') }}</p>
                    <p class="mb-1"><i class="far fa-edit"></i> Cập nhật: {{ $item->updated_at->format('H:i d/m/Y') }}</p>
                </div>

                <form action="{{ route('admin.consulting-requests.destroy', $item->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Xóa vĩnh viễn yêu cầu này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-block">
                        <i class="fas fa-trash-alt mr-1"></i> Xóa yêu cầu
                    </button>
                </form>
            </div>
        </div>
        <a href="{{ route('admin.consulting-requests.index') }}" class="btn btn-default btn-block mt-3">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
        </a>
    </div>
</div>
@endsection
