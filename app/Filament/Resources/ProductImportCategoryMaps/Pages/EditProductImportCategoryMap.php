<?php

namespace App\Filament\Resources\ProductImportCategoryMaps\Pages;

use App\Filament\Resources\ProductImportCategoryMaps\ProductImportCategoryMapResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductImportCategoryMap extends EditRecord
{
    protected static string $resource = ProductImportCategoryMapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
