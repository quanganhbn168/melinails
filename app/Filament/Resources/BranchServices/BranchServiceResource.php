<?php

namespace App\Filament\Resources\BranchServices;

use App\Filament\Resources\BranchServices\Pages\CreateBranchService;
use App\Filament\Resources\BranchServices\Pages\EditBranchService;
use App\Filament\Resources\BranchServices\Pages\ListBranchServices;
use App\Models\BranchService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

class BranchServiceResource extends Resource
{
    protected static ?string $model = BranchService::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Shop & Booking';
    }

    public static function getModelLabel(): string
    {
        return 'Dịch vụ theo chi nhánh';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Dịch vụ theo chi nhánh';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Chi nhánh & dịch vụ')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('branch_id')
                                    ->label('Chi nhánh')
                                    ->relationship('branch', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('service_id')
                                    ->label('Dịch vụ')
                                    ->relationship('service', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('price')
                                    ->label('Giá số')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('Kč'),
                                TextInput::make('price_text')
                                    ->label('Giá hiển thị')
                                    ->placeholder('od 450 Kč / dle hodnoty')
                                    ->maxLength(255),
                                TextInput::make('duration_minutes')
                                    ->label('Thời lượng')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('phút'),
                                Toggle::make('is_available')
                                    ->label('Có ở chi nhánh này')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->label('Chi nhánh')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Dịch vụ')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service.category.name')
                    ->label('Danh mục')
                    ->badge(),
                TextColumn::make('price_text')
                    ->label('Giá hiển thị')
                    ->getStateUsing(fn (BranchService $record): string => $record->price_text ?: ($record->price ? number_format($record->price, 0, ',', ' ') . ' Kč' : '-')),
                TextColumn::make('duration_minutes')
                    ->label('Phút')
                    ->suffix(' min'),
                ToggleColumn::make('is_available')
                    ->label('Available'),
            ])
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Chi nhánh')
                    ->relationship('branch', 'name'),
                SelectFilter::make('service_id')
                    ->label('Dịch vụ')
                    ->relationship('service', 'name'),
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
            'index' => ListBranchServices::route('/'),
            'create' => CreateBranchService::route('/create'),
            'edit' => EditBranchService::route('/{record}/edit'),
        ];
    }
}
