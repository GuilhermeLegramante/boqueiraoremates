<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class BreedForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.name'))
                ->required()
                ->unique()
                ->maxLength(255),
        ];
    }
}
