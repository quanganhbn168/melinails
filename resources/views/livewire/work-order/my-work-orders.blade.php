<div>
    <section class="content-header">
        <div class="container-fluid">
            <h1>
                @if($filter == 'all')
                    Tất cả công việc
                @else
                    Việc hiện tại (Chưa xong)
                @endif
            </h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @forelse($orders as $order)
                    <div class="col-md-6 col-lg-4">
                            <div class="card card-outline {{ $order->status === \App\Enums\WorkOrderStatus::COMPLETED ? 'card-success' : 'card-primary' }}">
                                <div class="card-header">
                                    <h3 class="card-title font-weight-bold">
                                        <i class="fas fa-hashtag"></i> {{ $order->code }}
                                    </h3>
                                    <div class="card-tools">
                                        {{-- Priority Badge --}}
                                        {{-- Priority Badge --}}
                                        @if($order->priority === 'urgent')
                                            <span class="badge badge-danger mr-1"><i class="fas fa-exclamation-circle"></i> Khẩn cấp</span>
                                        @elseif($order->priority === 'high')
                                            <span class="badge badge-warning mr-1" style="color: #fff; background-color: #fd7e14;"><i class="fas fa-arrow-up"></i> Cao</span>
                                        @elseif($order->priority === 'medium')
                                            <span class="badge badge-info mr-1">TB</span>
                                        @else
                                            <span class="badge badge-secondary mr-1">Thấp</span>
                                        @endif

                                        {{-- Badge trạng thái --}}
                                        @if($order->status === \App\Enums\WorkOrderStatus::PENDING)
                                            <span class="badge badge-warning">Chờ xử lý</span>
                                        @elseif($order->status === \App\Enums\WorkOrderStatus::PROCESSING)
                                            <span class="badge badge-primary">Đang làm</span>
                                        @elseif($order->status === \App\Enums\WorkOrderStatus::COMPLETED)
                                            <span class="badge badge-success">Hoàn thành</span>
                                        @else
                                            <span class="badge badge-secondary">Đã hủy</span>
                                        @endif
                                    </div>
                                </div>
                            <div class="card-body">
                                <h5 class="text-primary">{{ $order->title }}</h5>
                                <p class="text-muted small mb-2"><i class="far fa-clock"></i> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                
                                <ul class="list-unstyled">
                                    <li><strong>Khách:</strong> {{ $order->customer->name }}</li>
                                    {{-- Lấy SĐT chính --}}
                                    @php $phone = $order->customer->contacts->where('type', 'phone')->first(); @endphp
                                    <li><strong>SĐT:</strong> <a href="tel:{{ $phone->value ?? '' }}">{{ $phone->value ?? '---' }}</a></li>
                                </ul>
                                
                                <a href="{{ route('admin.work-orders.show', $order->id) }}" class="btn btn-block btn-info">
                                    <i class="fas fa-eye"></i> Xem chi tiết & Báo cáo
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            Bạn chưa được giao công việc nào.
                        </div>
                    </div>
                @endforelse
            </div>
            
            {{-- Phân trang --}}
            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </section>
</div>