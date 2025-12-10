<div>
    {{-- DIRECT CHAT BOX using AdminLTE3 --}}
    <div class="direct-chat direct-chat-primary">
        {{-- Toolbar --}}
        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
            <button wire:click="loadFeed" class="btn btn-xs btn-outline-secondary">
                <i class="fas fa-sync-alt"></i> Tải lại
            </button>
            <button onclick="scrollChatToBottom()" class="btn btn-xs btn-outline-primary">
                <i class="fas fa-arrow-down"></i> Cuối trang
            </button>
        </div>
        {{-- CHAT MESSAGES AREA --}}
        <div class="direct-chat-messages" id="chat-messages" style="height: 400px;">
            @forelse(collect($feedItems)->reverse() as $item)
                @if($item['type'] === 'comment')
                    {{-- COMMENT MESSAGE --}}
                    @php 
                        $comment = $item['data'];
                        $isMe = $comment->admin_id === auth('admin')->id();
                    @endphp
                    
                    <div class="direct-chat-msg {{ $isMe ? 'right' : '' }}" wire:key="{{ $item['id'] }}">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name {{ $isMe ? 'float-right' : 'float-left' }}">
                                {{ $comment->author->name }}
                            </span>
                            <span class="direct-chat-timestamp {{ $isMe ? 'float-left' : 'float-right' }}">
                                {{ $comment->created_at->format('d/m H:i') }}
                            </span>
                        </div>
                        <img class="direct-chat-img" src="{{ $comment->author->avatar_url }}" alt="{{ $comment->author->name }}">
                        
                        <div class="direct-chat-text">
                            @if($comment->content)
                                <div style="white-space: pre-wrap;">{!! preg_replace('/@\[([^\]]+)\]\(\d+\)/', '<span class="badge badge-light">@$1</span>', e($comment->content)) !!}</div>
                            @endif

                            @if($comment->attachments->count() > 0)
                                <div class="mt-2 pt-2 {{ $comment->content ? 'border-top' : '' }}">
                                    @php $images = $comment->attachments->where('file_type', 'image'); @endphp
                                    @if($images->count() > 0)
                                        <div class="d-flex flex-wrap mb-1" style="gap: 4px;">
                                            @foreach($images as $img)
                                                <a href="{{ $img->file_url }}" target="_blank">
                                                    <img src="{{ $img->file_url }}" class="rounded" style="max-width: 120px; max-height: 80px; object-fit: cover;">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    @php $videos = $comment->attachments->where('file_type', 'video'); @endphp
                                    @if($videos->count() > 0)
                                        @foreach($videos as $video)
                                            <video controls class="rounded mb-1" style="max-width: 200px; max-height: 120px;">
                                                <source src="{{ $video->file_url }}" type="{{ $video->mime_type }}">
                                            </video>
                                        @endforeach
                                    @endif

                                    @php $docs = $comment->attachments->where('file_type', 'document'); @endphp
                                    @if($docs->count() > 0)
                                        @foreach($docs as $doc)
                                            <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-xs btn-outline-secondary mr-1 mb-1">
                                                <i class="{{ $doc->file_icon }} mr-1"></i>{{ Str::limit($doc->file_name, 15) }}
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif($item['type'] === 'report')
                    {{-- TASK REPORT CARD --}}
                    @php $report = $item['data']; @endphp
                    
                    <div class="report-card mb-3" wire:key="{{ $item['id'] }}">
                        <div class="card card-success card-outline mb-0 shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-clipboard-check mr-1"></i> BÁO CÁO
                                    @if($report->is_completed)
                                        <span class="badge badge-success ml-2"><i class="fas fa-check"></i> Hoàn thành</span>
                                    @endif
                                </h3>
                                <div class="card-tools">
                                    <span class="text-muted text-sm">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                {{-- Reporter Info --}}
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $report->reporter->avatar_url }}" class="img-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <strong>{{ $report->reporter->name }}</strong>
                                        <div class="text-muted text-sm">{{ Str::limit($report->task->report_content ?? 'Task #'.$report->task_id, 50) }}</div>
                                    </div>
                                </div>

                                {{-- Report Content --}}
                                @if($report->content)
                                    <div class="callout callout-info py-2 px-3 mb-3">
                                        <h5 class="text-sm font-weight-bold mb-1"><i class="fas fa-comment-alt mr-1"></i> Nội dung báo cáo</h5>
                                        <p class="mb-0 text-sm" style="white-space: pre-wrap;">{{ $report->content }}</p>
                                    </div>
                                @endif

                                {{-- Acceptance Images --}}
                                @if($report->images->count() > 0)
                                    <div class="mb-3">
                                        <h5 class="text-sm font-weight-bold text-muted mb-2"><i class="fas fa-camera mr-1"></i> Ảnh nghiệm thu</h5>
                                        <div class="d-flex flex-wrap" style="gap: 8px;">
                                            @foreach($report->images as $image)
                                                <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="thumbnail-link">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Materials Used (Table) --}}
                                @if($report->items->count() > 0)
                                    <div class="mb-3">
                                        <h5 class="text-sm font-weight-bold text-muted mb-2"><i class="fas fa-box mr-1"></i> Vật tư sử dụng</h5>
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Tên vật tư</th>
                                                    <th>Serial</th>
                                                    <th class="text-center" style="width: 60px;">SL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($report->items as $materialItem)
                                                    <tr>
                                                        <td>{{ $materialItem->item_name }}</td>
                                                        <td class="text-muted">{{ $materialItem->serial_number ?? '-' }}</td>
                                                        <td class="text-center">{{ $materialItem->quantity }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                {{-- Returned Items (Table) --}}
                                @if($report->returnedItems->count() > 0)
                                    <div class="mb-3">
                                        <h5 class="text-sm font-weight-bold text-muted mb-2"><i class="fas fa-undo mr-1"></i> Vật tư thu hồi</h5>
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Tên vật tư</th>
                                                    <th>Serial</th>
                                                    <th>Lý do</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($report->returnedItems as $returned)
                                                    <tr>
                                                        <td>{{ $returned->item_name }}</td>
                                                        <td class="text-muted">{{ $returned->serial_number ?? '-' }}</td>
                                                        <td><span class="badge badge-warning">{{ $returned->reason_label }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                {{-- Payment Collected --}}
                                @if($report->collected_amount > 0)
                                    <div class="callout callout-success py-2 px-3 mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-money-bill-wave mr-1"></i> <strong>Đã thu tiền</strong>
                                            </div>
                                            <div class="text-right">
                                                <span class="h5 text-success font-weight-bold mb-0">{{ number_format($report->collected_amount) }}đ</span>
                                                <span class="badge badge-light ml-1">{{ $report->payment_method == 'transfer' ? 'Chuyển khoản' : 'Tiền mặt' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif($item['type'] === 'task_spawn')
                    {{-- TASK SPAWN CARD --}}
                    @php $spawnedTask = $item['data']; @endphp
                    
                    <div class="spawn-card mb-3" wire:key="{{ $item['id'] }}">
                        <div class="card card-info card-outline mb-0 shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-code-branch mr-1"></i> CÔNG VIỆC MỚI
                                </h3>
                                <div class="card-tools">
                                    <span class="text-muted text-sm">{{ $spawnedTask->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                {{-- Task Title --}}
                                <div class="mb-2">
                                    <strong class="text-dark">{{ $spawnedTask->title ?: $spawnedTask->report_content ?: 'Task #'.$spawnedTask->id }}</strong>
                                </div>

                                {{-- Meta Info --}}
                                <div class="d-flex flex-wrap text-sm text-muted mb-2" style="gap: 12px;">
                                    @if($spawnedTask->parentTask)
                                        <span>
                                            <i class="fas fa-link mr-1"></i> Từ: <a href="{{ route('admin.tasks.detail', $spawnedTask->parent_task_id) }}" class="text-info">{{ Str::limit($spawnedTask->parentTask->title ?: $spawnedTask->parentTask->report_content ?: 'Task #'.$spawnedTask->parent_task_id, 30) }}</a>
                                        </span>
                                    @endif
                                    @if($spawnedTask->createdByWorker)
                                        <span>
                                            <i class="fas fa-user-plus mr-1"></i> Tạo bởi: {{ $spawnedTask->createdByWorker->name }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Scheduled & Performer --}}
                                <div class="d-flex flex-wrap" style="gap: 8px;">
                                    @if($spawnedTask->scheduled_at)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-calendar-alt mr-1"></i> Hẹn: {{ $spawnedTask->scheduled_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                    @if($spawnedTask->performer)
                                        <span class="badge badge-primary">
                                            <i class="fas fa-user mr-1"></i> {{ $spawnedTask->performer->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-user-clock mr-1"></i> Chưa gán
                                        </span>
                                    @endif
                                    <span class="badge badge-{{ $spawnedTask->status->color() }}">
                                        {{ $spawnedTask->status->label() }}
                                    </span>
                                </div>

                                {{-- Link to Task --}}
                                <div class="mt-3">
                                    <a href="{{ route('admin.tasks.detail', $spawnedTask->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt mr-1"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="far fa-comment-dots fa-3x mb-3" style="opacity: 0.3;"></i>
                    <p class="mb-0">Chưa có tin nhắn hoặc báo cáo nào</p>
                    <small>Hãy bắt đầu cuộc trò chuyện!</small>
                </div>
            @endforelse
        </div>

        {{-- ATTACHMENT PREVIEW --}}
        @if(count($attachments) > 0)
            <div class="p-2 bg-light border-top d-flex flex-wrap align-items-center" style="gap: 8px;">
                <small class="text-muted"><i class="fas fa-paperclip"></i> Đính kèm:</small>
                @foreach($attachments as $index => $file)
                    <span class="badge badge-secondary d-flex align-items-center" style="gap: 5px;">
                        @if(in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <i class="fas fa-image text-success"></i>
                        @elseif(in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov', 'avi', 'webm']))
                            <i class="fas fa-video text-danger"></i>
                        @else
                            <i class="fas fa-file text-info"></i>
                        @endif
                        {{ Str::limit($file->getClientOriginalName(), 15) }}
                        <button type="button" wire:click="removeAttachment({{ $index }})" class="btn btn-xs p-0 ml-1 text-white">&times;</button>
                    </span>
                @endforeach
                <div wire:loading wire:target="attachments" class="text-primary small">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        @endif

        {{-- INPUT AREA --}}
        <div class="card-footer p-2">
            <div class="input-group">
                {{-- Attachment Buttons --}}
                <div class="input-group-prepend">
                    <label class="btn btn-default mb-0" title="Ảnh" style="cursor: pointer;">
                        <i class="fas fa-image text-success"></i>
                        <input type="file" wire:model="attachments" multiple accept="image/*" class="d-none">
                    </label>
                    <label class="btn btn-default mb-0" title="Video" style="cursor: pointer;">
                        <i class="fas fa-video text-danger"></i>
                        <input type="file" wire:model="attachments" multiple accept="video/*" class="d-none">
                    </label>
                    <label class="btn btn-default mb-0" title="Tài liệu" style="cursor: pointer;">
                        <i class="fas fa-file-alt text-primary"></i>
                        <input type="file" wire:model="attachments" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.csv" class="d-none">
                    </label>
                </div>

                {{-- Text Input --}}
                <textarea 
                    wire:model.defer="newComment" 
                    id="chat-input"
                    class="form-control" 
                    placeholder="Nhập tin nhắn... (Ctrl+Enter để gửi)"
                    rows="1"
                    style="resize: none;"
                ></textarea>

                {{-- Send Button --}}
                <span class="input-group-append">
                    <button type="button" wire:click="sendComment" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('livewire:initialized', () => {
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    let hasScrolledOnce = false;
    
    // Only scroll to bottom on first load
    function scrollToBottomOnce() {
        if (chatMessages && !hasScrolledOnce) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
            hasScrolledOnce = true;
        }
    }
    
    // Scroll after initial render
    setTimeout(scrollToBottomOnce, 100);
    
    // Scroll to bottom button handler
    window.scrollChatToBottom = function() {
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    };
    
    if (chatInput) {
        chatInput.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                const component = Livewire.find(chatInput.closest('[wire\\:id]').getAttribute('wire:id'));
                if (component) {
                    component.call('sendComment');
                }
            }
        });
        
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
    }
});
</script>
@endpush

@push('css')
<style>
    .direct-chat-messages {
        overflow-y: auto;
        padding: 10px;
    }
    .direct-chat-msg {
        margin-bottom: 15px;
        position: relative;
    }
    .direct-chat-text {
        max-width: 80%;
        word-wrap: break-word;
    }
    .direct-chat-msg.right .direct-chat-text {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        margin-right: 50px;
    }
    .direct-chat-msg:not(.right) .direct-chat-text {
        margin-left: 50px;
    }
    .report-card {
        clear: both;
    }
    .report-card .card {
        border-left: 4px solid #28a745;
    }
    @media (max-width: 576px) {
        .direct-chat-text {
            max-width: 85%;
        }
    }
</style>
@endpush
