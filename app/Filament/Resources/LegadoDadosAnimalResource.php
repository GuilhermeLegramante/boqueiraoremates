<?php

namespace App\Filament\Resources;

use App\Models\LegadoDadosAnimal;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class LegadoDadosAnimalResource extends Resource
{
    protected static ?string $model = LegadoDadosAnimal::class;
    // protected static ?string $navigationIcon = 'heroicon-o-archive';
    protected static ?string $navigationLabel = 'Animais';
    protected static ?string $navigationGroup = 'Dados Site Antigo';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('idanimais')->label('id')->sortable(),
                Tables\Columns\TextColumn::make('nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('especie')->sortable(),
                Tables\Columns\TextColumn::make('nlote'),
            ])
            ->filters([])
            ->actions([])  // sem ações de editar/deletar
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LegadoDadosAnimalResource\Pages\ListLegadoDadosAnimals::route('/'),
        ];
    }
}
