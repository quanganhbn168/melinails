<?php

namespace App\Filament\Resources\FieldCategories;

use App\Filament\Resources\FieldCategories\Pages\CreateFieldCategory;
use App\Filament\Resources\FieldCategories\Pages\EditFieldCategory;
use App\Filament\Resources\FieldCategories\Pages\ListFieldCategories;
use App\Filament\Resources\FieldCategories\Schemas\FieldCategoryForm;
use App\Filament\Resources\FieldCategories\Tables\FieldCategoriesTable;
use App\Models\FieldCategory;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FieldCategoryResource extends Resource
{
    protected static ?string $model = FieldCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Lĩnh vực & Dịch vụ';
    protected static ?string $navigationLabel = 'Danh mục Lĩnh vực';
    protected static ?string $modelLabel = 'Danh mục Lĩnh vực';
    protected static ?string $pluralModelLabel = 'Danh mục Lĩnh vực';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FieldCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FieldCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFieldCategories::route('/'),
            'create' => CreateFieldCategory::route('/create'),
            'edit' => EditFieldCategory::route('/{record}/edit'),
        ];
    }
}
