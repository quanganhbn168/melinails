<?php

namespace App\Filament\Resources\FieldCategories\Pages;

use App\Filament\Resources\FieldCategories\FieldCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFieldCategories extends ListRecords
{
    protected static string $resource = FieldCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
