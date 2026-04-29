<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Thông tin khách hàng')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên khách hàng')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('position')
                            ->label('Chức vụ / Công ty')
                            ->placeholder('Ví dụ: CEO tại ABC Company')
                            ->maxLength(255),

                        Textarea::make('content')
                            ->label('Nội dung đánh giá')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ]),

                Grid::make(1)
                    ->schema([
                        Section::make('Ảnh đại diện')
                            ->schema([
                                CuratorPicker::make('image_id')
                                    ->label('Ảnh khách hàng'),
                            ])
                            ->columns(1),

                        Section::make('Hiển thị')
                            ->schema([
                                Toggle::make('status')
                                    ->label('Kích hoạt')
                                    ->default(true)
                                    ->required(),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),
            ]);
    }
}
