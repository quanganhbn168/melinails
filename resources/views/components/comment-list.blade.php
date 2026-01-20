{{-- 
    Component: Comment List
    Props:
        - $comments: Collection of approved comments (use $model->approvedComments)
        - $showRating: true/false (default: true)
--}}
@props([
    'comments',
    'showRating' => true,
])



@pushOnce('css')
<style>
    .comment-list-wrapper {
        background-color: #fff;
        border-radius: 8px;
    }
    
    .comment-item:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
    
    /* Avatar */
    .comment-avatar {
        margin-right: 15px;
    }
    .avatar-circle {
        width: 45px; 
        height: 45px; 
        border-radius: 50%;
        background-color: #0d6efd;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.2rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Content */
    .comment-meta {
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .comment-author {
        font-weight: 700;
        color: #333;
        font-size: 1rem;
    }
    
    .comment-date {
        font-size: 0.85rem;
        color: #888;
        display: flex;
        align-items: center;
    }
    
    .comment-text {
        color: #444;
        line-height: 1.5;
        font-size: 0.95rem;
    }
    
    .comment-rating {
        font-size: 0.8rem;
    }
    
    /* Replies */
    .comment-replies {
        margin-top: 15px;
        padding-left: 15px;
        border-left: 2px solid #f0f0f0;
    }
    .reply-item {
        margin-bottom: 15px;
    }
    .reply-avatar .avatar-circle {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
        background-color: #6c757d;
    }
</style>
@endPushOnce

@if($comments->count() > 0)
<div class="comment-list-wrapper mt-4 mb-4 p-3 p-md-4 bg-white rounded border shadow-sm">
    <h4 class="mb-4">
        Bình luận <span class="text-muted" style="font-size: 0.9em">({{ $comments->count() }})</span>
    </h4>
    
    <div class="comment-list">
        @foreach($comments as $comment)
            <div class="comment-item mb-4 pb-4 border-bottom" id="comment-{{ $comment->id }}">
                <div class="d-flex">
                    {{-- Avatar --}}
                    <div class="comment-avatar flex-shrink-0">
                        <div class="avatar-circle">
                            {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <div class="comment-content flex-grow-1">
                        <div class="comment-meta">
                            <span class="comment-author">{{ $comment->author_name }}</span>
                            
                            @if($showRating && $comment->rating)
                                <span class="comment-rating text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $comment->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </span>
                            @endif
                            
                            <span class="text-muted d-none d-sm-inline">•</span>
                            
                            <span class="comment-date">
                                <i class="far fa-clock me-1"></i> {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="comment-text">
                            {!! nl2br(e($comment->content)) !!}
                        </div>
                        
                        {{-- Replies --}}
                        @if($comment->replies->count() > 0)
                            <div class="comment-replies">
                                @foreach($comment->replies as $reply)
                                    <div class="reply-item d-flex">
                                        <div class="comment-avatar reply-avatar flex-shrink-0" style="margin-right: 10px;">
                                            <div class="avatar-circle">
                                                {{ strtoupper(substr($reply->author_name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="reply-content flex-grow-1">
                                            <div class="comment-meta mb-1" style="gap: 8px;">
                                                <strong class="comment-author" style="font-size: 0.9rem">{{ $reply->author_name }}</strong>
                                                <span class="comment-date" style="font-size: 0.8rem">
                                                    {{ $reply->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="comment-text small">{!! nl2br(e($reply->content)) !!}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
