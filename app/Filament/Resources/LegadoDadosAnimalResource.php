<?php

namespace App\Filament\Resources;

use App\Models\LegadoDadosAnimal;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class LegadoDadosAnimalResource extends Resource
{
    protected static ?string $model = LegadoDadosAnimal::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Animais (Site antigo)';
    protected static ?string $navigationGroup = 'Dados Site Antigo';

    protected static ?string $modelLabel = 'Animal (Site antigo)';
    protected static ?string $pluralModelLabel = 'Animais (Site antigo)';
    protected static ?string $slug = 'animais-site-antigo';

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
