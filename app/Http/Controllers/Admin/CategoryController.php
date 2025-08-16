<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Attribute;
use App\Services\CategoryService;


class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = Category::latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = $this->categoryService->getCategoryOptions();
        $attributes = Attribute::where('is_variant_defining', false)->get();
        return view('admin.categories.create', compact('attributes','categories'));
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Tạo danh mục thành công!');
    }

    public function edit(Category $category)
    {
        $categories = $this->categoryService->getCategoryOptions();
        $attributes = Attribute::where('is_variant_defining', false)->get();
        $category->load('attributes');
        return view('admin.categories.edit', compact('category', 'attributes','categories'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->update($category, $request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Category $category)
    {
        // Giả sử bạn có hàm delete trong service
        // $this->categoryService->delete($category);
        $category->delete(); // Hoặc xóa trực tiếp nếu logic đơn giản
        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}