<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class DocumentTypeForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.name'))
                ->required()
                ->maxLength(255),
        ];
    }
}
