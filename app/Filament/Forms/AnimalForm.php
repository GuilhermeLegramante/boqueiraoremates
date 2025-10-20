<?php

namespace App\Filament\Forms;

use App\Models\Breed;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;

class AnimalForm
{
    public static function form(): array
    {
        return [
            // FileUpload::make('photo')
            //     ->label('Foto (Miniatura)')
            //     ->image()
            //     ->openable()
            //     ->downloadable()
            //     ->directory('animals/photos') // pasta onde será armazenada
            //     ->visibility('public')
            //     ->maxSize(2048),
            // FileUpload::make('photo_full')
            //     ->label('Foto (Grande)')
            //     ->image()
            //     ->openable()
            //     ->downloadable()
            //     ->directory('animals/photos') // pasta onde será armazenada
            //     ->visibility('public')
            //     ->maxSize(2048),
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
                // ->unique(ignoreRecord: true)
                ->visible(fn(Get $get): bool => self::isCrioulo($get('breed_id'))),
            TextInput::make('rb')
                ->live()
                ->label(__('fields.rb'))
                ->visible(fn(Get $get): bool => self::isCrioulo($get('breed_id'))),
            TextInput::make('register')
                ->label(__('fields.register'))
                ->numeric()
                ->live()
                ->visible(fn(Get $get): bool => self::isQuartoDeMilha($get('breed_id'))),
            Radio::make('blood_level')
                ->label(__('fields.blood_level'))
                ->live()
                ->options([
                    'pure' => 'Puro',
                    'mixed' => 'Mestiço',
                ])
                ->visible(fn(Get $get): bool => self::isQuartoDeMilha($get('breed_id'))),
            TextInput::make('blodd_percentual')
                ->label(__('fields.blodd_percentual'))
                ->live()
                ->numeric()
                ->visible(fn(Get $get): bool => $get('blood_level') == 'mixed'),
            Radio::make('breeding')
                ->label(__('fields.breeding'))
                ->live()
                ->options([
                    'breeder' => 'Reprodutor',
                    'whole_male' => 'Macho Inteiro',
                    'castrated' => 'Castrado'
                ])
                ->visible(fn(Get $get): bool => $get('gender') == 'male'),
            TextInput::make('quantity')
                ->label(__('fields.quantity'))
                ->numeric(),
            DatePicker::make('birth_date')
                ->maxDate(now())
                ->label('Data de Nascimento'),
            // Textarea::make('note')
            //     ->label('Comentário')
            //     ->columnSpanFull()
            //     ->rows(4)
            //     ->maxLength(65535),
            // TextInput::make('video_link')
            //     ->label('Link do Vídeo')
            //     ->url() // valida como URL
            //     ->placeholder('https://youtube.com/...') // opcional
            //     ->columnSpan('full'), // ocupa toda a largura do form
            TextInput::make('generation_link')
                ->label('Link da Quinta Geração')
                ->url() // valida como URL
                ->placeholder('https://...')
                ->columnSpan('full')
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
