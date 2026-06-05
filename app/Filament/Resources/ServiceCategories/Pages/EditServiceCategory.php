<?php

namespace App\Filament\Resources\ServiceCategories\Pages;

use App\Filament\Resources\ServiceCategories\ServiceCategoryResource;
use App\Models\Branch;
use App\Models\ServiceCategory;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceCategory extends EditRecord
{
    protected static string $resource = ServiceCategoryResource::class;

    protected array $branchCategorySettings = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var ServiceCategory $category */
        $category = $this->record->load('branches');

        $data['branch_category_settings'] = Branch::query()
            ->orderBy('id')
            ->get(['id', 'name'])
            ->map(function (Branch $branch) use ($category): array {
                $branchCategory = $category->branches->firstWhere('id', $branch->id);

                return [
                    'branch_id' => $branch->id,
                    'branch_name' => $branch->name,
                    'is_available' => $branchCategory ? (bool) $branchCategory->pivot->is_available : true,
                ];
            })
            ->values()
            ->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->branchCategorySettings = $data['branch_category_settings'] ?? [];
        unset($data['branch_category_settings']);

        return $data;
    }

    protected function afterSave(): void
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
