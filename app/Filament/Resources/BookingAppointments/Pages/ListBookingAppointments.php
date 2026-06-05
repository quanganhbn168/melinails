<?php

namespace App\Filament\Resources\BookingAppointments\Pages;

use App\Filament\Exports\BookingAppointmentExporter;
use App\Filament\Imports\BookingAppointmentImporter;
use App\Filament\Resources\BookingAppointments\BookingAppointmentResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingAppointments extends ListRecords
{
    protected static string $resource = BookingAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Import lịch hẹn')
                ->importer(BookingAppointmentImporter::class),
            ExportAction::make()
                ->label('Export lịch hẹn')
                ->exporter(BookingAppointmentExporter::class),
            CreateAction::make(),
        ];
    }
}
