<?php

namespace App\Filament\Widgets;

use App\Models\BookingAppointmentSegment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class TopBookingServicesWidget extends Widget
{
    protected string $view = 'filament.widgets.top-booking-services';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    public function getServices(): array
    {
        return BookingAppointmentSegment::query()
            ->select([
                'service_name',
                DB::raw('COUNT(*) as bookings_count'),
                DB::raw('SUM(price) as revenue'),
            ])
            ->whereHas('appointment', fn ($query) => $query->whereIn('status', ['confirmed', 'completed']))
            ->whereNotNull('service_name')
            ->groupBy('service_name')
            ->orderByDesc('bookings_count')
            ->limit(6)
            ->get()
            ->map(fn (BookingAppointmentSegment $segment) => [
                'name' => $segment->service_name,
                'count' => (int) $segment->bookings_count,
                'revenue' => number_format((int) $segment->revenue, 0, ',', ' ') . ' Kč',
            ])
            ->all();
    }
}
