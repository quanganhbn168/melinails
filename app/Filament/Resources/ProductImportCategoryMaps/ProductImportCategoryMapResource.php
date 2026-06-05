<?php

namespace App\Filament\Resources\ProductImportCategoryMaps;

use App\Filament\Resources\ProductImportCategoryMaps\Pages\CreateProductImportCategoryMap;
use App\Filament\Resources\ProductImportCategoryMaps\Pages\EditProductImportCategoryMap;
use App\Filament\Resources\ProductImportCategoryMaps\Pages\ListProductImportCategoryMaps;
use App\Models\ProductImportCategoryMap;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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

class ProductImportCategoryMapResource extends Resource
{
    protected static ?string $model = ProductImportCategoryMap::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;
    protected static ?string $navigationLabel = 'Map danh mục import';
    protected static ?string $modelLabel = 'Map danh mục import';
    protected static ?string $pluralModelLabel = 'Map danh mục import';
    protected static string|UnitEnum|null $navigationGroup = 'Quản lý Hàng hóa';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Nguồn trong Excel')
                    ->schema([
                        Select::make('product_import_profile_id')
                            ->label('Profile')
                            ->relationship('profile', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Để trống nếu mapping dùng chung cho mọi profile.'),
                        Select::make('source_type')
                            ->label('Loại nguồn')
                            ->options([
                                'sheet' => 'Tên sheet',
                                'header' => 'Tiêu đề trong sheet',
                                'cell' => 'Giá trị ô',
                                'text' => 'Text bất kỳ',
                            ])
                            ->default('sheet')
                            ->required(),
                        TextInput::make('source_value')
                            ->label('Giá trị nguồn')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('priority')
                            ->label('Ưu tiên')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
                Section::make('Danh mục đích')
                    ->schema([
                        Select::make('category_id')
                            ->label('Danh mục có sẵn')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('category_path')
                            ->label('Category path')
                            ->placeholder('Camera / Camera IP'),
                        Toggle::make('auto_create')
                            ->label('Tự tạo category path nếu chưa có')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profile.name')
                    ->label('Profile')
                    ->placeholder('Dùng chung')
                    ->toggleable(),
                TextColumn::make('source_type')
                    ->label('Loại')
                    ->badge(),
                TextColumn::make('source_value')
                    ->label('Nguồn')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->placeholder('Theo path'),
                TextColumn::make('category_path')
                    ->label('Path')
                    ->limit(36),
                TextColumn::make('priority')
                    ->label('Ưu tiên')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('auto_create')
                    ->label('Tự tạo')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->boolean(),
            ])
            ->defaultSort('priority', 'desc')
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
            'index' => ListProductImportCategoryMaps::route('/'),
            'create' => CreateProductImportCategoryMap::route('/create'),
            'edit' => EditProductImportCategoryMap::route('/{record}/edit'),
        ];
    }
}
