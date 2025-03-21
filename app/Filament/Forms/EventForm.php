<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class EventForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.name'))
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            DatePicker::make('start_date')
                ->label(__('fields.start_date')),
            DatePicker::make('finish_date')
                ->label(__('fields.finish_date')),
            TextInput::make('multiplier')
                ->label(__('fields.multiplier'))
                ->numeric(),
            Select::make('animals')
                ->label('Animais')
                ->multiple()
                ->relationship('animals', 'name')
                ->preload()
                ->columnSpanFull()
                ->searchable(),
            Textarea::make('note')
                ->label(__('fields.note'))
                ->columnSpanFull()
                ->maxLength(255),
        ];
    }
}
