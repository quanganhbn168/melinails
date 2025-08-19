<?php

namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
class CategoryService
{
    // Sử dụng Trait để xử lý upload ảnh đã cung cấp trước đó
    use UploadImageTrait;

    /**
     * Tạo một danh mục mới.
     *
     * @param CategoryRequest $request Dữ liệu đã được validate.
     * @return Category Danh mục vừa được tạo.
     */
    public function store(CategoryRequest $request): Category
    {
        // Bọc trong transaction để đảm bảo toàn vẹn dữ liệu
        return DB::transaction(function () use ($request) {
            $category = new Category();
            $this->saveCategoryData($category, $request);
            return $category;
        });
    }

    /**
     * Cập nhật một danh mục đã có.
     *
     * @param CategoryRequest $request Dữ liệu đã được validate.
     * @param Category $category Danh mục cần cập nhật.
     * @return Category Danh mục vừa được cập nhật.
     */
    public function update(CategoryRequest $request, Category $category): Category
    {
        return DB::transaction(function () use ($request, $category) {
            $this->saveCategoryData($category, $request);
            return $category;
        });
    }

    /**
     * Phương thức private chứa logic chung cho việc lưu dữ liệu Category.
     *
     * @param Category $category Instance của Category (mới hoặc đã có).
     * @param CategoryRequest $request Dữ liệu từ request.
     */
    private function saveCategoryData(Category $category, CategoryRequest $request): void
    {
        // Lấy tất cả dữ liệu đã được validate từ FormRequest
        $validatedData = $request->validated();

        // Tự động tạo slug nếu người dùng không nhập
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        // Xử lý upload các file ảnh (image, icon, banner)
        $imageFields = ['image', 'icon', 'banner'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Quan trọng: Nếu là update và đã có ảnh cũ, hãy xóa nó đi
                if ($category->{$field}) {
                    $this->deleteImage($category->{$field});
                }
                // Upload ảnh mới và gán đường dẫn vào dữ liệu
                $validatedData[$field] = $this->uploadImage($request->file($field), 'categories');
            }
        }

        // Gán dữ liệu vào model và lưu
        $category->fill($validatedData)->save();

        // Đồng bộ hóa các thuộc tính (quan hệ nhiều-nhiều)
        // sync() là phương thức tối ưu, nó sẽ tự động thêm/xóa các mối quan hệ cần thiết
        if ($request->has('attributes')) {
            $category->attributes()->sync($validatedData['attributes']);
        } else {
            // Nếu không có thuộc tính nào được gửi lên, xóa tất cả các mối quan hệ cũ
            $category->attributes()->sync([]);
        }
    }

    public function destroy(Category $category): bool
    {
        // 1. Kiểm tra xem danh mục có danh mục con không
        if ($category->children()->count() > 0) {
            throw new Exception('Không thể xóa danh mục này vì nó chứa danh mục con.');
        }

        // 2. Kiểm tra xem danh mục có sản phẩm nào không
        if ($category->products()->count() > 0) {
            throw new Exception('Không thể xóa danh mục này vì nó chứa sản phẩm.');
        }

        return DB::transaction(function () use ($category) {
            // 3. Xóa các ảnh liên quan
            $imageFields = ['image', 'icon', 'banner'];
            foreach ($imageFields as $field) {
                if ($category->{$field}) {
                    $this->deleteImage($category->{$field});
                }
            }

            // 4. Xóa bản ghi
            return $category->delete();
        });
    }
}