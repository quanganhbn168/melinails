<?php

namespace App\Filament\Resources\FieldCategories\Schemas;

use App\Filament\Forms\Components\SlugInput;
use App\Models\FieldCategory;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FieldCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin danh mục lĩnh vực')->schema([
                    Select::make('parent_id')
                        ->label('Danh mục cha')
                        ->options(function (?FieldCategory $record) {
                            return [0 => '-- Danh mục gốc --'] + FieldCategory::getTreeOptions(optional($record)->id);
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
                        ->label('Ảnh đại diện / Icon')
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Mô tả ngắn')
                        ->rows(3)
                        ->columnSpanFull(),

                    RichEditor::make('content')
                        ->label('Nội dung chi tiết')
                        ->columnSpanFull(),
                ])->columns(2),

                Section::make('Cài đặt')->schema([
                    TextInput::make('position')
                        ->label('Vị trí hiển thị (Position)')
                        ->numeric()
                        ->default(0),
                        
                    TextInput::make('order')
                        ->label('Thứ tự sắp xếp (Order)')
                        ->numeric()
                        ->default(0),

                    Toggle::make('status')
                        ->label('Kích hoạt')
                        ->default(true),
                ])->columns(3),
            ]);
    }
}
