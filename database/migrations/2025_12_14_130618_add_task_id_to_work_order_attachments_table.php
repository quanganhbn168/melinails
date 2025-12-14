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
        Schema::table('work_order_attachments', function (Blueprint $table) {
            // Link attachment với task phát sinh
            if (!Schema::hasColumn('work_order_attachments', 'task_id')) {
                $table->foreignId('task_id')->nullable()
                    ->after('work_order_id')
                    ->constrained('tasks')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_order_attachments', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn('task_id');
        });
    }
};
