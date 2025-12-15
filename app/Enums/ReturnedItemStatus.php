<?php

namespace App\Enums;

enum ReturnedItemStatus: string
{
    case PENDING = 'pending';
    case SENT_TO_SUPPLIER = 'sent_to_supplier';
    case RETURNED_FROM_SUPPLIER = 'returned_from_supplier';
    case RETURNED = 'returned';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Trong kho (Chờ xử lý)',
            self::SENT_TO_SUPPLIER => 'Đã gửi NCC',
            self::RETURNED_FROM_SUPPLIER => 'Đã nhận từ NCC',
            self::RETURNED => 'Đã xử lý xong',
            self::CLOSED => 'Đã đóng',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::SENT_TO_SUPPLIER => 'info',
            self::RETURNED_FROM_SUPPLIER => 'primary',
            self::RETURNED => 'success',
            self::CLOSED => 'secondary',
        };
    }
}
