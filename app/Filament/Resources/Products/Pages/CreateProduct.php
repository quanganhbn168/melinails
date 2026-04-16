<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Attribute;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
