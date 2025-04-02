<?php

namespace App\Filament\Resources\SalesMapResource\Widgets;

use App\Models\Animal;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Support\Facades\DB;

class ViewSalesMapAnimals extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public $record;

    public function mount($record)
    {
        $this->record = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading($this->record->event->name)
            ->query(fn() => Animal::whereHas(
                'events',
                fn($query) => $query->where('event_id', $this->record->event_id)
            )
                ->withSum([
                    'orders as total_gross_value' => fn($query) =>
                    $query->where('event_id', $this->record->event_id)
                ], 'gross_value'))
            ->columns([
                TextColumn::make('orders.0.batch')
                    ->label('Lote')
                    ->sortable(false)
                    ->formatStateUsing(fn($record) => $record->orders[0]->batch ?? 'SEM LOTE'),

                TextColumn::make('name')
                    ->label('Animal')
                    ->sortable(false),
                    // ->summarize([
                    //     Summarizer::make()
                    //         ->label('Lotes Vendidos')
                    //         ->using(fn() => \App\Models\Order::where('event_id', $this->record->id)->count()),
                    // ]),

                TextColumn::make('orders.0.seller.name')
                    ->label('Vendedor')
                    ->default('SEM VENDA')
                    ->sortable(false)
                    ->formatStateUsing(fn($record) => isset($record->orders[0]) ? $record->orders[0]->seller->name : 'SEM VENDA'),

                TextColumn::make('orders.0.seller.address.city')
                    ->label('Cidade')
                    ->default('-')
                    ->sortable(false)
                    ->formatStateUsing(fn($record) => isset($record->orders[0]) ? $record->orders[0]->seller->address->city : '-'),

                TextColumn::make('orders.0.parcel_value')
                    ->label('Parcela')
                    ->numeric()
                    ->money('BRL')
                    ->sortable(false)
                    ->formatStateUsing(fn($record) => $record->orders[0]->parcel_value ?? 0.00),

                TextColumn::make('total_gross_value')
                    ->label('Faturamento')
                    ->money('BRL')
                    ->sortable(false)
                    ->summarize([
                        Sum::make()->label('Faturamento Total')->money('BRL'),
                        Average::make()->label('Média Geral por Lote')->money('BRL'),
                    ]),
            ])
            ->defaultSort('name');
    }
}
