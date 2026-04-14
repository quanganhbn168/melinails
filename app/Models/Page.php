<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSlug;
use App\Traits\HasSeo;

class Page extends Model
{
    use HasFactory, HasSlug, HasSeo;

    protected $fillable = [
        'title',
        'content',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /** Ảnh SEO (Curator) */
    public function metaImage()
    {
        return $this->belongsTo(Media::class, 'meta_image_id');
    }
}
