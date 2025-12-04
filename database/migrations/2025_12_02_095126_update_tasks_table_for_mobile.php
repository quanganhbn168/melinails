<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('tasks', function (Blueprint $table) {
        // Trạng thái của từng đầu việc con
        $table->string('status')->default('pending')->after('performer_id'); // pending, completed
        
        // Chữ ký khách hàng (Lưu đường dẫn file ảnh)
        $table->string('customer_signature')->nullable()->after('report_content');
    });

    // Bảng lưu ảnh nghiệm thu (Vì 1 task có thể up nhiều ảnh)
    Schema::create('task_images', function (Blueprint $table) {
        $table->id();
        $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
        $table->string('image_path');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_images');
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['status', 'customer_signature']);
        });
    }
};
