<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Grid::make(1)->schema([
                        Section::make('Nội dung trang')->schema([
                            TextInput::make('title')
                                ->label('Tên trang (Tiêu đề)')
                                ->required()
                                ->maxLength(255),

                            Placeholder::make('slug_placeholder')
                                ->label('Đường dẫn (URL)')
                                ->content(fn (?Page $record): string => $record ? url('/' . $record->slugValue) : 'Tự động tạo từ Tên trang'),
                            
                            RichEditor::make('content')
                                ->label('Nội dung văn bản')
                                ->columnSpanFull(),
                        ])
                    ])->columnSpan(2),

                    Grid::make(1)->schema([
                        Section::make('Trạng thái')->schema([
                            Toggle::make('status')
                                ->label('Hiển thị công khai')
                                ->default(true),
                        ]),
                        
                        Section::make('Tối ưu SEO')->schema([
                            Textarea::make('meta_description')
                                ->label('Mô tả SEO (Meta Description)')
                                ->rows(3)
                                ->maxLength(500),
                            
                            TextInput::make('meta_keywords')
                                ->label('Từ khóa (Meta Keywords)')
                                ->maxLength(255),
                                
                            CuratorPicker::make('meta_image_id')
                                ->label('Ảnh chia sẻ (Meta Image)')
                                ->buttonLabel('Chọn ảnh'),
                        ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }
}
