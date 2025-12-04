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
        Schema::table('task_reports', function (Blueprint $table) {
        // Hình thức thanh toán: 'cash' (Tiền mặt), 'transfer' (Chuyển khoản)
            $table->string('payment_method')->nullable()->after('collected_amount');
            
        // Nếu chuyển khoản thì về đâu: 'company' (Cty), 'personal' (Cá nhân)
            $table->string('transfer_target')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_reports', function (Blueprint $table) {
            //
        });
    }
};
