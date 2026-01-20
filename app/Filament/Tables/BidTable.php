<?php

namespace App\Filament\Tables;

use App\Models\PaymentMethod;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;

class BidTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('id')->label('ID')
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),

            // TextColumn::make('client_name')
            //     ->label('Cliente')
            //     ->copyable()
            //     ->getStateUsing(function ($record) {
            //         return $record->user?->client?->name ?? '—';
            //     })
            //     ->sortable(),

            TextColumn::make('client_name')
                ->label('Cliente')
                ->getStateUsing(fn($record) => $record->user?->client?->name ?? '—')
                ->sortable(
                    query: function ($query, string $direction) {
                        $query
                            ->leftJoin('users', 'users.id', '=', 'bids.user_id')
                            ->leftJoin('clients', 'clients.id', '=', 'users.client_id')
                            ->orderBy('clients.name', $direction)
                            ->select('bids.*');
                    }
                ),

            TextColumn::make('event.name')
                ->searchable(['events.name'])
                ->label('Evento')
                ->sortable(['events.name']),

            TextColumn::make('lot_number')
                // ->searchable()
                // ->sortable()
                ->label('Lote'),

            TextColumn::make('animalToFilament.name')
                ->searchable(['animals.name'])
                ->label('Animal')
                ->sortable(['animals.name']),

            TextColumn::make('amount')
                ->label('Valor')
                ->money('BRL')
                ->sortable(),

            IconColumn::make('status')
                ->label('Status')
                ->sortable()
                ->icon(fn($state) => match ($state) {
                    1 => 'heroicon-o-check-circle',   // aprovado
                    2 => 'heroicon-o-x-circle',       // rejeitado
                    default => 'heroicon-o-clock',    // pendente
                })
                ->color(fn($state) => match ($state) {
                    1 => 'success',   // verde
                    2 => 'danger',    // vermelho
                    default => 'warning', // amarelo
                }),

            TextColumn::make('approvedBy.name')
                ->label('Aprovado por')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Data')
                ->dateTime()
                ->sortable(),
        ];
    }
}
