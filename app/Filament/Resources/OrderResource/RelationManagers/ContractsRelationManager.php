<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Contract;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractsRelationManager extends RelationManager
{
    protected static string $relationship = 'contract';

    protected static ?string $title = 'Contrato';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Contrato da Venda')

            ->columns([
                TextColumn::make('id')
                    ->label('Contrato'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'generated' => 'Emitido',
                        'cancelled' => 'Cancelado',
                        default => ucfirst($state),
                    }),

                TextColumn::make('version')
                    ->label('Versão'),

                TextColumn::make('generated_at')
                    ->label('Emitido em')
                    ->dateTime('d/m/Y H:i'),

                TextColumn::make('generatedBy.name')
                    ->label('Emitido por'),
            ])

            ->headerActions([
                Tables\Actions\Action::make('generate')
                    ->label('Gerar Contrato')
                    ->icon('heroicon-o-document-plus')
                    ->color('primary')
                    ->visible(fn(): bool => ! $this->getOwnerRecord()->hasContract())
                    ->requiresConfirmation()
                    ->modalHeading('Gerar Contrato')
                    ->modalDescription(
                        'Ao gerar o Contrato, a Fatura de Venda/OS será considerada fechada e não poderá mais ser alterada.'
                    )
                    ->action(function (): void {
                        $order = $this->getOwnerRecord();

                        if ($order->hasContract()) {
                            return;
                        }

                        Contract::create([
                            'order_id' => $order->id,
                            'generated_by' => auth()->id(),
                            'status' => 'generated',
                            'version' => '1.0',
                            'snapshot' => $this->makeSnapshot($order),
                            'generated_at' => now(),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Contrato gerado')
                            ->success()
                            ->send();
                    }),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('pdf')
                    ->label('Gerar PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(
                        fn(Contract $record): string =>
                        route('contract-pdf', $record->id)
                    )
                    ->openUrlInNewTab(),
            ])

            ->bulkActions([]);
    }

    protected function makeSnapshot($order): array
    {
        $order->load([
            'event',
            'seller',
            'buyer',
            'animal',
            'paymentWay',
            'parcels',
            'buyerParcels',
            'sellerParcels',
        ]);

        return [
            'order' => [
                'id' => $order->id,
                'number' => $order->number,
                'base_date' => $order->base_date
                    ? \Carbon\Carbon::parse($order->base_date)->format('Y-m-d')
                    : null,
                'gross_value' => $order->gross_value,
                'net_value' => $order->net_value,
                'discount_percentage' => $order->discount_percentage,
                'multiplier' => $order->multiplier,
                'parcel_value' => $order->parcel_value,
                'first_parcel_value' => $order->first_parcel_value,
                'payment_way_id' => $order->payment_way_id,
                'due_day' => $order->due_day,
                'first_due_date' => $order->first_due_date,
                'sale_type' => $order->sale_type,
                'sale_type_percentage' => $order->sale_type_percentage,
                'sale_type_quantity' => $order->sale_type_quantity,
            ],

            'event' => $order->event ? [
                'id' => $order->event->id,
                'name' => $order->event->name,
            ] : null,

            'seller' => $order->seller ? [
                'id' => $order->seller->id,
                'name' => $order->seller->name,
            ] : null,

            'buyer' => $order->buyer ? [
                'id' => $order->buyer->id,
                'name' => $order->buyer->name,
            ] : null,

            'animal' => $order->animal ? [
                'id' => $order->animal->id,
                'name' => $order->animal->name,
            ] : null,

            'payment_way' => $order->paymentWay ? [
                'id' => $order->paymentWay->id,
                'name' => $order->paymentWay->name,
            ] : null,

            'seller_commission' => [
                'percentage' => $order->seller_commission,
                'value' => $order->seller_comission_value,
                'installments_number' => $order->seller_commission_installments_number,
                'due_day' => $order->seller_due_day,
            ],

            'buyer_commission' => [
                'percentage' => $order->buyer_commission,
                'value' => $order->buyer_comission_value,
                'installments_number' => $order->buyer_commission_installments_number,
                'due_day' => $order->buyer_due_day,
            ],

            'parcels' => $order->parcels
                ->map(fn($parcel) => $parcel->toArray())
                ->values()
                ->all(),

            'buyer_parcels' => $order->buyerParcels
                ->map(fn($parcel) => $parcel->toArray())
                ->values()
                ->all(),

            'seller_parcels' => $order->sellerParcels
                ->map(fn($parcel) => $parcel->toArray())
                ->values()
                ->all(),
        ];
    }
}
