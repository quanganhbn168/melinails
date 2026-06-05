<?php

namespace App\Filament\Imports;

use App\Models\BookingAppointment;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class BookingAppointmentImporter extends Importer
{
    protected static ?string $model = BookingAppointment::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Mã lịch')
                ->rules(['nullable', 'max:255'])
                ->ignoreBlankState(),
            ImportColumn::make('branch_id')
                ->label('Shop ID')
                ->requiredMapping()
                ->integer()
                ->rules(['required', 'exists:branches,id']),
            ImportColumn::make('beauty_staff_id')
                ->label('Nhân viên ID')
                ->integer()
                ->rules(['nullable', 'exists:beauty_staff,id'])
                ->ignoreBlankState(),
            ImportColumn::make('starts_at')
                ->label('Bắt đầu')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->example('2026-06-05 13:00'),
            ImportColumn::make('ends_at')
                ->label('Kết thúc')
                ->requiredMapping()
                ->rules(['required', 'date', 'after:starts_at'])
                ->example('2026-06-05 14:15'),
            ImportColumn::make('customer_name')
                ->label('Tên khách')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('customer_phone')
                ->label('Điện thoại')
                ->requiredMapping()
                ->rules(['required', 'max:40']),
            ImportColumn::make('customer_email')
                ->label('Email')
                ->rules(['nullable', 'email', 'max:255'])
                ->ignoreBlankState(),
            ImportColumn::make('service_names')
                ->label('Dịch vụ')
                ->helperText('Nhập tên dịch vụ, phân cách bằng dấu phẩy.')
                ->rules(['nullable', 'max:1000'])
                ->fillRecordUsing(function (mixed $state, ImportColumn $column): void {
                    $names = collect(explode(',', (string) $state))
                        ->map(fn (string $name): string => trim($name))
                        ->filter()
                        ->values();

                    if ($names->isEmpty()) {
                        return;
                    }

                    $column->getRecord()->service_snapshot = $names
                        ->map(fn (string $name): array => ['name' => $name])
                        ->all();
                }),
            ImportColumn::make('total_duration_minutes')
                ->label('Tổng phút')
                ->requiredMapping()
                ->integer()
                ->rules(['required', 'integer', 'min:1']),
            ImportColumn::make('total_price')
                ->label('Tổng tiền Kč')
                ->integer()
                ->rules(['nullable', 'integer', 'min:0'])
                ->ignoreBlankState(),
            ImportColumn::make('status')
                ->label('Trạng thái')
                ->rules(['nullable', 'in:pending,confirmed,cancelled,completed'])
                ->ignoreBlankState()
                ->example('confirmed'),
            ImportColumn::make('customer_note')
                ->label('Ghi chú')
                ->rules(['nullable'])
                ->ignoreBlankState(),
        ];
    }

    public function resolveRecord(): ?BookingAppointment
    {
        if (filled($this->data['code'] ?? null)) {
            return BookingAppointment::query()->firstOrNew([
                'code' => $this->data['code'],
            ]);
        }

        return new BookingAppointment();
    }

    protected function beforeSave(): void
    {
        if (blank($this->record->code)) {
            $this->record->code = 'BK-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
        }

        $this->record->status = $this->record->status ?: 'confirmed';
        $this->record->total_price = $this->record->total_price ?: 0;
        $this->record->service_ids = $this->record->service_ids ?: [];
        $this->record->service_snapshot = $this->record->service_snapshot ?: [];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Đã import ' . number_format($import->successful_rows) . ' lịch hẹn.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' Có ' . number_format($failedRowsCount) . ' dòng lỗi cần kiểm tra.';
        }

        return $body;
    }
}
