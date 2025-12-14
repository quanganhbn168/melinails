<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xác nhận',
            self::VERIFIED => 'Đã xác nhận',
            self::CANCELLED => 'Đã hủy',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::VERIFIED => 'success',
            self::CANCELLED => 'secondary',
        };
    }
}
