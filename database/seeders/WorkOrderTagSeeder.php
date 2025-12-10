<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Enums\TagType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorkOrderTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Bảo hành', 'color' => '#007bff', 'description' => 'Công việc bảo hành'],
            ['name' => 'Sửa chữa', 'color' => '#fd7e14', 'description' => 'Công việc sửa chữa'],
            ['name' => 'Khảo sát', 'color' => '#6f42c1', 'description' => 'Khảo sát hiện trạng'],
            ['name' => 'Lắp mới', 'color' => '#28a745', 'description' => 'Lắp đặt mới'],
            ['name' => 'Bảo trì', 'color' => '#ffc107', 'description' => 'Bảo trì định kỳ'],
            ['name' => 'Hỗ trợ', 'color' => '#17a2b8', 'description' => 'Hỗ trợ kỹ thuật'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tagData['name']), 'type' => TagType::WORK_ORDER],
                [
                    'name' => $tagData['name'],
                    'slug' => Str::slug($tagData['name']),
                    'type' => TagType::WORK_ORDER,
                    'color' => $tagData['color'],
                    'description' => $tagData['description'],
                ]
            );
        }
    }
}
