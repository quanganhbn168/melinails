<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sửa bảng work_orders
        Schema::table('work_orders', function (Blueprint $table) {
            // Lưu ý: Nếu đang dùng MySQL cũ hoặc Laravel cũ có thể cần gói doctrine/dbal
            // Nhưng Laravel 11 hỗ trợ tốt native modify rồi.
            $table->enum('status', [
                'pending', 
                'processing', 
                'pending_approval', 
                'completed', 
                'cancelled'
            ])->default('pending')->change();
        });

        // 2. Sửa bảng warranty_devices (Nếu bảng này đã tồn tại)
        if (Schema::hasTable('warranty_devices')) {
            Schema::table('warranty_devices', function (Blueprint $table) {
                $table->enum('status', [
                    'active', 
                    'expired', 
                    'void'
                ])->default('active')->change();
            });
        }
    }

    public function down(): void
    {
        // Rollback về dạng string nếu cần
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        if (Schema::hasTable('warranty_devices')) {
            Schema::table('warranty_devices', function (Blueprint $table) {
                $table->string('status')->default('active')->change();
            });
        }
    }
};