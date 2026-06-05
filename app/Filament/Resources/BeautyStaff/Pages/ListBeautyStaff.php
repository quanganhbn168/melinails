<?php

namespace App\Filament\Resources\BeautyStaff\Pages;

use App\Filament\Resources\BeautyStaff\BeautyStaffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeautyStaff extends ListRecords
{
    protected static string $resource = BeautyStaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
