<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'city',
        'phone',
        'email',
        'timezone',
        'opening_time',
        'closing_time',
        'booking_slot_minutes',
        'booking_buffer_minutes',
        'booking_min_notice_minutes',
        'booking_max_days_ahead',
        'booking_capacity',
        'online_booking_enabled',
        'map_url',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'booking_slot_minutes' => 'integer',
        'booking_buffer_minutes' => 'integer',
        'booking_min_notice_minutes' => 'integer',
        'booking_max_days_ahead' => 'integer',
        'booking_capacity' => 'integer',
        'online_booking_enabled' => 'boolean',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot(['price', 'price_text', 'duration_minutes', 'is_available', 'availability_note'])
            ->withTimestamps();
    }

    public function serviceCategories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'branch_service_category')
            ->withPivot(['is_available'])
            ->withTimestamps();
    }

    public function staff()
    {
        return $this->hasMany(BeautyStaff::class);
    }

    public function appointments()
    {
        return $this->hasMany(BookingAppointment::class);
    }
}
