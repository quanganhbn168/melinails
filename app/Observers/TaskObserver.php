<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\ActivityLog;
use App\Enums\TaskStatus;

class TaskObserver
{
    /**
     * Khi tạo Task mới
     */
    public function created(Task $task): void
    {
        $description = $task->is_additional 
            ? "Tạo task phát sinh: {$task->name}"
            : "Tạo task: {$task->name}";

        ActivityLog::log(
            $task->work_order_id,
            'task_created',
            $description,
            [
                'task_id' => $task->id,
                'task_name' => $task->name,
                'is_additional' => $task->is_additional,
                'performer' => $task->performer?->name,
            ]
        );
    }

    /**
     * Khi cập nhật Task
     */
    public function updated(Task $task): void
    {
        // Task hoàn thành
        if ($task->isDirty('status') && $task->status === TaskStatus::COMPLETED) {
            ActivityLog::log(
                $task->work_order_id,
                'task_completed',
                "Hoàn thành task: {$task->name}",
                [
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                    'performer' => $task->performer?->name,
                ]
            );
        }

        // Gán performer mới
        if ($task->isDirty('performer_id')) {
            $oldPerformer = $task->getOriginal('performer_id');
            $newPerformer = $task->performer_id;
            
            if ($newPerformer && !$oldPerformer) {
                ActivityLog::log(
                    $task->work_order_id,
                    'assigned',
                    "Gán task '{$task->name}' cho {$task->performer?->name}",
                    [
                        'task_id' => $task->id,
                        'performer_id' => $newPerformer,
                        'performer_name' => $task->performer?->name,
                    ]
                );
            }
        }
    }
}
