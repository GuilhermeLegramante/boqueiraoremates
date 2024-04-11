<?php

namespace App\Filament\Resources;

use App\Filament\Forms\OrderForm;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

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
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Número')
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
                TextColumn::make('parcel_value')
                    ->label('Valor da Parcela')
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('multiplier')
                    ->label('Multiplicador')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('gross_value')
                    ->label('Valor Bruto')
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('paymentWay.name')
                    ->label('Comprador')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('report')
                    ->label('Gerar PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn (Order $record): string => route('order-pdf', $record->id))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/criar'),
            'edit' => Pages\EditOrder::route('/{record}/editar'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
