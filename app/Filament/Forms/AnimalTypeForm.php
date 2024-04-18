<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class AnimalTypeForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.name'))
                ->unique()
                ->required()
                ->maxLength(255),
        ];
    }
}
