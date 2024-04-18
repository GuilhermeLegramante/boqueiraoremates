<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class PaymentWayForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.description'))
                ->regex('/^[0-9+]+$/')
                ->required()
                ->unique()
                ->maxLength(255),
        ];
    }
}
