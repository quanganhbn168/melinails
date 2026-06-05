<?php

namespace App\Filament\Resources\Branches;

use App\Filament\Resources\Branches\Pages\CreateBranch;
use App\Filament\Resources\Branches\Pages\EditBranch;
use App\Filament\Resources\Branches\Pages\ListBranches;
use App\Models\Branch;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Shop & Booking';
    }

    public static function getModelLabel(): string
    {
        return 'Chi nhánh';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Chi nhánh';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Cài đặt shop')
                    ->tabs([
                        Tab::make('Thông tin shop')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tên shop / chi nhánh')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        TextInput::make('city')
                                            ->label('Thành phố')
                                            ->maxLength(255),
                                        TextInput::make('timezone')
                                            ->label('Timezone')
                                            ->default('Europe/Prague')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Textarea::make('address')
                                    ->label('Địa chỉ')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Grid::make(2)
                                    ->schema([
                                        TimePicker::make('opening_time')
                                            ->label('Giờ mở cửa')
                                            ->seconds(false)
                                            ->default('08:00'),
                                        TimePicker::make('closing_time')
                                            ->label('Giờ đóng cửa')
                                            ->seconds(false)
                                            ->default('20:00'),
                                    ]),
                            ])
                            ->columns(1),

                        Tab::make('Booking rule')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Section::make('Luật nhận booking của shop này')
                                    ->description('Các rule ở đây áp dụng riêng cho từng shop: sức chứa, bước slot, buffer và giới hạn đặt lịch.')
                                    ->schema([
                                        Toggle::make('online_booking_enabled')
                                            ->label('Cho phép booking online tại shop này')
                                            ->default(true),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('booking_capacity')
                                                    ->label('Số khách tối đa trong 1 session')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->suffix('khách')
                                                    ->helperText('Ví dụ nhập 3 nghĩa là shop có thể nhận tối đa 3 lịch trùng cùng một khung giờ.')
                                                    ->default(1),
                                                TextInput::make('booking_slot_minutes')
                                                    ->label('Bước slot booking')
                                                    ->numeric()
                                                    ->minValue(5)
                                                    ->suffix('phút')
                                                    ->default(15),
                                                TextInput::make('booking_buffer_minutes')
                                                    ->label('Buffer giữa các lịch')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->suffix('phút')
                                                    ->default(0),
                                                TextInput::make('booking_min_notice_minutes')
                                                    ->label('Khách phải báo trước tối thiểu')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->suffix('phút')
                                                    ->default(0),
                                                TextInput::make('booking_max_days_ahead')
                                                    ->label('Cho đặt trước tối đa')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->suffix('ngày')
                                                    ->default(60),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Liên hệ & bản đồ')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('phone')
                                            ->label('Điện thoại')
                                            ->tel()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                                TextInput::make('map_url')
                                    ->label('Google Map embed URL')
                                    ->url()
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Trạng thái')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Toggle::make('status')
                                    ->label('Kích hoạt shop')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Chi nhánh')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label('Thành phố')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->searchable(),
                TextColumn::make('opening_time')
                    ->label('Giờ mở'),
                TextColumn::make('closing_time')
                    ->label('Giờ đóng'),
                TextColumn::make('booking_slot_minutes')
                    ->label('Slot')
                    ->suffix(' min')
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('status')
                    ->label('Kích hoạt'),
                ToggleColumn::make('online_booking_enabled')
                    ->label('Booking online'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBranches::route('/'),
            'create' => CreateBranch::route('/create'),
            'edit' => EditBranch::route('/{record}/edit'),
        ];
    }
}
