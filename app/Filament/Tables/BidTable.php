<?php

namespace App\Filament\Tables;

use App\Models\PaymentMethod;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;

class BidTable
{
    public static function columns(): array
    {
        return [
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

            TextColumn::make('created_at')
                ->label('Data')
                ->date('d/m/y')
                ->sortable(),

            TextColumn::make('amount')
                ->label('Valor')
                ->money('BRL')
                ->sortable(),

            TextColumn::make('lot_number')
                // ->searchable()
                // ->sortable()
                ->label('Lote'),

            TextColumn::make('event.name')
                ->searchable(['events.name'])
                ->sortable(['events.name'])
                ->size(TextColumnSize::Small)
                ->extraAttributes([
                    'class' => 'text-xs',
                ])
                ->label('Evento')
                ->formatStateUsing(function (?string $state): string {
                    if (!$state) {
                        return '';
                    }

                    $substituicoes = [
                        'LEILÃO VIRTUAL DE COBERTURAS' => 'L. V. Cob.',
                        'LEILÃO VIRTUAL/PRESENCIAL'    => 'L. V./P.',
                        'LEILÃO VIRTUAL'               => 'L. V.',
                        'LEILÃO PRESENCIAL'            => 'L. P.',
                        'LEILÃO DE COBERTURAS'         => 'L. Cob.',
                    ];

                    foreach ($substituicoes as $original => $abreviado) {
                        if (str_starts_with($state, $original)) {
                            return str_replace($original, $abreviado, $state);
                        }
                    }

                    return $state;
                }),

            TextColumn::make('id')->label('ID')
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),

            TextColumn::make('user.name')
                ->label('Cliente')
                ->sortable(false)
                ->copyable()
                ->searchable(['users.name'])
                ->getStateUsing(function ($record) {
                    return $record->user?->name ?? '—';
                }),

            TextColumn::make('animalToFilament.name')
                ->searchable(['animals.name'])
                ->label('Animal')
                ->sortable(['animals.name']),

            TextColumn::make('approvedBy.name')
                ->label('Aprovado por')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

        ];
    }
}
