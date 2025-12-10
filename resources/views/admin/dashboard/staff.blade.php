@extends('layouts.admin')

@section('title', 'Việc của tôi')
@section('content_header_title', 'Tổng quan công việc')

@section('content')
<div class="container-fluid">

    {{-- 1. STATS CARDS - CHỈ HIỆN CÔNG VIỆC CỦA TÔI --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $myPendingTasks }}</h3>
                    <p>Task chờ xử lý</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('admin.my-work-orders.index') }}?status=pending" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $myProcessingTasks }}</h3>
                    <p>Task đang làm</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <a href="{{ route('admin.my-work-orders.index') }}?status=processing" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $myCompletedTasks }}</h3>
                    <p>Task hoàn thành (tháng)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('admin.my-work-orders.index') }}?status=completed" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($myCollectedAmount) }}đ</h3>
                    <p>Tiền thu (tháng)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <span class="small-box-footer">&nbsp;</span>
            </div>
        </div>
    </div>

    {{-- 2. CÔNG VIỆC HÔM NAY --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks mr-1"></i> 
                        Công việc hôm nay ({{ now()->format('d/m/Y') }})
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.my-work-orders.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list"></i> Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($todayTasks->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($todayTasks as $task)
                                <a href="{{ route('admin.work-orders.show', $task->work_order_id) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="font-weight-bold">
                                            {{ $task->workOrder->code ?? 'N/A' }} - {{ $task->workOrder->title ?? 'Công việc' }}
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ Str::limit($task->workOrder->site_address ?? 'Chưa có địa chỉ', 50) }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="badge badge-{{ $task->status->color() }}">
                                            {{ $task->status->label() }}
                                        </span>
                                        @if($task->workOrder && $task->workOrder->deadline && $task->workOrder->deadline->isPast())
                                            <span class="badge badge-danger ml-1">Quá hạn</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-umbrella-beach fa-3x mb-3 opacity-50"></i>
                            <p>Hôm nay không có công việc mới!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 3. PHIẾU VIỆC GẦN ĐÂY CỦA TÔI --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>
                        Phiếu việc gần đây của tôi
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Mã phiếu</th>
                                    <th>Khách hàng</th>
                                    <th>Địa chỉ</th>
                                    <th>Trạng thái</th>
                                    <th>Deadline</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myRecentWorkOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.work-orders.show', $order->id) }}" class="font-weight-bold">
                                                {{ $order->code }}
                                            </a>
                                        </td>
                                        <td>{{ $order->customer->name ?? 'Khách lẻ' }}</td>
                                        <td>
                                            <small>{{ Str::limit($order->site_address, 40) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $order->status->color() }}">
                                                {{ $order->status->label() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($order->deadline)
                                                @if($order->deadline->isPast() && !$order->isLocked())
                                                    <span class="text-danger">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        {{ $order->deadline->format('d/m H:i') }}
                                                    </span>
                                                @else
                                                    {{ $order->deadline->format('d/m H:i') }}
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.work-orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Chưa có phiếu việc nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
