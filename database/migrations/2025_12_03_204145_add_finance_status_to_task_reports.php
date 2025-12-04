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
            $table->enum('finance_status', ['pending', 'verified', 'handed_over'])->default('pending')->after('transfer_target');
            $table->text('finance_note')->nullable()->after('finance_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_reports', function (Blueprint $table) {
            $table->dropColumn(['finance_status', 'finance_note']);
        });
    }
};
