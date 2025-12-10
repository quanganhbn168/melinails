<div>
    <div class="container-fluid py-3">
        
        {{-- HEADER WITH INLINE FILTER BUTTONS --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="mb-2 mb-md-0 font-weight-bold">
                <i class="fas fa-briefcase text-primary"></i> Công việc
            </h5>
            
            {{-- INLINE STATUS BUTTONS --}}
            <div class="btn-group btn-group-sm flex-wrap" role="group">
                <button type="button" wire:click="$set('status', 'all')" 
                        class="btn {{ $status == 'all' ? 'btn-info' : 'btn-outline-info' }}">
                    <i class="fas fa-list"></i> {{ $this->counts['all'] }}
                </button>
                <button type="button" wire:click="$set('status', 'pending')" 
                        class="btn {{ $status == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-clock"></i> {{ $this->counts['pending'] }}
                </button>
                <button type="button" wire:click="$set('status', 'processing')" 
                        class="btn {{ $status == 'processing' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-spinner"></i> {{ $this->counts['processing'] }}
                </button>
                <button type="button" wire:click="$set('status', 'urgent')" 
                        class="btn {{ $status == 'urgent' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="fas fa-fire"></i> {{ $this->counts['urgent'] }}
                </button>
                <button type="button" wire:click="$set('status', 'completed')" 
                        class="btn {{ $status == 'completed' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="fas fa-check"></i> {{ $this->counts['completed'] }}
                </button>
            </div>
        </div>

        {{-- SEARCH BAR --}}
        <div class="mb-3">
            <div class="input-group input-group-sm">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Tìm theo mã, tên, SĐT...">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>
        </div>

        {{-- JOB CARDS --}}
        @forelse($orders as $order)
            <div class="card mb-3 shadow-sm border-left-{{ $order->status === \App\Enums\WorkOrderStatus::COMPLETED ? 'success' : ($order->isOverdue() ? 'danger' : 'primary') }}" style="border-left-width: 4px !important;">
                <div class="card-body p-3">
                    
                    {{-- ROW 1: Code + Priority + Status + Deadline --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="badge badge-light border">
                                <i class="fas fa-hashtag text-muted"></i> {{ $order->code }}
                            </span>
                            {!! $order->priority_badge !!}
                            @if($order->isOverdue())
                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Quá hạn</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $order->status->color() }}">{{ $order->status->label() }}</span>
                            @if($order->deadline && !$order->isLocked())
                                <div class="small mt-1">
                                    <i class="far fa-calendar-alt text-muted"></i>
                                    <span class="{{ $order->isOverdue() ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                        {{ $order->deadline->format('d/m H:i') }}
                                    </span>
                                    @if(!$order->isOverdue())
                                        <span class="text-info">({{ $order->deadline_status['label'] ?? '' }})</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ROW 2: Title + Tags --}}
                    <h6 class="font-weight-bold mb-1">{{ $order->title }}</h6>
                    @if($order->tags->count())
                        <div class="mb-2">
                            @foreach($order->tags as $tag)
                                {!! $tag->badge_html !!}
                            @endforeach
                        </div>
                    @endif

                    {{-- ROW 3: Description (if exists) --}}
                    @if($order->description)
                        <p class="text-muted small mb-2" style="line-height: 1.4;">
                            {{ Str::limit($order->description, 120) }}
                        </p>
                    @endif

                    {{-- ROW 4: Contact Info (Compact) --}}
                    <div class="small mb-2">
                        <div class="d-flex flex-wrap" style="gap: 10px;">
                            <span><i class="fas fa-user text-secondary"></i> {{ $order->contact_person }}</span>
                            @if($order->contact_phone)
                                <a href="tel:{{ $order->contact_phone }}" class="text-success">
                                    <i class="fas fa-phone"></i> {{ $order->contact_phone }}
                                </a>
                            @endif
                        </div>
                        <div class="mt-1">
                            <a href="https://maps.google.com/?q={{ urlencode($order->site_address) }}" target="_blank" class="text-muted">
                                <i class="fas fa-map-marker-alt text-danger"></i> {{ Str::limit($order->site_address, 50) }}
                            </a>
                        </div>
                    </div>

                    {{-- ROW 5: Progress + Action --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1 mr-3">
                            <div class="progress progress-xs mb-1">
                                <div class="progress-bar {{ $order->progress_color }}" style="width: {{ $order->progress_percent }}%"></div>
                            </div>
                            <small class="text-muted">{{ $order->progress_text }}</small>
                        </div>
                        <a href="{{ route('admin.work-orders.show', $order->id) }}" class="btn btn-sm btn-primary rounded-circle" title="Chi tiết">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-gray-300 mb-3"></i>
                <p class="text-muted">Không có công việc nào.</p>
            </div>
        @endforelse

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
