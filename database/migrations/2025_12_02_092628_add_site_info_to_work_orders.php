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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('site_address')->nullable()->comment('Địa chỉ thi công thực tế');
            $table->string('contact_person')->nullable()->comment('Tên người phụ trách tại công trình');
            $table->string('contact_phone')->nullable()->comment('SĐT người phụ trách tại công trình');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            //
        });
    }
};
