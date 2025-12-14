<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyRequest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'shop_name',
        'address',
        'area',
        'details',
        'status',
    ];
}
