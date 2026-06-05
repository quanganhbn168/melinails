<?php

namespace App\Filament\Resources\ProductImportProfiles\Pages;

use App\Filament\Resources\ProductImportProfiles\ProductImportProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductImportProfile extends EditRecord
{
    protected static string $resource = ProductImportProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
