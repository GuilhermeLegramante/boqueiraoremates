<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlantaoConfigResource\Pages;
use App\Filament\Resources\PlantaoConfigResource\RelationManagers;
use App\Models\PlantaoConfig;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class PlantaoConfigResource extends Resource
{
    protected static ?string $model = PlantaoConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Plantões';
    protected static ?string $pluralLabel = 'Plantões';

    protected static ?string $slug = 'plantoes';

    protected static ?string $modelLabel = 'plantão';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('fields.name'))
                    ->required()
                    ->maxLength(255),

                PhoneNumber::make('whatsapp')
                    ->label(__('fields.whatsapp'))
                    ->format('(99) 99999-9999'),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('fields.name'))
                    ->searchable(),

                TextColumn::make('phone')
                    ->label(__('fields.phone'))
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlantaoConfigs::route('/'),
            'create' => Pages\CreatePlantaoConfig::route('/criar'),
            'edit' => Pages\EditPlantaoConfig::route('/{record}/editar'),
        ];
    }
}
