<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Forms\Components\FaqRepeater;
use App\Filament\Forms\Components\SlugInput;
use App\Models\Branch;
use App\Models\ServiceCategory;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Traits\HasSeo;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make('Thông tin dịch vụ')
                    ->schema([
                        Select::make('service_category_id')
                            ->label('Danh mục dịch vụ')
                            ->options(function () {
                                return ServiceCategory::getLeafOptions();
                            })
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                SlugInput::sourceField(TextInput::make('name'))
                                    ->label('Tên dịch vụ')
                                    ->required()
                                    ->maxLength(255),

                                SlugInput::make('slug'),
                            ])
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Mô tả ngắn')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Nội dung chi tiết')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('services/content')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ]),

                Grid::make(1)
                    ->schema([
                        Section::make('Hình ảnh')
                            ->schema([
                                CuratorPicker::make('image_id')
                                    ->label('Ảnh đại diện / Icon'),

                                CuratorPicker::make('banner_id')
                                    ->label('Banner'),

                                CuratorPicker::make('gallery')
                                    ->label('Thư viện ảnh')
                                    ->multiple(),
                            ])
                            ->columns(1),

                        Section::make('Liên kết Landing Page')
                            ->description('Chọn các thực thể liên quan để hiển thị ở đáy trang (dạng Mini-landing page).')
                            ->schema([
                                Select::make('projects')
                                    ->label('Dự án đã triển khai')
                                    ->multiple()
                                    ->relationship('projects', 'name')
                                    ->preload(),

                                Select::make('products')
                                    ->label('Sản phẩm / Phân hệ')
                                    ->multiple()
                                    ->relationship('products', 'name')
                                    ->preload(),

                                Select::make('posts')
                                    ->label('Bài viết tham khảo')
                                    ->multiple()
                                    ->relationship('posts', 'title')
                                    ->preload(),
                            ])
                            ->columns(1),

                        Section::make('Hiển thị')
                            ->schema([
                                Toggle::make('status')
                                    ->label('Kích hoạt')
                                    ->default(true),

                                Toggle::make('is_home')
                                    ->label('Hiển thị Trang chủ')
                                    ->default(false),
                            ])
                            ->columns(1),

                        HasSeo::seoSection(),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),

                FaqRepeater::make(),

                Section::make('Shop áp dụng dịch vụ')
                    ->description('Bật/tắt dịch vụ theo từng chi nhánh và custom giá, thời lượng, nhãn giá riêng cho shop đó.')
                    ->schema([
                        Repeater::make('branch_settings')
                            ->label('')
                            ->schema([
                                Hidden::make('branch_id'),
                                TextInput::make('branch_name')
                                    ->label('Shop')
                                    ->disabled()
                                    ->dehydrated(true),
                                Toggle::make('is_available')
                                    ->label('Có dịch vụ')
                                    ->default(true),
                                TextInput::make('price')
                                    ->label('Giá số')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('Kč'),
                                TextInput::make('price_text')
                                    ->label('Giá hiển thị')
                                    ->placeholder('od 450 Kč / dle hodnoty'),
                                TextInput::make('duration_minutes')
                                    ->label('Thời lượng')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('phút'),
                                Textarea::make('availability_note')
                                    ->label('Ghi chú shop')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns([
                                'default' => 1,
                                'lg' => 6,
                            ])
                            ->default(fn () => Branch::query()
                                ->orderBy('id')
                                ->get(['id', 'name'])
                                ->map(fn (Branch $branch) => [
                                    'branch_id' => $branch->id,
                                    'branch_name' => $branch->name,
                                    'is_available' => true,
                                    'price' => null,
                                    'price_text' => null,
                                    'duration_minutes' => null,
                                    'availability_note' => null,
                                ])
                                ->values()
                                ->all())
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
