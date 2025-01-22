<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Filament\Forms\AnimalForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Support\Str;

class AnimalsRelationManager extends RelationManager
{
    protected static string $relationship = 'animals';

    protected static ?string $title = 'Animais';

    protected static ?string $label = 'Animal';

    protected static ?string $pluralLabel = 'Animais';


    public function form(Form $form): Form
    {
        return $form
            ->schema(AnimalForm::form());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')
                    ->label(__('fields.code'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('fields.name'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('breed.name')
                    ->label(__('fields.breed'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('coat.name')
                    ->label(__('fields.coat'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('animalType.name')
                    ->label(__('fields.animal_type'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('gender')
                    ->label(__('fields.gender'))
                    ->alignment(Alignment::Center)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => (($state == 'male' ? 'MACHO' : ($state == 'female' ? 'FÊMEA' : '')))
                    )
                    ->color(fn(string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'danger',
                    }),
                TextColumn::make('register')
                    ->label(__('fields.register'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('sbb')
                    ->label(__('fields.sbb'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('rb')
                    ->label(__('fields.rb'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('mother')
                    ->label(__('fields.mother'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('father')
                    ->label(__('fields.father'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('blood_level')
                    ->label(__('fields.blood_level'))
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(
                        fn(string $state): string => (($state == 'pure' ? 'PURO' : ($state == 'mixed' ? 'MESTIÇO' : '')))
                    )
                    ->color(fn(string $state): string => match ($state) {
                        'pure' => 'success',
                        'mixed' => 'warning',
                    }),
                TextColumn::make('breeding')
                    ->label(__('fields.breeding'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => (($state == 'breeder' ? 'REPRODUTOR' : ($state == 'whole_male' ? 'MACHO INTEIRO' : ($state == 'castrated' ? 'CASTRADO' : ''))))
                    )
                    ->color(fn(string $state): string => match ($state) {
                        'breeder' => 'primary',
                        'whole_male' => 'gray',
                        'castrated' => 'danger,'
                    }),
                TextColumn::make('birth_date')
                    ->label('Data de Nascimento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('breed')
                    ->label(__('fields.breed'))
                    ->relationship('breed', 'name')
                    ->preload(),
                SelectFilter::make('coat')
                    ->label(__('fields.coat'))
                    ->relationship('coat', 'name')
                    ->preload(),
                SelectFilter::make('animalType')
                    ->label(__('fields.animal_type'))
                    ->relationship('animalType', 'name')
                    ->preload(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Download')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(date('d-m-Y') . ' - Animais')
                    ])
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->mutateRecordDataUsing(function (array $data): array {
                            $data['name'] = Str::upper($data['name']);
                            $data['mother'] = Str::upper($data['mother']);
                            $data['father'] = Str::upper($data['father']);

                            return $data;
                        }),
                    // Tables\Actions\DeleteAction::make(),
                ]),

            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('Download'),
                ]),
            ]);
    }
}
