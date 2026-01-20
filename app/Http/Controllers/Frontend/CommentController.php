<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    /**
     * Xử lý form gửi bình luận/đánh giá
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string|in:post,field,project',
            'commentable_id'   => 'required|integer',
            'parent_id'        => 'nullable|integer|exists:comments,id',
            'author_name'      => 'required|string|max:100',
            'author_email'     => 'nullable|email|max:255',
            'content'          => 'required|string|max:2000',
            'rating'           => 'nullable|integer|min:1|max:5',
        ]);

        // Map type thành Model class
        $modelMap = [
            'post'    => \App\Models\Post::class,
            'field'   => \App\Models\Field::class,
            'project' => \App\Models\Project::class,
        ];

        $modelClass = $modelMap[$validated['commentable_type']];
        $commentable = $modelClass::findOrFail($validated['commentable_id']);

        $this->commentService->store($commentable, $validated);

        return back()->with('success', 'Cảm ơn bạn đã gửi bình luận! Nội dung sẽ được hiển thị sau khi duyệt.');
    }
}
