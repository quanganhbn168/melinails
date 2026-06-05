<?php

namespace App\Filament\Resources\ProductImports\Pages;

use App\Filament\Resources\ProductImports\ProductImportResource;
use App\Models\ProductImportBatch;
use App\Services\ProductImport\ProductCommitter;
use App\Services\ProductImport\RowNormalizer;
use App\Services\ProductImport\SpreadsheetExtractor;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProductImport extends EditRecord
{
    protected static string $resource = ProductImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('extract')
                ->label('Đọc file')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(function (): void {
                    app(SpreadsheetExtractor::class)->extract($this->record);

                    Notification::make()
                        ->title('Đã đọc file Excel')
                        ->success()
                        ->send();
                }),
            Action::make('normalize')
                ->label('Chuẩn hóa')
                ->icon('heroicon-o-sparkles')
                ->requiresConfirmation()
                ->action(function (): void {
                    app(RowNormalizer::class)->normalize($this->record);

                    Notification::make()
                        ->title('Đã chuẩn hóa dữ liệu import')
                        ->success()
                        ->send();
                }),
            Action::make('commit')
                ->label('Import các dòng sẵn sàng')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === ProductImportBatch::STATUS_READY)
                ->requiresConfirmation()
                ->action(function (): void {
                    app(ProductCommitter::class)->commit($this->record);

                    Notification::make()
                        ->title('Đã import các dòng sẵn sàng')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}
