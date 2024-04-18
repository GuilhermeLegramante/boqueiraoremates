<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class SendingDocsMethodForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.description'))
                ->unique()
                ->required()
                ->maxLength(255),
        ];
    }
}
