<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use App\DataTables\ProductDataTable;
class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index(ProductDataTable $dataTable)
    {
        $products = Product::with('category')->latest()->paginate(15); 
        return view('admin.products.index', compact('products'));
    }
    public function create()
    {
        $categories = Category::pluck('name', 'id')->toArray();
        $brands = Brand::pluck('name', 'id')->toArray();
        return view('admin.products.create', compact('categories', 'brands'));
    }
    public function store(ProductRequest $request, ProductService $productService)
    {
        $product = $productService->store($request);
        return redirect()->route('admin.products.edit',$product->id)->with('success', 'Sản phẩm đã được tạo thành công.');
    }
    public function edit(Product $product)
    {
        $categories = Category::pluck('name', 'id')->toArray();
        $brands     = Brand::pluck('name', 'id')->toArray();
        $product->load([
            'category',
            'variants.attributeValues:id,attribute_id', 
            'specifications',
        ]);
        $specificationAttributes   = collect();
        $variantAttributes         = collect(); 
        $variantAttributeOptions   = [];        
        $selectedVariantAttributeIds = [];      
        if ($product->category) {
            $allAttrs = $product->category
                ->attributes()
                ->with('values:id,attribute_id,value')
                ->orderBy('name')
                ->get();
            $specificationAttributes = $allAttrs->where('is_variant_defining', false)->values();
            $variantAttributes       = $allAttrs->where('is_variant_defining', true)->values();
            $variantAttributeOptions = $variantAttributes->pluck('name', 'id')->toArray();
        }
        if ($product->relationLoaded('variants') && $product->variants->isNotEmpty()) {
            $selectedVariantAttributeIds = $product->variants
                ->flatMap(fn($v) => $v->attributeValues->pluck('attribute_id'))
                ->filter()
                ->unique()
                ->values()
                ->all();
        }
        $selectedVariantValues = [];
        if ($product->relationLoaded('variants') && $product->variants->isNotEmpty()) {
            foreach ($product->variants as $variant) {
                foreach ($variant->attributeValues as $value) {
                    $attrId = $value->attribute_id;
                    $valId = $value->id;

                    if (!isset($selectedVariantValues[$attrId])) {
                        $selectedVariantValues[$attrId] = [];
                    }
                    if (!in_array($valId, $selectedVariantValues[$attrId])) {
                        $selectedVariantValues[$attrId][] = $valId;
                    }
                }
            }
        }
        return view('admin.products.edit', compact(
            'product',
            'categories',
            'brands',
            'specificationAttributes',
            'variantAttributes',          
            'variantAttributeOptions',    
            'selectedVariantAttributeIds',
            'selectedVariantValues',
        ));
    }
    public function update(ProductRequest $request, Product $product, ProductService $productService)
    {
        $productService->update($request, $product);
        return redirect()->back()->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}