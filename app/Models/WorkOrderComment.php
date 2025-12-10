<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrderComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'admin_id',
        'content',
    ];

    // --- RELATIONSHIPS ---

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CommentAttachment::class);
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(CommentMention::class);
    }

    // --- HELPERS ---

    /**
     * Lấy danh sách Admin được mention trong comment này
     */
    public function getMentionedAdmins()
    {
        return $this->mentions()->with('admin')->get()->pluck('admin');
    }

    /**
     * Parse content và tạo mentions từ @username
     * Format: @[Name](id)
     */
    public function parseMentions(): void
    {
        // Pattern: @[Tên Người Dùng](123)
        preg_match_all('/@\[([^\]]+)\]\((\d+)\)/', $this->content, $matches);

        if (!empty($matches[2])) {
            foreach ($matches[2] as $adminId) {
                // Chỉ tạo mention nếu chưa có
                $this->mentions()->firstOrCreate([
                    'admin_id' => $adminId,
                ]);
            }
        }
    }

    /**
     * Kiểm tra user có thể xóa comment này không
     */
    public function canDelete(Admin $admin): bool
    {
        // Owner hoặc có quyền admin
        return $this->admin_id === $admin->id 
            || $admin->hasRole(['super-admin', 'admin']);
    }
}
