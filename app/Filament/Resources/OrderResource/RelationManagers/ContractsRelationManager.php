<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Contract;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
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
                ActionGroup::make([
                    // 1ª Via do Contrato
                    Tables\Actions\Action::make('pdf_via1')
                        ->label('1ª Via')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(
                            fn(Contract $record): string =>
                            route('contract-pdf', ['contract' => $record->id, 'via' => 1])
                        )
                        ->openUrlInNewTab(),

                    // 2ª Via do Contrato
                    Tables\Actions\Action::make('pdf_via2')
                        ->label('2ª Via')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->url(
                            fn(Contract $record): string =>
                            route('contract-pdf', ['contract' => $record->id, 'via' => 2])
                        )
                        ->openUrlInNewTab(),

                    // Nota Promissória
                    Tables\Actions\Action::make('promissory_note')
                        ->label('Nota Promissória')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->url(
                            fn(Contract $record): string =>
                            route('promissory-note-pdf', ['contract' => $record->id])
                        )
                        ->openUrlInNewTab(),

                    // Regulamento
                    Tables\Actions\Action::make('regulation')
                        ->label('Regulamento')
                        ->icon('heroicon-o-document-text')
                        ->url(fn(Contract $record) => route('contract-regulation-pdf', $record))
                        ->openUrlInNewTab(),

                    Tables\Actions\DeleteAction::make()
                        ->label('Excluir')
                        ->modalHeading('Excluir Contrato')
                        ->modalDescription('Tem certeza que deseja excluir este contrato? Essa ação reabrirá a Fatura de Venda/OS para alterações.'),
                ])
                    ->label('Imprimir')
                    ->icon('heroicon-m-printer')
                    ->color('primary')
            ])
            ->bulkActions([]);
    }

    protected function makeSnapshot($order): array
    {
        $order->load([
            'event',
            'seller.address',
            'buyer.address',
            'animal.breed',
            'animal.coat',
            'animalEvent',
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
                'banner_min' => $order->event->banner_min ?? null,
                'start_date' => $order->event->start_date
                    ? \Carbon\Carbon::parse($order->event->start_date)->format('Y-m-d')
                    : null,
                'finish_date' => $order->event->finish_date
                    ? \Carbon\Carbon::parse($order->event->finish_date)->format('Y-m-d')
                    : null,
                'multiplier' => $order->event->multiplier,
                'note' => $order->event->note,
                'regulation' => $order->event->regulation,
                'pre_start_date' => $order->event->pre_start_date
                    ? \Carbon\Carbon::parse($order->event->pre_start_date)->format('Y-m-d')
                    : null,
                'pre_finish_date' => $order->event->pre_finish_date
                    ? \Carbon\Carbon::parse($order->event->pre_finish_date)->format('Y-m-d')
                    : null,
                'auctioneer' => $order->event->auctioneer,
                'witness_1_name' => $order->event->witness_1_name,
                'witness_2_name' => $order->event->witness_2_name
            ] : null,

            'seller' => $order->seller ? [
                'id' => $order->seller->id,
                'name' => $order->seller->name,
                'establishment' => $order->seller->establishment ?? null,
                'cpf_cnpj' => $order->seller->cpf_cnpj ?? null,
                'phone' => $order->seller->phone ?? null,
                'email' => $order->seller->email ?? null,
                'address' => $order->seller->address ? [
                    'street' => $order->seller->address->street ?? null,
                    'district' => $order->seller->address->district ?? null,
                    'city' => $order->seller->address->city ?? null,
                    'state' => $order->seller->address->state ?? null,
                    'postal_code' => $order->seller->address->postal_code ?? null,
                ] : null,
            ] : null,

            'buyer' => $order->buyer ? [
                'id' => $order->buyer->id,
                'name' => $order->buyer->name,
                'cpf_cnpj' => $order->buyer->cpf_cnpj ?? null,
                'phone' => $order->buyer->phone ?? null,
                'email' => $order->buyer->email ?? null,
                'address' => $order->buyer->address ? [
                    'street' => $order->buyer->address->street ?? null,
                    'district' => $order->buyer->address->district ?? null,
                    'city' => $order->buyer->address->city ?? null,
                    'state' => $order->buyer->address->state ?? null,
                    'postal_code' => $order->buyer->address->postal_code ?? null,
                ] : null,
            ] : null,

            'animal' => $order->animal ? [
                'id' => $order->animal->id,
                'name' => $order->animal->name,
                'rb' => $order->animal->rb ?? null,
                'sbb' => $order->animal->sbb ?? null,
                'register' => $order->animal->register ?? null,
                'blood_level' => $order->animal->blood_level ?? null,
                'blood_percentual' => $order->animal->blodd_percentual ?? null,
                'gender' => $order->animal->gender ?? null,
                'breed' => $order->animal->breed ? ['name' => $order->animal->breed->name] : null,
                'coat' => $order->animal->coat ? ['name' => $order->animal->coat->name] : null,
            ] : null,

            'lote' => $order->animalEvent ? [
                'id' => $order->animalEvent->id,
                'lot_number' => $order->animalEvent->lot_number,
                'name' => $order->animalEvent->name,
            ] : null,

            'payment_way' => $order->paymentWay ? [
                'id' => $order->paymentWay->id,
                'name' => $order->paymentWay->name,
            ] : null,

            'parcels' => $order->parcels
                ->map(fn($parcel) => $parcel->toArray())
                ->values()
                ->all(),
        ];
    }
}
