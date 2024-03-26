<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class OrderStatusForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.name'))
                ->required()
                ->maxLength(255),
            Textarea::make('note')
                ->label(__('fields.note'))
                ->maxLength(255),
        ];
    }
}
