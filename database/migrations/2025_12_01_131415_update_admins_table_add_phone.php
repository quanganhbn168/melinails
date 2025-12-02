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
    Schema::table('admins', function (Blueprint $table) {
        // Thêm cột phone (duy nhất) sau email
        if (!Schema::hasColumn('admins', 'phone')) {
            $table->string('phone')->unique()->nullable()->after('email');
        }
        // Thêm cột trạng thái (active/inactive)
        if (!Schema::hasColumn('admins', 'status')) {
            $table->boolean('status')->default(true)->after('password')->comment('1: Active, 0: Blocked');
        }
        // Đảm bảo có cột remember_token
        if (!Schema::hasColumn('admins', 'remember_token')) {
            $table->rememberToken();
        }
    });
}

public function down(): void
{
    Schema::table('admins', function (Blueprint $table) {
        $table->dropColumn(['phone', 'status']);
    });
}
};
