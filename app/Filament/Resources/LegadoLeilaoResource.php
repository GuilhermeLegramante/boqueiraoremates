<?php

namespace App\Filament\Resources;

use App\Models\LegadoLeilao;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class LegadoLeilaoResource extends Resource
{
    protected static ?string $model = LegadoLeilao::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationLabel = 'Leilões (Site antigo)';
    protected static ?string $navigationGroup = 'Dados Site Antigo';

    protected static ?string $modelLabel = 'Leilão (Site antigo)';
    protected static ?string $pluralModelLabel = 'Leilões (Site antigo)';
    protected static ?string $slug = 'leiloes-site-antigo';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('idleilao')
                    ->label('id')->sortable(),
                Tables\Columns\TextColumn::make('nomeleilao')
                    ->label('Nome')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('dataleilao')->label('Data'),
                Tables\Columns\TextColumn::make('publicado'),
            ])
            ->filters([])
            ->actions([])  // sem ações de editar/deletar
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LegadoLeilaoResource\Pages\ListLegadoLeilaos::route('/'),
        ];
    }
}
