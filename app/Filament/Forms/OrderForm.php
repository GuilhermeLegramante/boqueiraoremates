<?php

namespace App\Filament\Forms;

use App\Forms\Components\BuyerParcelsDetails;
use App\Forms\Components\ParcelsDetails;
use App\Forms\Components\SellerParcelsDetails;
use App\Models\Event;
use App\Models\Order;
use App\Utils\ParcelsVerification;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class OrderForm
{
    public static int $multiplier = 1;

    public static function form(): array
    {
        return [
            Section::make('Dados da Ordem de Serviço')
                ->description(
                    fn (string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                )
                ->collapsible()
                ->schema([
                    Select::make('order_status_id')
                        ->label('Status da OS')
                        ->preload()
                        ->searchable()
                        ->live()
                        ->relationship(name: 'status', titleAttribute: 'name')
                        ->required()
                        ->createOptionForm(OrderStatusForm::form()),
                    self::getService(),
                    self::getBusiness(),
                    self::getBuyerInvoicing(),
                    self::getSellerInvoicing(),
                    self::getEntry(),
                    self::getOutput(),
                    DatePicker::make('closing_date')
                        ->label('Data de Encerramento da OS'),

                ])
                ->columns(2)
        ];
    }

    private static function getService(): Fieldset
    {
        return Fieldset::make('Serviço')
            ->schema([
                TextInput::make('number')
                    ->afterStateHydrated(function (TextInput $component, $state, string $operation) {
                        if ($operation === 'create') {
                            $order = Order::whereRaw('number = (select max(`number`) from orders)')->get()->first();
                            if (isset($order->number)) {
                                $component->state($order->number + 1);
                            }
                        }
                    })
                    ->required()
                    ->label('Número')
                    ->numeric(),
                DatePicker::make('base_date')
                    ->afterStateHydrated(function (DatePicker $component, $state, string $operation) {
                        if ($operation === 'create') {
                            $component->state(now()->format('Y-m-d'));
                        }
                    })
                    ->afterStateUpdated(function (DatePicker $component, $state, string $operation, Set $set) {
                        // $set('due_day', now()->format('d'));
                    })
                    ->live()
                    ->required()
                    ->label('Data da Negociação'),
                Select::make('event_id')
                    ->label(__('fields.event'))
                    ->required()
                    ->preload()
                    ->searchable()
                    ->preload()
                    ->live()
                    // ->disabledOn('edit')
                    ->relationship(name: 'event', titleAttribute: 'name')
                    ->createOptionForm(EventForm::form())
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $event = Event::find($get('event_id'));
                        if (isset($event->multiplier)) {
                            $set('multiplier', $event->multiplier);
                        }
                    })
                    ->columnSpanFull(),
                Select::make('seller_id')
                    ->label(__('fields.seller'))
                    // ->disabledOn('edit')
                    ->required()
                    ->preload()
                    ->relationship(name: 'seller', titleAttribute: 'name')
                    ->createOptionForm(ClientForm::form())
                    ->columnSpanFull(),
            ]);
    }

    private static function getBusiness(): Fieldset
    {
        return Fieldset::make('Negócio')
            ->schema([
                Select::make('buyer_id')
                    ->label(__('fields.buyer'))
                    // ->disabledOn('edit')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship(name: 'buyer', titleAttribute: 'name')
                    ->createOptionForm(ClientForm::form())
                    ->columnSpanFull(),
                Select::make('animal_id')
                    ->label(__('fields.animal'))
                    // ->disabledOn('edit')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship(name: 'animal', titleAttribute: 'name')
                    ->createOptionForm(AnimalForm::form())
                    ->columnSpan(5),
                TextInput::make('batch')
                    ->label(__('fields.batch'))
                    ->numeric(),
                TextInput::make('original_parcel')
                    ->prefix('R$')
                    ->numeric()
                    ->live()
                    ->debounce(1000)
                    ->columnSpan(2)
                    ->label('Parcela de Venda')
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $grossValue = floatval($get('original_parcel')) * floatval($get('multiplier'));
                        $set('gross_value', $grossValue);
                    }),
                TextInput::make('multiplier')
                    // ->disabledOn('edit')
                    ->label(__('fields.multiplier'))
                    ->live()
                    ->columnSpan(1)
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        // Calcula o valor bruto
                        $grossValue = floatval($get('parcel_value')) * floatval($get('multiplier'));
                        $set('gross_value', $grossValue);
                    })
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            // if ($get('multiplier') != null && !ParcelsVerification::checkIfPaymentWaySumIsInAccordingWithMultiplier($get('payment_way_id'), $get('multiplier'))) {
                            //     $fail('O multiplicador não está de acordo com a forma de pagamento.');
                            // }
                        },
                    ])
                    ->numeric(),
                TextInput::make('gross_value')
                    ->readOnly()
                    ->columnSpan(3)
                    ->prefix('R$')
                    ->numeric()
                    ->label(__('fields.gross_value')),
                Select::make('payment_way_id')
                    ->label('Forma de Pagamento')
                    // ->disabledOn('edit')
                    ->preload()
                    ->searchable()
                    ->live()
                    ->relationship(name: 'paymentWay', titleAttribute: 'name')
                    ->createOptionForm(PaymentWayForm::form())
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('parcel_value', null);
                        $set('first_parcel_value', null);
                        // $set('multiplier', ParcelsVerification::getMultiplier($get('payment_way_id')));
                    })
                    ->columnSpan(2),
                TextInput::make('discount_percentage')
                    ->label('Desconto')
                    // ->disabledOn('edit')
                    ->live()
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        // Calcula o Valor Líquido
                        $netValue = floatval($get('gross_value')) - (floatval($get('gross_value')) * floatval($get('discount_percentage'))) / 100;
                        $set('net_value', $netValue);

                        // Atualiza o valor da parcela 
                        $parcelsQuantity = ParcelsVerification::getMultiplier($get('payment_way_id'));
                        $parcelValue = $netValue / $parcelsQuantity;
                        $set('parcel_value', number_format((float)$parcelValue, 2, '.', ''));

                        // Atualiza o valor da entrada
                        $firstParcelValue = ParcelsVerification::getFirstParcelValue($get('payment_way_id'), $get('parcel_value'));
                        $set('first_parcel_value', number_format((float)$firstParcelValue, 2, '.', ''));
                    })
                    ->suffix('%')
                    ->numeric(),
                TextInput::make('net_value')
                    // ->readOnly()
                    ->live()
                    ->prefix('R$')
                    ->columnSpan(3)
                    ->numeric()
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $percentage = 100 - (floatval($get('net_value')) * 100) / floatval($get('gross_value'));
                        $set('discount_percentage', number_format((float)$percentage, 2, '.', ''));
                    })
                    ->label(__('fields.net_value')),
                TextInput::make('parcel_value')
                    // ->disabledOn('edit')
                    ->prefix('R$')
                    ->numeric()
                    ->live()
                    ->debounce(1000)
                    ->columnSpan(2)
                    ->label('Parcela de Compra')
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $firstParcelValue = ParcelsVerification::getFirstParcelValue($get('payment_way_id'), $get('parcel_value'));
                        $set('first_parcel_value', $firstParcelValue);

                        // $grossValue = floatval($get('parcel_value')) * floatval($get('multiplier'));
                        // $set('gross_value', $grossValue);
                    }),
                TextInput::make('first_parcel_value')
                    // ->disabledOn('edit')
                    ->prefix('R$')
                    ->numeric()
                    ->columnSpan(2)
                    ->label('Valor da Entrada'),

                // TextInput::make('due_day')
                //     ->label('Dia do Venc.')
                //     ->afterStateHydrated(function (Get $get, Set $set) {
                //         if ($get('base_date') != null) {
                //             $date = explode('-', $get('base_date'));
                //             $set('due_day', $date[2]);
                //         }
                //     })
                //     ->minValue(1)
                //     ->maxValue(28)
                //     ->live()
                //     ->columnSpan(1)
                //     ->numeric(),
                DatePicker::make('first_due_date')
                    ->columnSpan(2)
                    ->live()
                    ->label('1° Vencimento')
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        if ($get('base_date') != null) {
                            $set('first_due_date', $get('base_date'));
                        }
                    }),

                Textarea::make('business_note')
                    ->label('Observação')
                    ->columnSpanFull(),
                ParcelsDetails::make('parcels_details')
                    ->label('')
                    ->live()
                    ->columnSpanFull()
                    ->visible(fn (Get $get, string $operation): bool => ($get('gross_value') != null)),
            ])->columns(6);
    }

    private static function getBuyerInvoicing(): Fieldset
    {
        return Fieldset::make('Faturamento pelo Comprador')
            ->schema([
                TextInput::make('buyer_commission')
                    ->label('Comissão')
                    // ->disabledOn('edit')
                    ->live()
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $buyerComissionValue = (floatval($get('gross_value')) * floatval($get('buyer_commission'))) / 100;
                        $set('buyer_comission_value', $buyerComissionValue);
                    })
                    ->suffix('%')
                    ->numeric(),
                TextInput::make('buyer_comission_value')
                    // ->readOnly()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $buyerCommission = (floatval($get('buyer_comission_value')) * 100) / floatval($get('gross_value'));
                        $set('buyer_commission', $buyerCommission);
                    })
                    ->debounce(1000)
                    ->live()
                    ->columnSpan(3)
                    ->numeric()
                    ->label('Valor da Comissão'),
                TextInput::make('buyer_due_day')
                    ->label('Dia do Venc.')
                    // ->disabledOn('edit')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                TextInput::make('buyer_commission_installments_number')
                    ->label('N° de Parcelas')
                    // ->disabledOn('edit')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                BuyerParcelsDetails::make('buyer_parcels_details')
                    ->label('')
                    ->live()
                    ->columnSpanFull()
                    ->visible(fn (Get $get, string $operation): bool => ($get('buyer_commission_installments_number') != null)),
            ])->columns(6);
    }

    private static function getSellerInvoicing(): Fieldset
    {
        return Fieldset::make('Faturamento pelo Vendedor')
            ->schema([
                TextInput::make('seller_commission')
                    ->label('Comissão')
                    // ->disabledOn('edit')
                    ->live()
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $sellerComissionValue = (floatval($get('gross_value')) * floatval($get('seller_commission'))) / 100;
                        $set('seller_comission_value', $sellerComissionValue);
                    })
                    ->suffix('%')
                    ->numeric(),
                TextInput::make('seller_comission_value')
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $sellerCommission = (floatval($get('seller_comission_value')) * 100) / floatval($get('gross_value'));
                        $set('seller_commission', $sellerCommission);
                    })
                    ->live()
                    ->debounce(1000)
                    ->columnSpan(3)
                    ->numeric()
                    ->label('Valor da Comissão'),
                TextInput::make('seller_due_day')
                    ->label('Dia do Venc.')
                    // ->disabledOn('edit')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                TextInput::make('seller_commission_installments_number')
                    ->label('N° de Parcelas')
                    // ->disabledOn('edit')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                SellerParcelsDetails::make('seller_parcels_details')
                    ->label('')
                    ->live()
                    ->columnSpanFull()
                    ->visible(fn (Get $get, string $operation): bool => ($get('seller_commission_installments_number') != null)),
            ])->columns(6);
    }

    private static function getEntry(): Fieldset
    {
        return Fieldset::make('Documentação - envio para assinaturas (Comprador/Vendedor/Testemunhas)')
            ->schema([
                Checkbox::make('entry_contracts')
                    ->label('Contratos'),
                Checkbox::make('entry_promissory')
                    ->label('NP'),
                Checkbox::make('entry_comission_promissory')
                    ->label('NP da Comissão'),
                Checkbox::make('entry_register_copy')
                    ->label('Cópia do Regulamento'),
                Radio::make('entry_first_parcel_business')
                    ->label('Parcela 01 do Negócio')
                    ->columnSpan(2)
                    ->options([
                        'ticket' => 'Boleto',
                        'deposit' => 'Depósito',
                        'transfer' => 'Transferência',
                        'pix' => 'Pix'
                    ]),
                Radio::make('entry_first_parcel_comission')
                    ->label('Parcela 01 da Comissão')
                    ->columnSpan(2)
                    ->options([
                        'ticket' => 'Boleto',
                        'deposit' => 'Depósito',
                        'transfer' => 'Transferência',
                        'pix' => 'Pix'
                    ]),
                DatePicker::make('entry_buyer_sending_documentation_date')
                    ->label('Data de envio da Documentação'),
                Select::make('entry_sending_docs_method_id')
                    ->label('Forma de Envio da Documentação')
                    ->preload()
                    ->searchable()
                    ->relationship(name: 'entrySendingDocsMethod', titleAttribute: 'name')
                    ->createOptionForm(SendingDocsMethodForm::form()),
                DatePicker::make('entry_contract_return_date')
                    ->label('Assinatura do Comprador'),
                DatePicker::make('entry_seller_signature_date')
                    ->label('Assinatura do Vendedor'),
                DatePicker::make('entry_witness_signature_date')
                    ->label('Assinatura da Testemunha'),
                Textarea::make('entry_documentation_note')
                    ->columnSpanFull()
                    ->label('Observação')
            ])->columns(4);
    }

    private static function getOutput(): Fieldset
    {
        return Fieldset::make('Documentação - Saída (Envio para o Vendedor)')
            ->schema([
                Checkbox::make('output_contracts')
                    ->columnSpan(2)
                    ->label('Contratos'),
                Checkbox::make('output_promissory')
                    ->columnSpan(2)
                    ->label('NP'),
                Checkbox::make('output_comission_promissory')
                    ->label('NP da Comissão'),
                Checkbox::make('output_register_copy')
                    ->columnSpan(2)
                    ->label('Cópia do Regulamento'),
                DatePicker::make('output_first_parcel_date')
                    ->columnSpan(2)
                    ->label('Data da Parcela 01'),
                Radio::make('output_first_parcel_comission')
                    ->label('Parcela 01 do Negócio')
                    ->columnSpan(2)
                    ->options([
                        'BOLETO' => 'Boleto',
                        'DEPÓSITO' => 'Depósito',
                        'TRANSFERÊNCIA' => 'Transferência',
                        'PIX' => 'Pix'
                    ]),
                DatePicker::make('output_sending_documentation_date')
                    ->columnSpan(2)
                    ->label('Data de envio do processo físico'),
                Select::make('output_sending_docs_method_id')
                    ->label('Forma de Envio da Documentação')
                    ->preload()
                    ->searchable()
                    ->relationship(name: 'outputSendingDocsMethod', titleAttribute: 'name')
                    ->createOptionForm(SendingDocsMethodForm::form())
                    ->columnSpan(2),
                Textarea::make('output_documentation_note')
                    ->columnSpanFull()
                    ->label('Observação')
            ])->columns(6);
    }
}
