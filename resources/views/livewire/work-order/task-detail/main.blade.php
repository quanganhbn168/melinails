<div>
    {{-- 1. LOAD CSS --}}
    @include('livewire.work-order.task-detail.css')

    {{-- 2. HEADER --}}

    {{-- 3. NỘI DUNG CHÍNH --}}
    <div class="app-content-wrapper container-fluid px-2">
        @include('livewire.work-order.task-detail.header')
        
        {{-- Tabs --}}
        <ul class="nav nav-pills nav-justified mb-3 mx-1 p-1 bg-white rounded border shadow-sm">
            <li class="nav-item">
                <a class="nav-link py-2 {{ $activeTab == 'new_report' ? 'active shadow-sm font-weight-bold' : 'text-muted' }}" 
                   href="javascript:void(0)" wire:click="switchTab('new_report')">BÁO CÁO</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-2 {{ $activeTab == 'history' ? 'active shadow-sm font-weight-bold' : 'text-muted' }}" 
                   href="javascript:void(0)" wire:click="switchTab('history')">LỊCH SỬ</a>
            </li>
        </ul>

        {{-- TAB CONTENT --}}
        @if($activeTab == 'history')
            <div wire:key="tab-history">
                @include('livewire.work-order.task-detail.tab-history')
            </div>
        @endif

        @if($activeTab == 'new_report')
            <div wire:key="tab-report">
                @include('livewire.work-order.task-detail.tab-report')
            </div>

            {{-- 4. FOOTER BUTTON (Chỉ hiện khi ở tab báo cáo, chưa xong VÀ WorkOrder chưa khóa) --}}
            @if($task->status != \App\Enums\TaskStatus::COMPLETED && $task->workOrder->allowsReporting())
                <div class="mt-3 mb-4">
                    <button class="btn btn-success btn-lg btn-block shadow-sm font-weight-bold" onclick="submitReport()">
                        <i class="fas fa-paper-plane mr-2"></i> GỬI BÁO CÁO
                    </button>
                </div>

                {{-- FORM TẠO CÔNG VIỆC TIẾP THEO --}}
                @if($task->workOrder->allowsAdditionalTasks())
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-forward text-primary mr-1"></i> Tạo công việc tiếp theo</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Title --}}
                        <div class="form-group">
                            <label class="text-sm font-weight-bold">Nội dung công việc <span class="text-danger">*</span></label>
                            <input type="text" wire:model="newTaskTitle" class="form-control" 
                                   placeholder="VD: Triển khai lắp đặt camera...">
                            @error('newTaskTitle') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            {{-- Scheduled Date --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-sm font-weight-bold"><i class="fas fa-calendar mr-1"></i> Hẹn ngày thực hiện</label>
                                    <input type="datetime-local" wire:model="newTaskScheduledAt" class="form-control">
                                    @error('newTaskScheduledAt') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                                    <small class="text-muted">Để trống nếu chưa xác định</small>
                                </div>
                            </div>
                            
                            {{-- Assignee --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-sm font-weight-bold"><i class="fas fa-user mr-1"></i> Gán cho</label>
                                    <select wire:model="newTaskAssigneeId" class="form-control">
                                        <option value="">-- Chưa gán --</option>
                                        @foreach(\App\Models\Admin::all() as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Để trống để Admin gán sau</small>
                                </div>
                            </div>
                        </div>

                        <button wire:click="createAdditionalTask" class="btn btn-primary btn-block">
                            <i class="fas fa-plus-circle mr-1"></i> Tạo công việc tiếp theo
                        </button>
                    </div>
                </div>
                @endif
            @endif
        @endif

    </div>

    {{-- 5. CÁC MODAL HỖ TRỢ (Scanner, Image Viewer) --}}
    
    {{-- Scanner Overlay --}}
    <div id="scanner-overlay">
        <div class="text-white mb-3 font-weight-bold text-lg">QUÉT MÃ VẠCH</div>
        <div id="scanner-box">
            <div id="scanner-line"></div>
            <div id="reader"></div>
        </div>
        <button class="btn btn-outline-light mt-5 rounded-pill px-4" onclick="closeScanner()">
            <i class="fas fa-times mr-1"></i> Đóng Camera
        </button>
    </div>

    {{-- Image Viewer Fullscreen --}}
    <div id="imageViewer" onclick="closeImageViewer()">
        <span class="close-viewer">&times;</span>
        <img id="imageViewerSrc" src="">
    </div>

    {{-- 6. LOAD JS --}}
    @include('livewire.work-order.task-detail.scripts')
</div>