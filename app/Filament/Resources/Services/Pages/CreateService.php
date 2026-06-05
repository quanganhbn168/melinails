<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use App\Models\Service;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected array $branchSettings = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->branchSettings = $data['branch_settings'] ?? [];
        unset($data['branch_settings']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncBranchSettings($this->record, $this->branchSettings);
    }

    protected function syncBranchSettings(Service $service, array $settings): void
    {
        $sync = collect($settings)
            ->filter(fn (array $row) => filled($row['branch_id'] ?? null))
            ->mapWithKeys(fn (array $row) => [
                (int) $row['branch_id'] => [
                    'price' => filled($row['price'] ?? null) ? (int) $row['price'] : null,
                    'price_text' => filled($row['price_text'] ?? null) ? $row['price_text'] : null,
                    'duration_minutes' => filled($row['duration_minutes'] ?? null) ? (int) $row['duration_minutes'] : null,
                    'is_available' => (bool) ($row['is_available'] ?? false),
                    'availability_note' => filled($row['availability_note'] ?? null) ? json_encode(['note' => $row['availability_note']], JSON_UNESCAPED_UNICODE) : null,
                ],
            ])
            ->all();

        $service->branches()->sync($sync);
    }
}
