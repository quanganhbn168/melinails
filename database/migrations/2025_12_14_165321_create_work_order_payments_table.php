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
        Schema::create('work_order_payments', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('task_report_id')->nullable()->constrained('task_reports')->nullOnDelete();
            
            // Payment info
            $table->string('payment_type')->default('item_value'); // item_value, labor, other
            $table->decimal('amount', 15, 0)->default(0);
            $table->string('description')->nullable();
            
            // Collection info
            $table->boolean('is_collected')->default(false);
            $table->string('payment_method')->nullable(); // cash, transfer
            $table->string('transfer_target')->nullable(); // company, personal
            
            // Status
            $table->string('status')->default('pending'); // pending, verified, cancelled
            
            // Tracking - who did what
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('collector_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('collected_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            
            $table->text('note')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['work_order_id', 'status']);
            $table->index('is_collected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_payments');
    }
};
