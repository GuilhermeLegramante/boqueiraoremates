<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
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
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Contrato da Venda')
            ->columns([
                TextColumn::make('id')->label('Contrato'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'generated' => 'Emitido',
                        'cancelled' => 'Cancelado',
                        default => ucfirst($state),
                    }),
                TextColumn::make('version')->label('Versão'),
                TextColumn::make('generated_at')
                    ->label('Emitido em')
                    ->dateTime('d/m/Y H:i'),
                TextColumn::make('generatedBy.name')->label('Emitido por'),
            ])
            ->headerActions([
                // GRUPO DE PRÉ-VISUALIZAÇÃO
                ActionGroup::make([
                    // Modal para Selecionar Documentos na Pré-visualização
                    Tables\Actions\Action::make('preview_bundle_modal')
                        ->label('Selecionar Documentos (Prévia)')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('success')
                        ->form([
                            Forms\Components\CheckboxList::make('documents')
                                ->label('Selecione os documentos para pré-visualizar:')
                                ->options(fn() => $this->getAvailableDocumentOptions($this->getOwnerRecord()))
                                ->default(fn() => array_keys($this->getAvailableDocumentOptions($this->getOwnerRecord())))
                                ->required(),
                        ])
                        ->action(function (array $data) {
                            $query = http_build_query(['docs' => implode(',', $data['documents'])]);
                            $url = route('order-bundle-preview-pdf', ['order' => $this->getOwnerRecord()->id]) . '?' . $query;

                            $this->js("window.open('{$url}', '_blank')");
                        }),

                    Tables\Actions\Action::make('preview_via1')
                        ->label('1ª Via Contrato')
                        ->icon('heroicon-o-eye')
                        ->url(fn(): string => route('order-preview-pdf', ['order' => $this->getOwnerRecord()->id, 'via' => 1]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('preview_via2')
                        ->label('2ª Via Contrato')
                        ->icon('heroicon-o-eye')
                        ->url(fn(): string => route('order-preview-pdf', ['order' => $this->getOwnerRecord()->id, 'via' => 2]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('preview_promissory')
                        ->label('NP (Única)')
                        ->icon('heroicon-o-eye')
                        ->url(fn(): string => route('order-promissory-preview-pdf', ['order' => $this->getOwnerRecord()->id]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('preview_seller_promissory')
                        ->label('NP (Comissão Vendedor)')
                        ->icon('heroicon-o-eye')
                        ->visible(fn(): bool => $this->getOwnerRecord()->sellerParcels()->count() > 0)
                        ->url(fn(): string => route('order-seller-promissory-preview-pdf', ['order' => $this->getOwnerRecord()->id]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('preview_buyer_promissory')
                        ->label('NP (Comissão Comprador)')
                        ->icon('heroicon-o-eye')
                        ->visible(fn(): bool => $this->getOwnerRecord()->buyerParcels()->count() > 0)
                        ->url(fn(): string => route('order-buyer-promissory-preview-pdf', ['order' => $this->getOwnerRecord()->id]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('preview_regulation')
                        ->label('Regulamento')
                        ->icon('heroicon-o-eye')
                        ->url(fn(): string => route('order-regulation-preview-pdf', ['order' => $this->getOwnerRecord()->id]))
                        ->openUrlInNewTab(),
                ])
                    ->label('Pré-visualizar Documentos')
                    ->icon('heroicon-o-eye')
                    ->color('warning'),

                // BOTÃO DE GERAR E OFICIALIZAR CONTRATO
                Tables\Actions\Action::make('generate')
                    ->label('Gerar Contrato')
                    ->icon('heroicon-o-document-plus')
                    ->color('primary')
                    ->visible(fn(): bool => ! $this->getOwnerRecord()->hasContract())
                    ->requiresConfirmation()
                    ->modalHeading('Gerar Contrato')
                    ->modalDescription('Ao gerar o Contrato, a Fatura de Venda/OS será considerada fechada e não poderá mais ser alterada.')
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
                            ->title('Contrato gerado com sucesso!')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    // Modal para Selecionar Documentos para Impressão
                    Tables\Actions\Action::make('pdf_bundle_modal')
                        ->label('Selecionar Documentos (Pacote)')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('success')
                        ->form([
                            Forms\Components\CheckboxList::make('documents')
                                ->label('Selecione os documentos para gerar em PDF:')
                                ->options(fn(Contract $record) => $this->getAvailableDocumentOptions($record->order))
                                ->default(fn(Contract $record) => array_keys($this->getAvailableDocumentOptions($record->order)))
                                ->required(),
                        ])
                        ->action(function (Contract $record, array $data) {
                            $query = http_build_query(['docs' => implode(',', $data['documents'])]);
                            $url = route('contract-bundle-pdf', ['contract' => $record->id]) . '?' . $query;

                            $this->js("window.open('{$url}', '_blank')");
                        }),

                    Tables\Actions\Action::make('pdf_via1')
                        ->label('1ª Via')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn(Contract $record): string => route('contract-pdf', ['contract' => $record->id, 'via' => 1]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('pdf_via2')
                        ->label('2ª Via')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->url(fn(Contract $record): string => route('contract-pdf', ['contract' => $record->id, 'via' => 2]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('promissory_note')
                        ->label('NP (Única)')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->url(fn(Contract $record): string => route('promissory-note-pdf', ['contract' => $record->id]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('seller_promissory_note')
                        ->label('NP - Comissão Vendedor')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->visible(fn(Contract $record): bool => count($record->snapshot['seller_parcels'] ?? []) > 0)
                        ->url(fn(Contract $record): string => route('seller-promissory-note-pdf', ['contract' => $record->id]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('buyer_promissory_note')
                        ->label('NP - Comissão Comprador')
                        ->icon('heroicon-o-banknotes')
                        ->color('warning')
                        ->visible(fn(Contract $record): bool => count($record->snapshot['buyer_parcels'] ?? []) > 0)
                        ->url(fn(Contract $record): string => route('buyer-promissory-note-pdf', ['contract' => $record->id]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('regulation')
                        ->label('Regulamento')
                        ->icon('heroicon-o-document-text')
                        ->url(fn(Contract $record): string => route('contract-regulation-pdf', $record))
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

    private function getAvailableDocumentOptions($order): array
    {
        $options = [
            'via1' => '1ª Via do Contrato',
            'via2' => '2ª Via do Contrato',
            'promissory' => 'NP (Única)',
        ];

        $hasSellerParcels = $order && method_exists($order, 'sellerParcels') ? $order->sellerParcels()->count() > 0 : false;
        $hasBuyerParcels = $order && method_exists($order, 'buyerParcels') ? $order->buyerParcels()->count() > 0 : false;

        if ($hasSellerParcels) {
            $options['seller_promissory'] = 'Nota Promissória (Comissão Vendedor)';
        }

        if ($hasBuyerParcels) {
            $options['buyer_promissory'] = 'Nota Promissória (Comissão Comprador)';
        }

        $options['regulation'] = 'Regulamento do Remate';

        return $options;
    }

    protected function makeSnapshot($order): array
    {
        $order->load([
            'event.clauses',
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
                'base_date' => $order->base_date ? \Carbon\Carbon::parse($order->base_date)->format('Y-m-d') : null,
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
                'banner_contract' => $order->event->banner_contract ?? null,
                'city' => $order->event->city ?? null,
                'start_date' => $order->event->start_date ? \Carbon\Carbon::parse($order->event->start_date)->format('Y-m-d') : null,
                'finish_date' => $order->event->finish_date ? \Carbon\Carbon::parse($order->event->finish_date)->format('Y-m-d') : null,
                'multiplier' => $order->event->multiplier,
                'note' => $order->event->note,
                'regulation' => $order->event->regulation,
                'auctioneer' => $order->event->auctioneer,
                'witness_1_name' => $order->event->witness_1_name,
                'witness_2_name' => $order->event->witness_2_name,

                // 🔹 Mapeamento das cláusulas para o Snapshot
                'clauses' => $order->event->clauses ? $order->event->clauses->map(fn($clause) => [
                    'id' => $clause->id,
                    'title' => $clause->title,
                    'content' => $clause->content,
                    'order' => $clause->order,
                ])->toArray() : [],
            ] : null,
            'seller' => $order->seller ? [
                'id' => $order->seller->id,
                'name' => $order->seller->name,
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
            'parcels' => $order->parcels->map(fn($p) => $p->toArray())->values()->all(),
            'seller_parcels' => $order->sellerParcels ? $order->sellerParcels->map(fn($p) => $p->toArray())->values()->all() : [],
            'buyer_parcels' => $order->buyerParcels ? $order->buyerParcels->map(fn($p) => $p->toArray())->values()->all() : [],
        ];
    }
}
