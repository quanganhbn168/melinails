{{-- 
    Component: Comment Form
    Props:
        - $commentable: Model instance (Post, Field, Project)
        - $type: 'post', 'field', 'project'
        - $showRating: true/false (default: true)
--}}
@props([
    'commentable',
    'type',
    'showRating' => true,
])

<div class="comment-form-wrapper mt-5 pt-4 border-top">
    <h4 class="mb-4"><i class="fas fa-comment-dots me-2"></i> Để lại bình luận</h4>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('comments.store') }}" method="POST" class="comment-form">
        @csrf
        <input type="hidden" name="commentable_type" value="{{ $type }}">
        <input type="hidden" name="commentable_id" value="{{ $commentable->id }}">
        
        <div class="row g-3">
            <div class="col-md-6">
                <label for="author_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('author_name') is-invalid @enderror" 
                       id="author_name" name="author_name" value="{{ old('author_name') }}" required>
                @error('author_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="author_email" class="form-label">Email</label>
                <input type="email" class="form-control @error('author_email') is-invalid @enderror" 
                       id="author_email" name="author_email" value="{{ old('author_email') }}" placeholder="(Không bắt buộc)">
                @error('author_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            @if($showRating)
            <div class="col-12">
                <label class="form-label">Đánh giá của bạn</label>
                <div class="star-rating d-flex gap-1">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="d-none" {{ old('rating', 5) == $i ? 'checked' : '' }}>
                        <label for="star{{ $i }}" class="star-label" title="{{ $i }} sao">
                            <i class="far fa-star"></i>
                        </label>
                    @endfor
                </div>
            </div>
            @endif
            
            <div class="col-12">
                <label for="content" class="form-label">Nội dung bình luận <span class="text-danger">*</span></label>
                <textarea class="form-control @error('content') is-invalid @enderror" 
                          id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Gửi bình luận
                </button>
            </div>
        </div>
    </form>
</div>

@pushOnce('css')
<style>
    /* Comment Form Container */
    .comment-form-wrapper {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }
    .comment-form-wrapper h4 {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    /* Star Rating */
    .star-rating {
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .star-label {
        font-size: 1.75rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s, transform 0.1s;
        padding: 2px;
    }
    .star-label:hover {
        transform: scale(1.1);
    }
    .star-label:hover,
    .star-label:hover ~ .star-label,
    input:checked ~ .star-label {
        color: #ffc107;
    }
    input:checked ~ .star-label i,
    .star-label:hover i,
    .star-label:hover ~ .star-label i {
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
    }
    input:checked ~ .star-label i::before,
    .star-label:hover i::before,
    .star-label:hover ~ .star-label i::before {
        content: "\f005";
    }
    
    /* Mobile Responsive */
    @media (max-width: 576px) {
        .comment-form-wrapper {
            padding: 16px;
            margin-left: -15px;
            margin-right: -15px;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
        .star-label {
            font-size: 2rem;
            padding: 4px;
        }
        .comment-form .btn {
            width: 100%;
        }
    }
</style>
@endPushOnce
