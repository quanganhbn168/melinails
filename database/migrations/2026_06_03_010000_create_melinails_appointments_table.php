<?php

use App\Models\Branch;
use App\Models\BeautyStaff;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('melinails_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignIdFor(Branch::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(BeautyStaff::class)->nullable()->constrained('beauty_staff')->nullOnDelete();
            $table->json('service_ids');
            $table->json('service_snapshot')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedSmallInteger('total_duration_minutes');
            $table->unsignedInteger('total_price')->default(0);
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_note')->nullable();
            $table->string('status')->default('confirmed');
            $table->timestamps();

            $table->index(['branch_id', 'starts_at', 'ends_at']);
            $table->index(['beauty_staff_id', 'starts_at', 'ends_at']);
            $table->index(['status', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('melinails_appointments');
    }
};
