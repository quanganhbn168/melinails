<?php

namespace App\Filament\Resources\BeautyStaff\Pages;

use App\Filament\Resources\BeautyStaff\BeautyStaffResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBeautyStaff extends EditRecord
{
    protected static string $resource = BeautyStaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
