<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class RoleManager extends Component
{
    // Cấu hình hiển thị Matrix - ĐẦY ĐỦ CÁC MODULE
    public $modules = [
        // CORE
        'dashboard'    => 'Bảng điều khiển',
        
        // CÔNG VIỆC & CRM
        'customers'    => 'Khách hàng (CRM)',
        'work_orders'  => 'Phiếu việc (Job)',
        'tasks'        => 'Task/Công việc',
        'materials'    => 'Vật tư / Kho',
        'warranty'     => 'Bảo hành',
        'finance'      => 'Tài chính / Thu tiền',
        
        // SẢN PHẨM & NỘI DUNG
        'products'     => 'Sản phẩm',
        'categories'   => 'Danh mục SP',
        'posts'        => 'Bài viết',
        'media'        => 'Thư viện Media',
        'slides'       => 'Slide & Banner',
        'pages'        => 'Trang tĩnh',
        
        // MỞ RỘNG
        'projects'     => 'Dự án',
        'agents'       => 'Đại lý',
        'careers'      => 'Tuyển dụng',
        
        // HỆ THỐNG
        'staff'        => 'Nhân viên',
        'roles'        => 'Phân quyền',
        'settings'     => 'Cấu hình hệ thống',
        'tags'         => 'Quản lý Tags',
    ];

    public $actions = [
        'view'    => 'Xem / Truy cập',
        'create'  => 'Thêm mới',
        'update'  => 'Chỉnh sửa',
        'delete'  => 'Xóa bỏ',
        'approve' => 'Duyệt / Phê duyệt',
        'export'  => 'Xuất báo cáo',
    ];

    // Form Variables
    public $isEditing = false;
    public $roleId = null;
    public $name = '';
    public $selectedPermissions = []; 

    public function mount() { }

    public function resetForm()
    {
        $this->name = '';
        $this->selectedPermissions = [];
        $this->roleId = null;
        $this->isEditing = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-role-modal');
    }

    public function edit($id)
    {
        $role = Role::findById($id);
        if ($role->name == 'super_admin') {
            $this->dispatch('notify', type: 'error', message: 'Super Admin đã có full quyền.');
            return;
        }

        $this->roleId = $role->id;
        $this->name = $role->name;
        // Lấy permission name (dạng chuỗi)
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
        $this->dispatch('open-role-modal');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
        ]);

        if ($this->isEditing) {
            $role = Role::findById($this->roleId);
            if (!in_array($role->name, ['super_admin', 'staff'])) {
                $role->update(['name' => Str::slug($this->name, '_')]);
            }
        } else {
            $role = Role::create(['name' => Str::slug($this->name, '_'), 'guard_name' => 'admin']);
        }

        // Logic lưu thủ công (Reset & Add)
        DB::table('role_has_permissions')->where('role_id', $role->id)->delete();

        $permissionsToGive = array_filter($this->selectedPermissions, fn($p) => is_string($p));
        
        if (!empty($permissionsToGive)) {
            $perms = Permission::whereIn('name', $permissionsToGive)->where('guard_name', 'admin')->get();
            if ($perms->isNotEmpty()) $role->givePermissionTo($perms);
        }

        $this->dispatch('close-role-modal');
        $this->dispatch('notify', type: 'success', message: 'Đã lưu quyền thành công!');
    }

    public function delete($id)
    {
        $role = Role::findById($id);
        
        if (in_array($role->name, ['super_admin', 'staff'])) {
            $this->dispatch('notify', type: 'error', message: 'Không thể xóa vai trò mặc định.');
            return;
        }

        $role->delete();
        $this->dispatch('notify', type: 'success', message: 'Đã xóa vai trò.');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $roles = Role::where('guard_name', 'admin')->with('permissions')->get();
        return view('livewire.admin.role-manager', ['roles' => $roles]);
    }
}