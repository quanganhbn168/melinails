<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xử lý',
            self::PROCESSING => 'Đang thực hiện',
            self::COMPLETED => 'Hoàn thành',
            self::CANCELLED => 'Đã hủy',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'secondary',
        };
    }
}
