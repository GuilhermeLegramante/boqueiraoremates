<?php

namespace App\Filament\Forms;

use App\Models\City;
use App\Models\DocumentType;
use App\Models\State;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class ClientForm
{
    public static function form(): array
    {
        return [
            Section::make('Dados do Cliente')
                ->description(
                    fn (string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                )
                ->schema([
                    Fieldset::make('Informações Pessoais')
                        ->schema([
                            TextInput::make('name')
                                ->label(__('fields.name'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label(__('fields.email'))
                                ->required()
                                ->email(),
                            DatePicker::make('birth_date')
                                ->maxDate(now())
                                ->label('Data de Nascimento'),
                            Radio::make('gender')
                                ->label(__('fields.gender'))
                                ->options([
                                    'male' => 'Masculino',
                                    'female' => 'Feminino',
                                ]),
                            TextInput::make('establishment')
                                ->label(__('fields.establishment')),
                            TextInput::make('occupation')
                                ->label(__('fields.occupation')),
                            TextInput::make('note_occupation')
                                ->label(__('fields.note_occupation')),
                            Money::make('income')
                                ->label(__('fields.income')),
                        ])
                        ->columns(2),
                    Fieldset::make('Documentos')
                        ->schema([
                            Document::make('cpf_cnpj')
                                ->label(__('fields.cpf_cnpj'))
                                ->dynamic(),
                            TextInput::make('rg')
                                ->label(__('fields.rg'))
                                ->numeric(),
                        ])
                        ->columns(2),

                    Fieldset::make('Filiação')
                        ->schema([
                            TextInput::make('mother')
                                ->label(__('fields.mother')),
                            TextInput::make('father')
                                ->label(__('fields.father')),
                        ])
                        ->columns(2),
                    Fieldset::make('Contato')
                        ->schema([
                            PhoneNumber::make('whatsapp')
                                ->label(__('fields.whatsapp'))
                                ->format('(99) 99999-9999'),
                            PhoneNumber::make('cel_phone')
                                ->label(__('fields.cel_phone'))
                                ->format('(99) 99999-9999'),
                            PhoneNumber::make('business_phone')
                                ->label(__('fields.business_phone'))
                                ->format('(99) 9999-9999'),
                            PhoneNumber::make('home_phone')
                                ->label(__('fields.home_phone'))
                                ->format('(99) 9999-9999')
                        ])
                        ->columns(4),
                    Fieldset::make('Endereço')
                        ->relationship('address')
                        ->schema([
                            Cep::make('postal_code')
                                ->label(__('fields.cep'))
                                ->live(onBlur: true)
                                ->viaCep(
                                    mode: 'suffix',
                                    errorMessage: 'CEP inválido.',
                                    setFields: [
                                        'street' => 'logradouro',
                                        'number' => 'numero',
                                        'complement' => 'complemento',
                                        'district' => 'bairro',
                                        'city' => 'localidade',
                                        'state' => 'uf',
                                    ]
                                ),
                            TextInput::make('street')->label(__('fields.street'))->columnSpan(1),
                            TextInput::make('number')->label(__('fields.number')),
                            TextInput::make('complement')->label(__('fields.complement')),
                            TextInput::make('reference')->label(__('fields.reference')),
                            TextInput::make('district')->label(__('fields.district')),
                            TextInput::make('city')->label(__('fields.city')),
                            TextInput::make('state')->label(__('fields.state')),
                        ])
                        ->columns(4),

                    Fieldset::make('Informações Bancárias')
                        ->schema([
                            Select::make('bank_id')
                                ->label(__('fields.bank'))
                                ->preload()
                                ->relationship(name: 'bank', titleAttribute: 'name')
                                ->createOptionForm(BankForm::form()),
                            TextInput::make('bank_agency')
                                ->label(__('fields.bank_agency')),
                            TextInput::make('current_account')
                                ->label(__('fields.current_account')),
                        ])
                        ->columns(3),

                    Fieldset::make('Informações Adicionais')
                        ->schema([
                            Toggle::make('has_register_in_another_auctioneer')
                                ->label(__('fields.has_register_in_another_auctioneer')),
                            TextInput::make('auctioneer')
                                ->label(__('fields.auctioneer')),
                            Radio::make('situation')
                                ->label(__('fields.situation'))
                                ->options([
                                    'able' => 'Habilitado',
                                    'disabled' => 'Inabilitado',
                                    'inactive' => 'Inativo'
                                ]),
                            Radio::make('register_origin')
                                ->label(__('fields.register_origin'))
                                ->options([
                                    'marketing' => 'Divulgação',
                                    'local' => 'Recinto',
                                    'site' => 'Site'
                                ]),
                            Radio::make('profile')
                                ->label(__('fields.profile'))
                                ->label('Perfil')
                                ->options([
                                    'purchase' => 'Compra',
                                    'sale' => 'Venda',
                                    'both' => 'Ambos'
                                ])

                        ])
                        ->columns(3),
                ])
                ->columns(2)

        ];
    }
}
