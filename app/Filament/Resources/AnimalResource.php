<?php

namespace App\Filament\Resources;

use App\Filament\Forms\AnimalForm;
use App\Filament\Resources\AnimalResource\Pages;
use App\Filament\Resources\AnimalResource\RelationManagers;
use App\Models\Animal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AnimalResource extends Resource
{
    protected static ?string $model = Animal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'animal';

    protected static ?string $pluralModelLabel = 'animais';

    // protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'animais';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(AnimalForm::form());
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('fields.code'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('fields.name'))
                    ->searchable(),
                TextColumn::make('breed.name')
                    ->label(__('fields.breed'))
                    ->searchable(),
                TextColumn::make('coat.name')
                    ->label(__('fields.coat'))
                    ->searchable(),
                TextColumn::make('animalType.name')
                    ->label(__('fields.animal_type'))
                    ->searchable(),
                TextColumn::make('gender')
                    ->label(__('fields.gender'))
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => (($state == 'male' ? 'MACHO' : ($state == 'female' ? 'FÊMEA' : '')))
                    )
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'danger',
                    }),
                TextColumn::make('register')
                    ->label(__('fields.register'))
                    ->searchable(),
                TextColumn::make('sbb')
                    ->label(__('fields.sbb'))
                    ->searchable(),
                TextColumn::make('rb')
                    ->label(__('fields.rb'))
                    ->searchable(),
                TextColumn::make('mother')
                    ->label(__('fields.mother'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('father')
                    ->label(__('fields.father'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('blood_level')
                    ->label(__('fields.blood_level'))
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => (($state == 'pure' ? 'PURO' : ($state == 'mixed' ? 'MESTIÇO' : '')))
                    )
                    ->color(fn (string $state): string => match ($state) {
                        'pure' => 'success',
                        'mixed' => 'warning',
                    }),
                TextColumn::make('breeding')
                    ->label(__('fields.breeding'))
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => (($state == 'breeder' ? 'REPRODUTOR' : ($state == 'whole_male' ? 'MACHO INTEIRO' : ($state == 'castrated' ? 'CASTRADO' : ''))))
                    )
                    ->color(fn (string $state): string => match ($state) {
                        'breeder' => 'primary',
                        'whole_male' => 'gray',
                        'castrated' => 'danger,'
                    }),
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['name'] = Str::upper($data['name']);
                        $data['mother'] = Str::upper($data['mother']);
                        $data['father'] = Str::upper($data['father']);

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAnimals::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
