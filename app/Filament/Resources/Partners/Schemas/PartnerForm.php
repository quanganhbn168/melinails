<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Đối tác')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Tên đối tác')
                                ->required()
                                ->columnSpanFull(),

                            CuratorPicker::make('image_id')
                                ->label('Logo / Hình ảnh')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('url')
                                ->label('Đường dẫn Website (URL)')
                                ->url(),

                            TextInput::make('sort_order')
                                ->label('Thứ tự sắp xếp')
                                ->required()
                                ->numeric()
                                ->default(0),
                        ]),

                        Toggle::make('status')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
