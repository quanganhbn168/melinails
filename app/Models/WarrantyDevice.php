<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WarrantyDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_item_id', 'device_name', 'serial_number', 
        'start_date', 'warranty_months', 'expiration_date', 
        'notes', 'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiration_date' => 'datetime',
    ];

    // --- RELATIONSHIPS ---
    
    public function item()
    {
        return $this->belongsTo(TaskItem::class, 'task_item_id');
    }

    // --- ACCESSORS (Thuộc tính ảo) ---

    // Kiểm tra xem còn hạn bảo hành không
    // Dùng: $device->is_expired
    public function getIsExpiredAttribute()
    {
        if ($this->status !== 'active') return true;
        return Carbon::now()->gt($this->expiration_date);
    }

    // Lấy số ngày còn lại
    // Dùng: $device->days_remaining
    public function getDaysRemainingAttribute()
    {
        if ($this->is_expired) return 0;
        return Carbon::now()->diffInDays($this->expiration_date, false); // false để lấy số dương
    }

    
}