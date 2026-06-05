<?php

namespace App\Filament\Resources\BranchServices\Pages;

use App\Filament\Resources\BranchServices\BranchServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchService extends EditRecord
{
    protected static string $resource = BranchServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
