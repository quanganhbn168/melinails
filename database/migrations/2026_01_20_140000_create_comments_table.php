<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng bình luận đa hình (Polymorphic Comments)
     * Sử dụng cho: Post, Field, Project
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relationship
            $table->morphs('commentable'); // commentable_type, commentable_id
            
            // Cho phép reply comment (self-referential)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            
            // Thông tin người bình luận
            $table->string('author_name');
            $table->string('author_email')->nullable();
            
            // Nội dung
            $table->text('content');
            
            // Đánh giá (1-5 sao), null = chỉ bình luận không đánh giá
            $table->unsignedTinyInteger('rating')->nullable();
            
            // Trạng thái duyệt
            $table->enum('status', ['pending', 'approved', 'spam'])->default('pending');
            
            $table->timestamps();
            
            // Index để query nhanh
            $table->index(['commentable_type', 'commentable_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
