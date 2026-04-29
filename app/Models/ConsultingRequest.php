<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ConsultingRequest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'company',
        'address',
        'details',
        'file_path',
        'file_id',
        'budget',
        'status',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'file_id');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        if ($this->file?->url) {
            return url($this->file->url);
        }

        if ($this->file_path) {
            return url(Storage::disk('public')->url($this->file_path));
        }

        return null;
    }
}
