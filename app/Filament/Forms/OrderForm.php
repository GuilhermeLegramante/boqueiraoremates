<?php

namespace App\Filament\Forms;

use App\Forms\Components\BuyerParcelsDetails;
use App\Forms\Components\ParcelsDetails;
use App\Forms\Components\SellerParcelsDetails;
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
                Select::make('event_id')
                    ->label(__('fields.event'))
                    ->preload()
                    ->searchable()
                    ->preload()
                    ->relationship(name: 'event', titleAttribute: 'name')
                    ->createOptionForm(EventForm::form())
                    ->columnSpanFull(),
                Select::make('seller_id')
                    ->label(__('fields.seller'))
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
                    ->preload()
                    ->searchable()
                    ->relationship(name: 'buyer', titleAttribute: 'name')
                    ->createOptionForm(ClientForm::form())
                    ->columnSpanFull(),
                Select::make('animal_id')
                    ->label(__('fields.animal'))
                    ->preload()
                    ->searchable()
                    ->relationship(name: 'animal', titleAttribute: 'name')
                    ->createOptionForm(AnimalForm::form())
                    ->columnSpan(5),
                TextInput::make('batch')
                    ->label(__('fields.batch'))
                    ->numeric(),
                TextInput::make('parcel_value')
                    ->prefix('R$')
                    ->numeric()
                    ->columnSpan(2)
                    ->label(__('fields.parcel_value')),
                TextInput::make('due_day')
                    ->label('Dia do Vencimento')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                TextInput::make('multiplier')
                    ->label(__('fields.multiplier'))
                    ->live()
                    ->columnSpan(1)
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $grossValue = floatval($get('parcel_value')) * floatval($get('multiplier'));
                        $set('gross_value', $grossValue);
                    })
                    ->numeric(),
                TextInput::make('gross_value')
                    ->readOnly()
                    ->columnSpan(2)
                    ->numeric()
                    ->label(__('fields.gross_value')),
                Select::make('payment_way_id')
                    ->label('Forma de Pagamento')
                    ->preload()
                    ->searchable()
                    ->live()
                    ->relationship(name: 'paymentWay', titleAttribute: 'name')
                    ->createOptionForm(PaymentWayForm::form())
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            if (!ParcelsVerification::checkIfPaymentWaySumIsInAccordingWithMultiplier($value, $get('multiplier'))) {
                                $fail("A forma de pagamento não está de acordo com o multiplicador.");
                            }
                        },
                    ])
                    ->columnSpan(2),
                TextInput::make('discount_percentage')
                    ->label(__('fields.discount_percentage'))
                    ->columnSpan(2)
                    ->live()
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $netValue = floatval($get('gross_value')) - (floatval($get('gross_value')) * floatval($get('discount_percentage'))) / 100;
                        $set('net_value', $netValue);
                    })
                    ->suffix('%')
                    ->numeric(),
                TextInput::make('net_value')
                    ->readOnly()
                    ->live()
                    ->columnSpan(2)
                    ->numeric()
                    ->label(__('fields.net_value')),
                Textarea::make('business_note')
                    ->label('Observação')
                    ->columnSpanFull(),
                ParcelsDetails::make('parcels_details')
                    ->label('')
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('gross_value') != null),
            ])->columns(6);
    }

    private static function getBuyerInvoicing(): Fieldset
    {
        return Fieldset::make('Faturamento pelo Comprador')
            ->schema([
                TextInput::make('buyer_commission')
                    ->label('Comissão')
                    ->live()
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $buyerComissionValue = (floatval($get('gross_value')) * floatval($get('buyer_commission'))) / 100;
                        $set('buyer_comission_value', $buyerComissionValue);
                    })
                    ->suffix('%')
                    ->numeric(),
                TextInput::make('buyer_comission_value')
                    ->readOnly()
                    ->live()
                    ->columnSpan(3)
                    ->numeric()
                    ->label('Valor da Comissão'),
                TextInput::make('buyer_due_day')
                    ->label('Dia do Vencimento')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                TextInput::make('buyer_commission_installments_number')
                    ->label('Quantidade de Parcelas')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                BuyerParcelsDetails::make('buyer_parcels_details')
                    ->label('')
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('buyer_commission_installments_number') != null),
            ])->columns(6);
    }

    private static function getSellerInvoicing(): Fieldset
    {
        return Fieldset::make('Faturamento pelo Vendedor')
            ->schema([
                TextInput::make('seller_commission')
                    ->label('Comissão')
                    ->live()
                    ->debounce(600)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $sellerComissionValue = (floatval($get('gross_value')) * floatval($get('seller_commission'))) / 100;
                        $set('seller_comission_value', $sellerComissionValue);
                    })
                    ->suffix('%')
                    ->numeric(),
                TextInput::make('seller_comission_value')
                    ->readOnly()
                    ->live()
                    ->columnSpan(3)
                    ->numeric()
                    ->label('Valor da Comissão'),
                TextInput::make('seller_due_day')
                    ->label('Dia do Vencimento')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                TextInput::make('seller_commission_installments_number')
                    ->label('Quantidade de Parcelas')
                    ->live()
                    ->columnSpan(1)
                    ->numeric(),
                SellerParcelsDetails::make('seller_parcels_details')
                    ->label('')
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('seller_commission_installments_number') != null),
            ])->columns(6);
    }

    private static function getEntry(): Fieldset
    {
        return Fieldset::make('Documentação - Entrada (Envio e Recebimento do Comprador')
            ->schema([
                Checkbox::make('entry_contracts')
                    ->label('Contratos'),
                Checkbox::make('entry_promissory')
                    ->label('NP'),
                Checkbox::make('entry_register_copy')
                    ->label('Cópia do Registro'),
                Radio::make('entry_first_parcel_business')
                    ->label('Parcela 01 do Negócio')
                    ->options([
                        'ticket' => 'Boleto',
                        'deposit' => 'Depósito',
                    ]),
                Radio::make('entry_first_parcel_comission')
                    ->label('Parcela 01 da Comissão')
                    ->options([
                        'ticket' => 'Boleto',
                        'deposit' => 'Depósito',
                    ]),
                DatePicker::make('entry_buyer_sending_documentation_date')
                    ->label('Data de envio'),
                Radio::make('entry_buyer_sending_documentation_way')
                    ->label('Forma de Envio')
                    ->options([
                        'email' => 'E-mail',
                        'whatsapp' => 'Whatsapp',
                        'material' => 'Físico'
                    ]),
                DatePicker::make('entry_contract_return_date')
                    ->label('Retorno do Contrato'),
                Textarea::make('entry_documentation_note')
                    ->columnSpanFull()
                    ->label('Observação')
            ])->columns(4);
    }

    private static function getOutput(): Fieldset
    {
        return Fieldset::make('Documentação - Saída (Envio para o Vendedor')
            ->schema([
                Checkbox::make('output_contracts')
                    ->columnSpan(2)
                    ->label('Contratos'),
                Checkbox::make('output_promissory')
                    ->columnSpan(2)
                    ->label('NP'),
                Checkbox::make('output_register_copy')
                    ->columnSpan(2)
                    ->label('Cópia do Registro'),
                DatePicker::make('output_first_parcel_date')
                    ->columnSpan(2)
                    ->label('Data da Parcela 01'),
                DatePicker::make('output_sending_documentation_date')
                    ->columnSpan(2)
                    ->label('Data de envio do processo físico'),
                Radio::make('output_seller_sending_documentation_way')
                    ->columnSpan(2)
                    ->label('Forma de Envio')
                    ->options([
                        'email' => 'E-mail',
                        'whatsapp' => 'Whatsapp',
                        'material' => 'Físico'
                    ]),
                Textarea::make('output_documentation_note')
                    ->columnSpanFull()
                    ->label('Observação')
            ])->columns(6);
    }
}
