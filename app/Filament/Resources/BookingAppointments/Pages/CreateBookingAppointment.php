<?php

namespace App\Filament\Resources\BookingAppointments\Pages;

use App\Filament\Resources\BookingAppointments\BookingAppointmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingAppointment extends CreateRecord
{
    protected static string $resource = BookingAppointmentResource::class;
}
