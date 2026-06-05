<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchService extends Model
{
    protected $table = 'branch_service';

    protected $fillable = [
        'branch_id',
        'service_id',
        'price',
        'price_text',
        'duration_minutes',
        'is_available',
        'availability_note',
    ];

    protected $casts = [
        'price' => 'integer',
        'duration_minutes' => 'integer',
        'is_available' => 'boolean',
        'availability_note' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
