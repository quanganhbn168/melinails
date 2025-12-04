<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Thông báo</h1>
                <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check-double"></i> Đọc tất cả
                </button>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item list-group-item-action flex-column align-items-start {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-primary" style="font-size: 1rem;">
                                    {{ $notification->data['title'] ?? 'Thông báo mới' }}
                                </h5>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-sm">{{ $notification->data['message'] ?? '' }}</p>
                            
                            @if(!$notification->read_at)
                                <small class="text-primary" style="cursor: pointer;" wire:click="markAsRead('{{ $notification->id }}')">
                                    Đánh dấu đã đọc
                                </small>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i><br>
                    Không có thông báo nào.
                </div>
            @endif
        </div>
    </section>
</div>
