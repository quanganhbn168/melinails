<?php

namespace App\Filament\Resources\ProductImports\Pages;

use App\Filament\Resources\ProductImports\ProductImportResource;
use App\Models\ProductImportBatch;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateProductImport extends CreateRecord
{
    protected static string $resource = ProductImportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $disk = (string) ($data['disk'] ?? config('product_import.source_disk', 'local'));
        $path = is_array($data['stored_path'] ?? null)
            ? reset($data['stored_path'])
            : ($data['stored_path'] ?? null);

        $data['disk'] = $disk;
        $data['stored_path'] = $path;
        $data['original_filename'] = is_array($data['original_filename'] ?? null)
            ? reset($data['original_filename'])
            : ($data['original_filename'] ?? basename((string) $path));
        $data['source_hash'] = $path && Storage::disk($disk)->exists($path)
            ? hash_file('sha256', Storage::disk($disk)->path($path))
            : null;
        $data['status'] = ProductImportBatch::STATUS_PENDING;

        return $data;
    }
}
