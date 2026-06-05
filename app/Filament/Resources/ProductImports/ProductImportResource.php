<?php

namespace App\Filament\Resources\ProductImports;

use App\Filament\Resources\ProductImports\Pages\CreateProductImport;
use App\Filament\Resources\ProductImports\Pages\EditProductImport;
use App\Filament\Resources\ProductImports\Pages\ListProductImports;
use App\Filament\Resources\ProductImports\RelationManagers\AssetsRelationManager;
use App\Filament\Resources\ProductImports\RelationManagers\RowsRelationManager;
use App\Filament\Resources\ProductImports\Schemas\ProductImportForm;
use App\Filament\Resources\ProductImports\Tables\ProductImportsTable;
use App\Models\ProductImportBatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductImportResource extends Resource
{
    protected static ?string $model = ProductImportBatch::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;
    protected static ?string $navigationLabel = 'Import sản phẩm';
    protected static ?string $modelLabel = 'Import sản phẩm';
    protected static ?string $pluralModelLabel = 'Import sản phẩm';
    protected static string|UnitEnum|null $navigationGroup = 'Quản lý Hàng hóa';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ProductImportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductImportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RowsRelationManager::class,
            AssetsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductImports::route('/'),
            'create' => CreateProductImport::route('/create'),
            'edit' => EditProductImport::route('/{record}/edit'),
        ];
    }
}
