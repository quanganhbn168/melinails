<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $avatar; // New upload
    public $currentAvatarUrl;

    // Password Change
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    // Settings (Flags)
    public $notify_new_job = true;
    public $notify_due_job = true;
    public $notify_warranty = true;
    public $notify_sos = true;
    public $pref_sort_job = 'priority';
    public $pref_default_tab = 'current';

    // Stats
    public $stats = [];

    public function mount()
    {
        $user = Auth::guard('admin')->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->currentAvatarUrl = $user->avatar_url;

        // Calculate Stats
        $this->stats = [
            'completed_7' => $user->performedTasks()
                ->where('status', \App\Enums\TaskStatus::COMPLETED)
                ->where('updated_at', '>=', now()->subDays(7))
                ->count(),
            'completed_30' => $user->performedTasks()
                ->where('status', \App\Enums\TaskStatus::COMPLETED)
                ->where('updated_at', '>=', now()->subDays(30))
                ->count(),
            'warranty_handled' => 0, // Placeholder
        ];
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . Auth::guard('admin')->id(),
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $user = Auth::guard('admin')->user();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->save();

        if ($this->avatar) {
            $path = $this->avatar->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
            
            $this->currentAvatarUrl = asset('storage/' . $path);
            $this->reset('avatar');
        }

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Cập nhật thông tin thành công!']);
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password:admin',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::guard('admin')->user();
        $user->password = bcrypt($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Đổi mật khẩu thành công!']);
    }

    public function saveSettings()
    {
        // Mock save - just show success message
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Đã lưu cài đặt (Demo)!']);
    }

    public function render()
    {
        return view('livewire.admin.user-profile')->layout(auth('admin')->user()->layout ?? 'layouts.admin');
    }
    
    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
