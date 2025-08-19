<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component{
    use WithFileUploads, UploadImageTrait;

    //================================================================================
    // Model & Thuộc tính chính
    //================================================================================
    public ?Product $product;
    public $name, $slug, $description, $content, $category_id, $brand_id, $cate_type;
    public $status = true;
    public $slugStatus = 'unchecked';

    //================================================================================
    // State cho sản phẩm đơn giản
    //================================================================================
    public $sku, $price = '', $compare_at_price = '', $stock = 0;

    //================================================================================
    // State quản lý phần biến thể
    //================================================================================
    public $hasVariants = false;
    public $variantAttributes = [];
    public $generatedVariants = [];

    //================================================================================
    // State cho Ảnh & Gallery
    //================================================================================
    public $image, $existingImage;
    public $gallery = [], $existingGallery = [];

    //================================================================================
    // Dữ liệu cho các ô select
    //================================================================================
    public Collection $allCategories;
    public $allBrands = [];
    public $allVariantDefiningAttributes = [];

    //================================================================================
    // Validation Rules
    //================================================================================
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . ($this->product->id ?? null),
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'boolean',
            'cate_type' => 'required|string|in:physics,services|not_in:0',
            'image' => 'nullable|image|max:2048',
        ];

        if (!$this->hasVariants) {
            $rules['sku'] = 'required|string|max:255';
            $rules['price'] = 'required|numeric|min:0|lte:compare_at_price';
            $rules['compare_at_price'] = 'nullable|numeric|min:0';
            $rules['stock'] = 'required|integer|min:0';
        }
        
        return $rules;
    }

    protected $messages = [
        'cate_type.not_in' => 'Vui lòng chọn Loại sản phẩm.',
        'price.lte' => 'Giá bán phải nhỏ hơn hoặc bằng Giá so sánh.',
    ];

    //================================================================================
    // Vòng đời Component
    //================================================================================
    public function mount(?Product $product = null)
    {
        $this->product = $product;
        $this->allBrands = Brand::all();

        if ($this->product && $this->product->exists) {
            $this->fill($this->product->toArray());
            $this->cate_type = $this->product->type;
            $this->price = $this->product->price;
            $this->compare_at_price = $this->product->price_discount;
            $this->stock = $this->product->stock;
            $this->sku = $this->product->code;
            $this->existingImage = $this->product->image;
            $this->allCategories = Category::where('cate_type', $this->cate_type)->get();
            if ($this->product->variants()->count() > 0) {
                $this->hasVariants = true;
            }
        } else {
            $this->product = new Product();
            $this->allCategories = collect();
        }
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
        if (empty($this->slug)) { 
            $this->slugStatus = 'unchecked'; 
            return; 
        }
        $query = Product::where('slug', $this->slug);
        if ($this->product && $this->product->exists) { 
            $query->where('id', '!=', $this->product->id); 
        }
        $this->slugStatus = $query->exists() ? 'invalid' : 'valid';
    }

    public function updatedCateType($value)
    {
        $this->allCategories = $value && $value != '0' ? Category::where('cate_type', $value)->get() : collect();
        $this->category_id = null;
        $this->dispatch('categories-updated', categories: $this->categoryTree);
    }

    public function getCategoryTreeProperty(): array
    {
        return $this->buildCategoryTree($this->allCategories);
    }
    
    private function buildCategoryTree(Collection $categories, $parentId = 0, $prefix = ''): array
    {
        $tree = [];
        $items = $categories->where('parent_id', $parentId);
        foreach ($items as $item) {
            $tree[$item->id] = $prefix . ' ' . $item->name;
            $children = $this->buildCategoryTree($categories, $item->id, $prefix . '—');
            $tree = array_merge($tree, $children);
        }
        return $tree;
    }
    
    public function save()
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
        
        $validatedData = $this->validate();

        if ($this->image) {
            if ($this->product->exists && $this->existingImage) {
                $this->deleteImage($this->existingImage);
            }
            $validatedData['image'] = $this->uploadImage($this->image, 'products');
        }

        $productData = [
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'category_id' => $validatedData['category_id'],
            'brand_id' => $validatedData['brand_id'],
            'description' => $validatedData['description'],
            'content' => $validatedData['content'],
            'status' => $validatedData['status'],
            'type' => $validatedData['cate_type'],
            'image' => $validatedData['image'] ?? $this->existingImage,
        ];
        
        if (!$this->hasVariants) {
            $productData['product_type'] = 'simple';
            $productData['code'] = $this->sku;
            $productData['price'] = $this->price;
            $productData['price_discount'] = $this->compare_at_price;
            $productData['stock'] = $this->stock;
        } else {
            $productData['product_type'] = 'variable';
        }

        $this->product->fill($productData)->save();

        session()->flash('success', $this->product->wasRecentlyCreated ? 'Tạo sản phẩm thành công!' : 'Cập nhật sản phẩm thành công!');
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.product-form');
    }
}