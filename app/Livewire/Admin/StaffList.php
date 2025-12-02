<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class StaffList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    #[Layout('layouts.admin')]
    public function render()
    {
        $query = Admin::query()->with('roles');

        // Tìm kiếm theo tên, email hoặc số điện thoại
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Không cho phép xóa chính mình (để tránh lỗi)
        $staffs = $query->orderByDesc('id')->paginate(10);

        return view('livewire.admin.staff-list', [
            'staffs' => $staffs
        ]);
    }

    public function delete($id)
    {
        if ($id == Auth::guard('admin')->id()) {
            $this->dispatch('notify', type: 'error', message: 'Bạn không thể xóa chính mình!');
            return;
        }

        $admin = Admin::find($id);
        if ($admin) {
            // Xóa cả quyền trước khi xóa user (tùy chọn, thường Laravel tự xử lý cascade)
            $admin->roles()->detach();
            $admin->delete();
            $this->dispatch('notify', type: 'success', message: 'Đã xóa nhân viên thành công.');
        }
    }
    
    // Toggle trạng thái Active/Block nhanh
    public function toggleStatus($id)
    {
        if ($id == Auth::guard('admin')->id()) return;
        
        $admin = Admin::find($id);
        if ($admin) {
            $admin->status = !$admin->status;
            $admin->save();
            $this->dispatch('notify', type: 'success', message: 'Đã cập nhật trạng thái.');
        }
    }
}