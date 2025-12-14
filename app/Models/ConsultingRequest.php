<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultingRequest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'company',
        'address',
        'details',
        'file_path',
        'budget',
        'status',
    ];
}
