<?php

namespace App\Filament\Exports;

use App\Models\BookingAppointment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BookingAppointmentExporter extends Exporter
{
    protected static ?string $model = BookingAppointment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code')
                ->label('Mã lịch'),
            ExportColumn::make('status')
                ->label('Trạng thái'),
            ExportColumn::make('branch_id')
                ->label('Shop ID'),
            ExportColumn::make('branch.name')
                ->label('Shop'),
            ExportColumn::make('beauty_staff_id')
                ->label('Nhân viên ID'),
            ExportColumn::make('staff.name')
                ->label('Nhân viên'),
            ExportColumn::make('starts_at')
                ->label('Bắt đầu')
                ->formatStateUsing(fn ($state): ?string => $state?->format('Y-m-d H:i')),
            ExportColumn::make('ends_at')
                ->label('Kết thúc')
                ->formatStateUsing(fn ($state): ?string => $state?->format('Y-m-d H:i')),
            ExportColumn::make('customer_name')
                ->label('Tên khách'),
            ExportColumn::make('customer_phone')
                ->label('Điện thoại'),
            ExportColumn::make('customer_email')
                ->label('Email'),
            ExportColumn::make('service_names')
                ->label('Dịch vụ')
                ->state(fn (BookingAppointment $record): string => self::serviceNames($record)),
            ExportColumn::make('total_duration_minutes')
                ->label('Tổng phút'),
            ExportColumn::make('total_price')
                ->label('Tổng tiền Kč'),
            ExportColumn::make('customer_note')
                ->label('Ghi chú'),
            ExportColumn::make('created_at')
                ->label('Ngày tạo')
                ->formatStateUsing(fn ($state): ?string => $state?->format('Y-m-d H:i')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Đã export ' . number_format($export->successful_rows) . ' lịch hẹn.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Có ' . number_format($failedRowsCount) . ' dòng lỗi.';
        }

        return $body;
    }

    private static function serviceNames(BookingAppointment $record): string
    {
        $segmentNames = $record->segments
            ->pluck('service_name')
            ->filter()
            ->values();

        if ($segmentNames->isNotEmpty()) {
            return $segmentNames->join(', ');
        }

        return collect($record->service_snapshot ?? [])
            ->pluck('name')
            ->filter()
            ->values()
            ->join(', ');
    }
}
