<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderAssignedNotification extends Notification
{
    use Queueable;

    public $workOrder;

    public function __construct($workOrder)
    {
        $this->workOrder = $workOrder;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Công việc mới: ' . $this->workOrder->code,
            'message' => 'Bạn được giao việc: ' . $this->workOrder->title,
            'work_order_id' => $this->workOrder->id,
            'type' => 'assigned_job'
        ];
    }
}
