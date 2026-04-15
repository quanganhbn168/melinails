<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('field_categories')
            ->whereNull('parent_id')
            ->update(['parent_id' => 0]);

        DB::table('service_categories')
            ->whereNull('parent_id')
            ->update(['parent_id' => 0]);

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE field_categories MODIFY parent_id BIGINT UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE service_categories MODIFY parent_id BIGINT UNSIGNED NOT NULL DEFAULT 0');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE field_categories MODIFY parent_id BIGINT UNSIGNED NULL DEFAULT NULL');
            DB::statement('ALTER TABLE service_categories MODIFY parent_id BIGINT UNSIGNED NOT NULL DEFAULT 0');
        }
    }
};
