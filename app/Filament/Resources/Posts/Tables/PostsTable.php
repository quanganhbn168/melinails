<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Columns\ToggleColumn;
use Awcodes\Curator\Components\Tables\CuratorColumn;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                CuratorColumn::make('image')
                    ->label('Ảnh'),
                CuratorColumn::make('banner')
                    ->label('Banner'),
                ToggleColumn::make('status')
                    ->label('Kích hoạt'),
                ToggleColumn::make('is_featured')
                    ->label('Nổi bật'),
                ToggleColumn::make('is_home')
                    ->label('Trang chủ'),
                ToggleColumn::make('is_menu')
                    ->label('Menu')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
