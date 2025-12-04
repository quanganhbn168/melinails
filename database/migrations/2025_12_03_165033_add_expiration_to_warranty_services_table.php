<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warranty_services', function (Blueprint $table) {
            // Thêm cột thời hạn bảo hành (số tháng)
            $table->integer('warranty_months')->default(12)->after('start_date');
            
            // Thêm cột ngày hết hạn
            $table->date('expiration_date')->nullable()->after('warranty_months');
        });
    }

    public function down(): void
    {
        Schema::table('warranty_services', function (Blueprint $table) {
            $table->dropColumn(['warranty_months', 'expiration_date']);
        });
    }
};
