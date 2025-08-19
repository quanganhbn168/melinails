<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Str;
use App\Traits\UploadImageTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
class BrandService
{
    use UploadImageTrait;

    public function getAll()
    {
        return Brand::latest()->paginate(20);
    }

    public function create(array $data): Brand
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'uploads/brands', 250, 53);
        }
        return Brand::create($data);
    }

    public function update(Brand $brand, array $data): bool
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        if (isset($data['image'])) {
            $this->deleteImage($brand->image);
            $data['image'] = $this->uploadImage($data['image'], 'uploads/brands', 250, 53);
        }
        return $brand->update($data);
    }

    public function delete(Brand $brand): ?bool
    {
        return $brand->delete();
    }

    public function getById($id): ?Brand
    {
        return Brand::findOrFail($id);
    }

    public function searchAjax(string $query)
    {
        return Brand::where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name']);
    }
}
