<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- QUAN TRỌNG: Phải có dòng này
use Illuminate\Database\Eloquent\Model;

class TaskImage extends Model
{
    use HasFactory;

    protected $fillable = ['task_report_id', 'image_path']; 

    public function report() {
        return $this->belongsTo(TaskReport::class, 'task_report_id');
    }
}