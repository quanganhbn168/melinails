<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'work_order_id',
        'user_id',
        'type',
        'icon',
        'color',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // --- RELATIONSHIPS ---

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    // --- STATIC HELPERS ---

    /**
     * Log một activity
     */
    public static function log(
        int $workOrderId,
        string $type,
        string $description,
        ?array $metadata = null,
        ?string $icon = null,
        ?string $color = null
    ): self {
        return self::create([
            'work_order_id' => $workOrderId,
            'user_id' => auth('admin')->id(),
            'type' => $type,
            'description' => $description,
            'metadata' => $metadata,
            'icon' => $icon ?? self::getDefaultIcon($type),
            'color' => $color ?? self::getDefaultColor($type),
        ]);
    }

    /**
     * Icon mặc định theo type
     */
    public static function getDefaultIcon(string $type): string
    {
        return match($type) {
            'created' => 'fas fa-plus-circle',
            'status_changed' => 'fas fa-exchange-alt',
            'assigned' => 'fas fa-user-plus',
            'unassigned' => 'fas fa-user-minus',
            'task_completed' => 'fas fa-check-circle',
            'task_created' => 'fas fa-tasks',
            'payment' => 'fas fa-money-bill-wave',
            'deadline_changed' => 'fas fa-calendar-alt',
            'priority_changed' => 'fas fa-flag',
            'approved' => 'fas fa-clipboard-check',
            'reopened' => 'fas fa-undo',
            'cancelled' => 'fas fa-ban',
            default => 'fas fa-info-circle',
        };
    }

    /**
     * Màu mặc định theo type
     */
    public static function getDefaultColor(string $type): string
    {
        return match($type) {
            'created' => 'primary',
            'status_changed' => 'info',
            'assigned' => 'success',
            'unassigned' => 'warning',
            'task_completed' => 'success',
            'task_created' => 'primary',
            'payment' => 'success',
            'deadline_changed' => 'warning',
            'priority_changed' => 'warning',
            'approved' => 'success',
            'reopened' => 'warning',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}
