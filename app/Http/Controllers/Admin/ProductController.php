<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        // Tốt nhất là dùng Yajra DataTable ở đây
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // Giai đoạn 1: Form đơn giản
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(ProductRequest $request)
    {
        // Giai đoạn 1: Chỉ tạo sản phẩm cha
        $product = $this->productService->createProduct($request->validated());

        // Chuyển hướng ngay đến trang sửa để hoàn thiện (Giai đoạn 2)
        return redirect()->route('admin.products.edit', $product)->with('success', 'Tạo sản phẩm thành công! Giờ hãy hoàn thiện các thông tin chi tiết.');
    }

    public function edit(Product $product)
    {
        // Giai đoạn 2: Giao diện Tab hoàn chỉnh
        $categories = Category::all();
        $brands = Brand::all();
        // Lấy thuộc tính LỌC dựa trên danh mục của sản phẩm
        $specificationAttributes = $product->category->attributes()->where('is_variant_defining', false)->get();
        // Lấy thuộc tính TẠO BIẾN THỂ
        $variantAttributes = Attribute::where('is_variant_defining', true)->with('values')->get();
        
        $product->load(['specifications', 'variants.attributeValues']);

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'specificationAttributes', 'variantAttributes'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->productService->updateProduct($product, $request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        // Giả sử bạn sẽ thêm hàm delete vào service
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}