<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_at_price',
        'stock',
        'image_id',
        'is_default',
        'options',
    ];

    protected $casts = [
        'options'    => 'array',
        'is_default' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $variant): void {
            if (blank($variant->sku)) {
                $variant->sku = static::generateUniqueSku($variant->product_id);
            }
        });

        static::saved(function (self $variant): void {
            if (! $variant->product_id) {
                return;
            }

            if ($variant->is_default) {
                static::query()
                    ->where('product_id', $variant->product_id)
                    ->whereKeyNot($variant->id)
                    ->update(['is_default' => false]);
                return;
            }

            $hasDefault = static::query()
                ->where('product_id', $variant->product_id)
                ->where('is_default', true)
                ->exists();

            if (! $hasDefault) {
                static::query()
                    ->whereKey($variant->id)
                    ->update(['is_default' => true]);
            }
        });
    }

    // ─── Accessors ───

    public function getIsOnSaleAttribute(): bool
    {
        return $this->compare_at_price > $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->getIsOnSaleAttribute()) {
            return round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
        }
        return 0;
    }

    // ─── Relationships ───

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attribute_value',
            'product_variant_id',
            'attribute_value_id'
        );
    }

    public static function generateUniqueSku(?int $productId = null): string
    {
        $prefix = 'SKU';

        if ($productId) {
            $productCode = Product::query()->whereKey($productId)->value('code');
            if (filled($productCode)) {
                $sanitized = strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string) $productCode));
                $prefix = Str::of($sanitized)->limit(12, '')->toString() ?: $prefix;
            }
        }

        do {
            $candidate = sprintf('%s-%s', $prefix, strtoupper(Str::random(6)));
        } while (static::query()->where('sku', $candidate)->exists());

        return $candidate;
    }
}