<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title')->nullable()->after('work_order_id');
            $table->boolean('is_additional')->default(false)->after('status');
            $table->unsignedBigInteger('created_by_worker_id')->nullable()->after('is_additional');
            $table->foreign('created_by_worker_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['created_by_worker_id']);
            $table->dropColumn(['title', 'is_additional', 'created_by_worker_id']);
        });
    }
};
