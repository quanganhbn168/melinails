<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'cate_type',
        'name',
        'slug',
        'image',
        'icon',
        'banner',
        'status',
        'is_home',
        'is_menu',
        'is_footer',
        'position',
        'meta_description',
        'meta_keywords',
        'meta_image'
    ];
    protected $casts = [
        'status'     => 'boolean',
        'is_home'    => 'boolean',
        'is_menu'    => 'boolean',
        'is_footer'  => 'boolean',
        'parent_id'  => 'integer',
        'position'   => 'integer',
    ];
    const TYPE_PHYSICS         = 'physics';
    const TYPE_SERVICE        = 'services';

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->position) || $model->position === 0) {
                $model->position = static::max('position') + 1;
            }
        });
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    /**
     * Hàm đệ quy để lấy ID của TẤT CẢ các danh mục con (con, cháu, chắt...).
     * @return array Mảng chứa ID của các danh mục con.
     */
    public function getAllDescendantIds()
    {
        $descendantIds = $this->children()->pluck('id')->toArray();
        foreach ($this->children as $child) {
            $descendantIds = array_merge($descendantIds, $child->getAllDescendantIds());
        }
        return $descendantIds;
    }
    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = $value ?: 0;
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function slug()
    {
        return $this->morphOne(Slug::class, 'sluggable');
    }
    public function getSlugUrlAttribute()
    {
        return url($this->slug->slug ?? '#');
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'category_attribute');
    }

}