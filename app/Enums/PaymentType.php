<?php

namespace App\Enums;

enum PaymentType: string
{
    case ITEM_VALUE = 'item_value';
    case LABOR = 'labor';
    case OTHER = 'other';
    case COLLECTION = 'collection'; // Khoản thu tiền (để cấn trừ)

    public function label(): string
    {
        return match($this) {
            self::ITEM_VALUE => 'Vật tư/Thiết bị',
            self::LABOR => 'Tiền công',
            self::OTHER => 'Khoản khác',
            self::COLLECTION => 'Thu tiền',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ITEM_VALUE => 'info',
            self::LABOR => 'primary',
            self::OTHER => 'secondary',
            self::COLLECTION => 'success',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ITEM_VALUE => 'fa-box',
            self::LABOR => 'fa-hard-hat',
            self::OTHER => 'fa-file-invoice-dollar',
            self::COLLECTION => 'fa-hand-holding-usd',
        };
    }
}
