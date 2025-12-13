<?php

namespace App\Enums;

enum TagType: string
{
    case PRODUCT = 'product';
    case WORK_ORDER = 'work_order';
    case POST = 'post';
    case SUPPLIER = 'supplier';

    public function label(): string
    {
        return match($this) {
            self::PRODUCT => 'Sản phẩm',
            self::WORK_ORDER => 'Phiếu việc',
            self::POST => 'Bài viết',
            self::SUPPLIER => 'Nhà cung cấp',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PRODUCT => 'fas fa-box',
            self::WORK_ORDER => 'fas fa-tools',
            self::POST => 'fas fa-newspaper',
            self::SUPPLIER => 'fas fa-truck',
        };
    }
}
