<div>
    {{-- TABS --}}
    <div class="bg-white shadow-sm mb-3">
        <ul class="nav nav-pills nav-fill p-2">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'product' ? 'active' : '' }}" 
                   wire:click.prevent="switchTab('product')" href="#">
                   <i class="fas fa-barcode mr-1"></i> SP / Serial
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'service' ? 'active' : '' }}" 
                   wire:click.prevent="switchTab('service')" href="#">
                   <i class="fas fa-history mr-1"></i> Lịch sử DV
                </a>
            </li>
        </ul>
    </div>

    <div class="container">
        {{-- TAB 1: PRODUCT WARRANTY --}}
        @if($activeTab == 'product')
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">Tra cứu bảo hành sản phẩm</h6>
                    
                    <div class="input-group mb-3">
                        <input type="text" wire:model="serialNumber" class="form-control" placeholder="Nhập hoặc quét Serial...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="startScanner()">
                                <i class="fas fa-qrcode"></i>
                            </button>
                        </div>
                    </div>
                    <button wire:click="checkProductWarranty" class="btn btn-primary btn-block mb-3">
                        Kiểm tra
                    </button>

                    @if($searchError)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> {{ $searchError }}
                        </div>
                    @endif

                    @if($productResult)
                        <div class="alert {{ $productResult['status'] == 'valid' ? 'alert-success' : 'alert-warning' }}">
                            <h5 class="alert-heading font-weight-bold">
                                {{ $productResult['status'] == 'valid' ? 'CÒN BẢO HÀNH' : 'HẾT BẢO HÀNH' }}
                            </h5>
                            <hr>
                            <p class="mb-1"><strong>Sản phẩm:</strong> {{ $productResult['name'] }}</p>
                            <p class="mb-1"><strong>Serial:</strong> {{ $productResult['serial'] }}</p>
                            <p class="mb-1"><strong>Ngày mua:</strong> {{ $productResult['sale_date'] }}</p>
                            <p class="mb-1"><strong>Hết hạn:</strong> {{ $productResult['expiry_date'] }}</p>
                            <p class="mb-0"><strong>Phiếu việc:</strong> {{ $productResult['job_code'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Scanner Modal (Hidden by default) --}}
            <div id="scanner-container" style="display:none; position: fixed; top:0; left:0; right:0; bottom:0; background:#000; z-index:9999;">
                <div id="reader" style="width: 100%; height: 100%;"></div>
                <button onclick="stopScanner()" class="btn btn-danger" style="position:absolute; bottom: 20px; left: 50%; transform: translateX(-50%); z-index:10000;">
                    Đóng Scanner
                </button>
            </div>
        @endif

        {{-- TAB 2: SERVICE WARRANTY --}}
        @if($activeTab == 'service')
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">Tra cứu bảo hành dịch vụ & thiết bị</h6>
                    
                    <div class="form-group">
                        <input type="text" wire:model.live.debounce.500ms="customerSearch" 
                               wire:keyup="searchServiceHistory"
                               class="form-control" placeholder="Nhập tên hoặc SĐT khách hàng...">
                    </div>

                    @if(!empty($serviceHistory))
                        
                        {{-- 1. Bảo hành Dịch vụ --}}
                        @if($serviceHistory['services']->count() > 0)
                            <h6 class="text-primary font-weight-bold mt-3 border-bottom pb-1">Gói Bảo Hành Dịch Vụ</h6>
                            <div class="list-group list-group-flush">
                                @foreach($serviceHistory['services'] as $srv)
                                    <a href="#" wire:click.prevent="selectJob('service', {{ $srv->id }})" 
                                       class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 font-weight-bold">{{ $srv->workOrder->title ?? 'Bảo hành dịch vụ' }}</h6>
                                            <small>{{ $srv->start_date ? $srv->start_date->format('d/m/Y') : '' }}</small>
                                        </div>
                                        <p class="mb-1 text-muted small">Mã Job: {{ $srv->workOrder->code }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- 2. Bảo hành Thiết bị --}}
                        @if($serviceHistory['devices']->count() > 0)
                            <h6 class="text-success font-weight-bold mt-3 border-bottom pb-1">Bảo Hành Thiết Bị</h6>
                            <div class="list-group list-group-flush">
                                @foreach($serviceHistory['devices'] as $dev)
                                    <a href="#" wire:click.prevent="selectJob('device', {{ $dev->id }})" 
                                       class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 font-weight-bold">{{ $dev->device_name }}</h6>
                                            <small class="{{ $dev->is_expired ? 'text-danger' : 'text-success' }}">
                                                {{ $dev->is_expired ? 'Hết hạn' : 'Còn hạn' }}
                                            </small>
                                        </div>
                                        <p class="mb-1 text-muted small">Serial: {{ $dev->serial_number }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if($serviceHistory['services']->isEmpty() && $serviceHistory['devices']->isEmpty())
                             <p class="text-muted text-center mt-3">Khách hàng này chưa có thông tin bảo hành nào.</p>
                        @endif

                    @elseif(!empty($customerSearch))
                        <p class="text-muted text-center mt-3">Không tìm thấy khách hàng.</p>
                    @endif
                </div>
            </div>

            {{-- DETAIL MODAL --}}
            @if($selectedJob)
                <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5); z-index: 1050;" tabindex="-1">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    {{ $selectedJob->type == 'service' ? 'Chi tiết BH Dịch vụ' : 'Chi tiết BH Thiết bị' }}
                                </h5>
                                <button type="button" class="close" wire:click="$set('selectedJob', null)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if($selectedJob->type == 'service')
                                    <h6><strong>{{ $selectedJob->workOrder->code }}</strong></h6>
                                    <p>Khách hàng: {{ $selectedJob->customer_name }}</p>
                                    <p>Ngày bắt đầu: {{ $selectedJob->start_date ? $selectedJob->start_date->format('d/m/Y') : 'N/A' }}</p>
                                    <hr>
                                    <h6>Danh sách thiết bị:</h6>
                                    <p>{{ $selectedJob->device_list_details }}</p>
                                    <p>Số lượng: {{ $selectedJob->device_list_qty }}</p>
                                    <p class="text-muted small">Ghi chú: {{ $selectedJob->notes }}</p>
                                @else
                                    {{-- Device Detail --}}
                                    <h5 class="text-primary font-weight-bold">{{ $selectedJob->device_name }}</h5>
                                    <p><strong>Serial:</strong> {{ $selectedJob->serial_number }}</p>
                                    <p><strong>Ngày mua:</strong> {{ $selectedJob->start_date ? $selectedJob->start_date->format('d/m/Y') : 'N/A' }}</p>
                                    <p><strong>Hết hạn:</strong> {{ $selectedJob->expiration_date ? $selectedJob->expiration_date->format('d/m/Y') : 'N/A' }}</p>
                                    <p><strong>Thời hạn:</strong> {{ $selectedJob->warranty_months }} tháng</p>
                                    <p>
                                        <strong>Trạng thái:</strong> 
                                        <span class="badge {{ $selectedJob->is_expired ? 'badge-danger' : 'badge-success' }}">
                                            {{ $selectedJob->is_expired ? 'Hết hạn' : 'Đang bảo hành' }}
                                        </span>
                                    </p>
                                    <hr>
                                    <p class="small text-muted">Job gốc: {{ $selectedJob->item->report->task->workOrder->code ?? 'N/A' }}</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="$set('selectedJob', null)">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    @push('js')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrcodeScanner;

        function startScanner() {
            document.getElementById('scanner-container').style.display = 'block';
            
            html5QrcodeScanner = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrcodeScanner.start({ facingMode: "environment" }, config, 
                (decodedText) => {
                    // Success
                    @this.set('serialNumber', decodedText);
                    stopScanner();
                    // Optional: Auto submit
                    // @this.call('checkProductWarranty');
                },
                (errorMessage) => {
                    // Parse error, ignore
                }
            ).catch(err => {
                console.log(err);
                alert('Không thể khởi động camera');
                stopScanner();
            });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    document.getElementById('scanner-container').style.display = 'none';
                }).catch(err => {
                    console.log('Failed to stop scanner', err);
                });
            } else {
                document.getElementById('scanner-container').style.display = 'none';
            }
        }
    </script>
    @endpush
</div>
