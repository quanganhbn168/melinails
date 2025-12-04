<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <h1><i class="fas fa-shield-alt text-success"></i> Danh sách Bảo Hành</h1>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- TABS --}}
            <div class="mb-3">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ $type == 'device' ? 'active' : '' }}" href="#" wire:click.prevent="$set('type', 'device')">
                            Bảo hành Thiết bị
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $type == 'service' ? 'active' : '' }}" href="#" wire:click.prevent="$set('type', 'service')">
                            Bảo hành Dịch vụ
                        </a>
                    </li>
                </ul>
            </div>

            {{-- QUICK FILTERS --}}
            <div class="mb-3">
                <button wire:click="setQuickFilter('expiring_30')" class="btn btn-app bg-warning">
                    <i class="fas fa-hourglass-half"></i> Sắp hết hạn (30 ngày)
                </button>
                <button wire:click="setQuickFilter('expired')" class="btn btn-app bg-danger">
                    <i class="fas fa-times-circle"></i> Đã hết hạn
                </button>
                <button wire:click="setQuickFilter('created_7')" class="btn btn-app bg-info">
                    <i class="fas fa-calendar-plus"></i> Mới tạo (7 ngày)
                </button>
                <button wire:click="resetFilters" class="btn btn-app bg-default">
                    <i class="fas fa-sync"></i> Đặt lại bộ lọc
                </button>
            </div>

            {{-- FILTER PANEL --}}
            <div class="card {{ $showFilters ? '' : 'collapsed-card' }}">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter"></i> Bộ lọc nâng cao</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" wire:click="$toggle('showFilters')">
                            <i class="fas {{ $showFilters ? 'fa-minus' : 'fa-plus' }}"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="{{ $showFilters ? 'display: block;' : 'display: none;' }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select wire:model.live="status" class="form-control">
                                    <option value="all">Tất cả</option>
                                    <option value="active">Đang bảo hành</option>
                                    <option value="expiring_soon">Sắp hết hạn</option>
                                    <option value="expired">Đã hết hạn</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tìm kiếm chung</label>
                                <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="{{ $type == 'device' ? 'Tên máy, Serial...' : 'Tên gói, Mã Job...' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tên khách hàng</label>
                                <input type="text" wire:model.live.debounce.500ms="customerName" class="form-control" placeholder="Nhập tên...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>SĐT Khách hàng</label>
                                <input type="text" wire:model.live.debounce.500ms="customerPhone" class="form-control" placeholder="Nhập số điện thoại...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Loại ngày</label>
                                <select wire:model.live="dateType" class="form-control">
                                    <option value="expiration_date">Ngày hết hạn</option>
                                    <option value="start_date">Ngày bắt đầu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Từ ngày</label>
                                <input type="date" wire:model.live="dateFrom" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <input type="date" wire:model.live="dateTo" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kết quả: {{ $warranties->total() }} bản ghi</h3>
                </div>
                
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr class="bg-light">
                                <th>ID</th>
                                @if($type == 'device')
                                    <th>Thiết bị / Serial</th>
                                @else
                                    <th>Gói dịch vụ</th>
                                @endif
                                <th>Khách hàng</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày hết hạn</th>
                                <th class="text-center">Trạng thái</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warranties as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    
                                    {{-- Cột Tên/Serial --}}
                                    <td>
                                        @if($type == 'device')
                                            <span class="font-weight-bold">{{ $item->device_name }}</span><br>
                                            <small class="text-muted code">SN: {{ $item->serial_number }}</small>
                                        @else
                                            <span class="font-weight-bold">{{ $item->workOrder->title ?? 'Bảo hành dịch vụ' }}</span><br>
                                            <small class="text-muted">Job: {{ $item->workOrder->code ?? 'N/A' }}</small>
                                        @endif
                                    </td>

                                    {{-- Khách hàng --}}
                                    <td>
                                        @php
                                            if($type == 'device') {
                                                $customer = optional(optional(optional(optional($item->item)->report)->task)->workOrder)->customer;
                                            } else {
                                                $customer = optional($item->workOrder)->customer;
                                            }
                                        @endphp
                                        {{ $customer->name ?? '---' }}<br>
                                        <small class="text-muted">{{ $customer->phone ?? '' }}</small>
                                    </td>

                                    {{-- Ngày bắt đầu --}}
                                    <td>
                                        {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') : 'N/A' }}
                                    </td>

                                    {{-- Ngày hết hạn --}}
                                    <td class="font-weight-bold">
                                        {{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('d/m/Y') : 'N/A' }}
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td class="text-center">
                                        @if(!$item->expiration_date)
                                            <span class="badge badge-secondary">Không xác định</span>
                                        @elseif(\Carbon\Carbon::now()->gt($item->expiration_date))
                                            <span class="badge badge-secondary">Đã hết hạn</span>
                                        @else
                                            @php
                                                $daysLeft = \Carbon\Carbon::now()->diffInDays($item->expiration_date, false);
                                            @endphp
                                            
                                            @if($daysLeft < 30)
                                                <span class="badge badge-warning">Sắp hết ({{ $daysLeft }} ngày)</span>
                                            @else
                                                <span class="badge badge-success">Đang bảo hành</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>{{ $item->notes }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-box-open fa-3x mb-3 text-gray-300"></i><br>
                                        Không tìm thấy dữ liệu phù hợp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $warranties->links() }}
                </div>
            </div>
        </div>
    </section>
</div>