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
            ->query(
                fn() => Order::where('event_id', $this->record->event_id)
                    ->with(['animal', 'seller.address']) // Garantir que carregamos os relacionamentos necessários
            )
            ->columns([
                TextColumn::make('number')
                    ->label('OS')
                    ->sortable(false),

                TextColumn::make('batch')
                    ->label('Lote')
                    ->sortable(false)
                    ->formatStateUsing(fn($record) => $record->batch ?? 'SEM LOTE'),

                TextColumn::make('animal.name')
                    ->label('Animal')
                    ->sortable(false),

                TextColumn::make('seller.name')
                    ->label('Vendedor')
                    ->default('SEM VENDA')
                    ->sortable(false),

                TextColumn::make('seller.address.city')
                    ->label('Cidade')
                    ->default('-')
                    ->sortable(false),

                TextColumn::make('parcel_value')
                    ->label('Parcela')
                    ->numeric()
                    ->money('BRL')
                    ->sortable(false),

                TextColumn::make('gross_value')
                    ->label('Faturamento')
                    ->money('BRL')
                    ->sortable(false)
                    ->summarize([
                        Sum::make()->label('Faturamento Total')->money('BRL'),
                        Average::make()->label('Média Geral por Lote')->money('BRL'),
                    ]),
            ])
            ->defaultSort('batch');
    }
}
