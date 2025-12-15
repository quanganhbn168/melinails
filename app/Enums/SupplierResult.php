<?php

namespace App\Enums;

enum SupplierResult: string
{
    case FIXED = 'fixed';
    case UNFIXABLE = 'unfixable';
    case EXTRA_COST = 'extra_cost';
    case REFUND = 'refund';

    public function label(): string
    {
        return match($this) {
            self::FIXED => 'Đã sửa xong',
            self::UNFIXABLE => 'Không sửa được',
            self::EXTRA_COST => 'Có phát sinh phí',
            self::REFUND => 'Hoàn tiền',
        };
    }
}
