<?php

namespace App\Filament\Resources\ProductImports\RelationManagers;

use App\Models\ProductImportRow;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RowsRelationManager extends RelationManager
{
    protected static string $relationship = 'rows';

    protected static ?string $title = 'Dòng sản phẩm';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('row_number')
                            ->label('Dòng Excel')
                            ->disabled()
                            ->dehydrated(true),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                ProductImportRow::STATUS_RAW => 'Raw',
                                ProductImportRow::STATUS_READY => 'Sẵn sàng',
                                ProductImportRow::STATUS_NEEDS_REVIEW => 'Cần duyệt',
                                ProductImportRow::STATUS_SKIPPED => 'Bỏ qua',
                                ProductImportRow::STATUS_IMPORTED => 'Đã import',
                                ProductImportRow::STATUS_FAILED => 'Lỗi',
                            ])
                            ->required(),
                        TextInput::make('code')
                            ->label('Mã sản phẩm')
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Tên sản phẩm')
                            ->maxLength(255),
                        TextInput::make('price')
                            ->label('Giá niêm yết')
                            ->numeric(),
                        Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('image_id')
                            ->label('Media ảnh đại diện')
                            ->numeric(),
                        TextInput::make('category_path')
                            ->label('Category path gợi ý'),
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('specifications')
                            ->label('Thông số kỹ thuật')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('raw_cells')
                            ->label('Raw cells')
                            ->formatStateUsing(fn ($state): string => json_encode($state, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?: '')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('row_number')
                    ->label('Dòng')
                    ->sortable(),
                ImageColumn::make('image.url')
                    ->label('Ảnh')
                    ->square()
                    ->height(44),
                TextColumn::make('code')
                    ->label('Mã')
                    ->searchable()
                    ->limit(24),
                TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->limit(48),
                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->badge()
                    ->placeholder('Chưa chọn'),
                TextColumn::make('price')
                    ->label('Giá')
                    ->money('VND')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        ProductImportRow::STATUS_READY => 'success',
                        ProductImportRow::STATUS_NEEDS_REVIEW => 'warning',
                        ProductImportRow::STATUS_FAILED => 'danger',
                        ProductImportRow::STATUS_IMPORTED => 'info',
                        ProductImportRow::STATUS_SKIPPED => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('warnings')
                    ->label('Cảnh báo')
                    ->formatStateUsing(fn ($state): string => is_array($state) ? implode(', ', $state) : (string) $state)
                    ->limit(42)
                    ->toggleable(),
            ])
            ->defaultSort('row_number')
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        ProductImportRow::STATUS_READY => 'Sẵn sàng',
                        ProductImportRow::STATUS_NEEDS_REVIEW => 'Cần duyệt',
                        ProductImportRow::STATUS_SKIPPED => 'Bỏ qua',
                        ProductImportRow::STATUS_IMPORTED => 'Đã import',
                        ProductImportRow::STATUS_FAILED => 'Lỗi',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()->slideOver(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
