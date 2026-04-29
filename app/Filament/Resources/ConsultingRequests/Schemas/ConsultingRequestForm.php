<?php

namespace App\Filament\Resources\ConsultingRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Awcodes\Curator\Components\Forms\CuratorPicker;
class ConsultingRequestForm
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

                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(1)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),

                Section::make('Thông tin đơn vị')
                    ->schema([
                        TextInput::make('company')
                            ->label('Công ty / Cửa hàng')
                            ->maxLength(255),

                        TextInput::make('address')
                            ->label('Địa chỉ')
                            ->maxLength(255),

                        TextInput::make('budget')
                            ->label('Ngân sách dự kiến')
                            ->placeholder('Ví dụ: 50 - 100 triệu')
                            ->maxLength(255),
                    ])
                    ->columns(1)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),

                Section::make('Trạng thái xử lý')
                    ->schema([
                        Select::make('status')
                            ->label('Trạng thái')
                            ->required()
                            ->options([
                                'new' => 'Mới',
                                'in_progress' => 'Đang xử lý',
                                'done' => 'Đã xử lý',
                            ])
                            ->default('new')
                            ->native(false),

                        CuratorPicker::make('file_id')
    ->label('File đính kèm')
    ->directory('consulting-requests')
    ->multiple(false)
                    ])
                    ->columns(1)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),

                Section::make('Nội dung yêu cầu')
                    ->schema([
                        Textarea::make('details')
                            ->label('Nội dung yêu cầu')
                            ->rows(7)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
