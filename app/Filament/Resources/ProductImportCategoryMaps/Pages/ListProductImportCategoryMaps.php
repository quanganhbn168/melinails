<?php

namespace App\Filament\Resources\ProductImportCategoryMaps\Pages;

use App\Filament\Resources\ProductImportCategoryMaps\ProductImportCategoryMapResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductImportCategoryMaps extends ListRecords
{
    protected static string $resource = ProductImportCategoryMapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
