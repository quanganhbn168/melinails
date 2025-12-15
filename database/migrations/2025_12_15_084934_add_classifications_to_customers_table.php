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
        Schema::table('customers', function (Blueprint $table) {
            $table->json('classifications')->nullable()->after('type');
        });
        
        // Cập nhật dữ liệu cũ mặc định là customer
        DB::table('customers')->update(['classifications' => json_encode(['customer'])]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('classifications');
        });
    }
};
