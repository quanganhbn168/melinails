<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CommentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_comment_id',
        'file_path',
        'file_name',
        'file_type', // image, video, document
        'mime_type',
        'file_size',
    ];

    // File type constants
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_DOCUMENT = 'document';

    // File size limits (bytes)
    const MAX_IMAGE_SIZE = 10 * 1024 * 1024;    // 10MB
    const MAX_VIDEO_SIZE = 50 * 1024 * 1024;    // 50MB
    const MAX_DOCUMENT_SIZE = 8 * 1024 * 1024;  // 8MB

    // Allowed extensions
    const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov', 'avi'];
    const DOCUMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv'];

    // --- RELATIONSHIPS ---

    public function comment(): BelongsTo
    {
        return $this->belongsTo(WorkOrderComment::class, 'work_order_comment_id');
    }

    // --- ACCESSORS ---

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getFileIconAttribute(): string
    {
        return match ($this->file_type) {
            self::TYPE_IMAGE => 'fas fa-image text-success',
            self::TYPE_VIDEO => 'fas fa-video text-danger',
            self::TYPE_DOCUMENT => $this->getDocumentIcon(),
            default => 'fas fa-file text-muted',
        };
    }

    protected function getDocumentIcon(): string
    {
        $ext = pathinfo($this->file_name, PATHINFO_EXTENSION);
        return match (strtolower($ext)) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'txt' => 'fas fa-file-alt text-muted',
            'csv' => 'fas fa-file-csv text-info',
            default => 'fas fa-file text-muted',
        };
    }

    // --- STATIC HELPERS ---

    public static function getTypeFromExtension(string $extension): string
    {
        $ext = strtolower($extension);
        
        if (in_array($ext, self::IMAGE_EXTENSIONS)) {
            return self::TYPE_IMAGE;
        }
        if (in_array($ext, self::VIDEO_EXTENSIONS)) {
            return self::TYPE_VIDEO;
        }
        if (in_array($ext, self::DOCUMENT_EXTENSIONS)) {
            return self::TYPE_DOCUMENT;
        }
        
        return self::TYPE_DOCUMENT; // Default
    }

    public static function getMaxSizeForType(string $type): int
    {
        return match ($type) {
            self::TYPE_IMAGE => self::MAX_IMAGE_SIZE,
            self::TYPE_VIDEO => self::MAX_VIDEO_SIZE,
            self::TYPE_DOCUMENT => self::MAX_DOCUMENT_SIZE,
            default => self::MAX_DOCUMENT_SIZE,
        };
    }
}
