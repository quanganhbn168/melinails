<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returned_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_report_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->string('serial_number')->nullable();
            $table->string('reason')->nullable(); // warranty, replace, defective, upgrade
            $table->text('condition_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returned_items');
    }
};
