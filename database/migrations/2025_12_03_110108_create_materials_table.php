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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable()->comment('Mã SKU chuẩn (VD: CAM-HIK-01)');
            $table->string('name')->index()->comment('Tên đầy đủ (VD: Camera Hikvision 2MP)');
            
            // CỘT QUAN TRỌNG ĐỂ SEARCH THÔNG MINH
            $table->string('short_name')->nullable()->index()->comment('Tên viết tắt/Từ khóa (VD: cam hik, camera dome)');
            
            $table->string('unit')->default('cái')->comment('Đơn vị tính');
            $table->decimal('price', 15, 0)->default(0)->comment('Đơn giá gợi ý');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
