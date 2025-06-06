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
                ->label('N°'),
            TextColumn::make('date')
                ->label('Dt de Venc.')
                ->formatStateUsing(
                    fn(string $state): string => (date('d/m/Y', strtotime($state)))
                ),
            TextColumn::make('value')
                ->label('Valor')
                ->money('BRL')
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
            TextInputColumn::make('payment_date')
                ->label('Dt do Pgto')
                ->type('date'),
            SelectColumn::make('payment_method_id')
                ->label('Método de Pagamento')
                ->options(PaymentMethod::all()->pluck('name', 'id')),
            ToggleColumn::make('paid')
                ->sortable()
                ->onColor('success')
                ->offColor('danger')
                ->label('Paga'),
            ToggleColumn::make('invoice_generated')
                ->sortable()
                ->onColor('success')
                ->offColor('danger')
                ->label('Boleto Emitido'),
            TextInputColumn::make('note')
                ->label('Observação'),
            TextInputColumn::make('map_note')
                ->label('Obs. p/ Mapa')
                // ->visible(fn($record) => $record && $record->payment_method_id == 5)
                ->placeholder('Obs. p/ mapa'),
        ];
    }
}
