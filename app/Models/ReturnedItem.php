<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedItem extends Model
{
    protected $fillable = [
        'task_report_id',
        'item_name',
        'serial_number',
        'reason',
        'condition_note',
    ];

    /**
     * Lý do thu hồi với label tiếng Việt
     */
    public function getReasonLabelAttribute(): string
    {
        return match($this->reason) {
            'warranty' => 'Bảo hành',
            'replace' => 'Đổi model',
            'defective' => 'Lỗi nhà SX',
            'upgrade' => 'Nâng cấp',
            default => $this->reason ?? 'Khác',
        };
    }

    public function report()
    {
        return $this->belongsTo(TaskReport::class, 'task_report_id');
    }
}
