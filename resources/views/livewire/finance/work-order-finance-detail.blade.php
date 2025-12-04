<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Duyệt tài chính - WO #{{ $workOrder->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.index') }}">Phiếu việc</a></li>
                        <li class="breadcrumb-item active">Duyệt tài chính</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- SUMMARY --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Khách hàng</h6>
                            <h5 class="font-weight-bold">{{ $workOrder->customer->name }}</h5>
                            <p class="mb-0 text-sm">{{ $workOrder->customer->phone }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Tổng cần thu</h6>
                            <h5 class="font-weight-bold text-primary">{{ number_format($reports->sum('collected_amount')) }} đ</h5>
                            <small class="text-muted">Tổng các khoản đã báo cáo</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRANSACTIONS --}}
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Danh sách khoản thu</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Ngày thu</th>
                                <th>Người thu</th>
                                <th>Số tiền</th>
                                <th>Hình thức</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $report->reporter->name }}</td>
                                    <td class="font-weight-bold text-success">{{ number_format($report->collected_amount) }} đ</td>
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
                                    <td>
                                        @if($report->finance_status == 'pending')
                                            @if($report->payment_method == 'transfer')
                                                <button wire:confirm="Xác nhận tiền đã về tài khoản?" wire:click="verify({{ $report->id }})" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check mr-1"></i> Đã về TK
                                                </button>
                                            @else
                                                <button wire:confirm="Xác nhận đã nhận tiền mặt từ nhân viên?" wire:click="handover({{ $report->id }})" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-hand-holding-usd mr-1"></i> Đã nhận tiền
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-muted"><i class="fas fa-check-circle"></i> Hoàn tất</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có khoản thu nào cần duyệt.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
