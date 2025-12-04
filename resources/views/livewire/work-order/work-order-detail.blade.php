<div>
    <style>
        body { background-color: #f4f6f9; }
        
        /* Card Task: Thiết kế phẳng, gọn */
        .task-card {
            background: #fff; 
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            margin-bottom: 12px; 
            border: 1px solid #e9ecef;
            overflow: hidden; /* Bo góc cho con bên trong */
            transition: transform 0.1s;
        }
        .task-card:active { transform: scale(0.99); }

        /* Border màu trạng thái bên trái */
        .status-border-completed { border-left: 5px solid #28a745; }
        .status-border-processing { border-left: 5px solid #17a2b8; }
        .status-border-pending { border-left: 5px solid #ffc107; }
    </style>

    {{-- HEADER (GIỮ NGUYÊN 100% CODE CỦA ANH) --}}
    <div class="content-header bg-white shadow-sm pb-2 pt-3 sticky-top">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <a href="{{ route('admin.work-orders.index') }}" class="btn btn-sm btn-light text-muted">
                    <i class="fas fa-arrow-left"></i> Danh sách
                </a>
                <div class="text-right">
                    <span class="badge badge-light border mr-1">#{{ $workOrder->code }}</span>
                    @if($workOrder->priority == 'urgent') <span class="badge badge-danger">GẤP</span>
                    @elseif($workOrder->priority == 'high') <span class="badge badge-warning">Cao</span>
                    @else <span class="badge badge-info">Thường</span> @endif
                </div>
            </div>
            
            <h5 class="font-weight-bold m-0 text-dark">{{ $workOrder->customer->name }}</h5>
            <div class="small text-muted mt-1">
                <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $workOrder->site_address ?? 'Chưa có địa chỉ' }}
            </div>
            <div class="small text-muted">
                <i class="fas fa-phone text-success mr-1"></i> {{ $workOrder->contact_phone ?? '---' }} 
                ({{ $workOrder->contact_person ?? '---' }})
            </div>
        </div>
    </div>

    {{-- NỘI DUNG CHÍNH --}}
    <section class="content mt-3 pb-5">
        <div class="container-fluid px-2">
            
            <div class="card card-primary card-outline card-outline-tabs border-0 shadow-sm">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold {{ $activeTab == 'progress' ? 'active' : '' }}" 
                               wire:click="$set('activeTab', 'progress')"
                               href="javascript:void(0)" role="tab">
                                <i class="fas fa-tasks mr-1"></i> Tiến độ & Công việc
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold {{ $activeTab == 'materials' ? 'active' : '' }}" 
                               wire:click="$set('activeTab', 'materials')"
                               href="javascript:void(0)" role="tab">
                                <i class="fas fa-box mr-1"></i> Vật tư
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold {{ $activeTab == 'finance' ? 'active' : '' }}" 
                               wire:click="$set('activeTab', 'finance')"
                               href="javascript:void(0)" role="tab">
                                <i class="fas fa-money-bill-wave mr-1"></i> Thu tiền
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content">
                        
                        {{-- TAB 1: TIẾN ĐỘ & CÔNG VIỆC --}}
                        <div class="tab-pane fade {{ $activeTab == 'progress' ? 'show active' : '' }}" id="tab-progress" role="tabpanel">
                            {{-- THỐNG KÊ TIẾN ĐỘ --}}
                            @php 
                                $total = $tasks->count();
                                $done = $tasks->where('status', \App\Enums\TaskStatus::COMPLETED)->count();
                                $percent = $total > 0 ? round(($done/$total)*100) : 0;
                            @endphp
                            <div class="card shadow-none border mb-3 bg-light">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between text-sm mb-1">
                                        <span class="font-weight-bold text-muted">TIẾN ĐỘ CÔNG VIỆC</span>
                                        <span class="font-weight-bold {{ $percent == 100 ? 'text-success' : 'text-primary' }}">{{ $done }}/{{ $total }} ({{ $percent }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 4px;">
                                        <div class="progress-bar {{ $percent == 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- DANH SÁCH ĐẦU VIỆC --}}
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-2 pl-1">Danh sách đầu việc</div>
                            
                            @foreach($tasks as $task)
                                <div class="task-card {{ $task->status === \App\Enums\TaskStatus::COMPLETED ? 'status-border-completed' : ($task->status === \App\Enums\TaskStatus::PROCESSING ? 'status-border-processing' : 'status-border-pending') }}">
                                    
                                    <a href="{{ route('admin.tasks.detail', $task->id) }}" class="d-block p-3 text-decoration-none text-dark">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div style="flex: 1; padding-right: 10px;">
                                                <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                                    NHIỆM VỤ #{{ $task->id }}
                                                </div>
                                                <h6 class="mb-1 font-weight-bold" style="font-size: 15px; line-height: 1.4;">
                                                    {{ $task->report_content }}
                                                </h6>
                                                <div class="text-xs text-muted mt-2">
                                                    <i class="fas fa-user-circle mr-1"></i> {{ $task->performer->name ?? 'Chưa gán' }}
                                                </div>
                                            </div>

                                            <div class="text-right" style="min-width: 80px;">
                                                @if($task->status === \App\Enums\TaskStatus::COMPLETED)
                                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check"></i> Xong</span>
                                                @elseif($task->status === \App\Enums\TaskStatus::PROCESSING)
                                                    <span class="badge badge-info px-2 py-1">Đang làm</span>
                                                @else
                                                    <span class="badge badge-warning px-2 py-1">Chờ làm</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>

                                    <div class="px-3 py-2 border-top bg-light d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-history text-gray mr-1"></i> {{ $task->reports->count() }} báo cáo
                                        </small>

                                        <div>
                                            @if($task->status === \App\Enums\TaskStatus::COMPLETED && $workOrder->status !== \App\Enums\WorkOrderStatus::COMPLETED)
                                                <div class="btn-reopen-wrapper d-inline-block mr-1">
                                                    <button wire:confirm="Xác nhận: Mở lại công việc này?" 
                                                            wire:click="reopenTask({{ $task->id }})" 
                                                            class="btn btn-xs btn-outline-danger bg-white font-weight-bold shadow-sm" 
                                                            style="border-style: dashed;"
                                                            title="Mở lại việc này">
                                                        <i class="fas fa-undo mr-1"></i> Mở lại
                                                    </button>
                                                </div>
                                            @endif

                                            <a href="{{ route('admin.tasks.detail', $task->id) }}" class="btn btn-xs btn-primary font-weight-bold">
                                                Chi tiết <i class="fas fa-chevron-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($workOrder->description)
                                <div class="card mt-3 border-0 shadow-sm">
                                    <div class="card-body bg-light rounded">
                                        <label class="text-xs text-muted font-weight-bold">GHI CHÚ CHUNG</label>
                                        <p class="mb-0 text-sm">{{ $workOrder->description }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- TAB 2: VẬT TƯ --}}
                        <div class="tab-pane fade {{ $activeTab == 'materials' ? 'show active' : '' }}" id="tab-materials" role="tabpanel">
                            <div class="card shadow-none border mb-4">
                                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                    <h3 class="card-title text-sm font-weight-bold text-muted text-uppercase">
                                        <i class="fas fa-box mr-1"></i> Bảng kê chi tiết vật tư
                                    </h3>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tên vật tư</th>
                                                <th>Serial</th>
                                                <th class="text-center">SL</th>
                                                <th>Ngày báo</th>
                                                <th class="text-right"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($allItems as $item)
                                                <tr>
                                                    <td>{{ $item['name'] }}</td>
                                                    <td><small class="text-muted">{{ $item['serial'] ?? '-' }}</small></td>
                                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                                    <td><small>{{ $item['report_date']->format('d/m H:i') }}</small></td>
                                                    <td></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">Chưa có vật tư nào được ghi nhận.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 3: THU TIỀN --}}
                        <div class="tab-pane fade {{ $activeTab == 'finance' ? 'show active' : '' }}" id="tab-finance" role="tabpanel">
                            
                            {{-- SUMMARY CARD --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="callout callout-success bg-light shadow-sm">
                                        <small class="text-muted font-weight-bold text-uppercase">Tổng tiền đã thu (Đã duyệt)</small>
                                        <h5 class="text-success font-weight-bold mb-0">{{ number_format($totalCollected) }} đ</h5>
                                    </div>
                                </div>
                            </div>

                            {{-- TABLE: LỊCH SỬ THANH TOÁN --}}
                            <div class="card shadow-none border mb-0">
                                <div class="card-header bg-light py-2">
                                    <h3 class="card-title text-sm font-weight-bold text-muted text-uppercase">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Lịch sử thu tiền
                                    </h3>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Ngày thu</th>
                                                <th>Người thu</th>
                                                <th>Hình thức</th>
                                                <th>Trạng thái</th>
                                                <th class="text-right">Số tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($allPayments as $payment)
                                                <tr>
                                                    <td>{{ $payment['date']->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $payment['reporter'] }}</td>
                                                    <td>
                                                        @if($payment['method'] == 'transfer')
                                                            <span class="badge badge-info">CK {{ $payment['target'] == 'company' ? '(Cty)' : '(CN)' }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">Tiền mặt</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($payment['status'] == 'verified') <span class="badge badge-success">Đã duyệt</span>
                                                        @elseif($payment['status'] == 'handed_over') <span class="badge badge-info">Đã nộp</span>
                                                        @else <span class="badge badge-warning">Chờ duyệt</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right font-weight-bold text-success">+{{ number_format($payment['amount']) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">Chưa có khoản thu nào.</td>
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

            {{-- MODAL THÊM VẬT TƯ --}}
            @if($showMaterialModal)
                <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thêm vật tư / Linh kiện</h5>
                                <button type="button" class="close" wire:click="$set('showMaterialModal', false)">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Gán vào Task <span class="text-danger">*</span></label>
                                    <select wire:model="newMaterial.task_id" class="form-control">
                                        @foreach($tasks as $t)
                                            <option value="{{ $t->id }}">#{{ $t->id }} - {{ Str::limit($t->report_content, 50) }} ({{ $t->performer->name ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tên vật tư <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="newMaterial.name" class="form-control" placeholder="VD: Camera Hikvision...">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Serial (nếu có)</label>
                                            <input type="text" wire:model="newMaterial.serial" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Số lượng</label>
                                            <input type="number" wire:model="newMaterial.quantity" class="form-control" min="1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Đơn giá (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" wire:model="newMaterial.price" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="$set('showMaterialModal', false)">Hủy</button>
                                <button type="button" class="btn btn-primary" wire:click="saveMaterial">Lưu lại</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
</div>