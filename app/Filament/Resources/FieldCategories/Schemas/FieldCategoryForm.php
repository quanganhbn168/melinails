<?php

namespace App\Filament\Resources\FieldCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FieldCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('parent_id')
                    ->relationship('parent', 'name'),
                TextInput::make('description'),
                TextInput::make('content'),
                Select::make('image_id')
                    ->relationship('image', 'name'),
                Toggle::make('status')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
