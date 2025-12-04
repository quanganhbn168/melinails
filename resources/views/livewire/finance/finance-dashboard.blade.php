<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tổng quan Tài chính</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tài chính</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- STATS CARDS --}}
            <div class="row">
                {{-- 1. Pending Approval --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($stats->pending_total ?? 0) }} <sup style="font-size: 20px">đ</sup></h3>
                            <p>Chờ duyệt ({{ $stats->pending_count ?? 0 }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                {{-- 2. Company Account --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($stats->company_total ?? 0) }} <sup style="font-size: 20px">đ</sup></h3>
                            <p>Đã về tài khoản Cty ({{ $stats->company_count ?? 0 }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>

                {{-- 3. Personal Account --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($stats->personal_total ?? 0) }} <sup style="font-size: 20px">đ</sup></h3>
                            <p>Đã về tài khoản CN ({{ $stats->personal_count ?? 0 }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>

                {{-- 4. Cash Received --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ number_format($stats->cash_total ?? 0) }} <sup style="font-size: 20px">đ</sup></h3>
                            <p>Đã nhận tiền mặt ({{ $stats->cash_count ?? 0 }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTERS & TABLE --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách giao dịch</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 350px;">
                            <select wire:model.live="statusFilter" class="form-control float-right">
                                <option value="all">Tất cả trạng thái</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="verified">Đã về TK</option>
                                <option value="handed_over">Đã nộp tiền</option>
                            </select>
                            <select wire:model.live="dateFilter" class="form-control float-right ml-2">
                                <option value="all">Tất cả thời gian</option>
                                <option value="today">Hôm nay</option>
                                <option value="this_month">Tháng này</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Phiếu việc</th>
                                <th>Khách hàng</th>
                                <th>Người thu</th>
                                <th>Số tiền</th>
                                <th>Hình thức</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.work-orders.show', $report->task->workOrder->id) }}">
                                            #{{ $report->task->workOrder->id }}
                                        </a>
                                    </td>
                                    <td>{{ $report->task->workOrder->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $report->reporter->name }}</td>
                                    <td class="font-weight-bold">{{ number_format($report->collected_amount) }} đ</td>
                                    <td>
                                        @if($report->payment_method == 'transfer')
                                            <span class="badge badge-info">CK {{ $report->transfer_target == 'company' ? '(Cty)' : '(CN)' }}</span>
                                        @else
                                            <span class="badge badge-secondary">Tiền mặt</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report->finance_status == 'verified')
                                            <span class="badge badge-success">Đã về TK</span>
                                        @elseif($report->finance_status == 'handed_over')
                                            <span class="badge badge-success">Đã nộp tiền</span>
                                        @else
                                            <span class="badge badge-warning">Chờ duyệt</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.finance.work-order', $report->task->workOrder->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fas fa-eye"></i> Duyệt
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Không tìm thấy giao dịch nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $reports->links() }}
                </div>
            </div>

        </div>
    </section>
</div>
