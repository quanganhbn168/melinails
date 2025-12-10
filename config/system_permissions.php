<?php

return [
    // 1. Danh sách các Module cần quản lý (SYNC VỚI RoleManager.php)
    'modules' => [
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
    ],

    // 2. Các hành động
    'actions' => [
        'view'    => 'Xem / Truy cập',
        'create'  => 'Thêm mới',
        'update'  => 'Chỉnh sửa',
        'delete'  => 'Xóa bỏ',
        'approve' => 'Duyệt / Phê duyệt',
        'export'  => 'Xuất báo cáo',
    ],

    // 3. Cấu hình Roles mặc định (dùng cho Seeder)
    'default_roles' => [
        'super_admin' => [
            'label' => 'Super Admin',
            'permissions' => '*', // Bypass tất cả qua Gate::before
        ],
        'admin' => [
            'label' => 'Quản trị viên',
            'permissions' => '*', // Gần như full trừ xóa settings
            'except' => ['delete_settings', 'delete_roles'],
        ],
        'mod' => [
            'label' => 'Điều phối viên',
            'permissions' => [
                'view_dashboard',
                'view_customers', 'create_customers', 'update_customers',
                'view_work_orders', 'create_work_orders', 'update_work_orders', 'approve_work_orders',
                'view_tasks', 'update_tasks',
                'view_materials',
                'view_warranty', 'create_warranty', 'update_warranty',
                'view_finance',
                'view_staff',
                'view_tags', 'create_tags', 'update_tags',
            ],
        ],
        'staff' => [
            'label' => 'Nhân viên thi công',
            'permissions' => [
                'view_dashboard',
                // Không có view_customers - staff không cần xem danh sách khách hàng
                'view_work_orders', 'update_work_orders',
                'view_tasks', 'update_tasks',
                'view_materials',
                'view_warranty',
            ],
        ],
        'sales' => [
            'label' => 'Kinh doanh',
            'permissions' => [
                'view_dashboard',
                'view_customers', 'create_customers', 'update_customers',
                'view_work_orders', 'create_work_orders',
                'view_products',
                'view_finance',
            ],
        ],
        'warehouse' => [
            'label' => 'Quản lý kho',
            'permissions' => [
                'view_dashboard',
                'view_materials', 'create_materials', 'update_materials', 'delete_materials', 'export_materials',
                'view_products',
                'view_work_orders',
            ],
        ],
        'content' => [
            'label' => 'Nhập liệu nội dung',
            'permissions' => [
                'view_dashboard',
                'view_posts', 'create_posts', 'update_posts', 'delete_posts',
                'view_media', 'create_media', 'update_media', 'delete_media',
                'view_slides', 'create_slides', 'update_slides', 'delete_slides',
                'view_pages', 'update_pages',
                'view_categories',
            ],
        ],
        'cs' => [
            'label' => 'Chăm sóc khách hàng',
            'permissions' => [
                'view_dashboard',
                'view_customers', 'update_customers',
                'view_work_orders',
                'view_warranty', 'create_warranty', 'update_warranty',
            ],
        ],
    ],
];