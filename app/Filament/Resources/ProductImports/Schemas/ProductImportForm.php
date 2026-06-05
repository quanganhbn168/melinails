<?php

namespace App\Filament\Resources\ProductImports\Schemas;

use App\Models\ProductImportBatch;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductImportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('File và profile')
                    ->schema([
                        Select::make('product_import_profile_id')
                            ->label('Profile import')
                            ->relationship('profile', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        FileUpload::make('stored_path')
                            ->label('File Excel')
                            ->disk((string) config('product_import.source_disk', 'local'))
                            ->directory((string) config('product_import.source_directory', 'product-imports/sources'))
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                                'application/octet-stream',
                            ])
                            ->preserveFilenames()
                            ->storeFileNamesIn('original_filename')
                            ->required(),

                        Hidden::make('disk')
                            ->default((string) config('product_import.source_disk', 'local')),

                        TextInput::make('original_filename')
                            ->label('Tên file gốc')
                            ->disabled()
                            ->dehydrated(true),

                        TextInput::make('source_hash')
                            ->label('SHA-256')
                            ->disabled()
                            ->dehydrated(true),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                ProductImportBatch::STATUS_PENDING => 'Chờ xử lý',
                                ProductImportBatch::STATUS_EXTRACTING => 'Đang đọc file',
                                ProductImportBatch::STATUS_EXTRACTED => 'Đã đọc file',
                                ProductImportBatch::STATUS_NORMALIZING => 'Đang chuẩn hóa',
                                ProductImportBatch::STATUS_READY => 'Sẵn sàng duyệt',
                                ProductImportBatch::STATUS_COMMITTING => 'Đang import',
                                ProductImportBatch::STATUS_COMMITTED => 'Đã import',
                                ProductImportBatch::STATUS_FAILED => 'Lỗi',
                            ])
                            ->default(ProductImportBatch::STATUS_PENDING)
                            ->disabled()
                            ->dehydrated(true),
                    ])
                    ->columns(2),
            ]);
    }
}
