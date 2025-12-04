<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    // QUAN TRỌNG: Phải thêm 'status' vào đây để code update trạng thái hoạt động
    protected $fillable = [
        'work_order_id', 
        'performer_id', 
        'report_content', 
        'collected_amount', 
        'is_paid', 
        'status',
        'customer_signature' // Nếu có dùng ở bảng task cũ
    ];

    protected $casts = [
        'status' => \App\Enums\TaskStatus::class,
    ];

    // --- RELATIONSHIPS ---

    // 1. Quan hệ với WorkOrder cha
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    // 2. Quan hệ với Người thực hiện
    public function performer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'performer_id');
    }

    // 3. Quan hệ với Báo cáo (Cốt lõi mới)
    public function reports(): HasMany
    {
        return $this->hasMany(TaskReport::class)->latest(); 
    }

    // --- BỎ HÀM items() cũ vì bảng items đã xóa cột task_id ---
    // Nếu muốn lấy tất cả vật tư của Task này (thông qua các báo cáo), dùng HasManyThrough:
    public function allItems()
    {
        return $this->hasManyThrough(TaskItem::class, TaskReport::class);
    }
}