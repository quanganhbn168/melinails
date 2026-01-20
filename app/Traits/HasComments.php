<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    /**
     * Lấy tất cả bình luận của model này
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderByDesc('created_at');
    }

    /**
     * Lấy bình luận đã duyệt (gốc, không phải reply)
     */
    public function approvedComments(): MorphMany
    {
        return $this->comments()->approved()->roots()->with('replies', function ($query) {
            $query->approved()->orderBy('created_at');
        });
    }

    /**
     * Lấy bình luận có đánh giá (reviews)
     */
    public function reviews(): MorphMany
    {
        return $this->comments()->approved()->withRating();
    }

    /**
     * Tính rating trung bình
     */
    public function averageRating(): ?float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    /**
     * Đếm số lượng đánh giá
     */
    public function reviewCount(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Lấy dữ liệu cho JSON-LD aggregateRating
     */
    public function getAggregateRatingData(): ?array
    {
        $count = $this->reviewCount();
        if ($count === 0) {
            return null;
        }

        return [
            '@type' => 'AggregateRating',
            'ratingValue' => $this->averageRating(),
            'reviewCount' => $count,
            'bestRating' => 5,
            'worstRating' => 1,
        ];
    }
}
