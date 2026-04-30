<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Forms\Components\FaqRepeater;
use App\Filament\Forms\Components\SlugInput;
use App\Filament\Forms\Components\TagSelect;
use App\Models\ProjectCategory;
use App\Traits\HasSeo;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make()
                    ->columns([
                        'default' => 1,
                        'xl' => 3,
                    ])
                    ->schema([
                        Grid::make()
                            ->columns(1)
                            ->columnSpan([
                                'default' => 1,
                                'xl' => 2,
                            ])
                            ->schema([
                                Section::make('Thông tin dự án')
                                    ->description('Thông tin chính dùng để hiển thị danh sách, chi tiết và SEO cơ bản của dự án.')
                                    ->schema([
                                        SlugInput::sourceField(TextInput::make('name'))
                                            ->label('Tên dự án')
                                            ->placeholder('Ví dụ: Hệ thống camera an ninh nhà máy ABC')
                                            ->required()
                                            ->columnSpanFull(),

                                        SlugInput::make('slug')
                                            ->columnSpanFull(),

                                        Select::make('project_category_id')
                                            ->label('Danh mục')
                                            ->options(fn () => ProjectCategory::getLeafOptions())
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        TextInput::make('year')
                                            ->label('Năm thực hiện')
                                            ->placeholder('2025'),

                                        TextInput::make('investor')
                                            ->label('Chủ đầu tư')
                                            ->placeholder('Công ty / Nhà máy / Khu đô thị'),

                                        TextInput::make('value')
                                            ->label('Giá trị dự án')
                                            ->placeholder('Ví dụ: 1.8 tỷ VNĐ'),

                                        TextInput::make('address')
                                            ->label('Địa chỉ / Địa điểm')
                                            ->placeholder('KCN, tòa nhà, khu dân cư...')
                                            ->columnSpanFull(),

                                        Textarea::make('description')
                                            ->label('Mô tả ngắn')
                                            ->placeholder('Tóm tắt mục tiêu, phạm vi và kết quả chính của dự án.')
                                            ->rows(4)
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ]),

                                Section::make('Nội dung chi tiết')
                                    ->description('Nội dung dài cho trang chi tiết dự án.')
                                    ->schema([
                                        RichEditor::make('content')
                                            ->hiddenLabel()
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),

                                Section::make('Nội dung trình bày dự án')
                                    ->description('Cấu trúc case study: tổng quan, bài toán, giải pháp, quy trình và kết quả.')
                                    ->schema([
                                        Tabs::make('project_case_study_tabs')
                                            ->tabs([
                                                Tab::make('Tổng quan')
                                                    ->schema([
                                                        RichEditor::make('project_overview')
                                                            ->label('Tổng quan dự án')
                                                            ->columnSpanFull(),
                                                    ]),

                                                Tab::make('Bài toán')
                                                    ->schema([
                                                        Repeater::make('business_problems')
                                                            ->label('Bài toán doanh nghiệp')
                                                            ->schema([
                                                                Select::make('icon')
                                                                    ->label('Icon')
                                                                    ->options(self::iconOptions())
                                                                    ->searchable()
                                                                    ->default('fas fa-triangle-exclamation'),

                                                                TextInput::make('title')
                                                                    ->label('Ý chính')
                                                                    ->placeholder('Khó kiểm soát an ninh tại nhiều khu vực')
                                                                    ->required(),

                                                                Textarea::make('description')
                                                                    ->label('Mô tả ngắn')
                                                                    ->placeholder('Mô tả vấn đề trước khi triển khai hệ thống camera, cảm biến hoặc kiểm soát ra vào.')
                                                                    ->rows(3)
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->columns([
                                                                'default' => 1,
                                                                'md' => 2,
                                                            ])
                                                            ->reorderable()
                                                            ->collapsible()
                                                            ->defaultItems(0)
                                                            ->maxItems(8)
                                                            ->columnSpanFull()
                                                            ->addActionLabel('+ Thêm bài toán'),
                                                    ]),

                                                Tab::make('Giải pháp')
                                                    ->schema([
                                                        Repeater::make('implemented_solutions')
                                                            ->label('Giải pháp triển khai')
                                                            ->schema([
                                                                Select::make('icon')
                                                                    ->label('Icon')
                                                                    ->options(self::iconOptions())
                                                                    ->searchable()
                                                                    ->default('fas fa-screwdriver-wrench'),

                                                                TextInput::make('title')
                                                                    ->label('Ý chính')
                                                                    ->placeholder('Lắp đặt camera AI giám sát toàn khu vực')
                                                                    ->required(),

                                                                Textarea::make('description')
                                                                    ->label('Mô tả ngắn')
                                                                    ->placeholder('Mô tả thiết bị, hạng mục và cách giải pháp được triển khai.')
                                                                    ->rows(3)
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->columns([
                                                                'default' => 1,
                                                                'md' => 2,
                                                            ])
                                                            ->reorderable()
                                                            ->collapsible()
                                                            ->defaultItems(0)
                                                            ->maxItems(10)
                                                            ->columnSpanFull()
                                                            ->addActionLabel('+ Thêm giải pháp'),
                                                    ]),

                                                Tab::make('Quy trình')
                                                    ->schema([
                                                        Repeater::make('implementation_process')
                                                            ->label('Quy trình triển khai')
                                                            ->schema([
                                                                Select::make('icon')
                                                                    ->label('Icon')
                                                                    ->options(self::iconOptions())
                                                                    ->searchable()
                                                                    ->default('fas fa-circle-check'),

                                                                TextInput::make('title')
                                                                    ->label('Tên bước')
                                                                    ->placeholder('Khảo sát hiện trạng')
                                                                    ->required(),

                                                                Textarea::make('description')
                                                                    ->label('Mô tả ngắn')
                                                                    ->placeholder('Mô tả công việc thực hiện trong từng giai đoạn.')
                                                                    ->rows(3)
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->columns([
                                                                'default' => 1,
                                                                'md' => 2,
                                                            ])
                                                            ->reorderable()
                                                            ->collapsible()
                                                            ->defaultItems(0)
                                                            ->maxItems(8)
                                                            ->columnSpanFull()
                                                            ->addActionLabel('+ Thêm bước'),
                                                    ]),

                                                Tab::make('Kết quả')
                                                    ->schema([
                                                        Repeater::make('achieved_results')
                                                            ->label('Kết quả đạt được')
                                                            ->schema([
                                                                TextInput::make('value')
                                                                    ->label('Số liệu')
                                                                    ->placeholder('24/7'),

                                                                TextInput::make('label')
                                                                    ->label('Nhãn')
                                                                    ->placeholder('Giám sát liên tục')
                                                                    ->required(),

                                                                Textarea::make('description')
                                                                    ->label('Mô tả ngắn')
                                                                    ->placeholder('Mô tả hiệu quả sau khi nghiệm thu và đưa vào vận hành.')
                                                                    ->rows(3)
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->columns([
                                                                'default' => 1,
                                                                'md' => 2,
                                                            ])
                                                            ->reorderable()
                                                            ->collapsible()
                                                            ->defaultItems(0)
                                                            ->maxItems(8)
                                                            ->columnSpanFull()
                                                            ->addActionLabel('+ Thêm kết quả'),
                                                    ]),
                                            ])
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),

                                Section::make('Thư viện ảnh')
                                    ->description('Ảnh công trình, thiết bị, tủ rack, camera, cảm biến, phòng điều khiển...')
                                    ->schema([
                                        CuratorPicker::make('gallery')
                                            ->hiddenLabel()
                                            ->multiple()
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),

                                FaqRepeater::make(),
                            ]),

                        Grid::make()
                            ->columns(1)
                            ->columnSpan([
                                'default' => 1,
                                'xl' => 1,
                            ])
                            ->schema([
                                Section::make('Xuất bản')
                                    ->description('Trạng thái hiển thị và đánh dấu nổi bật.')
                                    ->schema([
                                        Toggle::make('status')
                                            ->label('Kích hoạt')
                                            ->default(true)
                                            ->required(),

                                        Toggle::make('is_home')
                                            ->label('Hiển thị nổi bật ở trang chủ')
                                            ->default(false)
                                            ->required(),

                                        TagSelect::make()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),

                                Section::make('Ảnh đại diện & Banner')
                                    ->description('Ảnh thumbnail dùng cho danh sách, banner dùng cho trang chi tiết.')
                                    ->schema([
                                        CuratorPicker::make('image_id')
                                            ->label('Ảnh đại diện'),

                                        CuratorPicker::make('banner_id')
                                            ->label('Ảnh bìa / Banner'),
                                    ])
                                    ->columns(1),

                                HasSeo::seoSection(),
                            ]),
                    ]),
            ]);
    }

    private static function iconOptions(): array
    {
        return [
            'fas fa-triangle-exclamation' => 'Cảnh báo / Bài toán',
            'fas fa-screwdriver-wrench' => 'Triển khai',
            'fas fa-chart-line' => 'Tăng trưởng',
            'fas fa-chart-pie' => 'Báo cáo',
            'fas fa-cash-register' => 'POS / Bán hàng',
            'fas fa-boxes-stacked' => 'Kho hàng',
            'fas fa-users' => 'Khách hàng / Nhân sự',
            'fas fa-store' => 'Cửa hàng',
            'fas fa-warehouse' => 'Kho vận',
            'fas fa-shield-halved' => 'Bảo mật',
            'fas fa-clipboard-check' => 'Khảo sát',
            'fas fa-lightbulb' => 'Đề xuất',
            'fas fa-file-signature' => 'Ký kết',
            'fas fa-chalkboard-user' => 'Đào tạo',
            'fas fa-circle-check' => 'Hoàn tất / Nghiệm thu',
            'fas fa-headset' => 'Hỗ trợ',

            // Bổ sung hợp với dự án camera / cảm biến an ninh
            'fas fa-video' => 'Camera giám sát',
            'fas fa-camera' => 'Hình ảnh / Quan sát',
            'fas fa-wifi' => 'Kết nối mạng',
            'fas fa-satellite-dish' => 'Truyền tín hiệu',
            'fas fa-fingerprint' => 'Kiểm soát ra vào',
            'fas fa-bell' => 'Cảnh báo',
            'fas fa-microchip' => 'Cảm biến / Thiết bị',
            'fas fa-server' => 'Máy chủ / Lưu trữ',
            'fas fa-network-wired' => 'Hạ tầng mạng',
            'fas fa-lock' => 'An toàn / Bảo vệ',
        ];
    }
}
