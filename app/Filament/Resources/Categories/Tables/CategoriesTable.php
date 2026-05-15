<?php

namespace App\Filament\Resources\Categories\Tables;

use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use App\Models\Category;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('position')
            ->defaultSort('position')
            ->columns([
                CuratorColumn::make('image')
                    ->label('Ảnh')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->default('—')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('slugValue')
                    ->label('Slug')
                    ->searchable(query: function ($query, $search) {
                         $query->whereHas('slugData', fn($q) => $q->where('slug', 'like', "%{$search}%"));
                    })
                    ->color('primary')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('products_count')
                    ->label('Sản phẩm')
                    ->counts('products')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                ToggleColumn::make('status')
                    ->label('Kích hoạt')
                    ->alignCenter(),

                ToggleColumn::make('is_home')
                    ->label('Trang chủ')
                    ->alignCenter(),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
    SelectFilter::make('category_tree')
        ->label('Nhóm danh mục')
        ->options(fn () => Category::getTreeOptions())
        ->searchable()
        ->preload()
        ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
            ? $query->whereIn('id', Category::getTreeIds((int) $data['value']))
            : $query
        ),

    TernaryFilter::make('root_only')
        ->label('Cấp danh mục')
        ->placeholder('Tất cả')
        ->trueLabel('Chỉ danh mục gốc')
        ->falseLabel('Chỉ danh mục con')
        ->queries(
            true: fn (Builder $query) => $query->whereNull('parent_id'),
            false: fn (Builder $query) => $query->whereNotNull('parent_id'),
            blank: fn (Builder $query) => $query,
        ),

    TernaryFilter::make('status')
        ->label('Kích hoạt')
        ->placeholder('Tất cả')
        ->trueLabel('Đang bật')
        ->falseLabel('Đang tắt'),

    TernaryFilter::make('has_products')
        ->label('Sản phẩm')
        ->placeholder('Tất cả')
        ->trueLabel('Có sản phẩm')
        ->falseLabel('Chưa có sản phẩm')
        ->queries(
            true: fn (Builder $query) => $query->has('products'),
            false: fn (Builder $query) => $query->doesntHave('products'),
            blank: fn (Builder $query) => $query,
        ),
])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, $record) {
                        if ($record->products()->exists()) {
                            Notification::make()->warning()->title('Không thể xóa!')->body('Danh mục đang chứa sản phẩm.')->send();
                            $action->cancel();
                        }
                        if ($record->children()->exists()) {
                            Notification::make()->warning()->title('Không thể xóa!')->body('Danh mục này đang có thư mục con.')->send();
                            $action->cancel();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, DeleteBulkAction $action) {
                            $prevented = 0;
                            foreach ($records as $record) {
                                if ($record->products()->exists() || $record->children()->exists()) {
                                    $prevented++;
                                } else {
                                    $record->delete();
                                }
                            }
                            if ($prevented > 0) {
                                Notification::make()
                                    ->warning()
                                    ->title('Đã bỏ qua một số mục!')
                                    ->body("Không thể xóa {$prevented} danh mục vì đang chứa sản phẩm hoặc thư mục con.")
                                    ->send();
                            } else {
                                Notification::make()->success()->title('Đã xóa thành công!')->send();
                            }
                        }),
                ]),
            ]);
    }
}
