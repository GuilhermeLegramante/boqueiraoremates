<?php

namespace App\Filament\Resources;

use App\Models\LegadoCliente;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LegadoClienteResource extends Resource
{
    protected static ?string $model = LegadoCliente::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Clientes';
    // protected static ?string $navigationGroup = 'Dados Site Antigo';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('idclientes')->label('id')->sortable(),
                Tables\Columns\TextColumn::make('usuario')->searchable(),
                Tables\Columns\TextColumn::make('nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->filters([])
            ->actions([])  // sem ações de editar/deletar
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LegadoClienteResource\Pages\ListLegadoClientes::route('/'),
        ];
    }
}
