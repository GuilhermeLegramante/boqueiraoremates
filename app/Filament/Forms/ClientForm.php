<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Leandrocfe\FilamentPtbrFormFields\Document;
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
                            Select::make('gender')
                                ->options([
                                    'male' => 'Masculino',
                                    'female' => 'Feminino',
                                ])
                                ->default('male')
                                ->label(__('fields.gender')),
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
                    Fieldset::make('Informações Bancárias')
                        ->schema([
                            Select::make('bank')
                            ->label(__('fields.bank'))
                            ->preload()
                            ->relationship(name: 'banks', titleAttribute: 'name'),
                        ])
                        ->columns(4)

                    // Select::make('register_origin')
                    // ->options([
                    //     'marketing' => 'Divulgação',
                    //     'local' => 'Recinto',
                    //     'site' => 'Site',
                    // ])
                    // ->required()
                    // ->default('marketing')
                    // ->label('Canal de Inclusão'),
                ])
                ->columns(2)

        ];
    }
}
