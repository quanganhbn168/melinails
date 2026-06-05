<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slugs', function (Blueprint $table) {
            $table->dropUnique('slugs_slug_unique');
            $table->index('slug', 'slugs_slug_index');
        });
    }

    public function down(): void
    {
        Schema::table('slugs', function (Blueprint $table) {
            $table->dropIndex('slugs_slug_index');
            $table->unique('slug', 'slugs_slug_unique');
        });
    }
};
