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
        Schema::table('tasks', function (Blueprint $table) {
            // Mô tả chi tiết công việc
            if (!Schema::hasColumn('tasks', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            
            // Đánh dấu QUAN TÂM - để Admin chú ý điều chuyển
            if (!Schema::hasColumn('tasks', 'is_flagged')) {
                $table->boolean('is_flagged')->default(false)->after('is_additional');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_flagged']);
        });
    }
};
