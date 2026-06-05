<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyStaff extends Model
{
    use HasFactory;

    protected $table = 'beauty_staff';

    protected $fillable = [
        'branch_id',
        'name',
        'slug',
        'role',
        'phone',
        'email',
        'working_days',
        'working_mode',
        'shift_start',
        'shift_end',
        'working_weekdays',
        'working_shifts',
        'working_dates',
        'status',
    ];

    protected $casts = [
        'working_days' => 'array',
        'working_weekdays' => 'array',
        'working_shifts' => 'array',
        'working_dates' => 'array',
        'status' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'beauty_staff_service')->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(BookingAppointment::class);
    }

    public function appointmentSegments()
    {
        return $this->hasMany(BookingAppointmentSegment::class, 'beauty_staff_id');
    }

    public function isWorkingDuring(Carbon $startsAt, Carbon $endsAt, Branch $branch): bool
    {
        return collect($this->workingWindowsForDate($startsAt, $branch))
            ->contains(function (array $window) use ($startsAt, $endsAt) {
                return $startsAt->greaterThanOrEqualTo($window['start'])
                    && $endsAt->lessThanOrEqualTo($window['end']);
            });
    }

    public function workingWindowsForDate(Carbon $date, Branch $branch): array
    {
        $mode = $this->working_mode ?: 'full_time';
        $dayStart = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . substr($branch->opening_time, 0, 5));
        $dayEnd = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . substr($branch->closing_time, 0, 5));

        if ($mode === 'full_time') {
            return [['start' => $dayStart, 'end' => $dayEnd]];
        }

        if ($mode === 'weekdays') {
            $weekdays = collect($this->working_weekdays ?: $this->working_days ?: [1, 2, 3, 4, 5, 6, 7])
                ->map(fn ($day) => (int) $day);

            if (! $weekdays->contains((int) $date->isoWeekday())) {
                return [];
            }

            return [[
                'start' => $this->timeOnDate($date, $this->shift_start ?: $branch->opening_time),
                'end' => $this->timeOnDate($date, $this->shift_end ?: $branch->closing_time),
            ]];
        }

        if ($mode === 'shifts') {
            return collect($this->working_shifts ?: [])
                ->filter(fn (array $shift) => (int) ($shift['weekday'] ?? 0) === (int) $date->isoWeekday())
                ->map(fn (array $shift) => [
                    'start' => $this->timeOnDate($date, $shift['start'] ?? $branch->opening_time),
                    'end' => $this->timeOnDate($date, $shift['end'] ?? $branch->closing_time),
                ])
                ->values()
                ->all();
        }

        if ($mode === 'specific_dates') {
            return collect($this->working_dates ?: [])
                ->filter(fn (array $entry) => ($entry['date'] ?? null) === $date->format('Y-m-d'))
                ->map(fn (array $entry) => [
                    'start' => $this->timeOnDate($date, $entry['start'] ?? $branch->opening_time),
                    'end' => $this->timeOnDate($date, $entry['end'] ?? $branch->closing_time),
                ])
                ->values()
                ->all();
        }

        return [['start' => $dayStart, 'end' => $dayEnd]];
    }

    protected function timeOnDate(Carbon $date, string $time): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . substr($time, 0, 5));
    }
}
