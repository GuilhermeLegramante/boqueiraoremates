<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CityForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.name'))
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            TextInput::make('code')
                ->label(__('fields.code'))
                ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Código do IBGE')
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            Select::make('state_id')
                ->label(__('fields.state'))
                ->required()
                ->columnSpanFull()
                ->relationship(name: 'state', titleAttribute: 'acronym'),
        ];
    }
}
