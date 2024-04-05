<?php

namespace App\Filament\Forms;

use App\Utils\MoneyHandler;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Forms\Components\Actions\Action;


class OrderForm
{
    public static function form(): array
    {
        return [
            Section::make('Dados da Ordem de Serviço')
                ->description(
                    fn (string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                )
                ->schema([
                    Fieldset::make('Serviço')
                        ->schema([
                            Select::make('event_id')
                                ->label(__('fields.event'))
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
                        ]),
                    Fieldset::make('Negócio')
                        ->schema([
                            Select::make('buyer_id')
                                ->label(__('fields.buyer'))
                                ->preload()
                                ->relationship(name: 'buyer', titleAttribute: 'name')
                                ->createOptionForm(ClientForm::form())
                                ->columnSpanFull(),
                            Select::make('animal_id')
                                ->label(__('fields.animal'))
                                ->preload()
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
                            TextInput::make('multiplier')
                                ->label(__('fields.multiplier'))
                                ->live()
                                ->columnSpan(2)
                                ->debounce(600)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $grossValue = floatval($get('parcel_value')) * floatval($get('multiplier'));
                                    // $set('gross_value', number_format($grossValue, 2, ',', '.'));
                                    $set('gross_value', $grossValue);
                                })
                                ->numeric(),
                            TextInput::make('gross_value')
                                ->readOnly()
                                ->columnSpan(2)
                                ->numeric()
                                ->label(__('fields.gross_value')),
                            TextInput::make('installment_formula')
                                ->label(__('fields.installment_formula'))
                                ->columnSpan(2),
                            TextInput::make('discount_percentage')
                                ->label(__('fields.discount_percentage'))
                                ->columnSpan(2)
                                ->live()
                                ->debounce(600)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $netValue = floatval($get('gross_value')) - (floatval($get('gross_value')) * floatval($get('discount_percentage'))) / 100;
                                    // $set('net_value', number_format($netValue, 2, ',', '.'));
                                    $set('net_value', $netValue);
                                })
                                ->suffix('%')
                                ->numeric(),
                            TextInput::make('net_value')
                                ->readOnly()
                                ->columnSpan(2)
                                ->numeric()
                                ->label(__('fields.net_value')),
                        ])->columns(6),
                ])
                ->columns(2)

        ];
    }
}
