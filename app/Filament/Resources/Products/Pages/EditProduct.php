<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Attribute;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $categoryId = (int) ($data['category_id'] ?? 0);
        $hasVariants = (bool) ($data['has_variants'] ?? false);

        if ($hasVariants && ! $this->categoryHasVariantAttributes($categoryId)) {
            throw ValidationException::withMessages([
                'has_variants' => 'Danh mục hiện tại chưa có thuộc tính dùng cho biến thể. Vui lòng cấu hình danh mục trước.',
            ]);
        }

        return $data;
    }

    protected function categoryHasVariantAttributes(int $categoryId): bool
    {
        if (! $categoryId) {
            return false;
        }

        return Attribute::query()
            ->where('is_variant_defining', true)
            ->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryId))
            ->exists();
    }
}
