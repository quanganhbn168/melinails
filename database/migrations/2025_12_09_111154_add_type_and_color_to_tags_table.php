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
        Schema::table('tags', function (Blueprint $table) {
            $table->string('type')->default('product')->after('slug'); // product, work_order, post
            $table->string('color')->default('#6c757d')->after('type'); // Hex color
            $table->string('description')->nullable()->after('color');
            $table->integer('sort_order')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['type', 'color', 'description', 'sort_order']);
        });
    }
};
