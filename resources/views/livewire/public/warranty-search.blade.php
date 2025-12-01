<div class="container">
    <div class="search-box">
        <div class="text-center mb-4">
            <h2 class="text-primary font-weight-bold"><i class="fas fa-shield-alt"></i> TRA CỨU BẢO HÀNH</h2>
            <p class="text-muted">Nhập mã Serial/IMEI/QR Code trên thiết bị để kiểm tra</p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form wire:submit.prevent="search">
                    <div class="input-group input-group-lg">
                        <input type="text" wire:model="serial_number" class="form-control" placeholder="Nhập số Serial (VD: 8936...)" autofocus>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Kiểm tra
                            </button>
                        </div>
                    </div>
                    @error('serial_number') <span class="text-danger mt-2 d-block">{{ $message }}</span> @enderror
                </form>
            </div>
        </div>

        @if($searched)
            <div class="mt-4">
                @if($result)
                    <div class="card result-card shadow">
                        <div class="card-header bg-white">
                            <h5 class="card-title text-success font-weight-bold">
                                <i class="fas fa-check-circle"></i> Tìm thấy thông tin thiết bị
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 text-center mb-3">
                                    <i class="fas fa-server fa-4x text-secondary"></i>
                                    <h4 class="mt-2">{{ $result->item_name }}</h4>
                                    <span class="badge badge-warning text-md px-3">{{ $result->serial_number }}</span>
                                </div>
                            </div>
                            
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Ngày kích hoạt:</span>
                                    <strong>{{ $result->created_at->format('d/m/Y') }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Khách hàng:</span>
                                    {{-- Ẩn bớt tên khách --}}
                                    <span>{{ Str::mask($result->task->workOrder->customer->name ?? '---', '*', 3) }}</span>
                                </li>
                                 <li class="list-group-item d-flex justify-content-between">
                                    <span>Trạng thái:</span>
                                    <span class="badge badge-success">Chính hãng</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger text-center shadow-sm">
                        <h5><i class="fas fa-exclamation-triangle"></i> Không tìm thấy dữ liệu!</h5>
                        <p class="mb-0">Vui lòng kiểm tra lại mã Serial.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>