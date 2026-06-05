<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAppointment extends Model
{
    use HasFactory;

    protected $table = 'melinails_appointments';

    protected $fillable = [
        'code',
        'branch_id',
        'beauty_staff_id',
        'service_ids',
        'service_snapshot',
        'starts_at',
        'ends_at',
        'total_duration_minutes',
        'total_price',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_note',
        'status',
    ];

    protected $casts = [
        'service_ids' => 'array',
        'service_snapshot' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'total_duration_minutes' => 'integer',
        'total_price' => 'integer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function staff()
    {
        return $this->belongsTo(BeautyStaff::class, 'beauty_staff_id');
    }

    public function segments()
    {
        return $this->hasMany(BookingAppointmentSegment::class, 'appointment_id')->orderBy('position');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }
}
