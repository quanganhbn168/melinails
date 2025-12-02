<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Dọn dẹp
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Định nghĩa các Module (Tài nguyên) trong hệ thống
        // Cấu trúc: 'tên_trong_db' => 'Tên hiển thị tiếng Việt'
        $resources = [
            'dashboard'   => 'Bảng điều khiển',
            'staff'       => 'Nhân sự (Staff)',
            'customers'   => 'Khách hàng',
            'work_orders' => 'Phiếu việc (Job)',
            'roles'       => 'Phân quyền',
            'settings'    => 'Cấu hình hệ thống',
            'audit'       => 'Duyệt doanh thu',
        ];

        // 3. Định nghĩa các Hành động chuẩn
        $actions = ['view', 'create', 'update', 'delete'];

        // 4. Sinh Permission tự động
        foreach ($resources as $key => $label) {
            foreach ($actions as $action) {
                // Tạo permission: view_customers, create_customers...
                Permission::create([
                    'name' => "{$action}_{$key}", 
                    'guard_name' => 'admin'
                ]);
            }
        }

        // 5. Cấp quyền mẫu
        
        // Super Admin (Giữ nguyên không đổi)
        
        // Role Kỹ thuật viên (Chỉ xem và tạo Job, xem khách)
        $roleStaff = Role::where('name', 'staff')->first() ?? Role::create(['name' => 'staff', 'guard_name' => 'admin']);
        $roleStaff->givePermissionTo([
            'view_work_orders', 'create_work_orders', 
            'view_customers', 'create_customers'
        ]);

        echo "Done! Đã tạo hệ thống quyền chuẩn Matrix (Action_Resource).\n";
    }
}