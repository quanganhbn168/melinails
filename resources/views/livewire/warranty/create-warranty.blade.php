<div>
    <style>
        /* CSS Tùy chỉnh */
        .row-selected { background-color: #f0fff4; }
        .table-input { 
            border: 1px solid #ced4da; border-radius: 3px; 
            padding: 2px 8px; width: 100%; font-size: 14px; height: 30px; 
        }
        .table-input:focus { 
            border-color: #80bdff; outline: 0; 
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); 
        }
        
        /* Dropdown gợi ý tìm kiếm */
        .suggestion-box {
            position: absolute; z-index: 1050; background: white; width: 100%; min-width: 250px;
            border: 1px solid #ddd; border-radius: 0 0 4px 4px; 
            max-height: 200px; overflow-y: auto; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }
        .suggestion-item { padding: 8px 10px; cursor: pointer; border-bottom: 1px solid #f4f4f4; font-size: 13px; }
        .suggestion-item:hover { background-color: #e8f0fe; color: #007bff; }
    </style>

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="m-0"><i class="fas fa-shield-alt text-success mr-2"></i> Thiết lập Bảo Hành</h1>
                    <small class="text-muted">Tạo phiếu bảo hành cho Job <b>#{{ $workOrder->code }}</b></small>
                </div>
                <a href="{{ route('admin.work-orders.index') }}" class="btn btn-default border">
                    <i class="fas fa-times mr-1"></i> Hủy bỏ
                </a>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                
                {{-- CỘT TRÁI: THÔNG TIN KHÁCH (Cố định) --}}
                <div class="col-md-3">
                    <div class="card card-success card-outline sticky-top" style="top: 20px;">
                        <div class="card-body box-profile">
                            <h3 class="profile-username text-center">{{ $workOrder->customer->name }}</h3>
                            <p class="text-muted text-center mb-3">Mã Job: <b>{{ $workOrder->code }}</b></p>
                            
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Điện thoại</b> <a class="float-right">{{ $workOrder->contact_phone }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Liên hệ</b> <a class="float-right">{{ $workOrder->contact_person }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Nghiệm thu</b> 
                                    <span class="float-right text-success font-weight-bold">
                                        {{ $workOrder->approved_at ? \Carbon\Carbon::parse($workOrder->approved_at)->format('d/m/Y') : '---' }}
                                    </span>
                                </li>
                            </ul>
                            <div class="text-muted small">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $workOrder->site_address }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: FORM NHẬP LIỆU --}}
                <div class="col-md-9">
                    
                    {{-- 1. BẢO HÀNH DỊCH VỤ (GÓI TỔNG) --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-file-contract mr-1"></i> 1. Bảo hành Dịch vụ (Tổng hợp)</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Tổng tiền --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tổng giá trị Job</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control font-weight-bold text-success" 
                                                   value="{{ number_format($service['total_amount']) }}" disabled>
                                            <div class="input-group-append">
                                                <span class="input-group-text">đ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ngày bắt đầu --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" wire:model.live="service.start_date">
                                    </div>
                                </div>

                                {{-- Số tháng (Nhập vào tự tính ngày hết hạn) --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Thời hạn (Tháng)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control text-center font-weight-bold" 
                                                   wire:model.live="service.warranty_months">
                                            <div class="input-group-append"><span class="input-group-text">Th</span></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ngày hết hạn (Readonly) --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Ngày hết hạn</label>
                                        <input type="text" class="form-control bg-light text-danger font-weight-bold" readonly 
                                               wire:model="service.expiration_date">
                                    </div>
                                </div>

                                {{-- Ghi chú --}}
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label>Ghi chú / Điều khoản</label>
                                        <textarea class="form-control" wire:model="service.notes" rows="1" 
                                                  placeholder="VD: Bảo hành tại nhà 24/7..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. BẢO HÀNH THIẾT BỊ (CHI TIẾT) --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-microchip mr-1"></i> 2. Danh sách Thiết bị
                            </h3>
                            <span class="badge badge-info">Tick chọn thiết bị cần bảo hành</span>
                        </div>
                        
                        <div class="card-body table-responsive p-0" style="min-height: 300px;">
                            <table class="table table-hover table-bordered text-nowrap mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px">BH?</th>
                                        <th style="min-width: 250px">Tên thiết bị (Chuẩn hóa)</th>
                                        <th style="width: 150px">Serial Number</th>
                                        <th style="width: 130px">Ngày bán</th>
                                        <th style="width: 80px">Tháng</th>
                                        <th style="width: 120px">Hết hạn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($devices as $id => $dev)
                                        {{-- Đổi màu nền nếu được chọn --}}
                                        <tr class="{{ $dev['selected'] ? 'row-selected' : '' }}">
                                            
                                            {{-- 1. Checkbox --}}
                                            <td class="text-center align-middle bg-white">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" 
                                                           id="dev_{{ $id }}" 
                                                           wire:model.live="devices.{{ $id }}.selected">
                                                    <label for="dev_{{ $id }}" class="custom-control-label"></label>
                                                </div>
                                                @if($dev['is_existing'])
                                                    <div class="mt-1 text-success font-weight-bold" style="font-size: 9px;">Đã lưu</div>
                                                @endif
                                            </td>

                                            {{-- 2. Tên thiết bị (Có Search) --}}
                                            <td class="align-middle" style="position: relative; overflow: visible;">
                                                @if($dev['selected'])
                                                    <input type="text" class="table-input font-weight-bold" 
                                                           wire:model.live.debounce.300ms="devices.{{ $id }}.device_name"
                                                           placeholder="Nhập tên chuẩn..." autocomplete="off">
                                                    
                                                    {{-- Gợi ý tìm kiếm --}}
                                                    @if(!empty($suggestions[$id]))
                                                        <div class="suggestion-box">
                                                            @foreach($suggestions[$id] as $sug)
                                                                <div class="suggestion-item" wire:click="selectMaterial({{ $id }}, {{ $sug->id }})">
                                                                    <strong>{{ $sug->name }}</strong> <span class="text-muted">({{ $sug->code }})</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <div class="text-muted text-xs mt-1">
                                                        <i class="fas fa-history mr-1"></i> Gốc: {{ $dev['item_name'] }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">{{ $dev['item_name'] }}</span>
                                                @endif
                                            </td>

                                            {{-- 3. Serial --}}
                                            <td class="align-middle">
                                                @if($dev['selected'])
                                                    <input type="text" class="table-input" 
                                                           wire:model="devices.{{ $id }}.serial_number" placeholder="---">
                                                @else
                                                    <span class="text-muted font-italic">{{ $dev['serial_number'] ?: '---' }}</span>
                                                @endif
                                            </td>

                                            {{-- 4. Ngày bán --}}
                                            <td class="align-middle">
                                                @if($dev['selected'])
                                                    <input type="date" class="table-input" 
                                                           wire:model.live="devices.{{ $id }}.sold_date">
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </td>

                                            {{-- 5. Số tháng --}}
                                            <td class="align-middle">
                                                @if($dev['selected'])
                                                    <input type="number" class="table-input text-center" 
                                                           wire:model.live="devices.{{ $id }}.warranty_months">
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </td>

                                            {{-- 6. Ngày hết hạn (Tự tính) --}}
                                            <td class="align-middle">
                                                @if($dev['selected'])
                                                    <input type="text" class="table-input bg-white text-danger font-weight-bold border-0 px-0" readonly 
                                                           wire:model="devices.{{ $id }}.expiration_date">
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                                Không tìm thấy vật tư nào trong các báo cáo công việc.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Footer --}}
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted text-sm">
                                    <i class="fas fa-info-circle text-info mr-1"></i>
                                    Bỏ chọn (Uncheck) sẽ <b>xóa bảo hành</b> của thiết bị đó.
                                </div>
                                <button wire:click="save" class="btn btn-success px-4 font-weight-bold shadow-sm">
                                    <i class="fas fa-save mr-2"></i> LƯU & KÍCH HOẠT
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>