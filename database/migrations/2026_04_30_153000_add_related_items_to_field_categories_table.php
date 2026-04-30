<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('field_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('field_categories', 'related_product_ids')) {
                $table->json('related_product_ids')->nullable()->after('implementation_steps');
            }

            if (! Schema::hasColumn('field_categories', 'related_project_ids')) {
                $table->json('related_project_ids')->nullable()->after('related_product_ids');
            }
        });
    }

    public function down(): void
    {
        Schema::table('field_categories', function (Blueprint $table) {
            foreach ([
                'related_project_ids',
                'related_product_ids',
            ] as $column) {
                if (Schema::hasColumn('field_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
