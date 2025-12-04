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
        // 1. Bảng Bảo hành Thiết bị (Lẻ từng cái có Seri)
        Schema::create('warranty_devices', function (Blueprint $table) {
            $table->id();
            // Link về item gốc để truy xuất nguồn gốc
            $table->foreignId('task_item_id')->constrained('task_items')->onDelete('cascade');
            
            $table->string('device_name');
            $table->string('serial_number')->index(); // Index để tra cứu nhanh
            
            $table->timestamp('start_date'); // Lấy từ work_orders.approved_at
            $table->integer('warranty_months'); // Số tháng bảo hành
            $table->date('expiration_date'); // Ngày hết hạn
            
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, expired
            
            $table->timestamps();
        });

        // 2. Bảng Bảo hành Dịch vụ (Trọn gói theo Work Order)
        Schema::create('warranty_services', function (Blueprint $table) {
            $table->id();
            // Link về Work Order gốc
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            
            $table->string('customer_name');
            $table->decimal('total_amount', 15, 0)->nullable();
            $table->timestamp('start_date'); // Lấy từ work_orders.approved_at
            
            // Snapshot dữ liệu (Lưu cứng dạng text/json)
            $table->text('device_list_details')->nullable(); 
            $table->text('device_list_qty')->nullable();     
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_services');
        Schema::dropIfExists('warranty_devices');
    }
};
