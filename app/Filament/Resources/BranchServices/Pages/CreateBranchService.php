<?php

namespace App\Filament\Resources\BranchServices\Pages;

use App\Filament\Resources\BranchServices\BranchServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchService extends CreateRecord
{
    protected static string $resource = BranchServiceResource::class;
}
