<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('consulting_requests', 'file_id')) {
            Schema::table('consulting_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('file_id')->nullable()->after('file_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('consulting_requests', 'file_id')) {
            Schema::table('consulting_requests', function (Blueprint $table) {
                $table->dropColumn('file_id');
            });
        }
    }
};
