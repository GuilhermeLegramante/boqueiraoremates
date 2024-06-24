<?php

namespace App\Filament\Resources;

use App\Filament\Forms\OrderForm;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\BuyerParcelsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\ParcelsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\SellerParcelsRelationManager;
use App\Filament\Resources\OrderResource\Widgets\ActiveOrdersChart;
use App\Filament\Resources\OrderResource\Widgets\OrderStatsOverview;
use App\Models\Order;
use App\Utils\ReportFactory;
use Barryvdh\DomPDF\PDF;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class CommissionResource extends Resource
{
    // use HasPageShield;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $modelLabel = 'comissão por OS';

    protected static ?string $pluralModelLabel = 'comissões por OS';

    protected static ?string $slug = 'comissao-por-os';

    protected static ?string $navigationGroup = 'Relatórios';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(OrderForm::form());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Número')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('base_date')
                    ->label('Data da Negociação')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('event.name')
                    ->label('Evento')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('seller.name')
                    ->label('Vendedor')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('buyer.name')
                    ->label('Comprador')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('animal.name')
                    ->label('Animal')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('gross_value')
                    ->label('Base de Cálculo')
                    ->money('BRL')
                    // ->summarize(Sum::make()->label('Total Valor Bruto')->money('BRL'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('buyer_commission')
                    ->label('% Comprador')
                    ->suffix('%')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('buyer_comission_value')
                    ->label('Comissão Comprador')
                    ->money('BRL')
                    ->summarize(Summarizer::make()
                        ->label('Total Comissão Comprador')
                        ->money('BRL')
                        ->using(
                            fn (DatabaseBuilder $query): float =>
                            $query
                                ->sum(
                                    DB::raw('(gross_value * buyer_commission) / 100')
                                )
                        ))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('seller_commission')
                    ->label('% Vendedor')
                    ->suffix('%')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('seller_comission_value')
                    ->label('Comissão Vendedor')
                    ->money('BRL')
                    ->summarize(Summarizer::make()
                        ->label('Total Comissão Vendedor')
                        ->money('BRL')
                        ->using(
                            fn (DatabaseBuilder $query): float =>
                            $query
                                ->sum(
                                    DB::raw('(gross_value * seller_commission) / 100')
                                )
                        ))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('total_commission')
                    ->label('Comissão Total')
                    ->money('BRL')
                    ->summarize(Summarizer::make()
                        ->label('Total')
                        ->money('BRL')
                        ->using(
                            fn (DatabaseBuilder $query): float =>
                            $query
                                ->sum(
                                    DB::raw('(gross_value * buyer_commission) / 100 + (gross_value * buyer_commission) / 100')
                                )
                        ))
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('number', 'desc')
            ->groups([
                Group::make('status.name')
                    ->label('Status')
                    ->collapsible(),
                Group::make('event.name')
                    ->label('Evento')
                    ->collapsible(),
                Group::make('seller.name')
                    ->label('Vendedor')
                    ->collapsible(),
                Group::make('buyer.name')
                    ->label('Comprador')
                    ->collapsible(),
            ])
            ->filters([
                // SelectFilter::make('buyer')
                //     ->label('Comprador')
                //     ->searchable()
                //     ->relationship('buyer', 'name'),
                // SelectFilter::make('seller')
                //     ->label('Vendedor')
                //     ->searchable()
                //     ->relationship('seller', 'name'),
                // SelectFilter::make('status')
                //     ->label('Status')
                //     // ->default(1)
                //     ->relationship('status', 'name'),
                // SelectFilter::make('event')
                //     ->label('Evento')
                //     ->relationship('event', 'name'),
                Filter::make('base_date')
                    ->form([
                        DatePicker::make('created_from')->label('Data de Negociação (Inicial)')
                            ->afterStateHydrated(function (DatePicker $component, $state, string $operation) {
                                $component->state(Carbon::now()->firstOfMonth()->format('Y-m-d'));
                            }),
                        DatePicker::make('created_until')->label('Data de Negociação (Final)')
                            ->afterStateHydrated(function (DatePicker $component, $state, string $operation) {
                                $component->state(Carbon::now()->lastOfMonth()->format('Y-m-d'));
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('base_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('base_date', '<=', $date),
                            );
                    })
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn (Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->headerActions([
                // Action::make('report')
                //     ->label('Gerar PDF')
                //     ->icon('heroicon-o-document-text')
                //     ->color('info')
                //     ->url(fn (Collection $orders): string => dd($orders) route('order-pdf', '1'))
                //     ->openUrlInNewTab(),
                // ExportAction::make()
                //     ->label('Download')
                //     ->exports([
                //         ExcelExport::make()
                //             ->fromTable()
                //             ->withFilename(date('d-m-Y') . ' - Ordens de Serviço')
                //     ])
            ])
            ->actions([
                ActionGroup::make([
                    // Tables\Actions\ViewAction::make(),
                    Action::make('report')
                        ->label('Gerar PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn (Order $record): string => route('order-pdf', $record->id))
                        ->openUrlInNewTab(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('exportPdf')
                        ->label('Exportar em PDF')
                        // ->action(function (Collection $records, Table $table) {
                        //     self::exportPdf($records, $table);
                        // })
                        ->action(function (Collection $records, Table $table) {
                            $ids = [];

                            foreach ($records as $key => $value) {
                                array_push($ids, $value->id);
                            }

                            $columns = array_keys($table->getColumns());

                            $orders = Order::whereIn('id', $ids)->get();

                            $fileName = 'COMISSAO_POR_OS.pdf';

                            $args = [
                                'orders' => $orders,
                                'title' => 'COMISSÃO POR OS',
                                'columns' => $columns,
                                'filters' => $table->getFilter('base_date')->getState(),
                            ];

                            $pdf = app('dompdf.wrapper');

                            $pdf->loadView('reports.commission-per-os', $args);

                            $pdf->setPaper('a4', 'landscape');

                            $content = $pdf->download()->getOriginalContent();

                            return response()->streamDownload(function () use ($content) {
                                echo $content;
                            }, $fileName);
                        })
                ]),
            ]);
    }


    private static function exportPdf(Collection $records, Table $table)
    {
        $ids = [];

        foreach ($records as $key => $value) {
            array_push($ids, $value->id);
        }

        $columns = array_keys($table->getColumns());

        $orders = Order::whereIn('id', $ids)->get();

        $fileName = 'COMISSAO_POR_OS.pdf';

        $args = [
            'orders' => $orders,
            'title' => 'COMISSÃO POR OS',
            'columns' => $columns,
        ];

        $pdf = app('dompdf.wrapper');

        $pdf->loadView('reports.commission-per-os', $args);

        return response()->streamDownload(function () {
            echo 'CSV Contents...';
        }, 'export.csv');

        // return ReportFactory::download('portrait', 'reports.commission-per-os', $args, $fileName);

        // $models = CustomModel::whereIn('id', $records)->get();

        // $pdf = PDF::loadView('pdf.custom_models', ['models' => $models]);

        // return response()->streamDownload(function () use ($pdf) {
        //     echo $pdf->stream();
        // }, 'custom_models.pdf');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommissions::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getWidgets(): array
    {
        return [
            // OrderStatsOverview::class,
            // ActiveOrdersChart::class
        ];
    }
}
