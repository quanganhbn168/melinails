<?php

namespace App\Filament\Resources\FieldCategories\Pages;

use App\Filament\Resources\FieldCategories\FieldCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFieldCategory extends EditRecord
{
    protected static string $resource = FieldCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
