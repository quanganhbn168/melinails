<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('admins')->onDelete('set null');
            
            // Loại event
            $table->string('type'); // created, status_changed, assigned, task_completed, payment, deadline_changed, etc.
            
            // Icon và màu (optional, có thể derive từ type)
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            
            // Mô tả ngắn gọn
            $table->string('description');
            
            // Dữ liệu bổ sung (JSON): old_value, new_value, task_id, amount, etc.
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Index cho query hiệu quả
            $table->index(['work_order_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
