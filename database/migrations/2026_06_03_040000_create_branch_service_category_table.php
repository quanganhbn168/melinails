<?php

use App\Models\Branch;
use App\Models\ServiceCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_service_category', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ServiceCategory::class)->constrained()->cascadeOnDelete();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->unique(['branch_id', 'service_category_id']);
        });

        $now = now();
        $branches = DB::table('branches')->pluck('id');
        $categories = DB::table('service_categories')->pluck('id');
        $rows = [];

        foreach ($branches as $branchId) {
            foreach ($categories as $categoryId) {
                $rows[] = [
                    'branch_id' => $branchId,
                    'service_category_id' => $categoryId,
                    'is_available' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($rows !== []) {
            DB::table('branch_service_category')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_service_category');
    }
};
