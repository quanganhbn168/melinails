@extends('layouts.admin')

@section('title', 'Dashboard')
@section('content_header_title', 'Tổng quan')

@section('content')
<div class="container-fluid">

    {{-- WELCOME MESSAGE --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-hand-wave"></i> Xin chào, {{ auth('admin')->user()->name }}!</h5>
                <p class="mb-0">Chào mừng bạn đến với hệ thống quản lý CNET. Sidebar bên trái chứa các chức năng bạn có quyền truy cập.</p>
            </div>
        </div>
    </div>

    {{-- QUICK STATS (nếu role có quyền xem) --}}
    @can('view_work_orders')
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalWorkOrders ?? 0 }}</h3>
                    <p>Phiếu việc</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('admin.work-orders.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        @can('view_customers')
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalCustomers ?? 0 }}</h3>
                    <p>Khách hàng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.customers.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endcan

        @can('view_materials')
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalMaterials ?? 0 }}</h3>
                    <p>Vật tư</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('admin.materials.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endcan
    </div>
    @endcan

    {{-- QUICK ACTIONS --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt mr-1"></i> Thao tác nhanh</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @can('view_work_orders')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.my-work-orders.index') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-clipboard-list"></i> Việc của tôi
                            </a>
                        </div>
                        @endcan
                        
                        @can('create_work_orders')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.work-orders.create') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-plus"></i> Tạo phiếu việc
                            </a>
                        </div>
                        @endcan

                        @can('view_customers')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-users"></i> Khách hàng
                            </a>
                        </div>
                        @endcan

                        @can('view_warranty')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.warranty.index') }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-shield-alt"></i> Bảo hành
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Thông tin hệ thống</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>Tài khoản:</td>
                            <td class="font-weight-bold">{{ auth('admin')->user()->email }}</td>
                        </tr>
                        <tr>
                            <td>Vai trò:</td>
                            <td>
                                @foreach(auth('admin')->user()->roles as $role)
                                    <span class="badge badge-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Thời gian:</td>
                            <td>{{ now()->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
