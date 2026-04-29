<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable()
                    ->limit(35)
                    ->weight('medium'),

                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Chưa có'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->limit(35)
                    ->placeholder('Chưa có'),

                TextColumn::make('subject')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->limit(45)
                    ->placeholder('Không có tiêu đề'),

                TextColumn::make('message')
                    ->label('Nội dung')
                    ->searchable()
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('status')
                    ->label('Đã xử lý')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->searchable()
                    ->limit(45)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Ngày gửi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem'),

                EditAction::make()
                    ->label('Sửa'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
