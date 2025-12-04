<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tạo bảng Báo cáo công việc (Task Reports)
        Schema::create('task_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade'); // Thuộc task nào
            $table->foreignId('reporter_id')->constrained('admins'); // Ai báo cáo
            
            $table->text('content'); // Nội dung báo cáo
            $table->boolean('is_completed')->default(false); // Báo cáo này đã chốt xong Task chưa?
            
            $table->decimal('collected_amount', 15, 0)->default(0); // Thu tiền đợt này
            $table->string('customer_signature')->nullable(); // Chữ ký đợt này
            
            $table->timestamps();
        });

        // 2. Sửa bảng Ảnh: Chuyển từ task_id sang task_report_id
        Schema::table('task_images', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn('task_id');
            
            $table->foreignId('task_report_id')->after('id')->constrained('task_reports')->onDelete('cascade');
        });

        // 3. Sửa bảng Vật tư: Chuyển từ task_id sang task_report_id
        Schema::table('task_items', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn('task_id');
            
            $table->foreignId('task_report_id')->after('id')->constrained('task_reports')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_reports');
    }
};
