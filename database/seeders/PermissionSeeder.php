<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cache quyền
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Lấy cấu hình từ file config
        $modules = config('system_permissions.modules', []);
        $actions = config('system_permissions.actions', []);
        $defaultRoles = config('system_permissions.default_roles', []);
        
        if (empty($modules)) {
            $this->command->warn("Chưa có config modules trong system_permissions.php");
            return;
        }
        
        $guard = 'admin';

        // 3. Tạo tất cả Permissions
        $allPermissions = [];
        foreach ($modules as $moduleKey => $moduleName) {
            foreach ($actions as $actionKey => $actionName) {
                $permissionName = "{$actionKey}_{$moduleKey}";
                Permission::firstOrCreate([
                    'name' => $permissionName, 
                    'guard_name' => $guard
                ]);
                $allPermissions[] = $permissionName;
            }
        }

        $this->command->info("📋 Đã tạo " . count($allPermissions) . " permissions.");

        // 4. Tạo Roles từ config
        foreach ($defaultRoles as $roleKey => $roleConfig) {
            $role = Role::firstOrCreate([
                'name' => $roleKey, 
                'guard_name' => $guard
            ]);

            // Super Admin không cần gán permissions (Gate::before bypass)
            if ($roleKey === 'super_admin') {
                continue;
            }

            // Xử lý permissions
            $permissions = $roleConfig['permissions'];
            
            if ($permissions === '*') {
                // Gán tất cả trừ except
                $except = $roleConfig['except'] ?? [];
                $permissionsToGive = array_diff($allPermissions, $except);
            } else {
                // Gán theo danh sách cụ thể
                $permissionsToGive = $permissions;
            }

            // Chỉ gán những permissions đã tồn tại trong DB
            $validPermissions = Permission::whereIn('name', $permissionsToGive)
                ->where('guard_name', $guard)
                ->pluck('name')
                ->toArray();

            $role->syncPermissions($validPermissions);

            $this->command->info("👤 Role [{$roleKey}]: " . count($validPermissions) . " permissions");
        }

        // 5. Đảm bảo có Super Admin account (ID = 1)
        $admin = Admin::find(1);
        if (!$admin) {
            $admin = Admin::create([
                'name'     => 'Super Admin',
                'email'    => 'admin@cnet.vn',
                'password' => Hash::make('password'),
                'status'   => 1,
            ]);
            $this->command->warn("🔑 Đã tạo Super Admin: admin@cnet.vn / password");
        }

        // Gán role super_admin nếu chưa có
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
        
        $this->command->info("✅ Hoàn tất! Super Admin ID = {$admin->id}");
    }
}