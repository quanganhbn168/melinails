<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyService extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'customer_name', 'total_amount', 
        'start_date', 'device_list_details', 'device_list_qty', 'notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}