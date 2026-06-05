<?php

namespace App\Filament\Resources\ServiceCategories\Pages;

use App\Filament\Resources\ServiceCategories\ServiceCategoryResource;
use App\Models\ServiceCategory;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceCategory extends CreateRecord
{
    protected static string $resource = ServiceCategoryResource::class;

    protected array $branchCategorySettings = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->branchCategorySettings = $data['branch_category_settings'] ?? [];
        unset($data['branch_category_settings']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncBranchCategorySettings($this->record, $this->branchCategorySettings);
    }

    protected function syncBranchCategorySettings(ServiceCategory $category, array $settings): void
    {
        $sync = collect($settings)
            ->filter(fn (array $row) => filled($row['branch_id'] ?? null))
            ->mapWithKeys(fn (array $row) => [
                (int) $row['branch_id'] => [
                    'is_available' => (bool) ($row['is_available'] ?? false),
                ],
            ])
            ->all();

        $category->branches()->sync($sync);
    }
}
