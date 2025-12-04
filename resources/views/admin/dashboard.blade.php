@extends('layouts.admin')

@section('title', 'Dashboard Quản Lý')
@section('content_header_title', 'Tổng quan hệ thống')

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">

    {{-- 1. TOP STATS CARDS --}}
    <div class="row">
        {{-- Tổng sản phẩm --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalProducts) }}</h3>
                    <p>Sản phẩm</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Tổng bài viết --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalPosts) }}</h3>
                    <p>Bài viết Blog</p>
                </div>
                <div class="icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <a href="{{ route('admin.posts.index') }}" class="small-box-footer">
                    Xem danh sách <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Hồ sơ ứng tuyển --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalApplications) }}</h3>
                    <p>Hồ sơ ứng tuyển</p>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <a href="{{ route('admin.career-applications.index') }}" class="small-box-footer">
                    Xem hồ sơ <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Liên hệ --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalContacts) }}</h3>
                    <p>Liên hệ mới</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <a href="{{ route('admin.contacts.index') }}" class="small-box-footer">
                    Xem liên hệ <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- 2. CHARTS ROW --}}
    <div class="row">
        {{-- Activity Chart --}}
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Hiệu suất công việc
                    </h3>
                    <div class="card-tools d-flex align-items-center">
                        <form action="{{ route('admin.dashboard') }}" method="GET" id="filterForm" class="d-flex align-items-center">
                            <select name="range" class="form-control form-control-sm mr-2" onchange="toggleCustomDate(this.value)">
                                <option value="7_days" {{ $range == '7_days' ? 'selected' : '' }}>7 ngày qua</option>
                                <option value="28_days" {{ $range == '28_days' ? 'selected' : '' }}>28 ngày qua</option>
                                <option value="3_months" {{ $range == '3_months' ? 'selected' : '' }}>3 tháng qua</option>
                                <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Tùy chọn...</option>
                            </select>
                            
                            <div id="customDateInputs" class="d-flex {{ $range == 'custom' ? '' : 'd-none' }}">
                                <input type="date" name="start_date" value="{{ $customStart }}" class="form-control form-control-sm mr-1">
                                <span class="mr-1">-</span>
                                <input type="date" name="end_date" value="{{ $customEnd }}" class="form-control form-control-sm mr-2">
                                <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
                            </div>
                        </form>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart" style="height: 300px;">
                        <canvas id="activityChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Task Status Pie --}}
        <div class="col-md-4">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Trạng thái công việc</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="taskStatusPieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Chờ</span>
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Đang làm</span>
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Xong</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. BOTTOM ROW: RECENT ORDERS & TOP TECHS --}}
    <div class="row">
        {{-- Recent Work Orders --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Phiếu việc gần đây</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.work-orders.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tạo mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-hover">
                            <thead>
                                <tr>
                                    <th>Mã phiếu</th>
                                    <th>Khách hàng</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentWorkOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.work-orders.show', $order->id) }}" class="font-weight-bold">{{ $order->code }}</a></td>
                                    <td>
                                        {{ $order->customer->name ?? 'Khách lẻ' }}
                                        <div class="small text-muted">{{ $order->customer->phone ?? '' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->status->color() }}">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m H:i') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.work-orders.show', $order->id) }}" class="btn btn-sm btn-default">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">Chưa có dữ liệu</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-sm btn-secondary float-right">Xem tất cả</a>
                </div>
            </div>
        </div>

        {{-- Top Technicians --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top Kỹ thuật viên (Tháng này)</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($topTechnicians as $tech)
                        <li class="item">
                            <div class="product-img">
                                <img src="{{ $tech->performer->avatar_url ?? asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" alt="User Image" class="img-size-50">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">{{ $tech->performer->name ?? 'Unknown' }}
                                    <span class="badge badge-success float-right">{{ $tech->total }} Tasks</span>
                                </a>
                                <span class="product-description">
                                    {{ $tech->performer->email ?? '' }}
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="item text-center text-muted py-3">Chưa có dữ liệu thi đua</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>

<script>
    Chart.defaults.global.defaultFontFamily = 'Source Sans Pro';
    Chart.defaults.global.defaultFontColor = '#858796';

    // 1. ACTIVITY CHART (Bar Chart)
    var ctxAct = document.getElementById("activityChart");
    var myActChart = new Chart(ctxAct, {
        type: 'bar',
        data: {
            labels: @json($activityChart['labels']), 
            datasets: [{
                label: "Task hoàn thành",
                backgroundColor: "#007bff",
                borderColor: "#007bff",
                data: @json($activityChart['data']), 
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: { beginAtZero: true, stepSize: 1 }
                }],
                xAxes: [{ gridLines: { display: false } }]
            },
            legend: { display: false }
        }
    });

    // 2. PIE CHART - TASK STATUS
    var ctxPie = document.getElementById("taskStatusPieChart");
    var myPieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ["Chờ", "Đang làm", "Xong"],
            datasets: [{
                data: [
                    {{ $taskStatusData['pending'] }}, 
                    {{ $taskStatusData['processing'] }}, 
                    {{ $taskStatusData['completed'] }}
                ],
                backgroundColor: ['#ffc107', '#007bff', '#28a745'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false }
        }
    });
</script>
@endpush