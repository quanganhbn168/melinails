<?php

namespace App\Enums;

enum WorkOrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case PENDING_APPROVAL = 'pending_approval';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xử lý',
            self::PROCESSING => 'Đang thực hiện',
            self::COMPLETED => 'Hoàn thành',
            self::CANCELLED => 'Đã hủy',
            self::PENDING_APPROVAL => 'Chờ duyệt',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'secondary',
            self::PENDING_APPROVAL => 'info',
        };
    }
}
