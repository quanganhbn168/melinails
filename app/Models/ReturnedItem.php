<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnedItem extends Model
{
    protected $fillable = [
        'task_report_id',
        'item_name',
        'serial_number',
        'reason',
        'condition_note',
        'supplier_id',
        'status',
        'returned_by',
        'returned_at',
        'notes',
        'sent_to_supplier_by',
        'sent_to_supplier_at',
        'received_from_supplier_at',
        'supplier_result',
        'repair_cost',
    ];

    protected $casts = [
        'status' => \App\Enums\ReturnedItemStatus::class,
        'supplier_result' => \App\Enums\SupplierResult::class,
        'returned_at' => 'datetime',
        'sent_to_supplier_at' => 'datetime',
        'received_from_supplier_at' => 'datetime',
    ];

    // --- RELATIONSHIPS ---

    public function report(): BelongsTo
    {
        return $this->belongsTo(TaskReport::class, 'task_report_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'supplier_id');
    }

    public function returnedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'returned_by');
    }

    public function sentToSupplierBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'sent_to_supplier_by');
    }

    // --- ACCESSORS ---

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
}
