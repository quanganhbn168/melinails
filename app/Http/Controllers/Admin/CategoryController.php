<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Attribute;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    protected $categoryService;

    // Sử dụng Dependency Injection để inject CategoryService vào controller
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Hiển thị danh sách các danh mục.
     */
    public function index()
    {
        // Lấy danh sách danh mục có phân trang
        $categories = Category::with('parent')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }
    /**
     * Hiển thị form để tạo mới category.
     */
    public function create()
    {
        $category = new Category();
        $categories = Category::all();
        $attributes = Attribute::all();

        return view('admin.categories.create', compact('category', 'categories', 'attributes'));
    }

    /**
     * Lưu category mới vào database.
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryService->store($request);

        // Giả sử bạn có route index để quay về danh sách
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Tạo danh mục thành công.');
    }

    /**
     * Hiển thị form để chỉnh sửa category.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        $selectedAttributes = $category->attributes()->pluck('id')->toArray();
        $attributes = Attribute::all();

        return view('admin.categories.edit', compact('category', 'categories', 'attributes', 'selectedAttributes'));
    }

    /**
     * Cập nhật category trong database.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->update($request, $category);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Cập nhật danh mục thành công.');
    }
    
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->destroy($category);
            return redirect()->route('admin.categories.index')
                             ->with('success', 'Xóa danh mục thành công.');
        } catch (\Exception $e) {
            // Nếu có lỗi (ví dụ: còn danh mục con), quay lại và báo lỗi
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}