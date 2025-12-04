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
        // Thêm các cột mới
            $table->string('email')->nullable()->after('name');
            $table->string('tax_code')->nullable()->after('email');
            $table->string('representative_name')->nullable()->after('tax_code');
            $table->string('type')->default('personal')->after('id'); // personal, company
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
