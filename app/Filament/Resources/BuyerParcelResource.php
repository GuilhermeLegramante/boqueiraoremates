<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyerParcelResource\Pages;
use App\Filament\Resources\BuyerParcelResource\RelationManagers;
use App\Filament\Tables\ParcelsTable;
use App\Models\BuyerParcel;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;

class BuyerParcelResource extends Resource
{
    protected static ?string $model = BuyerParcel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $modelLabel = 'parcela comissão comprador';

    protected static ?string $pluralModelLabel = 'parcelas comissão comprador';

    protected static ?string $slug = 'parcelas-comissao-comprador';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ParcelsTable::table())
            ->filters([
                // Filtro por Evento
                SelectFilter::make('event')
                    ->label('Evento')
                    ->options(\App\Models\Event::pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('order', function ($q) use ($data) {
                                $q->where('event_id', $data['value']);
                            });
                        }
                    })
                    ->placeholder('Todos os eventos')
                    ->searchable(),

                // Filtro por Parcelas Pagas / Não Pagas
                SelectFilter::make('paid')
                    ->label('Situação da Parcela')
                    ->options([
                        '1' => 'Pagas',
                        '0' => 'Não Pagas',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->where('paid', $data['value']);
                        }
                    })
                    ->placeholder('Todas'),

                // Filtro por Boleto Gerado
                SelectFilter::make('invoice_generated')
                    ->label('Boleto Gerado')
                    ->options([
                        '1' => 'Sim',
                        '0' => 'Não',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->where('invoice_generated', $data['value']);
                        }
                    })
                    ->placeholder('Todos'),

                // Filtro por Método de Pagamento
                SelectFilter::make('payment_method_id')
                    ->label('Método de Pagamento')
                    ->options(\App\Models\PaymentMethod::pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('payment_method_id', $data['value']);
                        }
                    })
                    ->placeholder('Todos'),

                // Filtro por Período de Pagamento
                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('created_from')->label('Data de Negociação (Inicial)'),
                        DatePicker::make('created_until')->label('Data de Negociação (Final)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    })
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->groups([
                Group::make('order.number')
                    ->label('Fatura de Venda / OS')
                    ->collapsible(),
                Group::make('order.event.name')
                    ->label('Evento')
                    ->collapsible(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuyerParcels::route('/'),
            'create' => Pages\CreateBuyerParcel::route('/criar'),
            // 'edit' => Pages\EditBuyerParcel::route('/{record}/editar'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
