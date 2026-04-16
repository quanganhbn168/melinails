<?php

namespace App\Filament\Resources\Attributes\Pages;

use App\Filament\Resources\Attributes\AttributeResource;
use App\Models\Attribute;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Throwable;

class ListAttributes extends ListRecords
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('quickImportAttributes')
                ->label('Import nhanh thuộc tính')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->slideOver()
                ->modalWidth('4xl')
                ->form([
                    Textarea::make('payload')
                        ->label('JSON thuộc tính')
                        ->rows(22)
                        ->placeholder($this->getImportPayloadExample())
                        ->required()
                        ->helperText('Dữ liệu sẽ upsert theo tên thuộc tính. Giá trị mới sẽ được thêm nếu chưa có, không xóa giá trị cũ.'),
                ])
                ->action(function (array $data): void {
                    try {
                        $result = DB::transaction(function () use ($data) {
                            return $this->importAttributesFromPayload((string) ($data['payload'] ?? ''));
                        });

                        Notification::make()
                            ->title('Import thuộc tính thành công')
                            ->body("Đã tạo {$result['created_attributes']} thuộc tính mới, cập nhật {$result['updated_attributes']} thuộc tính, thêm {$result['created_values']} giá trị.")
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Import thất bại')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }

    private function importAttributesFromPayload(string $payload): array
    {
        $decoded = json_decode($payload, true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('JSON không hợp lệ. Vui lòng kiểm tra lại format.');
        }

        $items = $decoded['attributes'] ?? $decoded;
        if (! is_array($items) || empty($items)) {
            throw new \RuntimeException('JSON phải chứa danh sách thuộc tính trong key "attributes" hoặc mảng gốc.');
        }

        $allowedTypes = ['button', 'dropdown', 'color_swatch'];
        $createdAttributes = 0;
        $updatedAttributes = 0;
        $createdValues = 0;

        foreach ($items as $index => $item) {
            if (! is_array($item)) {
                throw new \RuntimeException('Mỗi phần tử thuộc tính phải là object. Lỗi tại vị trí: ' . ($index + 1));
            }

            $name = trim((string) ($item['name'] ?? ''));
            if ($name === '') {
                throw new \RuntimeException('Thiếu "name" cho thuộc tính tại vị trí: ' . ($index + 1));
            }

            $type = (string) ($item['type'] ?? 'button');
            if (! in_array($type, $allowedTypes, true)) {
                throw new \RuntimeException("Type '{$type}' không hợp lệ tại '{$name}'. Chỉ chấp nhận: button, dropdown, color_swatch.");
            }

            $isVariantDefining = (bool) ($item['is_variant_defining'] ?? true);
            $values = $item['values'] ?? [];

            if (! is_array($values)) {
                throw new \RuntimeException("Trường values của '{$name}' phải là mảng.");
            }

            $normalizedValues = collect($values)
                ->map(fn ($value) => trim((string) $value))
                ->filter(fn ($value) => $value !== '')
                ->unique()
                ->values();

            $attribute = Attribute::query()->where('name', $name)->first();
            if (! $attribute) {
                $attribute = Attribute::query()->create([
                    'name' => $name,
                    'type' => $type,
                    'is_variant_defining' => $isVariantDefining,
                ]);
                $createdAttributes++;
            } else {
                $attribute->update([
                    'type' => $type,
                    'is_variant_defining' => $isVariantDefining,
                ]);
                $updatedAttributes++;
            }

            $existingValues = $attribute->values()->pluck('value')->map(fn ($v) => trim((string) $v))->toArray();
            foreach ($normalizedValues as $value) {
                if (! in_array($value, $existingValues, true)) {
                    $attribute->values()->create(['value' => $value]);
                    $createdValues++;
                }
            }
        }

        return [
            'created_attributes' => $createdAttributes,
            'updated_attributes' => $updatedAttributes,
            'created_values' => $createdValues,
        ];
    }

    private function getImportPayloadExample(): string
    {
        return <<<JSON
{
  "attributes": [
    {
      "name": "Độ phân giải",
      "type": "dropdown",
      "is_variant_defining": true,
      "values": ["2MP", "3MP", "4MP", "5MP", "8MP (4K)"]
    }
  ]
}
JSON;
    }
}
