<?php

namespace App\Filament\Tables;

use App\Models\PaymentMethod;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;

class ParcelsTable
{
    public static function table(): array
    {
        return [
            TextColumn::make('number')
                ->toggleable(isToggledHiddenByDefault: false)
                ->label('N°'),
            TextColumn::make('date')
                ->toggleable(isToggledHiddenByDefault: false)
                ->label('Dt de Venc.')
                ->formatStateUsing(
                    fn(string $state): string => (date('d/m/Y', strtotime($state)))
                ),
            TextColumn::make('order.event.name')
                ->label('Evento')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
            TextColumn::make('value')
                ->label('Valor')
                ->money('BRL')
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
            TextInputColumn::make('payment_date')
                ->toggleable(isToggledHiddenByDefault: false)
                ->label('Dt do Pgto')
                ->type('date'),
            SelectColumn::make('payment_method_id')
                ->toggleable(isToggledHiddenByDefault: false)
                ->label('Método de Pagamento')
                ->options(PaymentMethod::all()->pluck('name', 'id')),
            ToggleColumn::make('paid')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->onColor('success')
                ->offColor('danger')
                ->label('Paga'),
            ToggleColumn::make('invoice_generated')
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable()
                ->onColor('success')
                ->offColor('danger')
                ->label('Boleto Emitido'),
            TextInputColumn::make('note')
                ->toggleable(isToggledHiddenByDefault: false)
                ->label('Observação'),
            TextInputColumn::make('map_note')
                ->toggleable(isToggledHiddenByDefault: false)
                ->label('Obs. p/ Mapa')
                // ->visible(fn($record) => $record && $record->payment_method_id == 5)
                ->placeholder('Obs. p/ mapa'),
        ];
    }
}
