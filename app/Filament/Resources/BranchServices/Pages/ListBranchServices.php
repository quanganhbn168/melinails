<?php

namespace App\Filament\Resources\BranchServices\Pages;

use App\Filament\Resources\BranchServices\BranchServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchServices extends ListRecords
{
    protected static string $resource = BranchServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
