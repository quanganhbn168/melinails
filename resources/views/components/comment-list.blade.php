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
