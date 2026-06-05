<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (! Schema::hasColumn('branches', 'booking_slot_minutes')) {
                $table->unsignedSmallInteger('booking_slot_minutes')->default(15)->after('closing_time');
            }
            if (! Schema::hasColumn('branches', 'booking_buffer_minutes')) {
                $table->unsignedSmallInteger('booking_buffer_minutes')->default(0)->after('booking_slot_minutes');
            }
            if (! Schema::hasColumn('branches', 'booking_min_notice_minutes')) {
                $table->unsignedSmallInteger('booking_min_notice_minutes')->default(0)->after('booking_buffer_minutes');
            }
            if (! Schema::hasColumn('branches', 'booking_max_days_ahead')) {
                $table->unsignedSmallInteger('booking_max_days_ahead')->default(60)->after('booking_min_notice_minutes');
            }
        });

        Schema::table('beauty_staff', function (Blueprint $table) {
            if (! Schema::hasColumn('beauty_staff', 'working_mode')) {
                $table->string('working_mode')->default('full_time')->after('working_days');
            }
            if (! Schema::hasColumn('beauty_staff', 'shift_start')) {
                $table->time('shift_start')->nullable()->after('working_mode');
            }
            if (! Schema::hasColumn('beauty_staff', 'shift_end')) {
                $table->time('shift_end')->nullable()->after('shift_start');
            }
            if (! Schema::hasColumn('beauty_staff', 'working_weekdays')) {
                $table->json('working_weekdays')->nullable()->after('shift_end');
            }
            if (! Schema::hasColumn('beauty_staff', 'working_shifts')) {
                $table->json('working_shifts')->nullable()->after('working_weekdays');
            }
            if (! Schema::hasColumn('beauty_staff', 'working_dates')) {
                $table->json('working_dates')->nullable()->after('working_shifts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('beauty_staff', function (Blueprint $table) {
            foreach (['working_dates', 'working_shifts', 'working_weekdays', 'shift_end', 'shift_start', 'working_mode'] as $column) {
                if (Schema::hasColumn('beauty_staff', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('branches', function (Blueprint $table) {
            foreach (['booking_max_days_ahead', 'booking_min_notice_minutes', 'booking_buffer_minutes', 'booking_slot_minutes'] as $column) {
                if (Schema::hasColumn('branches', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
