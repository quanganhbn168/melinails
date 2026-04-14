<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use App\Traits\HasSeo;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    // Cột chính (2 phần)
                    Group::make()->schema([
                        Section::make('Thông tin cơ bản')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Tên dự án')
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('slug')
                                    ->required()
                                    ->columnSpanFull(),
                                Select::make('project_category_id')
                                    ->label('Danh mục')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->required()
                                    ->columnSpanFull(),
                                Grid::make(2)->schema([
                                    TextInput::make('investor')->label('Chủ đầu tư'),
                                    TextInput::make('address')->label('Địa chỉ/Địa điểm'),
                                    TextInput::make('year')->label('Năm thực hiện'),
                                    TextInput::make('value')->label('Giá trị dự án'),
                                ]),
                                Textarea::make('description')
                                    ->label('Mô tả ngắn')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Nội dung chi tiết')
                            ->schema([
                                RichEditor::make('content')
                                    ->hiddenLabel()
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Thư viện ảnh')
                            ->schema([
                                CuratorPicker::make('gallery')
                                    ->hiddenLabel()
                                    ->multiple()
                                    ->columnSpanFull(),
                            ]),
                        HasSeo::seoSection(),
                    ])->columnSpan(2),

                    // Cột phụ (1 phần)
                    Group::make()->schema([
                        Section::make('Cài đặt & Trạng thái')
                            ->schema([
                                Toggle::make('status')
                                    ->label('Kích hoạt')
                                    ->default(true)
                                    ->required(),
                                Toggle::make('is_home')
                                    ->label('Hiển thị Nổi bật (Trang chủ)')
                                    ->default(false)
                                    ->required(),
                            ]),
                        Section::make('Ảnh đại diện & Bìa')
                            ->schema([
                                CuratorPicker::make('image_id')
                                    ->label('Ảnh đại diện (Thumbnail)'),
                                CuratorPicker::make('banner_id')
                                    ->label('Ảnh Bìa / Banner'),
                            ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }
}
