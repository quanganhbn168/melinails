<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Facades\Schema;
use Filament\Schemas\Components\Utilities\Set;
class SlugInput extends TextInput
{
    /**
     * Helper: trả về closure cho afterStateUpdated của source field.
     * 
     * Dùng trong form:
     *   TextInput::make('name')        // hoặc 'title', 'label', bất kỳ field nào
     *       ->live(debounce: 500)      // KHÔNG dùng live(onBlur: true) — blur không fire trong Filament 5
     *       ->afterStateUpdated(SlugInput::autoSlug())
     */
    public static function autoSlug(string $slugField = 'slug'): \Closure
    {
        return function (Set $set, ?string $state, ?Model $record) use ($slugField) {
            if (! filled($state)) {
                $set($slugField, null);
                return;
            }

            $baseSlug = Str::slug($state);
            $slug     = $baseSlug;
            $counter  = 1;

            // Lấy ID slug hiện tại của record (khi Edit) để loại trừ khỏi check
            $excludeId = $record && method_exists($record, 'slugData')
                ? optional($record->slugData)->id
                : null;

            while (true) {
                $exists = \DB::table('slugs')
                    ->where('slug', $slug)
                    ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                    ->exists();

                if (! $exists) break;

                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $set($slugField, $slug);
        };
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Đường dẫn')
            ->required()
            ->maxLength(255)
            ->prefix('/')
            ->helperText('Tự động tạo từ tên. Có thể chỉnh sửa thủ công.')
            // Tự load giá trị khi Edit từ relation hoặc cột local
            ->afterStateHydrated(function (SlugInput $component, $state, ?Model $record) {
                if (filled($state)) {
                    return;
                }
                if ($record && method_exists($record, 'getSlugValueAttribute') && $record->slug_value) {
                    $component->state($record->slug_value);
                } elseif ($record && $record->slug) {
                    $component->state($record->slug);
                }
            })
            // Check trùng theo bảng slugs (loại trừ record hiện tại)
            ->unique(
                table: 'slugs',
                column: 'slug',
                modifyRuleUsing: function (Unique $rule, ?Model $record) {
                    if ($record && method_exists($record, 'slugData') && $record->slugData) {
                        return $rule->ignore($record->slugData->id);
                    }
                    return $rule;
                }
            )
            // Filament cần dehydrate state để $set() hoạt động reactive trên UI
            // saveRelationshipsUsing xử lý việc lưu xuống DB, Model::$fillable không có 'slug' nên sẽ không bị mass-assign
            // Can thiệp sau khi Model được Save
            ->saveRelationshipsUsing(function ($state, ?Model $record) {
                if ($record && method_exists($record, 'slugData')) {
                    if (empty($state)) {
                        $state = Str::slug($record->name ?? $record->title ?? 'item-' . $record->id);
                    }

                    $record->slugData()->updateOrCreate([], ['slug' => $state]);

                    if (Schema::hasColumn($record->getTable(), 'slug')) {
                        if ($record->slug !== $state) {
                            $record->slug = $state;
                            $record->saveQuietly();
                        }
                    }
                }
            });
    }
}
