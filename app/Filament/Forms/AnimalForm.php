<?php

namespace App\Filament\Forms;

use App\Models\Breed;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;

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
                ->live()
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
            TextInput::make('mother')
                ->label(__('fields.mother')),
            TextInput::make('father')
                ->label(__('fields.father')),
            Radio::make('gender')
                ->label(__('fields.gender'))
                ->live()
                ->options([
                    'male' => 'MACHO',
                    'female' => 'FÊMEA',
                ]),
            TextInput::make('sbb')
                ->label(__('fields.sbb'))
                ->maxLength(7)
                ->live()
                ->nullable()
                ->unique(ignoreRecord: true)
                ->visible(fn (Get $get): bool => self::isCrioulo($get('breed_id'))),
            TextInput::make('rb')
                ->live()
                ->nullable()
                ->unique(ignoreRecord: true)
                ->label(__('fields.rb'))
                ->visible(fn (Get $get): bool => self::isCrioulo($get('breed_id'))),
            TextInput::make('register')
                ->label(__('fields.register'))
                ->numeric()
                ->live()
                ->nullable()
                ->unique(ignoreRecord: true)
                ->visible(fn (Get $get): bool => self::isQuartoDeMilha($get('breed_id'))),
            Radio::make('blood_level')
                ->label(__('fields.blood_level'))
                ->live()
                ->options([
                    'pure' => 'Puro',
                    'mixed' => 'Mestiço',
                ])
                ->visible(fn (Get $get): bool => self::isQuartoDeMilha($get('breed_id'))),
            TextInput::make('blodd_percentual')
                ->label(__('fields.blodd_percentual'))
                ->live()
                ->numeric()
                ->visible(fn (Get $get): bool => $get('blood_level') == 'mixed'),
            Radio::make('breeding')
                ->label(__('fields.breeding'))
                ->live()
                ->options([
                    'breeder' => 'Reprodutor',
                    'whole_male' => 'Macho Inteiro',
                    'castrated' => 'Castrado'
                ])
                ->visible(fn (Get $get): bool => $get('gender') == 'male'),
            TextInput::make('quantity')
                ->label(__('fields.quantity'))
                ->numeric(),
            DatePicker::make('birth_date')
                ->maxDate(now())
                ->label('Data de Nascimento'),
        ];
    }

    private static function isQuartoDeMilha($breedId): bool
    {
        $breed = Breed::where('id', $breedId)->get()->first();

        if (isset($breed) && $breed->name == 'QUARTO DE MILHA') {
            return true;
        } else {
            return false;
        }
    }

    private static function isCrioulo($breedId): bool
    {
        $breed = Breed::where('id', $breedId)->get()->first();

        if (isset($breed) && $breed->name == 'CRIOULA') {
            return true;
        } else {
            return false;
        }
    }
}
