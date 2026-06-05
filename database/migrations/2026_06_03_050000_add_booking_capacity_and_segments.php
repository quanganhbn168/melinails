<?php

use App\Models\BeautyStaff;
use App\Models\BookingAppointment;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (! Schema::hasColumn('branches', 'booking_capacity')) {
                $table->unsignedSmallInteger('booking_capacity')->default(1)->after('booking_max_days_ahead');
            }
        });

        DB::table('branches')
            ->whereNull('timezone')
            ->orWhere('timezone', 'Asia/Ho_Chi_Minh')
            ->update(['timezone' => 'Europe/Prague']);

        if (! Schema::hasTable('melinails_appointment_segments')) {
            Schema::create('melinails_appointment_segments', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(BookingAppointment::class, 'appointment_id')->constrained('melinails_appointments')->cascadeOnDelete();
                $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
                $table->foreignIdFor(BeautyStaff::class)->nullable()->constrained('beauty_staff')->nullOnDelete();
                $table->unsignedSmallInteger('position')->default(1);
                $table->string('service_name');
                $table->unsignedSmallInteger('duration_minutes');
                $table->unsignedInteger('price')->default(0);
                $table->string('price_text')->nullable();
                $table->dateTime('starts_at');
                $table->dateTime('ends_at');
                $table->timestamps();

                $table->index(['appointment_id', 'position'], 'meli_seg_appt_pos_idx');
                $table->index(['beauty_staff_id', 'starts_at', 'ends_at'], 'meli_seg_staff_time_idx');
                $table->index(['starts_at', 'ends_at'], 'meli_seg_time_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('melinails_appointment_segments');

        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'booking_capacity')) {
                $table->dropColumn('booking_capacity');
            }
        });
    }
};
