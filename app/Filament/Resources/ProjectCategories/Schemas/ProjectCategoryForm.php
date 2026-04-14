<?php

namespace App\Filament\Resources\ProjectCategories\Schemas;

use App\Filament\Forms\Components\SlugInput;
use App\Models\ProjectCategory;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Traits\HasSeo;

class ProjectCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                Section::make('Thông tin danh mục')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Danh mục cha')
                            ->options(function (?ProjectCategory $record) {
                                return [0 => '-- Danh mục gốc --'] + ProjectCategory::getTreeOptions(optional($record)->id);
                            })
                            ->searchable()
                            ->default(0),

                        TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->afterStateUpdated(SlugInput::autoSlug('slug')),

                        SlugInput::make('slug'),

                        CuratorPicker::make('image_id')
                            ->label('Ảnh đại diện'),
                        CuratorPicker::make('banner_id')
                            ->label('Banner'),

                        Textarea::make('description')
                            ->label('Mô tả ngắn')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Nội dung chi tiết')
                            ->columnSpanFull(),

                        Toggle::make('status')
                            ->label('Kích hoạt')
                            ->default(true),
                    ])->columns(2),

                HasSeo::seoSection(),
            ]);
    }
}
