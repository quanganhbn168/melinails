<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class CommentService
{
    /**
     * Tạo comment/review mới
     * 
     * @param Model $commentable Model được comment (Post, Field, Project...)
     * @param array $data Dữ liệu comment
     * @return Comment
     */
    public function store(Model $commentable, array $data): Comment
    {
        return $commentable->comments()->create([
            'parent_id'    => $data['parent_id'] ?? null,
            'author_name'  => $data['author_name'],
            'author_email' => $data['author_email'] ?? null,
            'content'      => $data['content'],
            'rating'       => $data['rating'] ?? null,
            'status'       => 'pending', // Luôn chờ duyệt
        ]);
    }

    /**
     * Duyệt comment
     */
    public function approve(int $id): bool
    {
        $comment = Comment::findOrFail($id);
        $comment->status = 'approved';
        return $comment->save();
    }

    /**
     * Đánh dấu spam
     */
    public function markAsSpam(int $id): bool
    {
        $comment = Comment::findOrFail($id);
        $comment->status = 'spam';
        return $comment->save();
    }

    /**
     * Xóa comment (và tất cả reply của nó do cascade)
     */
    public function delete(int $id): bool
    {
        $comment = Comment::findOrFail($id);
        return $comment->delete();
    }

    /**
     * Xóa nhiều comment
     */
    public function bulkDelete(array $ids): int
    {
        return Comment::whereIn('id', $ids)->delete();
    }

    /**
     * Duyệt nhiều comment
     */
    public function bulkApprove(array $ids): int
    {
        return Comment::whereIn('id', $ids)->update(['status' => 'approved']);
    }
}
