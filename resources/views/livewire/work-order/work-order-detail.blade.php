<div>
    <style>
        body { background-color: #f4f6f9; }
        .task-card {
            background: #fff; 
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            margin-bottom: 12px; 
            border: 1px solid #e9ecef;
            position: relative;
        }
        .task-card .dropdown-menu {
            z-index: 1050;
        }
        .status-border-completed { border-left: 5px solid #28a745; }
        .status-border-processing { border-left: 5px solid #17a2b8; }
        .status-border-pending { border-left: 5px solid #ffc107; }
    </style>

    {{-- HEADER WITH STATUS DROPDOWN --}}
    <div class="content-header bg-white shadow-sm pb-2 pt-3 sticky-top">
        <div class="container-fluid px-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <a href="{{ route('admin.work-orders.index') }}" class="btn btn-sm btn-light text-muted font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Danh sách
                </a>
                
                {{-- Status Display (Simple) --}}
                <div class="d-flex align-items-center">
                    @php
                        $statusEnum = $workOrder->status instanceof \App\Enums\WorkOrderStatus 
                            ? $workOrder->status 
                            : \App\Enums\WorkOrderStatus::tryFrom($workOrder->status);
                    @endphp
                    @if($statusEnum)
                        <span class="badge badge-{{ $statusEnum->color() }} font-weight-bold shadow-sm px-3 py-2" style="font-size: 13px;">
                            {{ $statusEnum->label() }}
                        </span>
                    @endif

                    @unless(auth('admin')->user()->hasRole('staff'))
                     <a href="{{ route('admin.work-orders.edit', $workOrder->id) }}" class="btn btn-sm btn-outline-primary shadow-sm bg-white ml-2 rounded-circle border-0" title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endunless
                </div>
            </div>
        </div>
    </div>

    {{-- INFO CARD --}}
    <section class="content pt-0 pb-2">
        <div class="container-fluid px-2">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge badge-light border">#{{ $workOrder->code }}</span>
                         @php
                            $priorityEnum = $workOrder->priority instanceof \App\Enums\WorkOrderPriority 
                                ? $workOrder->priority 
                                : \App\Enums\WorkOrderPriority::tryFrom($workOrder->priority);
                        @endphp
                        @if($priorityEnum)
                            <span class="badge badge-{{ $priorityEnum->color() }}">{{ $priorityEnum->label() }}</span>
                        @endif
                    </div>
                    
                    <h5 class="font-weight-bold text-primary mb-2">{{ $workOrder->title }}</h5>

                    @if($workOrder->description)
                        <div class="bg-light p-2 rounded text-sm text-muted mb-3">
                            <i class="fas fa-sticky-note mr-1"></i> {{ $workOrder->description }}
                        </div>
                    @endif

                    <div class="border-top pt-2 mt-2">
                        <div class="font-weight-bold text-dark mb-1">{{ $workOrder->customer->name }}</div>
                        <div class="small text-muted mb-1">
                            <a href="https://maps.google.com/?q={{ $workOrder->site_address }}" target="_blank" class="text-dark">
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $workOrder->site_address ?? 'Chưa có địa chỉ' }}
                            </a>
                        </div>
                        <div class="small text-muted mb-1">
                            <a href="tel:{{ $workOrder->contact_phone }}" class="text-dark">
                                <i class="fas fa-phone text-success mr-1"></i> {{ $workOrder->contact_phone ?? '---' }} 
                            </a>
                            ({{ $workOrder->contact_person ?? '---' }})
                        </div>
                        
                        {{-- THỜI GIAN BẮT ĐẦU & DEADLINE --}}
                        <div class="small mt-2 d-flex flex-wrap" style="gap: 15px;">
                            @if($workOrder->started_at)
                                <div>
                                    <i class="fas fa-play-circle text-success mr-1"></i>
                                    <span>Bắt đầu: {{ $workOrder->started_at->format('H:i - d/m/Y') }}</span>
                                </div>
                            @endif
                            
                            @if($workOrder->deadline)
                                <div>
                                    <i class="far fa-calendar-alt text-info mr-1"></i>
                                    <span>Hạn: {{ $workOrder->deadline->format('H:i - d/m/Y') }}</span>
                                </div>
                                
                                {{-- Countdown sử dụng accessor từ Model --}}
                                @if($workOrder->deadline_status)
                                    <span class="badge badge-{{ $workOrder->deadline_status['color'] }} font-weight-bold">
                                        @if($workOrder->deadline_status['is_overdue'])
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                        @else
                                            <i class="fas fa-clock mr-1"></i>
                                        @endif
                                        {{ $workOrder->deadline_status['label'] }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content pb-5">
        <div class="container-fluid px-2">
            
            <div class="card card-primary card-outline card-outline-tabs border-0 shadow-sm">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold {{ $activeTab == 'progress' ? 'active' : '' }}" 
                               wire:click="$set('activeTab', 'progress')"
                               href="javascript:void(0)" role="tab">
                                <i class="fas fa-tasks mr-1"></i> Công việc
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold {{ $activeTab == 'discussion' ? 'active' : '' }}" 
                               wire:click="$set('activeTab', 'discussion')"
                               href="javascript:void(0)" role="tab">
                                <i class="fas fa-comments mr-1"></i> Trao đổi
                                @if($workOrder->comments()->count() > 0)
                                    <span class="badge badge-primary badge-pill ml-1">{{ $workOrder->comments()->count() }}</span>
                                @endif
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
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold {{ $activeTab == 'attachments' ? 'active' : '' }}" 
                               wire:click="$set('activeTab', 'attachments')"
                               href="javascript:void(0)" role="tab">
                                <i class="fas fa-paperclip mr-1"></i> Tài liệu
                                @if($workOrder->attachments->count() > 0)
                                    <span class="badge badge-secondary badge-pill ml-1">{{ $workOrder->attachments->count() }}</span>
                                @endif
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content">
                        
                        {{-- TAB 1: CÔNG VIỆC --}}
                        <div class="tab-pane fade {{ $activeTab == 'progress' ? 'show active' : '' }}" id="tab-progress" role="tabpanel">
                            
                             @php 
                                $total = $tasks->count();
                                $done = $tasks->where('status', \App\Enums\TaskStatus::COMPLETED)->count();
                                $percent = $total > 0 ? round(($done/$total)*100) : 0;
                            @endphp
                            <div class="mb-3 text-muted small font-weight-bold">
                                TIẾN ĐỘ: {{ $done }}/{{ $total }} ({{ $percent }}%)
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            
                            @foreach($tasks as $index => $task)
                                <div class="task-card {{ $task->status === \App\Enums\TaskStatus::COMPLETED ? 'status-border-completed' : ($task->status === \App\Enums\TaskStatus::PROCESSING ? 'status-border-processing' : 'status-border-pending') }}">
                                    
                                    {{-- Link to Admin Task Detail --}}
                                    <a href="{{ route('admin.tasks.detail', $task->id) }}" class="d-block p-3 text-decoration-none text-dark">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div style="flex: 1; padding-right: 10px;">
                                                <h6 class="mb-1 font-weight-bold" style="font-size: 15px; line-height: 1.4;">
                                                    <span class="text-muted">Hạng mục {{ $index + 1 }}:</span> {{ $task->title }}
                                                    @if($task->is_additional)
                                                        <span class="badge badge-info text-xs ml-1">Phát sinh</span>
                                                    @endif
                                                </h6>
                                                @if($task->description)
                                                <p class="text-muted small mb-1">{{ Str::limit($task->description, 100) }}</p>
                                                @endif
                                                <div class="text-xs text-muted mt-1">
                                                    <i class="fas fa-users text-xs mr-1"></i> 
                                                    @if($task->performers->count() > 0)
                                                        {{ $task->performers->pluck('name')->join(', ') }}
                                                    @elseif($task->performer)
                                                        {{ $task->performer->name }}
                                                    @else
                                                        Chưa gán
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="text-right" style="min-width: 90px;">
                                                <span class="badge badge-{{ $task->status->color() }} px-2 py-1">
                                                    @if($task->status === \App\Enums\TaskStatus::COMPLETED)
                                                        <i class="fas fa-check mr-1"></i>
                                                    @endif
                                                    {{ $task->status->label() }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="px-3 py-2 border-top bg-light d-flex justify-content-between align-items-center">
                                        {{-- Thông tin bổ sung --}}
                                        <div class="text-muted small">
                                            @if($task->reports->count() > 0)
                                                <i class="fas fa-file-alt mr-1"></i> {{ $task->reports->count() }} báo cáo
                                            @else
                                                <span class="text-warning"><i class="fas fa-exclamation-circle mr-1"></i>Chưa có báo cáo</span>
                                            @endif
                                        </div>
                                        
                                        {{-- Menu 3 chấm --}}
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                                {{-- Chi tiết --}}
                                                <a class="dropdown-item" href="{{ route('admin.tasks.detail', $task->id) }}">
                                                    <i class="fas fa-eye text-primary mr-2"></i>Chi tiết
                                                </a>
                                                
                                                <div class="dropdown-divider"></div>
                                                
                                                {{-- Tạo việc phát sinh (nếu WO cho phép) --}}
                                                @if($workOrder->allowsAdditionalTasks())
                                                <a class="dropdown-item" href="javascript:void(0)" 
                                                   wire:click="$dispatch('openAdditionalTaskModal', { parentTaskId: {{ $task->id }} })">
                                                    <i class="fas fa-plus-circle text-info mr-2"></i>Tạo việc phát sinh
                                                </a>
                                                @endif
                                                
                                                {{-- Đánh dấu xong (nếu chưa xong + có báo cáo) --}}
                                                @if($task->status !== \App\Enums\TaskStatus::COMPLETED && $task->reports->count() > 0)
                                                <a class="dropdown-item" href="javascript:void(0)" 
                                                   wire:click="quickFinishTask({{ $task->id }})"
                                                   wire:confirm="Admin: Xác nhận nhiệm vụ này ĐÃ HOÀN THÀNH?">
                                                    <i class="fas fa-check-circle text-success mr-2"></i>Đánh dấu hoàn thành
                                                </a>
                                                @endif
                                                
                                                {{-- Mở lại (nếu đã xong) --}}
                                                @if($task->status === \App\Enums\TaskStatus::COMPLETED)
                                                <a class="dropdown-item" href="javascript:void(0)" 
                                                   wire:click="reopenTask({{ $task->id }})"
                                                   wire:confirm="Admin: Mở lại nhiệm vụ này?">
                                                    <i class="fas fa-undo text-warning mr-2"></i>Mở lại
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- TAB 2: VẬT TƯ --}}
                        <div class="tab-pane fade {{ $activeTab == 'materials' ? 'show active' : '' }}" id="tab-materials" role="tabpanel">
                            {{-- VẬT TƯ ĐÃ SỬ DỤNG --}}
                            <div class="mb-3">
                                <h6 class="text-muted font-weight-bold mb-2"><i class="fas fa-box mr-1"></i> Vật tư đã sử dụng</h6>
                                <table class="table table-sm table-striped mb-0 text-sm">
                                    <thead>
                                        <tr>
                                            <th>Vật tư</th>
                                            <th class="text-center">SL</th>
                                            <th class="text-right">Ngày</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($allItems as $item)
                                            <tr>
                                                <td>
                                                    {{ $item['name'] }}
                                                    @if($item['serial'])
                                                        <div class="text-muted text-xs">{{ $item['serial'] }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item['quantity'] }}</td>
                                                <td class="text-right text-muted text-xs">{{ $item['report_date']->format('d/m') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Chưa có vật tư.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- VẬT TƯ NHẬN LẠI --}}
                            @if(count($allReturnedItems) > 0)
                                <div class="border-top pt-3">
                                    <h6 class="text-muted font-weight-bold mb-2"><i class="fas fa-undo mr-1"></i> Vật tư nhận lại</h6>
                                    <table class="table table-sm table-striped mb-0 text-sm">
                                        <thead>
                                            <tr>
                                                <th>Vật tư</th>
                                                <th>Lý do</th>
                                                <th class="text-right">Ngày</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allReturnedItems as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item['name'] }}
                                                        @if($item['serial'])
                                                            <div class="text-muted text-xs">{{ $item['serial'] }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">{{ $item['reason'] }}</span>
                                                        @if($item['condition'])
                                                            <div class="text-xs text-muted mt-1">{{ $item['condition'] }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="text-right text-muted text-xs">{{ $item['report_date']->format('d/m') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>



                        <div class="tab-pane fade {{ $activeTab == 'finance' ? 'show active' : '' }}" id="tab-finance" role="tabpanel">
                            {{-- SUMMARY CARD - 3 Cột --}}
                            <div class="row mb-3">
                                {{-- Tổng giá trị --}}
                                <div class="col-4 pr-1">
                                    <div class="bg-white border rounded p-2 shadow-sm text-center border-primary h-100">
                                        <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 10px;">Tổng giá trị</small>
                                        <h5 class="text-primary font-weight-bold mb-0 mt-1">{{ number_format($workOrder->total_value) }}</h5>
                                    </div>
                                </div>
                                {{-- Đã thu --}}
                                <div class="col-4 px-1">
                                    <div class="bg-white border rounded p-2 shadow-sm text-center border-success h-100">
                                        <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 10px;">Đã thu</small>
                                        <h5 class="text-success font-weight-bold mb-0 mt-1">{{ number_format($workOrder->total_collected) }}</h5>
                                    </div>
                                </div>
                                {{-- Còn nợ --}}
                                <div class="col-4 pl-1">
                                    <div class="bg-white border rounded p-2 shadow-sm text-center border-danger h-100">
                                        <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size: 10px;">Còn nợ</small>
                                        <h5 class="text-danger font-weight-bold mb-0 mt-1">{{ number_format($workOrder->balance) }}</h5>
                                    </div>
                                </div>
                            </div>

                            {{-- TABLE: LỊCH SỬ THANH TOÁN --}}
                             <div class="card shadow-none border mb-0">
                                <div class="card-header bg-light py-2">
                                    <h3 class="card-title text-sm font-weight-bold text-muted text-uppercase">
                                        Chi tiết giao dịch
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0 text-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 35%">Mô tả</th>
                                                <th class="text-center" style="width: 20%">Loại</th>
                                                <th class="text-center" style="width: 20%">Trạng thái</th>
                                                <th class="text-right" style="width: 25%">Số tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($allPayments as $payment)
                                                <tr>
                                                    <td>
                                                        <div class="font-weight-bold">{{ $payment['description'] ?? 'N/A' }}</div>
                                                        <div class="text-xs text-muted">
                                                            {{ $payment['created_by'] }} · {{ $payment['date']->format('d/m H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $type = $payment['payment_type'] ?? null;
                                                        @endphp
                                                        @if($type && $type->value === 'collection')
                                                            <span class="badge badge-success text-xs">Thu tiền</span>
                                                        @elseif($type && $type->value === 'item_value')
                                                            <span class="badge badge-info text-xs">Ghi nợ</span>
                                                        @else
                                                            <span class="badge badge-secondary text-xs">{{ $type ? $type->label() : 'Khác' }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $statusValue = is_object($payment['status']) ? $payment['status']->value : $payment['status'];
                                                        @endphp
                                                        
                                                        <div class="mb-1">
                                                           @if($payment['is_collected'])
                                                                <span class="text-success text-xs font-weight-bold"><i class="fas fa-check"></i> Đã thu</span>
                                                            @else
                                                                <span class="text-muted text-xs"><i class="fas fa-times"></i> Chưa thu</span>
                                                            @endif 
                                                        </div>

                                                        @if($statusValue == 'verified') 
                                                            <span class="badge badge-outline-success text-xs">Đã duyệt</span>
                                                        @elseif($statusValue == 'cancelled')
                                                            <span class="badge badge-secondary text-xs">Hủy</span>
                                                        @else
                                                            <span class="badge badge-outline-warning text-xs">Chờ duyệt</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right font-weight-bold {{ $payment['is_collected'] ? 'text-success' : 'text-primary' }}">
                                                        {{ number_format($payment['amount']) }}đ
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">Chưa có giao dịch nào.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 5: TRAO ĐỔI (DISCUSSION) --}}
                        <div class="tab-pane fade {{ $activeTab == 'discussion' ? 'show active' : '' }}" id="tab-discussion" role="tabpanel">
                            @livewire('work-order.work-order-discussion', ['workOrder' => $workOrder], key('discussion-'.$workOrder->id))
                        </div>

                        {{-- TAB 6: TÀI LIỆU ĐÍNH KÈM --}}
                        <div class="tab-pane fade {{ $activeTab == 'attachments' ? 'show active' : '' }}" id="tab-attachments" role="tabpanel">
                            @if($workOrder->attachments->count() > 0)
                                {{-- Ảnh đính kèm --}}
                                @php $images = $workOrder->attachments->where('type', 'image'); @endphp
                                @if($images->count() > 0)
                                    <h6 class="text-muted font-weight-bold mb-2"><i class="fas fa-images mr-1"></i> Hình ảnh ({{ $images->count() }})</h6>
                                    <div class="d-flex flex-wrap mb-3" style="gap: 10px;">
                                        @foreach($images as $img)
                                            <a href="{{ asset('storage/' . $img->file_path) }}" target="_blank" class="position-relative">
                                                <img src="{{ asset('storage/' . $img->file_path) }}" 
                                                     class="img-thumbnail shadow-sm" 
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Tài liệu khác (PDF, CAD, Word...) --}}
                                @php $docs = $workOrder->attachments->where('type', 'document'); @endphp
                                @if($docs->count() > 0)
                                    <h6 class="text-muted font-weight-bold mb-2"><i class="fas fa-file-alt mr-1"></i> Tài liệu ({{ $docs->count() }})</h6>
                                    <div class="list-group">
                                        @foreach($docs as $doc)
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" 
                                               class="list-group-item list-group-item-action d-flex align-items-center">
                                                @php
                                                    $ext = pathinfo($doc->file_name, PATHINFO_EXTENSION);
                                                    $icon = match(strtolower($ext)) {
                                                        'pdf' => 'fa-file-pdf text-danger',
                                                        'doc', 'docx' => 'fa-file-word text-primary',
                                                        'xls', 'xlsx' => 'fa-file-excel text-success',
                                                        'dwg', 'dxf' => 'fa-drafting-compass text-info',
                                                        default => 'fa-file text-secondary'
                                                    };
                                                @endphp
                                                <i class="fas {{ $icon }} fa-lg mr-3"></i>
                                                <div>
                                                    <div class="font-weight-bold">{{ $doc->file_name }}</div>
                                                    <small class="text-muted">Tải về</small>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p>Chưa có tài liệu đính kèm nào.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- MODAL: Tạo việc phát sinh --}}
    @if($showAdditionalTaskModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Tạo việc phát sinh</h5>
                    <button type="button" class="close text-white" wire:click="closeAdditionalTaskModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $parentTask = $tasks->firstWhere('id', $parentTaskId);
                    @endphp
                    @if($parentTask)
                    <div class="alert alert-light border mb-3">
                        <small class="text-muted">Phát sinh từ:</small>
                        <div class="font-weight-bold">{{ $parentTask->title }}</div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label>Tiêu đề công việc <span class="text-danger">*</span></label>
                        <input type="text" wire:model="newAdditionalTask.title" class="form-control" 
                               placeholder="VD: Kiểm tra lại, Bổ sung vật tư...">
                        @error('newAdditionalTask.title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea wire:model="newAdditionalTask.description" class="form-control" rows="3"
                                  placeholder="Mô tả chi tiết công việc cần làm..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Gán cho <span class="text-danger">*</span></label>
                        <select wire:model="newAdditionalTask.assignee_id" class="form-control">
                            <option value="">-- Chọn người thực hiện --</option>
                            @foreach($workOrder->assignees as $assignee)
                                <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
                            @endforeach
                        </select>
                        @error('newAdditionalTask.assignee_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeAdditionalTaskModal">Hủy</button>
                    <button type="button" class="btn btn-info" wire:click="createAdditionalTask">
                        <i class="fas fa-plus mr-1"></i> Tạo việc phát sinh
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>