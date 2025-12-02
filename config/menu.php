<?php

return [
    // DASHBOARD
    [
        'title' => 'Dashboard',
        'icon' => 'bi bi-speedometer2',
        'route' => 'admin.dashboard',
        'active_pattern' => 'admin.dashboard',
        'permission' => 'view-dashboard',
    ],

    // ================================================================
    // NHÓM 1: QUẢN LÝ CÔNG VIỆC (JOB & CRM) - Ưu tiên lên trên
    // ================================================================
    ['type' => 'header', 'title' => 'QUẢN LÝ CÔNG VIỆC'],

    [
        'title' => 'Khách hàng (CRM)',
        'icon' => 'bi bi-people-fill', 
        'route' => 'admin.customers.index',
        'active_pattern' => 'admin.customers.*',
        // 'permission' => 'manage-customers', 
    ],
    [
        'title' => 'Phiếu việc (Job)',
        'icon' => 'bi bi-tools', 
        'active_pattern' => ['admin.work-orders.*', 'admin.my-work-orders.*'],
        'submenu' => [
            [
                'title' => 'Danh sách phiếu việc',
                'route' => 'admin.work-orders.index',
                'active_pattern' => 'admin.work-orders.index'
            ],
            [
                'title' => 'Tạo phiếu việc',     
                'route' => 'admin.work-orders.create',   
                'active_pattern' => 'admin.work-orders.create'
            ],
            [
                'title' => 'Việc của tôi',       
                'route' => 'admin.my-work-orders.index', 
                'active_pattern' => 'admin.my-work-orders.*' 
            ],
        ],
    ],
    [
        'title' => 'Duyệt & Doanh thu',
        'icon' => 'bi bi-cash-stack', 
        'route' => 'admin.task-audit.index',
        'active_pattern' => 'admin.task-audit.*',
        // 'permission' => 'audit-tasks',
    ],

    // ================================================================
    // NHÓM 2: SẢN PHẨM & DỊCH VỤ
    // ================================================================
    ['type' => 'header', 'title' => 'SẢN PHẨM & DỊCH VỤ'],

    [
        'title' => 'Quản lý sản phẩm',
        'icon' => 'bi bi-box-seam',
        'permission' => 'manage-products',
        'active_pattern' => ['admin.products.*', 'admin.categories.*', 'admin.attributes.*'],
        'submenu' => [
            ['title' => 'Danh mục sản phẩm', 'route' => 'admin.categories.index', 'active_pattern' => 'admin.categories.*'],
            ['title' => 'Sản phẩm',          'route' => 'admin.products.index',   'active_pattern' => 'admin.products.*'],
            ['title' => 'Thuộc tính',        'route' => 'admin.attributes.index', 'active_pattern' => 'admin.attributes.*'],
        ],
    ],

    // ================================================================
    // NHÓM 3: NỘI DUNG & MEDIA (Gom Media vào đây luôn cho gọn nếu muốn)
    // ================================================================
    ['type' => 'header', 'title' => 'NỘI DUNG & MEDIA'],

    [
        'title' => 'Quản lý Media',
        'icon' => 'bi bi-folder2-open', 
        'route' => 'admin.media.index', 
        'active_pattern' => 'admin.media.*',
        'permission' => 'manage-media',
    ],
    [
        'title' => 'Quản lý menu',
        'icon' => 'fas fa-bars', 
        'route' => 'admin.menus.index', 
        'active_pattern' => 'admin.menus.*',
        'permission' => 'manage-menu',
    ],
    [
        'title' => 'Quản lý bài viết',
        'icon' => 'bi bi-pencil-square',
        'permission' => 'manage-posts',
        'active_pattern' => ['admin.post-categories.*', 'admin.posts.*'],
        'submenu' => [
            ['title' => 'Danh mục bài viết', 'route' => 'admin.post-categories.index', 'active_pattern' => 'admin.post-categories.*'],
            ['title' => 'Bài viết',          'route' => 'admin.posts.index',           'active_pattern' => 'admin.posts.*'],
        ],
    ],
    [
        'title' => 'Thư viện & Hiển thị',
        'icon' => 'bi bi-collection',
        'active_pattern' => ['admin.slides.*', 'admin.testimonials.*', 'admin.intros.*', 'admin.brands.*'],
        'submenu' => [
            ['title' => 'Slide trang chủ',   'route' => 'admin.slides.index',       'active_pattern' => 'admin.slides.*'],
            ['title' => 'Feedback',          'route' => 'admin.testimonials.index', 'active_pattern' => 'admin.testimonials.*'],
            ['title' => 'Giới thiệu',        'route' => 'admin.intros.index',       'active_pattern' => 'admin.intros.*'],
            ['title' => 'Thương hiệu',       'route' => 'admin.brands.index',       'active_pattern' => 'admin.brands.*'],
        ],
    ],

    // ================================================================
    // NHÓM 4: DỰ ÁN - ĐỐI TÁC - TUYỂN DỤNG
    // ================================================================
    ['type' => 'header', 'title' => 'MỞ RỘNG'],

    [
        'title' => 'Quản lý Dự án',
        'icon' => 'bi bi-building',
        'active_pattern' => ['admin.project-categories.*', 'admin.projects.*'],
        'submenu' => [
            ['title' => 'Danh mục Dự án', 'route' => 'admin.project-categories.index', 'active_pattern' => 'admin.project-categories.*'],
            ['title' => 'Dự án',          'route' => 'admin.projects.index',           'active_pattern' => 'admin.projects.*'],
        ],
    ],
    [
        'title' => 'Quản lý Lĩnh vực',
        'icon' => 'bi bi-diagram-3',
        'permission' => 'manage-fields',
        'active_pattern' => ['admin.field-categories.*', 'admin.fields.*'],
        'submenu' => [
            ['title' => 'Danh mục Lĩnh vực', 'route' => 'admin.field-categories.index', 'active_pattern' => 'admin.field-categories.*'],
            ['title' => 'Lĩnh vực',          'route' => 'admin.fields.index',           'active_pattern' => 'admin.fields.*'],
        ],
    ],
    [
        'title' => 'Quản lý Đại lý',
        'icon' => 'bi bi-shop-window',
        'route' => 'admin.agents.index',
        'active_pattern' => 'admin.agents.*',
        'permission' => 'manage-agents',
    ],
    [
        'title' => 'Quản lý Tuyển dụng',
        'icon' => 'bi bi-briefcase',
        'permission' => 'manage-careers',
        'active_pattern' => ['admin.careers.*', 'admin.career_applications.*'],
        'submenu' => [
            ['title' => 'Tin tuyển dụng',    'route' => 'admin.careers.index',             'active_pattern' => 'admin.careers.*'],
            ['title' => 'Hồ sơ ứng tuyển',   'route' => 'admin.career-applications.index', 'active_pattern' => 'admin.career-applications.*'],
        ],
    ],

    // ================================================================
    // NHÓM 5: HỆ THỐNG (Quản lý Nhân viên nằm đây)
    // ================================================================
    ['type' => 'header', 'title' => 'HỆ THỐNG'],

    // --- MỚI THÊM: QUẢN LÝ NHÂN VIÊN (STAFF) ---
    [
        'title' => 'Quản lý Nhân viên',
        'icon' => 'bi bi-person-badge-fill',
        'active_pattern' => ['admin.staff.*'],
        'submenu' => [
            ['title' => 'Danh sách nhân viên', 'route' => 'admin.staff.index',  'active_pattern' => 'admin.staff.index'],
            ['title' => 'Thêm nhân viên',      'route' => 'admin.staff.create', 'active_pattern' => 'admin.staff.create'],
        ],
    ],
    
    // --- QUẢN LÝ USER WEB (Cũ) ---
    [
        'title' => 'Quản lý User Web',
        'icon' => 'bi bi-person-gear',
        'permission' => 'manage-users',
        'active_pattern' => 'admin.users.*',
        'submenu' => [
            ['title' => 'Danh sách người dùng', 'route' => 'admin.users.index',  'active_pattern' => 'admin.users.*'],
            ['title' => 'Thêm người dùng',      'route' => 'admin.users.create', 'active_pattern' => 'admin.users.create'],
        ],
    ],
    
    [
        'title' => 'Phân quyền',
        'icon' => 'bi bi-shield-check',
        'permission' => 'manage-roles',
        'active_pattern' => 'admin.roles.*',
        'submenu' => [
            ['title' => 'Vai trò & Quyền', 'route' => 'admin.roles.index', 'active_pattern' => 'admin.roles.*'],
        ],
    ],
    [
        'title' => 'Page Settings',
        'icon' => 'bi bi-file-earmark-richtext',
        'route' => 'admin.pages.index',
        'active_pattern' => 'admin.pages.*',
        'permission' => 'manage-pages',
    ],
    [
        'title' => 'Cấu hình chung',
        'icon' => 'bi bi-gear',
        'route' => 'admin.settings.index',
        'active_pattern' => 'admin.settings.*',
        'permission' => 'manage-settings',
    ],
];