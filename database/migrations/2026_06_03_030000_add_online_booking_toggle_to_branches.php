<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (! Schema::hasColumn('branches', 'online_booking_enabled')) {
                $table->boolean('online_booking_enabled')->default(true)->after('booking_max_days_ahead');
            }
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'online_booking_enabled')) {
                $table->dropColumn('online_booking_enabled');
            }
        });
    }
};
