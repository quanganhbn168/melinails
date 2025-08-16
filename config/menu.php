<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'bi bi-speedometer',
        'route' => 'admin.dashboard',
        'permission' => 'view-dashboard',
    ],
    [
        'title' => 'Quản lý sản phẩm',
        'icon' => 'bi bi-box-seam',
        'permission' => 'manage-products',
        'active_pattern' => ['admin.products.*', 'admin.categories.*', 'admin.attributes.*'],
        'submenu' => [
            [
                'title' => 'Danh mục sản phẩm',
                'route' => 'admin.categories.index',
                'active_pattern' => 'admin.categories.*',
                'icon' => 'bi bi-folder2-open',
            ],
            [
                'title' => 'Thuộc tính',
                'route' => 'admin.attributes.index',
                'active_pattern' => 'admin.attributes.*',
                'icon' => 'bi bi-tags',
            ],
            [
                'title' => 'Sản phẩm',
                'route' => 'admin.products.index',
                'active_pattern' => 'admin.products.*',
                'icon' => 'bi bi-bag',
            ],
        ],
    ],
    [
        'title' => 'Quản lý Đơn hàng',
        'icon' => 'bi bi-receipt',
        'permission' => 'manage-orders',
        'active_pattern' => 'admin.orders.*',
        'submenu' => [
            [
                'title' => 'Danh sách Đơn hàng',
                'route' => 'admin.orders.index',
                'active_pattern' => 'admin.orders.*',
                'icon' => 'bi bi-list-ul',
            ],
            [
                'title' => 'Tạo Đơn hàng mới',
                'route' => 'admin.orders.create',
                'active_pattern' => 'admin.orders.create',
                'icon' => 'bi bi-cart-plus',
            ],
        ],
    ],
    [
        'title' => 'Quản lý dịch vụ',
        'icon' => 'bi bi-journal-bookmark-fill',
        'active_pattern' => ['admin.service_categories.*', 'admin.services.*'],
        'submenu' => [
            [
                'title' => 'Danh mục dịch vụ',
                'route' => 'admin.service_categories.index',
                'active_pattern' => 'admin.service_categories.*',
                'icon' => 'bi bi-journal',
            ],
            [
                'title' => 'Dịch vụ',
                'route' => 'admin.services.index',
                'active_pattern' => 'admin.services.*',
                'icon' => 'bi bi-journal-medical',
            ],
        ],
    ],
    [
        'title' => 'Quản lý bài viết',
        'icon' => 'bi bi-file-text',
        'permission' => 'manage-posts',
        'active_pattern' => ['admin.post-categories.*', 'admin.posts.*'],
        'submenu' => [
            [
                'title' => 'Danh mục bài viết',
                'route' => 'admin.post-categories.index',
                'active_pattern' => 'admin.post-categories.*',
                'icon' => 'bi bi-folder2',
            ],
            [
                'title' => 'Bài viết',
                'route' => 'admin.posts.index',
                'active_pattern' => 'admin.posts.*',
                'icon' => 'bi bi-file-earmark',
            ],
        ],
    ],
    [
        'title' => 'Quản lý slide',
        'icon' => 'bi bi-images',
        'route' => 'admin.slides.index',
        'active_pattern' => 'admin.slides.*',
        'permission' => 'manage-settings',
    ],
    [
        'title' => 'Quản lý giới thiệu',
        'icon' => 'bi bi-images',
        'route' => 'admin.intros.index',
        'active_pattern' => 'admin.intros.*',
        'permission' => 'manage-settings',
    ],
    [
        'title' => 'Quản lý người dùng',
        'icon' => 'bi bi-person',
        'permission' => 'manage-users',
        'active_pattern' => 'admin.users.*',
        'submenu' => [
            [
                'title' => 'Danh sách người dùng',
                'route' => 'admin.users.index',
                'active_pattern' => 'admin.users.*',
                'icon' => 'bi bi-circle',
            ],
            
            [
                'title' => 'Thêm người dùng',
                'route' => 'admin.users.create',
                'active_pattern' => 'admin.users.create',
                'icon' => 'bi bi-circle',
            ],
        ],
    ],
    [
        'title' => 'Phân quyền',
        'icon' => 'bi bi-shield-lock',
        'permission' => 'manage-roles',
        'active_pattern' => 'admin.roles.*',
        'submenu' => [
            [
                'title' => 'Vai trò & Quyền',
                'route' => 'admin.roles.index',
                'active_pattern' => 'admin.roles.*',
                'icon' => 'bi bi-person-check-fill',
            ],
        ],
    ],
    [
        'title' => 'Cấu hình',
        'icon' => 'bi bi-gear',
        'route' => 'admin.settings.index',
        'active_pattern' => 'admin.settings.*',
        'permission' => 'manage-settings',
    ],
];