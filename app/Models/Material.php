<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'short_name', 'unit', 'price'];

    // Scope tìm kiếm thông minh: Tìm cả Mã, Tên, và Tên viết tắt
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('code', 'like', '%' . $term . '%')
              ->orWhere('short_name', 'like', '%' . $term . '%');
        });
    }
    
    // Tự động tạo chuỗi hiển thị đẹp: "CAM-01 | Camera Hikvision (Cái)"
    public function getDisplayNameAttribute()
    {
        return "{$this->code} | {$this->name} ({$this->unit})";
    }
}