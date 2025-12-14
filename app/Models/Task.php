<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 
        'parent_task_id',      // Task cha (nếu được spawn từ task khác)
        'title',
        'description',         // Mô tả chi tiết công việc
        'performer_id', 
        'report_content', 
        'collected_amount', 
        'is_paid', 
        'status',
        'is_additional',
        'created_by_worker_id', 
        'customer_signature',
        'scheduled_at',        // Ngày hẹn thực hiện
    ];

    protected $casts = [
        'status' => \App\Enums\TaskStatus::class,
        'is_additional' => 'boolean',
        'scheduled_at' => 'datetime',
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

    // 4. Task cha (nếu được spawn từ task khác)
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    // 5. Các task con (được spawn từ task này)
    public function childTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    // 6. Lấy tất cả vật tư của Task này (thông qua các báo cáo)
    public function allItems()
    {
        return $this->hasManyThrough(TaskItem::class, TaskReport::class);
    }

    // 7. Người tạo task phát sinh
    public function createdByWorker(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_worker_id');
    }

    // 8. Lấy tất cả thiết bị thu hồi của Task này
    public function allReturnedItems()
    {
        return $this->hasManyThrough(ReturnedItem::class, TaskReport::class);
    }

    // --- HELPER METHODS ---

    public function isAdditional(): bool
    {
        return $this->is_additional === true;
    }

    public function isSpawned(): bool
    {
        return $this->parent_task_id !== null;
    }

    // 9. Người được @mention trong task (@QUAN TÂM)
    public function watchers()
    {
        return $this->belongsToMany(Admin::class, 'task_watchers')
            ->withTimestamps();
    }

    public function hasWatchers(): bool
    {
        return $this->watchers()->count() > 0;
    }

    // 10. Nhiều người thực hiện task (performers)
    public function performers()
    {
        return $this->belongsToMany(Admin::class, 'task_performers')
            ->withTimestamps();
    }

    public function hasPerformers(): bool
    {
        return $this->performers()->count() > 0;
    }

    // Helper: Lấy danh sách tên performers
    public function getPerformerNamesAttribute(): string
    {
        if ($this->hasPerformers()) {
            return $this->performers->pluck('name')->implode(', ');
        }
        return $this->performer?->name ?? 'Chưa gán';
    }
}