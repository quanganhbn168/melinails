<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use App\Models\Branch;
use App\Models\Service;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected array $branchSettings = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Service $service */
        $service = $this->record->load('branches');

        $data['branch_settings'] = Branch::query()
            ->orderBy('id')
            ->get(['id', 'name'])
            ->map(function (Branch $branch) use ($service): array {
                $branchService = $service->branches->firstWhere('id', $branch->id);
                $note = $branchService?->pivot?->availability_note;
                $noteText = null;

                if (is_string($note) && filled($note)) {
                    $decoded = json_decode($note, true);
                    $noteText = is_array($decoded) ? ($decoded['note'] ?? null) : $note;
                }

                return [
                    'branch_id' => $branch->id,
                    'branch_name' => $branch->name,
                    'is_available' => $branchService ? (bool) $branchService->pivot->is_available : true,
                    'price' => $branchService?->pivot?->price,
                    'price_text' => $branchService?->pivot?->price_text,
                    'duration_minutes' => $branchService?->pivot?->duration_minutes,
                    'availability_note' => $noteText,
                ];
            })
            ->values()
            ->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->branchSettings = $data['branch_settings'] ?? [];
        unset($data['branch_settings']);

        return $data;
    }

    protected function afterSave(): void
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
