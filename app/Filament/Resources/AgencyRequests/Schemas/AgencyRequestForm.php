<?php

namespace App\Filament\Resources\AgencyRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AgencyRequestForm
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

                Section::make('Thông tin đại lý / cửa hàng')
                    ->schema([
                        TextInput::make('shop_name')
                            ->label('Tên cửa hàng / đại lý')
                            ->maxLength(255),

                        TextInput::make('address')
                            ->label('Địa chỉ')
                            ->maxLength(255),

                        TextInput::make('area')
                            ->label('Khu vực')
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
                                'contacted' => 'Đã liên hệ',
                                'processing' => 'Đang xử lý',
                                'approved' => 'Đã duyệt',
                                'rejected' => 'Từ chối',
                            ])
                            ->default('new')
                            ->native(false),
                    ])
                    ->columns(1)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),

                Section::make('Nội dung đăng ký')
                    ->schema([
                        Textarea::make('details')
                            ->label('Chi tiết')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
