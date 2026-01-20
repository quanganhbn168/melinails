<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    /**
     * Danh sách tất cả bình luận
     */
    public function index(Request $request)
    {
        $query = Comment::with('commentable')
            ->orderByDesc('created_at');

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo loại model
        if ($request->filled('type')) {
            $typeMap = [
                'post'    => 'App\Models\Post',
                'field'   => 'App\Models\Field',
                'project' => 'App\Models\Project',
            ];
            if (isset($typeMap[$request->type])) {
                $query->where('commentable_type', $typeMap[$request->type]);
            }
        }

        $comments = $query->paginate(20);

        // Thống kê
        $stats = [
            'total'    => Comment::count(),
            'pending'  => Comment::pending()->count(),
            'approved' => Comment::approved()->count(),
        ];

        return view('admin.comments.index', compact('comments', 'stats'));
    }

    /**
     * Duyệt bình luận
     */
    public function approve($id)
    {
        $this->commentService->approve($id);
        return back()->with('success', 'Đã duyệt bình luận.');
    }

    /**
     * Xóa bình luận
     */
    public function destroy($id)
    {
        $this->commentService->delete($id);
        return back()->with('success', 'Đã xóa bình luận.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:comments,id',
            'action' => 'required|in:approve,delete',
        ]);

        $ids = $request->input('ids');
        $action = $request->input('action');

        if ($action === 'approve') {
            $this->commentService->bulkApprove($ids);
            $message = 'Đã duyệt ' . count($ids) . ' bình luận.';
        } else {
            $this->commentService->bulkDelete($ids);
            $message = 'Đã xóa ' . count($ids) . ' bình luận.';
        }

        return back()->with('success', $message);
    }
}
