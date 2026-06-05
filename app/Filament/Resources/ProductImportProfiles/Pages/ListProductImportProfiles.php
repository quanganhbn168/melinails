<?php

namespace App\Filament\Resources\ProductImportProfiles\Pages;

use App\Filament\Resources\ProductImportProfiles\ProductImportProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductImportProfiles extends ListRecords
{
    protected static string $resource = ProductImportProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
