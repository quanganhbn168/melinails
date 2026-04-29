<?php

namespace App\Filament\Resources\ConsultingRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                    ->searchable()
                    ->sortable()
                    ->limit(35)
                    ->weight('medium'),

                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->limit(35)
                    ->placeholder('Chưa có'),

                TextColumn::make('company')
                    ->label('Công ty / Cửa hàng')
                    ->searchable()
                    ->limit(35)
                    ->placeholder('Chưa cập nhật'),

                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->searchable()
                    ->limit(45)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('attachment_url')
                    ->label('File đính kèm')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Xem file' : 'Không có')
                    ->url(fn ($record): ?string => $record->attachment_url)
                    ->openUrlInNewTab()
                    ->color(fn (?string $state): string => $state ? 'primary' : 'gray')
                    ->toggleable(),

                TextColumn::make('budget')
                    ->label('Ngân sách dự kiến')
                    ->formatStateUsing(fn ($state): string => filled($state)
                        ? number_format((float) $state, 0, ',', '.') . ' ₫'
                        : 'Chưa cập nhật')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'done' => 'Đã xử lý',
                        default => $state ?: 'Không xác định',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'new' => 'warning',
                        'in_progress' => 'primary',
                        'done' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

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
