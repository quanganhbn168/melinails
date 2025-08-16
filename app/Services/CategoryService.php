<?php
namespace App\Services;
use App\Models\Category;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection; 
class CategoryService
{
    use UploadImageTrait;

    public function create(array $data): Category
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'uploads/categories', 800, 800);
        }
        if (isset($data['banner'])) {
            $data['banner'] = $this->uploadImage($data['banner'], 'uploads/categories', 1920, 600);
        }
        if (isset($data['meta_image'])) {
            $data['meta_image'] = $this->uploadImage($data['meta_image'], 'uploads/categories', 1200, 638);
        }
        return Category::create($data);
    }
    

    public function update(Category $category, array $data): bool
    {
        $attributes = Arr::pull($data, 'attributes', []);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        if (isset($data['image'])) {
            $this->deleteImage($category->image);
            $data['image'] = $this->uploadImage($data['image'], 'uploads/categories', 800, 800);
        }
        if (isset($data['banner'])) {
            $this->deleteImage($category->banner);
            $data['banner'] = $this->uploadImage($data['banner'], 'uploads/categories', 1920, 600);
        }
        if (isset($data['meta_image'])) {
            $this->deleteImage($category->meta_image);
            $data['meta_image'] = $this->uploadImage($data['meta_image'], 'uploads/categories', 1200, 638);
        }
        $category->attributes()->sync($attributes);
        return $category->update($data);
    }
    public function getCategoryOptions(): Collection
    {
        return Category::select('id', 'name', 'parent_id')->get();
    }
    public function delete(Category $category): ?bool
    {
        $this->deleteImage($category->image);
        $this->deleteImage($category->banner);
        $this->deleteImage($category->meta_image);
        return $category->delete();
    }
}