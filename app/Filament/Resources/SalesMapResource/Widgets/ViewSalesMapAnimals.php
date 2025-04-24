<?php

namespace App\Filament\Resources\SalesMapResource\Widgets;

use App\Models\Animal;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;

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
            // ->query(
            //     fn() => Order::where('event_id', $this->record->event_id)
            //         ->with(['animal', 'seller.address']) // Garantir que carregamos os relacionamentos necessários
            // )
            ->query(function () {
                $orders = Order::where('event_id', $this->record->event_id)
                    ->with(['animal', 'seller.address']) // Relacionamentos
                    ->get(); // Executa a query aqui

                // dd($orders->first()); // Exibe o primeiro resultado para inspecionar

                return Order::where('event_id', $this->record->event_id)
                    ->with(['animal', 'seller.address']);
            })
            ->columns([
                TextColumn::make('number')
                    ->label('OS')
                    ->searchable(),

                TextColumn::make('batch')
                    ->label('Lote')
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->batch ?? 'SEM LOTE'),

                TextColumn::make('animal.name')
                    ->label('Animal')
                    ->searchable(),

                TextColumn::make('seller.name')
                    ->label('Vendedor')
                    ->searchable()
                    ->default('SEM VENDA'),

                TextColumn::make('seller.address.city')
                    ->label('Cidade')
                    ->searchable()
                    ->default('-'),

                TextColumn::make('parcel_value')
                    ->label('Parcela Calculada')
                    ->getStateUsing(function ($record) {
                        if ($record->gross_value && $record->multiplier) {
                            return $record->gross_value / $record->multiplier;
                        }
                        return 0;
                    })
                    ->alignEnd()
                    ->numeric()
                    ->money('BRL'),

                TextColumn::make('gross_value')
                    ->label('Faturamento')
                    ->money('BRL')
                    ->alignEnd()
                    ->summarize([
                        Sum::make()->label('Faturamento Total')->money('BRL'),
                        Average::make()->label('Média Geral por Lote')->money('BRL'),
                    ]),
            ])
            ->filters([
                SelectFilter::make('seller')
                    ->label('Vendedor')
                    ->searchable()
                    ->relationship('seller', 'name'),
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->defaultSort('batch');
    }
}
