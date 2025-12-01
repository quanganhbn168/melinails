@extends('layouts.admin')

@section('title', 'Dashboard Quản Lý')
@section('content_header_title', 'Tổng quan hệ thống')

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">

    {{-- NÚT TẠO NHANH (Đặt ở trên cùng cho tiện) --}}
    <div class="row mb-3">
        <div class="col-12 text-right">
            <a href="{{ route('admin.work-orders.create') }}" class="btn btn-app bg-success">
                <i class="fas fa-plus"></i> Tạo Phiếu Việc
            </a>
            <a href="{{ route('admin.task-audit.index') }}" class="btn btn-app bg-warning">
                <span class="badge bg-danger">!</span> {{-- Có thể làm logic đếm số chưa duyệt vào đây --}}
                <i class="fas fa-file-invoice-dollar"></i> Duyệt Tiền
            </a>
        </div>
    </div>
    
    {{-- 1. SMALL BOXES (Đặc sản AdminLTE 3) --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalJobs) }}</h3>
                    <p>Tổng Phiếu Việc</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <a href="{{ route('admin.work-orders.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($processingJobs) }}</h3>
                    <p>Đang Thực Hiện</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('admin.work-orders.index') }}?status=processing" class="small-box-footer">
                    Xem danh sách <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalRevenue) }}<sup style="font-size: 20px">đ</sup></h3>
                    <p>Doanh Thu Thực Tế</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i> {{-- Hoặc fas fa-dollar-sign --}}
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('admin.task-audit.index') }}" class="small-box-footer">
                    Kiểm tra dòng tiền <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalCustomers) }}</h3>
                    <p>Khách Hàng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Quản lý khách <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- 2. BIỂU ĐỒ (Sử dụng Card Tools của AdminLTE) --}}
    <div class="row">
        {{-- Area Chart --}}
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="far fa-chart-bar mr-1"></i>
                        Doanh thu 6 tháng gần nhất
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart" style="height: 300px;">
                        <canvas id="myAreaChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pie Chart --}}
        <div class="col-md-4">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tỷ lệ công việc</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="myPieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Chờ</span>
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Đang làm</span>
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Xong</span>
                        <span class="mr-2"><i class="fas fa-circle text-secondary"></i> Hủy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. DANH SÁCH VIỆC MỚI (Style Table AdminLTE) --}}
    <div class="card">
        <div class="card-header border-transparent">
            <h3 class="card-title">Phiếu việc mới tiếp nhận</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table m-0 table-valign-middle">
                    <thead>
                        <tr>
                            <th>Mã Job</th>
                            <th>Khách hàng</th>
                            <th>Yêu cầu</th>
                            <th>Ngày tạo</th>
                            <th class="text-center">Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.work-orders.show', $order->id) }}" class="text-bold text-primary">
                                    {{ $order->code }}
                                </a>
                            </td>
                            <td>{{ $order->customer->name }}</td>
                            <td>{{ Str::limit($order->title, 40) }}</td>
                            <td>
                                <span class="text-muted">
                                    <i class="far fa-clock"></i> {{ $order->created_at->format('d/m H:i') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($order->status == 'pending') <span class="badge badge-warning">Chờ xử lý</span>
                                @elseif($order->status == 'processing') <span class="badge badge-primary">Đang làm</span>
                                @elseif($order->status == 'completed') <span class="badge badge-success">Hoàn thành</span>
                                @else <span class="badge badge-secondary">Hủy</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.work-orders.show', $order->id) }}" class="btn btn-sm btn-tool">
                                    <i class="fas fa-search"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <a href="{{ route('admin.work-orders.create') }}" class="btn btn-sm btn-info float-left">Tạo mới ngay</a>
            <a href="{{ route('admin.work-orders.index') }}" class="btn btn-sm btn-secondary float-right">Xem tất cả</a>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>

<script>
    
    
    // Cấu hình Font chung
    Chart.defaults.global.defaultFontFamily = 'Source Sans Pro'; // Font chuẩn AdminLTE
    Chart.defaults.global.defaultFontColor = '#858796';

    // 1. AREA CHART - DOANH THU
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($revenueData['labels']), 
            datasets: [{
                label: "Doanh thu",
                lineTension: 0, // AdminLTE thường để line thẳng hoặc bo ít
                backgroundColor: "transparent",
                borderColor: "#007bff",
                pointBorderColor: "#007bff",
                pointBackgroundColor: "#fff",
                pointHoverBackgroundColor: "#007bff",
                pointHoverBorderColor: "#007bff",
                data: @json($revenueData['data']), 
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: 'index',
                intersect: true,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        return 'Doanh thu: ' + new Intl.NumberFormat().format(tooltipItem.yLabel) + ' đ';
                    }
                }
            },
            hover: {
                mode: 'index',
                intersect: true
            },
            legend: { display: false },
            scales: {
                yAxes: [{
                    // display: false,
                    gridLines: {
                        display: true,
                        lineWidth: '4px',
                        color: 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) { return new Intl.NumberFormat().format(value); }
                    }
                }],
                xAxes: [{
                    display: true,
                    gridLines: { display: false },
                    ticks: { fontColor: '#495057' }
                }]
            }
        }
    });

    // 2. PIE CHART
    var ctxPie = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ["Chờ", "Đang làm", "Xong", "Hủy"],
            datasets: [{
                data: [
                    {{ $statusData['pending'] }}, 
                    {{ $statusData['processing'] }}, 
                    {{ $statusData['completed'] }}, 
                    {{ $statusData['cancelled'] }}
                ],
                backgroundColor: ['#ffc107', '#007bff', '#28a745', '#6c757d'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false }
        }
    });
</script>
@endpush