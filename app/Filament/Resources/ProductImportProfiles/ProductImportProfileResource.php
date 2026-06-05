<?php

namespace App\Filament\Resources\ProductImportProfiles;

use App\Filament\Resources\ProductImportProfiles\Pages\CreateProductImportProfile;
use App\Filament\Resources\ProductImportProfiles\Pages\EditProductImportProfile;
use App\Filament\Resources\ProductImportProfiles\Pages\ListProductImportProfiles;
use App\Models\ProductImportProfile;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProductImportProfileResource extends Resource
{
    protected static ?string $model = ProductImportProfile::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;
    protected static ?string $navigationLabel = 'Profile import';
    protected static ?string $modelLabel = 'Profile import';
    protected static ?string $pluralModelLabel = 'Profile import';
    protected static string|UnitEnum|null $navigationGroup = 'Quản lý Hàng hóa';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên profile')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('vendor')
                            ->label('Nhà cung cấp')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                        Textarea::make('description')
                            ->label('Ghi chú')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Mapping JSON')
                    ->description('Ví dụ: {"default":{"start_row":2,"code":"B","name":"D","price":"H"},"sheets":{"Cam IP":{"category_path":"Camera / Camera IP"}}}')
                    ->schema([
                        Textarea::make('column_map')
                            ->label('Column map')
                            ->formatStateUsing(fn ($state): string => is_array($state) ? json_encode($state, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : (string) $state)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? json_decode((string) $state, true) : null)
                            ->rules(['nullable', 'json'])
                            ->rows(10)
                            ->columnSpanFull(),
                        Textarea::make('options')
                            ->label('Options')
                            ->formatStateUsing(fn ($state): string => is_array($state) ? json_encode($state, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : (string) $state)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? json_decode((string) $state, true) : null)
                            ->rules(['nullable', 'json'])
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('vendor')
                    ->label('Nhà cung cấp')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => ListProductImportProfiles::route('/'),
            'create' => CreateProductImportProfile::route('/create'),
            'edit' => EditProductImportProfile::route('/{record}/edit'),
        ];
    }
}
