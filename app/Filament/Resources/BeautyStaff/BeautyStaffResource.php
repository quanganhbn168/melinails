<?php

namespace App\Filament\Resources\BeautyStaff;

use App\Filament\Resources\BeautyStaff\Pages\CreateBeautyStaff;
use App\Filament\Resources\BeautyStaff\Pages\EditBeautyStaff;
use App\Filament\Resources\BeautyStaff\Pages\ListBeautyStaff;
use App\Models\BeautyStaff;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class BeautyStaffResource extends Resource
{
    protected static ?string $model = BeautyStaff::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Shop & Booking';
    }

    public static function getModelLabel(): string
    {
        return 'Nhân viên';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Nhân viên';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Thông tin nhân viên')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Tên nhân viên')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Select::make('branch_id')
                                    ->label('Chi nhánh')
                                    ->relationship('branch', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('role')
                                    ->label('Vai trò')
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label('Điện thoại')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                        CheckboxList::make('services')
                            ->label('Skill / dịch vụ làm được')
                            ->relationship('services', 'name')
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable()
                            ->columnSpanFull(),
                        Section::make('Rule làm việc')
                            ->description('Mặc định full-time theo giờ chi nhánh. Có thể đổi sang ca, ngày cụ thể hoặc thứ trong tuần.')
                            ->schema([
                                Select::make('working_mode')
                                    ->label('Kiểu làm việc')
                                    ->options([
                                        'full_time' => 'Full-time theo giờ chi nhánh',
                                        'weekdays' => 'Theo thứ trong tuần',
                                        'shifts' => 'Theo ca từng thứ',
                                        'specific_dates' => 'Theo ngày cụ thể',
                                    ])
                                    ->default('full_time')
                                    ->live()
                                    ->required(),
                                CheckboxList::make('working_weekdays')
                                    ->label('Các thứ làm việc')
                                    ->options([
                                        1 => 'Thứ 2',
                                        2 => 'Thứ 3',
                                        3 => 'Thứ 4',
                                        4 => 'Thứ 5',
                                        5 => 'Thứ 6',
                                        6 => 'Thứ 7',
                                        7 => 'Chủ nhật',
                                    ])
                                    ->columns(4)
                                    ->visible(fn (callable $get): bool => $get('working_mode') === 'weekdays'),
                                Grid::make(2)
                                    ->schema([
                                        TimePicker::make('shift_start')
                                            ->label('Giờ bắt đầu')
                                            ->seconds(false),
                                        TimePicker::make('shift_end')
                                            ->label('Giờ kết thúc')
                                            ->seconds(false),
                                    ])
                                    ->visible(fn (callable $get): bool => $get('working_mode') === 'weekdays'),
                                Repeater::make('working_shifts')
                                    ->label('Ca theo thứ')
                                    ->schema([
                                        Select::make('weekday')
                                            ->label('Thứ')
                                            ->options([
                                                1 => 'Thứ 2',
                                                2 => 'Thứ 3',
                                                3 => 'Thứ 4',
                                                4 => 'Thứ 5',
                                                5 => 'Thứ 6',
                                                6 => 'Thứ 7',
                                                7 => 'Chủ nhật',
                                            ])
                                            ->required(),
                                        TimePicker::make('start')
                                            ->label('Bắt đầu')
                                            ->seconds(false)
                                            ->required(),
                                        TimePicker::make('end')
                                            ->label('Kết thúc')
                                            ->seconds(false)
                                            ->required(),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->visible(fn (callable $get): bool => $get('working_mode') === 'shifts'),
                                Repeater::make('working_dates')
                                    ->label('Ngày làm việc cụ thể')
                                    ->schema([
                                        DatePicker::make('date')
                                            ->label('Ngày')
                                            ->required(),
                                        TimePicker::make('start')
                                            ->label('Bắt đầu')
                                            ->seconds(false)
                                            ->required(),
                                        TimePicker::make('end')
                                            ->label('Kết thúc')
                                            ->seconds(false)
                                            ->required(),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->visible(fn (callable $get): bool => $get('working_mode') === 'specific_dates'),
                            ])
                            ->columns(1)
                            ->columnSpanFull(),
                        Toggle::make('status')
                            ->label('Đang làm việc')
                            ->default(true),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nhân viên')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Chi nhánh')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Vai trò')
                    ->searchable(),
                TextColumn::make('working_mode')
                    ->label('Rule')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'weekdays' => 'Theo thứ',
                        'shifts' => 'Theo ca',
                        'specific_dates' => 'Theo ngày',
                        default => 'Full-time',
                    }),
                TextColumn::make('services.name')
                    ->label('Skill')
                    ->badge()
                    ->separator(',')
                    ->limitList(4),
                ToggleColumn::make('status')
                    ->label('Kích hoạt'),
            ])
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Chi nhánh')
                    ->relationship('branch', 'name'),
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
            'index' => ListBeautyStaff::route('/'),
            'create' => CreateBeautyStaff::route('/create'),
            'edit' => EditBeautyStaff::route('/{record}/edit'),
        ];
    }
}
