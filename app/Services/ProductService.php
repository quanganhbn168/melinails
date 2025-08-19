<?php
namespace App\Services;

use App\Handlers\ImageGalleryHandler;
use App\Models\Product;
use App\Models\Category;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use App\Traits\UploadImageTrait;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\AttributeService;

class ProductService
{
    use UploadImageTrait;

    public function __construct(
        protected ImageGalleryHandler $imageGallery,
        protected AttributeService $attributeService
    ) {}

    public function getAll(): LengthAwarePaginator
    {
        return Product::with('category')->latest()->paginate(20);
    }
    
    public function getCategoryOptions(): array
    {
        return Category::select('id', 'name', 'parent_id')->get()->toArray();
    }
    
    public function getAttribute()
    {
        return $this->attributeService->getAttributesWithValues(20);
    }

    /**
     * Tạo một sản phẩm mới.
     *
     * @param ProductRequest $request
     * @return Product
     * @throws \Throwable
     */
    public function store(ProductRequest $request): Product
    {
        $data = $request->validated();
        return DB::transaction(function () use ($request, $data) {
            $data['slug'] = Str::slug($data['name']);
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'), 'uploads/products', 800, 800, true);
            }
            if ($request->hasFile('banner')) {
                $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/products', 1920, 600, true);
            }
            
            $productData = $data;
            // Loại bỏ các trường liên quan đến biến thể trước khi tạo sản phẩm
            unset($productData['variants'], $productData['variant_attribute_ids'], $productData['attribute_values']);
            
            $product = Product::create($productData);
            
            $this->imageGallery->sync($product, $request, 'gallery', 'uploads/products/gallery');
            
            if (isset($data['has_variants']) && $data['has_variants']) {
                $this->syncVariants($product, $data['variants'] ?? [], $data['attribute_values'] ?? []);
            } else {
                $product->variants()->create([
                    'sku' => $data['code'] ?? null,
                    'price' => $data['price_discount'] ?? 0,
                    'compare_price' => $data['price'] ?? 0,
                    'stock' => 0,
                    'is_default' => true,
                ]);
            }
            
            return $product;
        });
    }

    /**
     * Cập nhật thông tin một sản phẩm đã có.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return Product
     * @throws \Throwable
     */
    public function update(ProductRequest $request, Product $product): Product
    {
        $data = $request->validated();
        
        return DB::transaction(function () use ($request, $data, $product) {
            $productData = $data;
            // Loại bỏ các trường liên quan đến biến thể trước khi cập nhật sản phẩm
            unset($productData['variants'], $productData['variant_attribute_ids'], $productData['attribute_values']);
            
            $productData['slug'] ??= Str::slug($productData['name']);

            if ($request->hasFile('image')) {
                $this->deleteImage($product->image);
                $productData['image'] = $this->uploadImage($request->file('image'), 'uploads/products', 800, 800, true);
            }
            if ($request->hasFile('banner')) {
                $this->deleteImage($product->banner);
                $productData['banner'] = $this->uploadImage($request->file('banner'), 'uploads/products', 1920, 300, true);
            }
            
            $product->update($productData);
            
            $this->imageGallery->sync($product, $request, 'gallery', 'uploads/products/gallery', 800, 800, true);
            
            if (isset($data['has_variants']) && $data['has_variants']) {
                $this->syncVariants($product, $data['variants'] ?? [], $data['attribute_values'] ?? []);
            } else {
                $product->variants()->delete();
                $product->variants()->create([
                    'sku' => $productData['code'] ?? null,
                    'price' => $productData['price_discount'] ?? 0,
                    'compare_price' => $productData['price'] ?? 0,
                    'stock' => 0,
                    'is_default' => true,
                ]);
            }
            
            return $product;
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $this->deleteImage($product->image);
            $this->deleteImage($product->banner);
            $this->imageGallery->deleteAll($product);
            $product->delete();
        });
    }

    /**
     * Đồng bộ (tạo, cập nhật, xóa) các biến thể của sản phẩm.
     *
     * @param Product $product
     * @param array $variantsData
     * @param array $attributeValuesData
     */
    public function syncVariants(Product $product, array $variantsData, array $attributeValuesData)
    {
        // Lấy tất cả các ID của biến thể hiện có
        $existingVariantIds = $product->variants()->pluck('id')->toArray();
        $submittedVariantIds = [];

        foreach ($variantsData as $variantKey => $variantData) {
            $valueIds = explode('_', $variantKey);
            sort($valueIds);
            $valueIds = array_map('intval', $valueIds);
            
            // Tìm biến thể dựa trên ID hoặc tạo mới
            $variant = $product->variants()->updateOrCreate(
                ['id' => $variantData['id'] ?? null],
                [
                    'sku' => $variantData['sku'] ?? null,
                    'price' => $variantData['price'] ?? 0,
                    'compare_price' => $variantData['compare_price'] ?? 0,
                    'stock' => $variantData['stock'] ?? 0,
                    'is_default' => false,
                ]
            );

            $variant->attributeValues()->sync($valueIds);
            $submittedVariantIds[] = $variant->id;
        }

        $variantsToDelete = array_diff($existingVariantIds, $submittedVariantIds);
        if (!empty($variantsToDelete)) {
            $product->variants()->whereIn('id', $variantsToDelete)->delete();
        }
    }
}