<?php

namespace App\Filament\Resources\ProductImports\Tables;

use App\Models\ProductImportBatch;
use App\Services\ProductImport\ProductCommitter;
use App\Services\ProductImport\RowNormalizer;
use App\Services\ProductImport\SpreadsheetExtractor;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductImportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('original_filename')
                    ->label('File')
                    ->searchable()
                    ->limit(36)
                    ->weight('bold'),
                TextColumn::make('profile.name')
                    ->label('Profile')
                    ->placeholder('Mặc định')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        ProductImportBatch::STATUS_READY => 'warning',
                        ProductImportBatch::STATUS_COMMITTED => 'success',
                        ProductImportBatch::STATUS_FAILED => 'danger',
                        ProductImportBatch::STATUS_EXTRACTING,
                        ProductImportBatch::STATUS_NORMALIZING,
                        ProductImportBatch::STATUS_COMMITTING => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('total_rows')
                    ->label('Dòng')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assets_count')
                    ->label('Ảnh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ready_rows')
                    ->label('Sẵn sàng')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('review_rows')
                    ->label('Cần duyệt')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('imported_rows')
                    ->label('Đã import')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                Action::make('extract')
                    ->label('Đọc file')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (ProductImportBatch $record): void {
                        app(SpreadsheetExtractor::class)->extract($record);

                        Notification::make()
                            ->title('Đã đọc file Excel')
                            ->success()
                            ->send();
                    }),
                Action::make('normalize')
                    ->label('Chuẩn hóa')
                    ->icon('heroicon-o-sparkles')
                    ->requiresConfirmation()
                    ->action(function (ProductImportBatch $record): void {
                        app(RowNormalizer::class)->normalize($record);

                        Notification::make()
                            ->title('Đã chuẩn hóa dữ liệu import')
                            ->success()
                            ->send();
                    }),
                Action::make('commit')
                    ->label('Import thật')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (ProductImportBatch $record): void {
                        app(ProductCommitter::class)->commit($record);

                        Notification::make()
                            ->title('Đã import các dòng sẵn sàng')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
