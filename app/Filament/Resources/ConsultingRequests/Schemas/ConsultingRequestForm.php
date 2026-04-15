<?php

namespace App\Filament\Resources\ConsultingRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ConsultingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Họ và tên')
                    ->required(),
                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                TextInput::make('company')
                    ->label('Công ty / Cửa hàng'),
                TextInput::make('address')
                    ->label('Địa chỉ'),
                Textarea::make('details')
                    ->label('Nội dung yêu cầu')
                    ->columnSpanFull(),
                TextInput::make('file_path')
                    ->label('Đường dẫn file đính kèm')
                    ->readOnly(),
                TextInput::make('budget')
                    ->label('Ngân sách dự kiến'),
                Select::make('status')
                    ->label('Trạng thái')
                    ->required()
                    ->options([
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'done' => 'Đã xử lý',
                    ])
                    ->default('new'),
            ]);
    }
}
