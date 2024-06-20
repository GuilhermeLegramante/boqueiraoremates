<?php

namespace App\Filament\Resources;

use App\Filament\Forms\OrderForm;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\BuyerParcelsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\ParcelsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\SellerParcelsRelationManager;
use App\Models\Order;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Support\Facades\DB;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $modelLabel = 'ordem de serviço';

    protected static ?string $pluralModelLabel = 'ordens de serviço';

    protected static ?string $slug = 'ordens-de-servico';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(OrderForm::form());
    }

    public static function table(Table $table): Table
    {
        $a = $table
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
                TextColumn::make('buyer.name')
                    ->label('Comprador')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('multiplier')
                    ->label('Multiplicador')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('parcel_value')
                    ->label('Valor da Parcela')
                    ->money('BRL')
                    ->summarize(Sum::make()->label('Total')->money('BRL'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('gross_value')
                    ->label('Valor Bruto')
                    ->money('BRL')
                    ->summarize(Sum::make()->label('Total')->money('BRL'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('net_value')
                    ->label('Valor Líquido')
                    ->money('BRL')
                    ->summarize(Summarizer::make()
                        ->label('Total')
                        ->money('BRL')
                        ->using(
                            fn (DatabaseBuilder $query): float =>
                            $query
                                ->sum(
                                    DB::raw('(gross_value * discount_percentage) / 100')
                                )
                        ))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('buyer_comission_value')
                    ->label('Comissão Comprador')
                    ->money('BRL')
                    ->summarize(Summarizer::make()
                        ->label('Total')
                        ->money('BRL')
                        ->using(
                            fn (DatabaseBuilder $query): float =>
                            $query
                                ->sum(
                                    DB::raw('(gross_value * buyer_commission) / 100')
                                )
                        ))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('seller_comission_value')
                    ->label('Comissão Vendedor')
                    ->money('BRL')
                    ->summarize(Summarizer::make()
                        ->label('Total')
                        ->money('BRL')
                        ->using(
                            fn (DatabaseBuilder $query): float =>
                            $query
                                ->sum(
                                    DB::raw('(gross_value * seller_commission) / 100')
                                )
                        ))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('buyer_commission')
                    ->label('% Comprador')
                    ->suffix('%')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('seller_commission')
                    ->label('% Vendedor')
                    ->suffix('%')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('paymentWay.name')
                    ->label('Forma de Pagamento')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->alignment(Alignment::Center)
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Emitida em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('buyer')
                    ->label('Comprador')
                    ->searchable()
                    ->relationship('buyer', 'name'),
                SelectFilter::make('seller')
                    ->label('Vendedor')
                    ->searchable()
                    ->relationship('seller', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    // ->default(1)
                    ->relationship('status', 'name'),
                SelectFilter::make('event')
                    ->label('Evento')
                    ->relationship('event', 'name'),
                Filter::make('base_date')
                    ->form([
                        DatePicker::make('created_from')->label('Data de Negociação (Inicial)'),
                        DatePicker::make('created_until')->label('Data de Negociação (Final)'),
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
                ExportAction::make()
                    ->label('Download')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(date('d-m-Y') . ' - Ordens de Serviço')
                    ])
            ])
            ->actions([
                ActionGroup::make([
                    // Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make('report')
                        ->label('Gerar PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn (Order $record): string => route('order-pdf', $record->id))
                        ->openUrlInNewTab(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('Download'),
                ]),
            ]);
        // dd($a);
        return $a;
    }

    public static function getRelations(): array
    {
        return [
            ParcelsRelationManager::class,
            BuyerParcelsRelationManager::class,
            SellerParcelsRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/criar'),
            'edit' => Pages\EditOrder::route('/{record}/editar'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
