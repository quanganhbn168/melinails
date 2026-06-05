<?php

namespace App\Filament\Resources\BookingAppointments\Pages;

use App\Filament\Resources\BookingAppointments\BookingAppointmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingAppointment extends EditRecord
{
    protected static string $resource = BookingAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
