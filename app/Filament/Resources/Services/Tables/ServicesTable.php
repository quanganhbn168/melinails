<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Tên dịch vụ')
                    ->searchable(),
                \Awcodes\Curator\Components\Tables\CuratorColumn::make('image')
                    ->label('Ảnh')
                    ->circular()
                    ->size(40),
                \Awcodes\Curator\Components\Tables\CuratorColumn::make('banner')
                    ->label('Banner')
                    ->size(40),
                IconColumn::make('status')
                    ->label('Kích hoạt')
                    ->boolean(),
                IconColumn::make('is_home')
                    ->label('Hiển thị Trang chủ')
                    ->boolean(),
                TextColumn::make('price')
                    ->label('Đơn giá')
                    ->money('VND')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
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
