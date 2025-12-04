<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class MobileBottomNav extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->checkNotifications();
    }

    public function checkNotifications()
    {
        $user = auth('admin')->user();
        if ($user) {
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function render()
    {
        return view('livewire.admin.mobile-bottom-nav');
    }
}
