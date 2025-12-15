<div>
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-undo-alt"></i> Vật tư thu hồi</h3>
        </div>
        <div class="card-body">
            
            {{-- Stats Cards - Theo Status --}}
            <div class="row mb-4">
                <div class="col-md-2 col-6">
                    <div class="info-box bg-info mb-3">
                        <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tổng cộng</span>
                            <span class="info-box-number">{{ $stats['total'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box bg-warning mb-3">
                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Chờ xử lý</span>
                            <span class="info-box-number">{{ $stats['pending'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box bg-primary mb-3">
                        <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Đã gửi NCC</span>
                            <span class="info-box-number">{{ $stats['sent_to_supplier'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box bg-primary mb-3">
                        <span class="info-box-icon"><i class="fas fa-inbox"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Đã nhận về</span>
                            <span class="info-box-number">{{ $stats['returned_from_supplier'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box bg-success mb-3">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Hoàn tất</span>
                            <span class="info-box-number">{{ $stats['done'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="row mb-3 align-items-end">
                <div class="col-md-2">
                    <label class="small mb-1">Tìm kiếm</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Tên, Serial...">
                </div>
                <div class="col-md-2">
                    <label class="small mb-1">Trạng thái</label>
                    <select wire:model.live="filterStatus" class="form-control form-control-sm">
                        <option value="">Tất cả</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- ... date filters ... --}}
                <div class="col-md-2">
                    <label class="small mb-1">Từ ngày</label>
                    <input type="date" wire:model.live="filterFrom" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="small mb-1">Đến ngày</label>
                    <input type="date" wire:model.live="filterTo" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-sm btn-default">
                        <i class="fas fa-sync"></i> Reset
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Thiết bị</th>
                            <th>Trạng thái & Tiến độ</th>
                            <th>Nhà cung cấp / Kết quả</th>
                            <th style="width: 100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>
                                    <div class="font-weight-bold">{{ $item->item_name }}</div>
                                    <small class="text-muted">{{ $item->serial_number ?: 'No Serial' }}</small>
                                    <div class="mt-1"><span class="badge badge-default border">{{ $item->reason_label }}</span></div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $item->status->color() }}">{{ $item->status->label() }}</span>
                                    <div class="text-xs text-muted mt-1">
                                        @if($item->status->value == 'sent_to_supplier')
                                            <i class="fas fa-arrow-right"></i> {{ $item->sentToSupplierBy->name ?? 'Staff' }}<br>
                                            <i class="far fa-clock"></i> {{ $item->sent_to_supplier_at ? $item->sent_to_supplier_at->format('d/m H:i') : '' }}
                                        @elseif($item->status->value == 'returned_from_supplier')
                                            <i class="fas fa-undo"></i> Từ NCC<br>
                                            <i class="far fa-clock"></i> {{ $item->received_from_supplier_at ? $item->received_from_supplier_at->format('d/m H:i') : '' }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    {{ $item->supplier->name ?? '---' }}
                                    @if($item->supplier_result)
                                        <div class="mt-1 font-weight-bold text-primary">
                                            {{ $item->supplier_result->label() }}
                                        </div>
                                        @if($item->repair_cost > 0)
                                            <small class="text-danger font-weight-bold">-{{ number_format($item->repair_cost) }}đ</small>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($item->status->value == 'pending')
                                        <button wire:click="openSendModal({{ $item->id }})" class="btn btn-xs btn-info" title="Gửi NCC">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    @endif

                                    @if($item->status->value == 'sent_to_supplier')
                                        <button wire:click="openReceiveModal({{ $item->id }})" class="btn btn-xs btn-primary" title="Nhận lại">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    @endif

                                    @if($item->status->value == 'returned_from_supplier')
                                        <button wire:click="markAsClosed({{ $item->id }})" class="btn btn-xs btn-success" title="Hoàn tất">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Không có dữ liệu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer clearfix">{{ $items->links() }}</div>
        </div>
    </div>

    {{-- MODAL SENT --}}
    <div class="modal fade" id="modal-send-supplier" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info"><h5 class="modal-title">Gửi NCC</h5></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nhà cung cấp (*)</label>
                        <select wire:model="sendSupplierId" class="form-control">
                            <option value="">-- Chọn NCC --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nhân viên gửi (*)</label>
                        <select wire:model="sendStaffId" class="form-control">
                            <option value="">-- Chọn NV --</option>
                            @foreach($staffs as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea wire:model="sendNote" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button wire:click="saveSendToSupplier" class="btn btn-info">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL RECEIVE --}}
    <div class="modal fade" id="modal-receive-supplier" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary"><h5 class="modal-title">Nhận từ NCC</h5></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kết quả (*)</label>
                        <select wire:model="receiveResult" class="form-control">
                            @foreach($resultOptions as $res)
                                <option value="{{ $res->value }}">{{ $res->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Chi phí</label>
                        <input type="number" wire:model="receiveCost" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea wire:model="receiveNote" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button wire:click="saveReceiveFromSupplier" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-send-modal', event => $('#modal-send-supplier').modal('show'));
        window.addEventListener('open-receive-modal', event => $('#modal-receive-supplier').modal('show'));
        window.addEventListener('close-modals', event => {
            $('.modal').modal('hide');
        });
    </script>
</div>
