<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $attributeNameToId = DB::table('attributes')
            ->pluck('id', 'name')
            ->all();

        DB::table('product_variants')
            ->select(['id', 'options'])
            ->orderBy('id')
            ->chunkById(200, function ($variants) use ($attributeNameToId) {
                foreach ($variants as $variant) {
                    $options = json_decode((string) ($variant->options ?? ''), true);

                    if (! is_array($options) || empty($options)) {
                        continue;
                    }

                    $normalized = [];

                    foreach ($options as $key => $value) {
                        if ($value === null || $value === '') {
                            continue;
                        }

                        $resolvedKey = null;

                        if (is_numeric($key)) {
                            $resolvedKey = (string) (int) $key;
                        } elseif (isset($attributeNameToId[$key])) {
                            $resolvedKey = (string) $attributeNameToId[$key];
                        }

                        if ($resolvedKey === null) {
                            continue;
                        }

                        $normalized[$resolvedKey] = (string) $value;
                    }

                    if (empty($normalized)) {
                        continue;
                    }

                    ksort($normalized, SORT_NATURAL);

                    DB::table('product_variants')
                        ->where('id', $variant->id)
                        ->update(['options' => json_encode($normalized, JSON_UNESCAPED_UNICODE)]);
                }
            });
    }

    public function down(): void
    {
        // Intentionally left blank: cannot reliably restore original string keys.
    }
};

