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
                    <div class="card-tools d-flex align-items-center">
                        <button wire:click="syncLegacyData" wire:loading.attr="disabled" class="btn btn-sm btn-outline-info mr-3">
                            <i class="fas fa-sync" wire:loading.remove wire:target="syncLegacyData"></i>
                            <i class="fas fa-spinner fa-spin" wire:loading wire:target="syncLegacyData"></i>
                            Đồng bộ dữ liệu cũ
                        </button>
                        <div class="input-group input-group-sm" style="width: 350px;">
                            <select wire:model.live="statusFilter" class="form-control float-right">
                                <option value="all">Tất cả trạng thái</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="verified">Đã duyệt</option>
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
                                <th>Nội dung</th>
                                <th>Người tạo/Thu</th>
                                <th>Số tiền</th>
                                <th>Loại</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $payment)
                                <tr>
                                    <td>
                                        <div>{{ $payment->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        {{-- Work Order Title --}}
                                        @if($payment->work_order_id)
                                            <div class="font-weight-bold">
                                                <a href="{{ route('admin.work-orders.show', $payment->work_order_id) }}">
                                                    {{ $payment->workOrder->title ?? '#'.$payment->work_order_id }}
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted font-italic">Không xác định</span>
                                        @endif
                                        
                                        {{-- Task Info --}}
                                        @if($payment->taskReport && $payment->taskReport->task)
                                             <div class="text-sm">
                                                <i class="fas fa-tasks text-muted mr-1"></i> 
                                                {{ $payment->taskReport->task->name }}
                                            </div>
                                        @endif

                                        {{-- Description --}}
                                        <div class="text-xs text-muted font-italic">{{ $payment->description }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $payment->creator->name ?? 'N/A' }}</div>
                                        @if($payment->collector_id && $payment->collector_id != $payment->created_by)
                                            <small class="text-muted">Thu bởi: {{ $payment->collector->name ?? '' }}</small>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">{{ number_format($payment->amount) }} đ</td>
                                    <td>
                                        <span class="badge badge-{{ $payment->payment_type->color() }}">
                                            {{ $payment->payment_type->label() }}
                                        </span>
                                        
                                        @if($payment->payment_type === \App\Enums\PaymentType::COLLECTION)
                                            <div class="text-xs mt-1">
                                                @if($payment->payment_method == 'transfer')
                                                    CK {{ $payment->transfer_target == 'company' ? '(Cty)' : '('.($payment->transfer_target == 'personal' ? 'CN' : $payment->transfer_target).')' }}
                                                @else
                                                    Tiền mặt
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $payment->status->color() }}">
                                            {{ $payment->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        @if($payment->work_order_id)
                                            <a href="{{ route('admin.work-orders.show', ['id' => $payment->work_order_id, 'tab' => 'finance']) }}" class="btn btn-xs btn-primary">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không tìm thấy giao dịch nào.</td>
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
