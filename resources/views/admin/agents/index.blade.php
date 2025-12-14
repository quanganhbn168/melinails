@extends('layouts.admin')

@section('title', 'Yêu cầu mở đại lý')
@section('content_header', 'Yêu cầu mở đại lý')

@push('css')
<style>
    .custom-checkbox { width: 18px; height: 18px; cursor: pointer; vertical-align: middle; }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    @endif

    {{-- BỘ LỌC --}}
    <div class="card collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Bộ lọc tìm kiếm</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <form method="GET" action="{{ route('admin.agents.index') }}" class="row">
                <div class="col-md-4">
                    <x-form.input name="keyword" label="Từ khóa" :value="request('keyword')" placeholder="Tên, SĐT, Email, Tên cửa hàng..." />
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Mới</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Đã xem</option>
                            <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Đã liên hệ</option>
                            <option value="cooperated" {{ request('status') == 'cooperated' ? 'selected' : '' }}>Đã hợp tác</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Hủy bỏ</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end mb-3">
                    <button class="btn btn-secondary btn-block"><i class="fas fa-search"></i> Lọc</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DANH SÁCH --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách yêu cầu</h3>
            <div class="card-tools d-flex align-items-center">
                {{-- Component Bulk Action --}}
                <x-admin.bulk-action-bar model="agents" />
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                <tr>
                    <th class="text-center" width="40">
                        <input type="checkbox" id="checkAll" class="custom-checkbox">
                    </th>
                    <th>Người liên hệ</th>
                    <th>Cửa hàng / Công ty</th>
                    <th>Khu vực</th>
                    <th>Bổ sung</th>
                    <th>Ngày gửi</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="custom-checkbox check-item" value="{{ $item->id }}">
                        </td>
                        <td>
                            <div class="font-weight-bold">{{ $item->name }}</div>
                            <div class="small text-muted">
                                <i class="fas fa-phone fa-xs mr-1"></i> {{ $item->phone }}
                                @if($item->email) <br><i class="fas fa-envelope fa-xs mr-1"></i> {{ $item->email }} @endif
                            </div>
                        </td>
                        <td>{{ $item->shop_name ?? '-' }}</td>
                        <td>{{ $item->area ?? '-' }}</td>
                        <td>
                             <div class="small text-muted line-clamp-2-new" style="max-width: 200px;">
                                {{ $item->details }}
                             </div>
                        </td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @switch($item->status)
                                @case('new') <span class="badge badge-warning">Mới</span> @break
                                @case('reviewed') <span class="badge badge-info">Đã xem</span> @break
                                @case('contacted') <span class="badge badge-success">Đã liên hệ</span> @break
                                @case('cooperated') <span class="badge badge-primary">Đã hợp tác</span> @break
                                @case('cancelled') <span class="badge badge-secondary">Hủy bỏ</span> @break
                                @default <span class="badge badge-light">{{ $item->status }}</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.agents.show', $item->id) }}" class="btn btn-sm btn-primary" title="Chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    onclick="UniversalBulk.confirmOne({{ $item->id }}, 'delete')" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có yêu cầu nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="card-footer clearfix">{{ $items->links() }}</div>
        @endif
    </div>
@endsection
