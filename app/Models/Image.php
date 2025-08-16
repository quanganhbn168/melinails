<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    
    use HasFactory;

    protected $fillable = [
        'item_type',
        'item_id',
        'image',
        'position',
    ];
    protected static function booted(): void
    {
        // Auto-assign position liên tiếp
        static::creating(function ($model) {
            if (empty($model->position) || $model->position === 0) {
                $model->position = static::max('position') + 1;
            }
        });
    }
    public function item()
    {
        return $this->morphTo();
    }

}
