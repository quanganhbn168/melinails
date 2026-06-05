<?php

namespace App\Filament\Resources\BookingAppointments;

use App\Filament\Exports\BookingAppointmentExporter;
use App\Filament\Resources\BookingAppointments\Pages\CreateBookingAppointment;
use App\Filament\Resources\BookingAppointments\Pages\EditBookingAppointment;
use App\Filament\Resources\BookingAppointments\Pages\ListBookingAppointments;
use App\Models\BookingAppointment;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class BookingAppointmentResource extends Resource
{
    protected static ?string $model = BookingAppointment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Shop & Booking';
    }

    public static function getModelLabel(): string
    {
        return 'Lịch hẹn';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Lịch hẹn';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Lịch hẹn')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('code')
                                    ->label('Mã lịch')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('status')
                                    ->label('Trạng thái')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'cancelled' => 'Cancelled',
                                        'completed' => 'Completed',
                                    ])
                                    ->required()
                                    ->default('confirmed'),
                                Select::make('branch_id')
                                    ->label('Chi nhánh')
                                    ->relationship('branch', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('beauty_staff_id')
                                    ->label('Nhân viên')
                                    ->relationship('staff', 'name')
                                    ->searchable()
                                    ->preload(),
                                DateTimePicker::make('starts_at')
                                    ->label('Bắt đầu')
                                    ->seconds(false)
                                    ->required(),
                                DateTimePicker::make('ends_at')
                                    ->label('Kết thúc')
                                    ->seconds(false)
                                    ->required(),
                                TextInput::make('total_duration_minutes')
                                    ->label('Tổng phút')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('total_price')
                                    ->label('Tổng tiền')
                                    ->numeric()
                                    ->suffix('Kč'),
                            ]),
                        CheckboxList::make('service_ids')
                            ->label('Dịch vụ')
                            ->options(fn () => Service::query()->where('status', true)->orderBy('sort_order')->pluck('name', 'id'))
                            ->columns(2)
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Khách hàng')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('customer_name')
                                    ->label('Tên khách')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('customer_phone')
                                    ->label('Điện thoại')
                                    ->required()
                                    ->maxLength(40),
                                TextInput::make('customer_email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                                Textarea::make('customer_note')
                                    ->label('Ghi chú')
                                    ->rows(3),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Mã')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('starts_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->description(fn (BookingAppointment $record): string => 'Kết thúc: ' . $record->ends_at->format('H:i')),
                TextColumn::make('branch.name')
                    ->label('Chi nhánh')
                    ->badge()
                    ->searchable(),
                TextColumn::make('staff.name')
                    ->label('Nhân viên')
                    ->placeholder('Chưa gán')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Khách')
                    ->searchable()
                    ->description(fn (BookingAppointment $record): string => $record->customer_phone),
                TextColumn::make('total_duration_minutes')
                    ->label('Phút')
                    ->suffix(' min'),
                TextColumn::make('total_price')
                    ->label('Giá')
                    ->money('CZK'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('starts_at')
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Chi nhánh')
                    ->relationship('branch', 'name'),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Export lịch đã chọn')
                        ->exporter(BookingAppointmentExporter::class),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingAppointments::route('/'),
            'create' => CreateBookingAppointment::route('/create'),
            'edit' => EditBookingAppointment::route('/{record}/edit'),
        ];
    }
}
