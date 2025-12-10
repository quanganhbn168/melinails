<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\WorkOrderAttachment;
use App\Models\Task;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WorkOrderAssignedNotification;

class WorkOrderService
{
    /**
     * Tạo Work Order mới
     */
    public function create(array $data): WorkOrder
    {
        return DB::transaction(function () use ($data) {
            // 1. Tạo Work Order
            $workOrder = WorkOrder::create([
                'customer_id' => $data['customer_id'],
                'created_by' => auth('admin')->id(),
                'code' => 'WO-' . strtoupper(Str::random(6)),
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => 'pending',
                'priority' => $data['priority'],
                'started_at' => $data['started_at'] ?? now(),
                'deadline' => $data['deadline'] ?? null,
                'site_address' => $data['site_address'],
                'contact_person' => $data['contact_person'],
                'contact_phone' => $data['contact_phone'],
            ]);

            // 2. Gán nhân viên
            if (!empty($data['assignee_ids'])) {
                $workOrder->assignees()->attach($data['assignee_ids']);
            }

            // 3. Tạo các Task con
            if (!empty($data['tasks'])) {
                $mainPerformer = $data['assignee_ids'][0] ?? auth('admin')->id();
                foreach ($data['tasks'] as $taskContent) {
                    if (!empty($taskContent)) {
                        Task::create([
                            'work_order_id' => $workOrder->id,
                            'performer_id' => $mainPerformer,
                            'report_content' => $taskContent,
                            'collected_amount' => 0,
                            'is_paid' => false,
                        ]);
                    }
                }
            }

            // 4. Gửi thông báo cho nhân viên
            if (!empty($data['assignee_ids'])) {
                $assignees = Admin::whereIn('id', $data['assignee_ids'])->get();
                Notification::send($assignees, new WorkOrderAssignedNotification($workOrder));
            }

            return $workOrder;
        });
    }

    /**
     * Lưu file đính kèm
     */
    public function storeAttachments(WorkOrder $workOrder, array $files, string $type = 'image'): void
    {
        foreach ($files as $file) {
            $path = $file->store("work-orders/{$workOrder->id}", 'public');
            
            WorkOrderAttachment::create([
                'work_order_id' => $workOrder->id,
                'type' => $type,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'uploaded_by' => auth('admin')->id(),
            ]);
        }
    }

    /**
     * Tính deadline từ số ngày
     */
    public function calculateDeadline($startedAt, int $days): \Carbon\Carbon
    {
        $start = $startedAt instanceof \Carbon\Carbon 
            ? $startedAt 
            : \Carbon\Carbon::parse($startedAt);
        
        return $start->copy()->addDays($days);
    }

    /**
     * Tính số ngày giữa 2 thời điểm
     */
    public function calculateDaysDiff($startedAt, $deadline): int
    {
        $start = $startedAt instanceof \Carbon\Carbon 
            ? $startedAt 
            : \Carbon\Carbon::parse($startedAt);
        
        $end = $deadline instanceof \Carbon\Carbon 
            ? $deadline 
            : \Carbon\Carbon::parse($deadline);
        
        return (int) $start->diffInDays($end);
    }
}
