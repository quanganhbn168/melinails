<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category_id',
        'brand_id',
        'name',
        'code',
        'slug',
        'image',
        'description',
        'content',
        'price',
        'price_discount',
        'stock',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_image',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];
    
    // -- Accessors cho Giá --
    public function getIsOnSaleAttribute(): bool
    {
        return $this->price_discount > $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->getIsOnSaleAttribute()) {
            return round((($this->price_discount - $this->price) / $this->price_discount) * 100);
        }
        return 0;
    }

    // -- Các mối quan hệ (Relationships) --
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Quan hệ cho các Biến thể (SKUs).
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Quan hệ cho các Thuộc tính Lọc/Thông số kỹ thuật.
     */
    public function specifications(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product');
    }

    public function slug()
    {
        return $this->morphOne(\App\Models\Slug::class, 'sluggable');
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'item');
    }
    
    public function getSlugUrlAttribute()
    {
        return url($this->slug->slug ?? '#');
    }
}