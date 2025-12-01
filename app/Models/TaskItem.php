<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskItem extends Model
{
    protected $fillable = ['task_id', 'item_name', 'serial_number', 'quantity', 'price'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}