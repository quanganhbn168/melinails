<?php

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (! Schema::hasColumn('branches', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('branches', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (! Schema::hasColumn('branches', 'timezone')) {
                $table->string('timezone')->default('Asia/Ho_Chi_Minh')->after('email');
            }
            if (! Schema::hasColumn('branches', 'opening_time')) {
                $table->time('opening_time')->default('08:00:00')->after('timezone');
            }
            if (! Schema::hasColumn('branches', 'closing_time')) {
                $table->time('closing_time')->default('20:00:00')->after('opening_time');
            }
        });

        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'code')) {
                $table->string('code')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('services', 'duration_minutes')) {
                $table->unsignedSmallInteger('duration_minutes')->default(30)->after('price');
            }
            if (! Schema::hasColumn('services', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('duration_minutes');
            }
        });

        Schema::create('branch_service', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price')->nullable();
            $table->string('price_text')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->boolean('is_available')->default(true);
            $table->json('availability_note')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'service_id']);
        });

        Schema::create('beauty_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->json('working_days')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('beauty_staff_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beauty_staff_id')->constrained('beauty_staff')->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['beauty_staff_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_staff_service');
        Schema::dropIfExists('beauty_staff');
        Schema::dropIfExists('branch_service');

        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'code')) {
                $table->dropUnique('services_code_unique');
            }
            foreach (['sort_order', 'duration_minutes', 'code'] as $column) {
                if (Schema::hasColumn('services', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'slug')) {
                $table->dropUnique('branches_slug_unique');
            }
            foreach (['closing_time', 'opening_time', 'timezone', 'city', 'slug'] as $column) {
                if (Schema::hasColumn('branches', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
