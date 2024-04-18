<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class PaymentMethodForm
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
