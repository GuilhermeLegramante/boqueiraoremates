<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BankForm
{
    public static function form(): array
    {
        return [
            TextInput::make('code')
                ->label(__('fields.code'))
                ->required()
                ->maxLength(255),
            TextInput::make('name')
                ->label(__('fields.name'))
                ->required()
                ->maxLength(255),
        ];
    }
}
