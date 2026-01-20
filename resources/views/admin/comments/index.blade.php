@extends('layouts.admin')

@section('title', 'Quản lý Bình luận')
@section('content_header', 'Quản lý Bình luận')

@section('content')
    {{-- Thông báo --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    @endif

    {{-- Thống kê --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Tổng bình luận</p>
                </div>
                <div class="icon"><i class="fas fa-comments"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p>Chờ duyệt</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['approved'] }}</h3>
                    <p>Đã duyệt</p>
                </div>
                <div class="icon"><i class="fas fa-check"></i></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách bình luận</h3>
            <div class="card-tools d-flex align-items-center">
                {{-- Bộ lọc --}}
                <form method="GET" class="form-inline mr-2">
                    <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                        <option value="">-- Trạng thái --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>Spam</option>
                    </select>
                    <select name="type" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Loại nội dung --</option>
                        <option value="post" {{ request('type') == 'post' ? 'selected' : '' }}>Bài viết</option>
                        <option value="field" {{ request('type') == 'field' ? 'selected' : '' }}>Lĩnh vực</option>
                        <option value="project" {{ request('type') == 'project' ? 'selected' : '' }}>Dự án</option>
                    </select>
                </form>

                {{-- Bulk Actions --}}
                <button type="button" id="btnBulkApprove" class="btn btn-success btn-sm mr-1" style="display: none;" onclick="CommentManager.submitBulk('approve')">
                    <i class="fas fa-check mr-1"></i> Duyệt (<span id="countApprove">0</span>)
                </button>
                <button type="button" id="btnBulkDelete" class="btn btn-danger btn-sm" style="display: none;" onclick="CommentManager.submitBulk('delete')">
                    <i class="fas fa-trash mr-1"></i> Xóa (<span id="countDelete">0</span>)
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 40px" class="text-center">
                            <input type="checkbox" id="checkAll" class="custom-checkbox">
                        </th>
                        <th>Người gửi</th>
                        <th>Nội dung</th>
                        <th>Nội dung được BL</th>
                        <th class="text-center">Đánh giá</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Ngày</th>
                        <th style="width: 100px" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="custom-checkbox check-item" value="{{ $comment->id }}">
                        </td>
                        <td>
                            <strong>{{ $comment->author_name }}</strong>
                            @if($comment->author_email)
                                <br><small class="text-muted">{{ $comment->author_email }}</small>
                            @endif
                        </td>
                        <td style="max-width: 300px;">
                            <div class="text-truncate" title="{{ $comment->content }}">
                                {{ Str::limit($comment->content, 100) }}
                            </div>
                        </td>
                        <td>
                            @if($comment->commentable)
                                <a href="#" class="text-primary">
                                    {{ Str::limit($comment->commentable->title ?? $comment->commentable->name ?? 'N/A', 30) }}
                                </a>
                                <br><small class="text-muted">{{ class_basename($comment->commentable_type) }}</small>
                            @else
                                <span class="text-muted">Đã xóa</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($comment->rating)
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $comment->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @switch($comment->status)
                                @case('pending')
                                    <span class="badge badge-warning">Chờ duyệt</span>
                                    @break
                                @case('approved')
                                    <span class="badge badge-success">Đã duyệt</span>
                                    @break
                                @case('spam')
                                    <span class="badge badge-danger">Spam</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-center text-muted small">
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                @if($comment->status !== 'approved')
                                    <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Duyệt">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Xác nhận xóa bình luận này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-comments fa-2x mb-2 opacity-50"></i><br>
                            Chưa có bình luận nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($comments->hasPages())
        <div class="card-footer">
            {{ $comments->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    {{-- Form ẩn cho Bulk Action --}}
    <form id="bulkForm" action="{{ route('admin.comments.bulk-action') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <div id="bulkIds"></div>
    </form>
@endsection

@push('js')
<script>
    const CommentManager = {
        init() {
            this.bindEvents();
        },

        bindEvents() {
            $('#checkAll').on('change', (e) => {
                $('.check-item').prop('checked', e.target.checked);
                this.toggleBulkButtons();
            });

            $('.check-item').on('change', () => {
                const allChecked = $('.check-item').length === $('.check-item:checked').length;
                $('#checkAll').prop('checked', allChecked);
                this.toggleBulkButtons();
            });
        },

        toggleBulkButtons() {
            const count = $('.check-item:checked').length;
            if (count > 0) {
                $('#countApprove, #countDelete').text(count);
                $('#btnBulkApprove, #btnBulkDelete').show();
            } else {
                $('#btnBulkApprove, #btnBulkDelete').hide();
            }
        },

        submitBulk(action) {
            const ids = [];
            $('.check-item:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length === 0) return;

            const actionText = action === 'approve' ? 'duyệt' : 'xóa';
            if (!confirm(`Xác nhận ${actionText} ${ids.length} bình luận?`)) return;

            $('#bulkAction').val(action);
            $('#bulkIds').empty();
            ids.forEach(id => {
                $('#bulkIds').append(`<input type="hidden" name="ids[]" value="${id}">`);
            });
            $('#bulkForm').submit();
        }
    };

    $(document).ready(() => CommentManager.init());
</script>
@endpush
