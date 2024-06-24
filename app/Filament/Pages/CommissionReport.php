<?php

namespace App\Filament\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Pages\Page;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class CommissionReport extends Resource
{
    // use HasPageShield;

    protected static ?string $title = 'Comissões por OS';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.commission-report';

    protected static ?string $pluralLabel = 'Comissões por OS';

    protected static ?string $label = 'Comissão por OS';

    protected static ?string $navigationLabel = 'Comissão por OS';

    protected static ?string $slug = 'comissao-por-os';

    protected static ?string $navigationGroup = 'Relatórios';

    protected static string $resource = OrderResource::class;

    protected static ?string $model = Order::class;

    protected static bool $shouldRegisterNavigation = true;


    protected function getTableQuery(): Builder
    {
        return Order::query();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number'),
                // Adicione outras colunas conforme necessário
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // public static function table(Table $table): Table
    // {
    //     dd($table);
    //     return $table
    //         ->columns([
    //             TextColumn::make('number')
    //                 ->label('Número')
    //                 ->searchable()
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //             TextColumn::make('base_date')
    //                 ->label('Data da Negociação')
    //                 ->date()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //             TextColumn::make('event.name')
    //                 ->label('Evento')
    //                 ->searchable(),
    //             TextColumn::make('seller.name')
    //                 ->label('Vendedor')
    //                 ->toggleable(isToggledHiddenByDefault: false)
    //                 ->searchable(),
    //             TextColumn::make('buyer.name')
    //                 ->label('Comprador')
    //                 ->toggleable(isToggledHiddenByDefault: false)
    //                 ->searchable(),
    //             TextColumn::make('animal.name')
    //                 ->label('Animal')
    //                 ->toggleable(isToggledHiddenByDefault: false)
    //                 ->searchable(),
    //             TextColumn::make('gross_value')
    //                 ->label('Base de Cálculo')
    //                 ->money('BRL')
    //                 // ->summarize(Sum::make()->label('Total Valor Bruto')->money('BRL'))
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //             TextColumn::make('buyer_commission')
    //                 ->label('% Comprador')
    //                 ->suffix('%')
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //             TextColumn::make('buyer_comission_value')
    //                 ->label('Comissão Comprador')
    //                 ->money('BRL')
    //                 ->summarize(Summarizer::make()
    //                     ->label('Total Comissão Comprador')
    //                     ->money('BRL')
    //                     ->using(
    //                         fn (DatabaseBuilder $query): float =>
    //                         $query
    //                             ->sum(
    //                                 DB::raw('(gross_value * buyer_commission) / 100')
    //                             )
    //                     ))
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //             TextColumn::make('seller_commission')
    //                 ->label('% Vendedor')
    //                 ->suffix('%')
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //             TextColumn::make('seller_comission_value')
    //                 ->label('Comissão Vendedor')
    //                 ->money('BRL')
    //                 ->summarize(Summarizer::make()
    //                     ->label('Total Comissão Vendedor')
    //                     ->money('BRL')
    //                     ->using(
    //                         fn (DatabaseBuilder $query): float =>
    //                         $query
    //                             ->sum(
    //                                 DB::raw('(gross_value * seller_commission) / 100')
    //                             )
    //                     ))
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //             TextColumn::make('total_commission')
    //                 ->label('Comissão Total')
    //                 ->money('BRL')
    //                 ->summarize(Summarizer::make()
    //                     ->label('Total Comissão Total')
    //                     ->money('BRL')
    //                     ->using(
    //                         fn (DatabaseBuilder $query): float =>
    //                         $query
    //                             ->sum(
    //                                 DB::raw('((gross_value * buyer_commission) / 100) + ((gross_value * seller_commission) / 100)')
    //                             )
    //                     ))
    //                 ->toggleable(isToggledHiddenByDefault: false),
    //         ])
    //         ->defaultSort('number', 'desc')
    //         ->groups([
    //             Group::make('status.name')
    //                 ->label('Status')
    //                 ->collapsible(),
    //             Group::make('event.name')
    //                 ->label('Evento')
    //                 ->collapsible(),
    //             Group::make('seller.name')
    //                 ->label('Vendedor')
    //                 ->collapsible(),
    //             Group::make('buyer.name')
    //                 ->label('Comprador')
    //                 ->collapsible(),
    //         ])
    //         ->filters([
    //             // SelectFilter::make('buyer')
    //             //     ->label('Comprador')
    //             //     ->searchable()
    //             //     ->relationship('buyer', 'name'),
    //             // SelectFilter::make('seller')
    //             //     ->label('Vendedor')
    //             //     ->searchable()
    //             //     ->relationship('seller', 'name'),
    //             // SelectFilter::make('status')
    //             //     ->label('Status')
    //             //     // ->default(1)
    //             //     ->relationship('status', 'name'),
    //             // SelectFilter::make('event')
    //             //     ->label('Evento')
    //             //     ->relationship('event', 'name'),
    //             Filter::make('base_date')
    //                 ->form([
    //                     DatePicker::make('created_from')->label('Data de Negociação (Inicial)'),
    //                     DatePicker::make('created_until')->label('Data de Negociação (Final)'),
    //                 ])
    //                 ->query(function (Builder $query, array $data): Builder {
    //                     return $query
    //                         ->when(
    //                             $data['created_from'],
    //                             fn (Builder $query, $date): Builder => $query->whereDate('base_date', '>=', $date),
    //                         )
    //                         ->when(
    //                             $data['created_until'],
    //                             fn (Builder $query, $date): Builder => $query->whereDate('base_date', '<=', $date),
    //                         );
    //                 })
    //         ])
    //         ->deferFilters()
    //         ->filtersApplyAction(
    //             fn (Action $action) => $action
    //                 ->link()
    //                 ->label('Aplicar Filtro(s)'),
    //         )
    //         ->headerActions([
    //             // ExportAction::make()
    //             //     ->label('Download')
    //             //     ->exports([
    //             //         ExcelExport::make()
    //             //             ->fromTable()
    //             //             ->withFilename(date('d-m-Y') . ' - Ordens de Serviço')
    //             //     ])
    //         ])
    //         ->actions([
    //             ActionGroup::make([
    //                 // Tables\Actions\ViewAction::make(),
    //                 Action::make('report')
    //                     ->label('Gerar PDF')
    //                     ->icon('heroicon-o-document-text')
    //                     ->color('info')
    //                     ->url(fn (Order $record): string => route('order-pdf', $record->id))
    //                     ->openUrlInNewTab(),
    //             ]),
    //         ], position: ActionsPosition::BeforeColumns)
    //         ->bulkActions([
    //             // BulkActionGroup::make([
    //             //     DeleteBulkAction::make(),
    //             //     ExportBulkAction::make()->label('Download'),
    //             // ]),
    //         ]);
    // }

    public static function canCreate(): bool
    {
        return false;
    }

    protected function getActions(): array
    {
        return [
            //            Actions\CreateAction::make(),
        ];
    }
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // AppointmentOverview::class,
        ];
    }
}
