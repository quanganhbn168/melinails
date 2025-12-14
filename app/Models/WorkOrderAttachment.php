<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderAttachment extends Model
{
    protected $fillable = [
        'work_order_id',
        'task_id',  // Link với task phát sinh
        'type', // 'image' hoặc 'document'
        'file_path',
        'file_name',
        'description',
        'uploaded_by',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    // Helper để lấy URL file
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    // Helper để check loại file
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isDocument(): bool
    {
        return $this->type === 'document';
    }
}
