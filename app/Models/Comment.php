<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'parent_id',
        'author_name',
        'author_email',
        'content',
        'rating',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Quan hệ đa hình với model cha (Post, Field, Project...)
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Comment cha (nếu là reply)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Các reply của comment này
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    // ==================== SCOPES ====================

    /**
     * Chỉ lấy comment đã duyệt
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Chỉ lấy comment chờ duyệt
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Chỉ lấy comment gốc (không phải reply)
     */
    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Chỉ lấy comment có đánh giá sao
     */
    public function scopeWithRating(Builder $query): Builder
    {
        return $query->whereNotNull('rating');
    }

    // ==================== HELPERS ====================

    /**
     * Check xem comment này có rating không
     */
    public function hasRating(): bool
    {
        return $this->rating !== null;
    }
}
