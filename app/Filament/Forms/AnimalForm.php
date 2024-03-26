<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AnimalForm
{
    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('fields.name'))
                ->required()
                ->maxLength(255),
            Select::make('breed_id')
                ->label(__('fields.breed'))
                ->relationship('breed', 'name')
                ->createOptionForm(BreedForm::form()),
            Select::make('coat_id')
                ->label(__('fields.coat'))
                ->relationship('coat', 'name')
                ->createOptionForm(CoatForm::form()),
            Select::make('animal_type_id')
                ->label(__('fields.animal_type'))
                ->relationship('animalType', 'name')
                ->createOptionForm(AnimalTypeForm::form()),
            Radio::make('gender')
                ->label(__('fields.gender'))
                ->options([
                    'male' => 'Masculino',
                    'female' => 'Feminino',
                ]),
            TextInput::make('register')
                ->label(__('fields.register'))
                ->numeric(),
            TextInput::make('sbb')
                ->label(__('fields.sbb'))
                ->maxLength(7),
            TextInput::make('rb')
                ->label(__('fields.rb')),
            TextInput::make('mother')
                ->label(__('fields.mother')),
            TextInput::make('father')
                ->label(__('fields.father')),
            Radio::make('blood_level')
                ->label(__('fields.blood_level'))
                ->options([
                    'pure' => 'Puro',
                    'mixed' => 'Mestiço',
                ]),
            TextInput::make('blodd_percentual')
                ->label(__('fields.blodd_percentual'))
                ->numeric(),
            Radio::make('breeding')
                ->label(__('fields.breeding'))
                ->options([
                    'breeder' => 'Reprodutor',
                    'whole_male' => 'Macho Inteiro',
                    'castrated' => 'Castrado'
                ]),
            TextInput::make('quantity')
                ->label(__('fields.quantity'))
                ->numeric(),
        ];
    }
}
