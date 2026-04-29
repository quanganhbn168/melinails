<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Thông tin liên hệ')
                    ->schema([
                        TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('address')
                            ->label('Địa chỉ')
                            ->maxLength(255),
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
                        Section::make('Trạng thái')
                            ->schema([
                                Toggle::make('status')
                                    ->label('Đã xử lý')
                                    ->default(false)
                                    ->required(),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),

                Section::make('Nội dung liên hệ')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Tiêu đề')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('message')
                            ->label('Nội dung')
                            ->required()
                            ->rows(7)
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }
}
