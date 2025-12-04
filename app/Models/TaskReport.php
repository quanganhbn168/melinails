<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReport extends Model
{
    use HasFactory;
    
    // Đảm bảo tên cột khớp 100% với database migration
    protected $fillable = [
        'task_id', 
        'reporter_id', 
        'content', 
        'is_completed', 
        'collected_amount', 
        'customer_signature',
        'payment_method', 
        'transfer_target',
        'finance_status',
        'finance_note',
    ];

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function reporter() {
        return $this->belongsTo(Admin::class, 'reporter_id');
    }

    public function images() {
        return $this->hasMany(TaskImage::class);
    }

    public function items() {
        return $this->hasMany(TaskItem::class);
    }
}