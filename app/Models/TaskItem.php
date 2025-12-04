<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- QUAN TRỌNG
use Illuminate\Database\Eloquent\Model;

class TaskItem extends Model
{
    use HasFactory;

    protected $fillable = ['task_report_id', 'item_name', 'serial_number', 'quantity', 'price'];

    public function report() {
        return $this->belongsTo(TaskReport::class, 'task_report_id');
    }
}