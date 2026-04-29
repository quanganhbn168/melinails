<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Nội dung bình luận')
                    ->schema([
                        Textarea::make('content')
                            ->label('Nội dung')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                TextInput::make('author_name')
                                    ->label('Tên người gửi')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('author_email')
                                    ->label('Email người gửi')
                                    ->email()
                                    ->maxLength(255),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ]),

                Grid::make(1)
                    ->schema([
                        Section::make('Đối tượng bình luận')
                            ->schema([
                                TextInput::make('commentable_type')
                                    ->label('Loại đối tượng')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('commentable_id')
                                    ->label('ID đối tượng')
                                    ->required()
                                    ->numeric(),

                                Select::make('parent_id')
                                    ->label('Bình luận cha')
                                    ->relationship('parent', 'id')
                                    ->searchable()
                                    ->preload(),
                            ])
                            ->columns(1),

                        Section::make('Đánh giá & trạng thái')
                            ->schema([
                                TextInput::make('rating')
                                    ->label('Đánh giá')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(5),

                                Select::make('status')
                                    ->label('Trạng thái')
                                    ->options([
                                        'pending' => 'Chờ duyệt',
                                        'approved' => 'Đã duyệt',
                                        'spam' => 'Spam',
                                    ])
                                    ->default('pending')
                                    ->native(false)
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
