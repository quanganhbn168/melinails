<?php

namespace App\Filament\Resources\ConsultingRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConsultingRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('company')
                    ->label('Công ty / Cửa hàng')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->searchable(),
                TextColumn::make('attachment_url')
                    ->label('File đính kèm')
                    ->formatStateUsing(fn (?string $state) => $state ? 'Xem file' : 'Không có')
                    ->url(fn ($record) => $record->attachment_url)
                    ->openUrlInNewTab()
                    ->color(fn (?string $state) => $state ? 'primary' : 'gray')
                    ->toggleable(),
                TextColumn::make('budget')
                    ->label('Ngân sách dự kiến')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'done' => 'Đã xử lý',
                        default => $state ?: 'Không xác định',
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'new' => 'warning',
                        'in_progress' => 'primary',
                        'done' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
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
