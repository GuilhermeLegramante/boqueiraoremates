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
                fn($query) =>
                $query->where('event_id', $this->record->event_id)
            )
                ->withSum([
                    'orders as total_gross_value' => fn($query) =>
                    $query->where('event_id', $this->record->event_id)
                ], 'gross_value'))
            ->columns([
                TextColumn::make('orders.0.batch')->label('Lote')->sortable(),
                TextColumn::make('name')->label('Animal')
                    ->sortable()
                    ->summarize([
                        Summarizer::make()
                            ->label('Lotes Vendidos')
                            ->using(fn() => \App\Models\Order::where('event_id', $this->record->id)->count()),

                    ]),
                TextColumn::make('orders.0.seller.name') // Pega a única order do evento
                    ->label('Vendedor')
                    ->default('SEM VENDA')
                    ->sortable()
                    ->summarize([
                        Summarizer::make()
                            ->label('Média Geral (Todos os Animais)')
                            ->money('BRL')
                            ->using(
                                fn() => \App\Models\Animal::leftJoin('orders', function ($join) {
                                    $join->on('animals.id', '=', 'orders.animal_id')
                                        ->where('orders.event_id', $this->record->id);
                                })
                                    ->whereHas('events', fn($query) => $query->where('event_id', $this->record->id))
                                    ->avg(DB::raw('IFNULL(orders.gross_value, 0)'))
                            ),

                    ]),
                TextColumn::make('orders.0.seller.address.city')
                    ->label('Cidade')
                    ->default('-')
                    ->sortable()
                    ->summarize([
                        Summarizer::make()
                            ->label('Média de Faturamento (Machos)')
                            ->money('BRL')
                            ->using(fn() => \App\Models\Order::whereIn('animal_id', function ($query) {
                                $query->select('id')
                                    ->from('animals')
                                    ->where('gender', 'male');
                            })->where('event_id', $this->record->id)
                                ->avg('gross_value') ?? 0.00),

                    ]),
                TextColumn::make('orders.0.parcel_value')
                    ->label('Parcela')
                    ->numeric()
                    ->money('BRL')
                    ->summarize([
                        Summarizer::make()
                            ->label('Média de Faturamento (Fêmeas)')
                            ->money('BRL')
                            ->using(fn() => \App\Models\Order::whereIn('animal_id', function ($query) {
                                $query->select('id')
                                    ->from('animals')
                                    ->where('gender', 'female');
                            })->where('event_id', $this->record->id)
                                ->avg('gross_value') ?? 0.00),
                    ]),
                TextColumn::make('total_gross_value')
                    ->label('Faturamento')
                    ->money('BRL')
                    ->sortable()
                    ->summarize([
                        Sum::make()->label('Faturamento Total')->money('BRL'),
                        Average::make()->label('Média Geral por Lote')->money('BRL'),
                    ]),
            ])
            ->defaultSort('name');
    }
}
