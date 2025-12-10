<?php

namespace App\Observers;

use App\Models\WorkOrder;
use App\Models\ActivityLog;
use App\Enums\WorkOrderStatus;

class WorkOrderObserver
{
    /**
     * Khi tạo WorkOrder mới
     */
    public function created(WorkOrder $workOrder): void
    {
        ActivityLog::log(
            $workOrder->id,
            'created',
            'Tạo phiếu việc mới',
            [
                'title' => $workOrder->title,
                'customer' => $workOrder->customer?->name,
            ]
        );
    }

    /**
     * Khi cập nhật WorkOrder
     */
    public function updated(WorkOrder $workOrder): void
    {
        // Thay đổi trạng thái
        if ($workOrder->isDirty('status')) {
            $oldStatus = $workOrder->getOriginal('status');
            $newStatus = $workOrder->status;
            
            $oldLabel = $oldStatus instanceof WorkOrderStatus ? $oldStatus->label() : $oldStatus;
            $newLabel = $newStatus instanceof WorkOrderStatus ? $newStatus->label() : $newStatus;

            $type = match($newStatus) {
                WorkOrderStatus::COMPLETED => 'approved',
                WorkOrderStatus::CANCELLED => 'cancelled',
                WorkOrderStatus::PROCESSING => $oldStatus === WorkOrderStatus::COMPLETED ? 'reopened' : 'status_changed',
                default => 'status_changed',
            };

            ActivityLog::log(
                $workOrder->id,
                $type,
                "Đổi trạng thái: {$oldLabel} → {$newLabel}",
                [
                    'old_status' => $oldStatus instanceof WorkOrderStatus ? $oldStatus->value : $oldStatus,
                    'new_status' => $newStatus instanceof WorkOrderStatus ? $newStatus->value : $newStatus,
                ]
            );
        }

        // Thay đổi deadline
        if ($workOrder->isDirty('deadline')) {
            $oldDeadline = $workOrder->getOriginal('deadline');
            $newDeadline = $workOrder->deadline;
            
            ActivityLog::log(
                $workOrder->id,
                'deadline_changed',
                'Thay đổi hạn hoàn thành: ' . ($newDeadline ? $newDeadline->format('d/m/Y H:i') : 'Không có'),
                [
                    'old_deadline' => $oldDeadline?->format('Y-m-d H:i:s'),
                    'new_deadline' => $newDeadline?->format('Y-m-d H:i:s'),
                ]
            );
        }

        // Thay đổi priority
        if ($workOrder->isDirty('priority')) {
            $oldPriority = $workOrder->getOriginal('priority');
            $newPriority = $workOrder->priority;
            
            ActivityLog::log(
                $workOrder->id,
                'priority_changed',
                'Đổi độ ưu tiên: ' . ($newPriority?->label() ?? 'N/A'),
                [
                    'old_priority' => $oldPriority?->value ?? $oldPriority,
                    'new_priority' => $newPriority?->value ?? $newPriority,
                ]
            );
        }
    }

    /**
     * Khi xóa WorkOrder
     */
    public function deleted(WorkOrder $workOrder): void
    {
        // Không log vì activity_logs sẽ bị cascade delete
    }
}
