<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class ProductImportController extends Controller
{
    public function index()
    {
        return view('product-import');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'brand' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:zip'],
        ]);

        $brand = Str::slug($request->input('brand'));
        $sessionId = (string) Str::uuid();

        $basePath = storage_path("app/imports/tmp/{$sessionId}");
        File::ensureDirectoryExists($basePath);

        $request->file('file')->move($basePath, 'upload.zip');

        $zipPath = $basePath . '/upload.zip';

        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            return response()->json([
                'message' => 'Không mở được file ZIP.',
            ], 422);
        }

        $zip->extractTo($basePath);
        $zip->close();

        $jsonPath = $this->findProductsJson($basePath);

        if (! $jsonPath) {
            return response()->json([
                'message' => 'Không tìm thấy products.json trong file ZIP.',
            ], 422);
        }

        $payload = json_decode(File::get($jsonPath), true);

        if (! is_array($payload)) {
            return response()->json([
                'message' => 'products.json không hợp lệ.',
            ], 422);
        }

        /*
         * Hỗ trợ 2 kiểu JSON:
         *
         * Kiểu 1:
         * [
         *   { product... }
         * ]
         *
         * Kiểu 2:
         * {
         *   "metadata": {...},
         *   "products": [
         *      { product... }
         *   ]
         * }
         */
        $rawProducts = $payload['products'] ?? $payload;
        $metadata = $payload['metadata'] ?? [];

        if (! is_array($rawProducts)) {
            return response()->json([
                'message' => 'Không tìm thấy danh sách products trong products.json.',
            ], 422);
        }

        $products = collect($rawProducts)
            ->filter(fn ($item) => is_array($item))
            ->map(function ($item) use ($sessionId, $brand) {
                return $this->normalizeProductForPreview($item, $sessionId, $brand);
            })
            ->values();

        $summary = [
            'brand' => $brand,
            'source_file' => $metadata['source_file'] ?? null,
            'total_products' => $products->count(),
            'products_with_images' => $products->filter(fn ($item) => count($item['images'] ?? []) > 0)->count(),
            'products_without_images' => $products->filter(fn ($item) => count($item['images'] ?? []) === 0)->count(),
            'total_images' => $products->sum(fn ($item) => count($item['images'] ?? [])),
            'products_with_specs' => $products->filter(fn ($item) => filled($item['specifications'] ?? null))->count(),
            'products_without_specs' => $products->filter(fn ($item) => blank($item['specifications'] ?? null))->count(),
        ];

        File::put($basePath . '/preview.json', json_encode([
            'brand' => $brand,
            'metadata' => $metadata,
            'summary' => $summary,
            'products' => $products,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return response()->json([
            'session_id' => $sessionId,
            'summary' => $summary,
            'products' => $products->take(100)->values(),
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'session_id' => ['required', 'string'],
            'brand' => ['required', 'string', 'max:100'],
            'only_has_image' => ['nullable'],
            'only_has_specs' => ['nullable'],
        ]);

        $sessionId = $request->input('session_id');
        $brand = Str::slug($request->input('brand'));

        $onlyHasImage = filter_var($request->input('only_has_image'), FILTER_VALIDATE_BOOLEAN);
        $onlyHasSpecs = filter_var($request->input('only_has_specs'), FILTER_VALIDATE_BOOLEAN);

        $basePath = storage_path("app/imports/tmp/{$sessionId}");
        $previewPath = $basePath . '/preview.json';

        if (! File::exists($previewPath)) {
            return response()->json([
                'message' => 'Không tìm thấy phiên preview. Anh đọc dữ liệu lại nhé.',
            ], 404);
        }

        $payload = json_decode(File::get($previewPath), true);

        if (! is_array($payload)) {
            return response()->json([
                'message' => 'File preview.json không hợp lệ.',
            ], 422);
        }

        $products = $payload['products'] ?? [];

        $imported = 0;
        $updated = 0;
        $created = 0;
        $skipped = 0;
        $missingImages = 0;

        foreach ($products as $item) {
            $name = trim((string) ($item['name'] ?? ''));

            if ($name === '') {
                $skipped++;
                continue;
            }

            $images = $item['images'] ?? [];
            $specifications = $item['specifications'] ?? null;

            if ($onlyHasImage && count($images) === 0) {
                $skipped++;
                continue;
            }

            if ($onlyHasSpecs && blank($specifications)) {
                $skipped++;
                continue;
            }

            $code = $this->cleanValue($item['code'] ?? null);

            $savedImages = [];

            foreach ($images as $image) {
                $oldRelativePath = $image['file'] ?? null;

                if (! $oldRelativePath) {
                    continue;
                }

                $safeOldRelativePath = $this->sanitizeRelativePath($oldRelativePath);
                $sourcePath = $basePath . '/' . $safeOldRelativePath;

                if (! File::exists($sourcePath)) {
                    $missingImages++;
                    continue;
                }

                /*
                 * JSON cũ:
                 * images/dahua/camera-ip/abc.png
                 *
                 * Public mới:
                 * public/images/products/dahua/camera-ip/abc.png
                 */
                $pathWithoutImagesPrefix = preg_replace('/^images\//', '', $safeOldRelativePath);
                $newRelativePath = 'images/products/' . $brand . '/' . $pathWithoutImagesPrefix;
                $destinationPath = public_path($newRelativePath);

                File::ensureDirectoryExists(dirname($destinationPath));
                File::copy($sourcePath, $destinationPath);

                $savedImages[] = [
                    'file' => $newRelativePath,
                    'url' => asset($newRelativePath),
                    'anchor_cell' => $image['anchor_cell'] ?? null,
                    'source_media' => $image['source_media'] ?? null,
                ];
            }

            $mainImage = $savedImages[0]['file'] ?? null;

            $price = $this->normalizeNumber($item['price'] ?? null);
            $retailPrice = $this->normalizeNumber($item['retail_price'] ?? null);

            $lookup = [
                'code' => $code,
                'name' => $name,
            ];

            /*
             * Nếu code rỗng, tránh unique lookup bị null quá rộng.
             */
            if (! $code) {
                $lookup = [
                    'slug' => $this->makeStableSlug($brand, null, $name),
                ];
            }

            $exists = Product::where($lookup)->exists();

            Product::updateOrCreate(
                $lookup,
                [
                    'slug' => $this->makeUniqueSlug($brand, $code, $name, $lookup),
                    'brand' => $brand,
                    'sheet' => $this->cleanValue($item['sheet'] ?? null),
                    'category' => $this->cleanValue($item['category'] ?? null),

                    'price' => $price,
                    'retail_price' => $retailPrice,

                    'warranty' => $this->cleanValue($item['warranty'] ?? null),
                    'status' => $this->cleanValue($item['status'] ?? null),

                    'short_description' => $this->makeShortDescription($item, $brand),
                    'description' => $this->makeDescription($item, $brand),
                    'specifications' => $specifications,

                    'image' => $mainImage,
                    'images' => $savedImages,
                    'raw' => $item['raw'] ?? $item,
                    'is_active' => true,
                ]
            );

            $imported++;

            if ($exists) {
                $updated++;
            } else {
                $created++;
            }
        }

        return response()->json([
            'imported' => $imported,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'missing_images' => $missingImages,
        ]);
    }

    public function previewImage(Request $request, string $sessionId)
    {
        $path = $request->query('path');

        if (! $path) {
            abort(404);
        }

        $safePath = $this->sanitizeRelativePath($path);
        $fullPath = storage_path("app/imports/tmp/{$sessionId}/{$safePath}");

        if (! File::exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath);
    }

    private function normalizeProductForPreview(array $item, string $sessionId, string $brand): array
    {
        $images = collect($item['images'] ?? [])
            ->filter(fn ($image) => is_array($image))
            ->map(function ($image) use ($sessionId) {
                $file = $image['file'] ?? null;

                if ($file) {
                    $image['preview_url'] = route('product-import.preview-image', [
                        'sessionId' => $sessionId,
                        'path' => $file,
                    ]);
                }

                return $image;
            })
            ->values()
            ->all();

        $code = $this->firstFilled([
            $item['code'] ?? null,
            data_get($item, 'data.code'),
            data_get($item, 'raw.Mã sản phẩm'),
            data_get($item, 'raw.Ma san pham'),
            data_get($item, 'raw.Mã SP'),
            data_get($item, 'raw.Code'),
        ]);

        $name = $this->firstFilled([
            $item['name'] ?? null,
            data_get($item, 'data.name'),
            data_get($item, 'raw.Tên sản phẩm'),
            data_get($item, 'raw.Ten san pham'),
            data_get($item, 'raw.Tên SP'),
            data_get($item, 'raw.Name'),
        ]);

        $specifications = $this->firstFilled([
            $item['specifications'] ?? null,
            data_get($item, 'data.specifications'),
            data_get($item, 'data.thong_so_ky_thuat'),
            data_get($item, 'raw.Thông số kỹ thuật'),
            data_get($item, 'raw.Thong so ky thuat'),
            data_get($item, 'raw.Thông số'),
            data_get($item, 'raw.Specifications'),
            data_get($item, 'raw.Specification'),
        ]);

        /*
         * Một số file như HIKVISION không có tên sản phẩm riêng.
         * Khi đó lấy dòng đầu của thông số kỹ thuật làm name.
         */
        if (blank($name) && filled($specifications)) {
            $name = $this->makeNameFromSpecifications($specifications);
        }

        $price = $this->firstFilled([
            $item['price'] ?? null,
            data_get($item, 'data.price'),
            data_get($item, 'data.don_gia_dai_ly'),
            data_get($item, 'data.gia_dai_ly'),
            data_get($item, 'raw.Giá đại lý'),
            data_get($item, 'raw.Gia dai ly'),
            data_get($item, 'raw.Đơn giá đại lý'),
        ]);

        $retailPrice = $this->firstFilled([
            $item['retail_price'] ?? null,
            data_get($item, 'data.retail_price'),
            data_get($item, 'data.don_gia_ban_le'),
            data_get($item, 'data.gia_ban_le'),
            data_get($item, 'data.gia_ban'),
            data_get($item, 'raw.Giá bán lẻ'),
            data_get($item, 'raw.Gia ban le'),
            data_get($item, 'raw.Đơn giá bán lẻ'),
            data_get($item, 'raw.Giá bán'),
        ]);

        $warranty = $this->firstFilled([
            $item['warranty'] ?? null,
            data_get($item, 'data.warranty'),
            data_get($item, 'data.bao_hanh'),
            data_get($item, 'data.bh'),
            data_get($item, 'raw.Bảo hành'),
            data_get($item, 'raw.Bao hanh'),
            data_get($item, 'raw.BH'),
        ]);

        $status = $this->firstFilled([
            $item['status'] ?? null,
            data_get($item, 'data.status'),
            data_get($item, 'data.tinh_trang'),
            data_get($item, 'raw.Tình trạng'),
            data_get($item, 'raw.Tinh trang'),
            data_get($item, 'raw.Status'),
        ]);

        $category = $this->firstFilled([
            $item['category'] ?? null,
            data_get($item, 'data.category'),
            data_get($item, 'data.system'),
            data_get($item, 'raw.Danh mục'),
            data_get($item, 'raw.Danh muc'),
            data_get($item, 'raw.Category'),
            data_get($item, 'raw.Hệ thống'),
        ]);

        return [
            'code' => $this->cleanValue($code),
            'name' => $this->cleanValue($name),
            'brand' => $item['brand'] ?? $brand,
            'sheet' => $this->cleanValue($item['sheet'] ?? null),
            'category' => $this->cleanValue($category),

            'price' => $this->normalizeNumber($price),
            'retail_price' => $this->normalizeNumber($retailPrice),

            'warranty' => $this->cleanValue($warranty),
            'status' => $this->cleanValue($status),
            'specifications' => $this->cleanValue($specifications),

            'images' => $images,
            'raw' => $item,
        ];
    }

    private function findProductsJson(string $basePath): ?string
    {
        foreach (File::allFiles($basePath) as $file) {
            if ($file->getFilename() === 'products.json') {
                return $file->getRealPath();
            }
        }

        return null;
    }

    private function firstFilled(array $values): mixed
    {
        foreach ($values as $value) {
            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function cleanValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function normalizeNumber(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (int) round((float) $value);
        }

        $value = (string) $value;

        /*
         * Xử lý dạng:
         * 1.200.000 đ
         * 1,200,000
         * 1200000
         */
        $number = preg_replace('/[^\d]/', '', $value);

        if ($number === '') {
            return null;
        }

        return (int) $number;
    }

    private function makeNameFromSpecifications(string $specifications): ?string
    {
        $lines = preg_split('/\r\n|\r|\n/', $specifications);

        foreach ($lines as $line) {
            $line = trim($line);
            $line = ltrim($line, "*-• \t");

            if ($line !== '') {
                return Str::limit($line, 180, '');
            }
        }

        return null;
    }

    private function sanitizeRelativePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = str_replace(['../', '..\\'], '', $path);
        $path = ltrim($path, '/');

        return $path;
    }

    private function makeStableSlug(string $brand, ?string $code, string $name): string
    {
        $base = $code
            ? "{$brand}-{$code}-{$name}"
            : "{$brand}-{$name}";

        return Str::slug($base);
    }

    private function makeUniqueSlug(string $brand, ?string $code, string $name, array $currentLookup = []): string
    {
        $base = $this->makeStableSlug($brand, $code, $name);

        if ($base === '') {
            $base = Str::random(12);
        }

        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($currentLookup, function ($query) use ($currentLookup) {
                    foreach ($currentLookup as $key => $value) {
                        $query->where($key, '!=', $value);
                    }
                })
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    private function makeShortDescription(array $item, string $brand): ?string
    {
        $name = $item['name'] ?? null;
        $code = $item['code'] ?? null;
        $warranty = $item['warranty'] ?? null;

        if (! $name) {
            return null;
        }

        $parts = [];

        if ($code) {
            $parts[] = "Sản phẩm {$code}";
        }

        $parts[] = $name;

        if ($warranty) {
            $parts[] = "Bảo hành {$warranty}";
        }

        return implode('. ', $parts) . '.';
    }

    private function makeDescription(array $item, string $brand): ?string
    {
        $name = $item['name'] ?? null;
        $code = $item['code'] ?? null;
        $category = $item['category'] ?? null;
        $warranty = $item['warranty'] ?? null;
        $specifications = $item['specifications'] ?? null;

        if (! $name) {
            return null;
        }

        $html = [];

        $html[] = '<p><strong>' . e($name) . '</strong>'
            . ' là sản phẩm thuộc thương hiệu <strong>' . e(Str::headline($brand)) . '</strong>'
            . ($category ? ', nhóm <strong>' . e($category) . '</strong>' : '')
            . '.</p>';

        if ($code) {
            $html[] = '<p><strong>Mã sản phẩm:</strong> ' . e($code) . '</p>';
        }

        if ($warranty) {
            $html[] = '<p><strong>Bảo hành:</strong> ' . e($warranty) . '</p>';
        }

        if ($specifications) {
            $html[] = '<h3>Thông số kỹ thuật</h3>';
            $html[] = '<div style="white-space: pre-line;">' . e($specifications) . '</div>';
        }

        return implode("\n", $html);
    }
}