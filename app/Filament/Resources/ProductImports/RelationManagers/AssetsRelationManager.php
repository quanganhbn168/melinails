<?php

namespace App\Filament\Resources\ProductImports\RelationManagers;

use App\Models\ProductImportAsset;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'assets';

    protected static ?string $title = 'Ảnh trích xuất';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('row_number')
                    ->label('Dòng')
                    ->numeric(),
                TextInput::make('column_number')
                    ->label('Cột')
                    ->numeric(),
                TextInput::make('media_id')
                    ->label('Media ID')
                    ->numeric(),
                Toggle::make('is_ignored')
                    ->label('Bỏ qua ảnh này'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('filename')
            ->columns([
                ImageColumn::make('media.url')
                    ->label('Ảnh')
                    ->square()
                    ->height(56),
                TextColumn::make('sheet_name')
                    ->label('Sheet')
                    ->limit(24),
                TextColumn::make('coordinate')
                    ->label('Ô')
                    ->badge(),
                TextColumn::make('media_id')
                    ->label('Media')
                    ->numeric(),
                TextColumn::make('filename')
                    ->label('File')
                    ->limit(36)
                    ->toggleable(),
                TextColumn::make('size')
                    ->label('Bytes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('width')
                    ->label('Rộng')
                    ->numeric()
                    ->toggleable(),
                TextColumn::make('height')
                    ->label('Cao')
                    ->numeric()
                    ->toggleable(),
                IconColumn::make('is_ignored')
                    ->label('Bỏ qua')
                    ->boolean(),
            ])
            ->defaultSort('row_number')
            ->filters([
                TernaryFilter::make('is_ignored')
                    ->label('Bỏ qua'),
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
