<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

class StaffForm extends Component
{
    public $staff_id;
    public $name;
    public $phone;
    public $email;
    public $password; // Mật khẩu (chỉ dùng để nhập liệu)
    public $role = 'staff'; // Mặc định là Thợ
    public $status = true;
    protected $roleLabels = [
        'super_admin' => 'Quản trị viên (Super Admin)',
        'staff'       => 'Kỹ thuật viên (Staff)',
        // Thêm các role khác nếu có
    ];
    public function mount($id = null)
    {
        if ($id) {
            $staff = Admin::findOrFail($id);
            $this->staff_id = $id;
            $this->name = $staff->name;
            $this->phone = $staff->phone;
            $this->email = $staff->email;
            $this->status = (bool) $staff->status;
            // Lấy role hiện tại
            $this->role = $staff->roles->first()->name ?? 'staff';
        }
    }

    public function save()
    {
        // Validate
        $rules = [
            'name' => 'required|min:2',
            'phone' => 'required|numeric|unique:admins,phone,' . $this->staff_id,
            'email' => 'required|email|unique:admins,email,' . $this->staff_id,
            'role' => 'required'
        ];

        // Nếu thêm mới -> bắt buộc pass. Sửa -> không bắt buộc.
        if (!$this->staff_id) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        $this->validate($rules, [
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'phone.numeric' => 'Số điện thoại phải là số.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự.'
        ]);

        // Chuẩn bị dữ liệu
        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
        ];

        // Chỉ hash password nếu có nhập
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        // Lưu
        $staff = Admin::updateOrCreate(['id' => $this->staff_id], $data);

        // Gán quyền (Sync role)
        if ($this->role) {
            $staff->syncRoles([(string)$this->role]);
        }

        session()->flash('success', $this->staff_id ? 'Đã cập nhật thông tin nhân viên.' : 'Đã thêm nhân viên mới.');
        return redirect()->route('admin.staff.index');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $roles = Role::where('guard_name', 'admin')->get();

        return view('livewire.admin.staff-form', [
            'roles' => $roles,
            'roleLabels' => $this->roleLabels // Truyền sang view
        ]);
    }
}