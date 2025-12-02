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
    // Cấu hình hiển thị Matrix
    public $modules = [
        'dashboard'   => 'Bảng điều khiển',
        'staff'       => 'Nhân sự',
        'customers'   => 'Khách hàng',
        'work_orders' => 'Phiếu việc (Job)',
        'audit'       => 'Duyệt doanh thu',
        'roles'       => 'Phân quyền',
        'settings'    => 'Cấu hình',
    ];

    public $actions = [
        'view'   => 'Xem / Truy cập',
        'create' => 'Thêm mới',
        'update' => 'Chỉnh sửa',
        'delete' => 'Xóa bỏ'
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