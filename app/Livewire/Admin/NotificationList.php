<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class NotificationList extends Component
{
    use \Livewire\WithPagination;

    public function markAsRead($id)
    {
        $notification = auth('admin')->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        auth('admin')->user()->unreadNotifications->markAsRead();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Đã đánh dấu tất cả là đã đọc.']);
    }

    public function render()
    {
        $notifications = auth('admin')->user()->notifications()->paginate(15);
        return view('livewire.admin.notification-list', [
            'notifications' => $notifications
        ])->layout(auth('admin')->user()->layout ?? 'layouts.admin');
    }
}
