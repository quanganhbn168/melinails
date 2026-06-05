<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAppointmentSegment extends Model
{
    use HasFactory;

    protected $table = 'melinails_appointment_segments';

    protected $fillable = [
        'appointment_id',
        'service_id',
        'beauty_staff_id',
        'position',
        'service_name',
        'duration_minutes',
        'price',
        'price_text',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'duration_minutes' => 'integer',
        'price' => 'integer',
        'position' => 'integer',
    ];

    public function appointment()
    {
        return $this->belongsTo(BookingAppointment::class, 'appointment_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function staff()
    {
        return $this->belongsTo(BeautyStaff::class, 'beauty_staff_id');
    }
}
