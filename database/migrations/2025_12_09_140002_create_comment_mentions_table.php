<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_comment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Đảm bảo mỗi user chỉ được mention 1 lần trong 1 comment
            $table->unique(['work_order_comment_id', 'admin_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_mentions');
    }
};
