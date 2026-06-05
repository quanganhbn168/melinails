<?php

namespace App\Filament\Resources\ProductImports\Pages;

use App\Filament\Resources\ProductImports\ProductImportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductImports extends ListRecords
{
    protected static string $resource = ProductImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
