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
        Schema::table('returned_items', function (Blueprint $table) {
            $table->foreignId('sent_to_supplier_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('sent_to_supplier_at')->nullable();
            $table->timestamp('received_from_supplier_at')->nullable();
            $table->string('supplier_result')->nullable()->comment('fixed, unfixable, extra_cost, refund');
            $table->decimal('repair_cost', 15, 0)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returned_items', function (Blueprint $table) {
            $table->dropForeign(['sent_to_supplier_by']);
            $table->dropColumn([
                'sent_to_supplier_by',
                'sent_to_supplier_at',
                'received_from_supplier_at',
                'supplier_result',
                'repair_cost',
            ]);
        });
    }
};
