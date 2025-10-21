<?php

namespace App\Filament\Forms;

use App\Models\Bank;
use App\Models\City;
use App\Models\Client;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
                    fn(string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
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
                                ->label('Data de Nascimento')
                                ->required()
                                ->maxDate(now()->subYears(18)) // Impede selecionar quem tem menos de 18 anos
                                ->rule('before_or_equal:' . now()->subYears(18)->toDateString(), 'O cliente deve ter pelo menos 18 anos.'),
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
                            // TextInput::make('income')->numeric()->label(__('fields.income')),
                            // Money::make('income')->label(__('fields.income')),
                            TextInput::make('income')
                                ->prefix('R$')
                                ->numeric()
                                ->live()
                                ->debounce(1000)
                                ->columnSpan(2)
                                ->label(__('fields.income')),

                            TextInput::make('instagram')
                                ->label('Instagram')
                                ->placeholder('Ex: @usuario')
                                ->prefixIcon('heroicon-o-at-symbol')
                                ->maxLength(100),

                            TextInput::make('facebook')
                                ->label('Facebook')
                                ->placeholder('Ex: facebook.com/usuario')
                                ->prefixIcon('heroicon-o-globe-alt')
                                ->maxLength(100),
                        ])
                        ->columns(2),
                    Fieldset::make('Documentos')
                        ->schema([
                            Document::make('cpf_cnpj')
                                ->label(__('fields.cpf_cnpj'))
                                ->dynamic(),
                            TextInput::make('inscricaoestadual')
                                ->label('Inscrição Estadual')
                                ->maxLength(10),
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
                                ->label(__('fields.cep')),
                            // ->live(onBlur: true)
                            // ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            //     // Força os campos preenchidos via ViaCEP a ficarem em MAIÚSCULAS
                            //     $set('street', strtoupper((string) $get('street')));
                            //     $set('district', strtoupper((string) $get('district')));
                            //     $set('city', strtoupper((string) $get('city')));
                            //     $set('state', strtoupper((string) $get('state')));
                            // })
                            // ->viaCep(
                            //     mode: 'suffix',
                            //     errorMessage: 'CEP inválido.',
                            //     setFields: [
                            //         'street' => 'logradouro',
                            //         'number' => 'numero',
                            //         'complement' => 'complemento',
                            //         'district' => 'bairro',
                            //         'city' => 'localidade',
                            //         'state' => 'uf',
                            //     ]
                            // ),
                            TextInput::make('street')
                                ->label(__('fields.street'))
                                ->columnSpan(1)
                                ->afterStateUpdated(fn($state, callable $set) => $set('street', strtoupper($state)))
                                ->extraAttributes(['style' => 'text-transform: uppercase;']),

                            TextInput::make('number')
                                ->label(__('fields.number'))
                                ->afterStateUpdated(fn($state, callable $set) => $set('number', strtoupper($state)))
                                ->extraAttributes(['style' => 'text-transform: uppercase;']),

                            TextInput::make('complement')
                                ->label(__('fields.complement'))
                                ->afterStateUpdated(fn($state, callable $set) => $set('complement', strtoupper($state)))
                                ->extraAttributes(['style' => 'text-transform: uppercase;']),

                            TextInput::make('reference')
                                ->label(__('fields.reference'))
                                ->afterStateUpdated(fn($state, callable $set) => $set('reference', strtoupper($state)))
                                ->extraAttributes(['style' => 'text-transform: uppercase;']),

                            TextInput::make('district')
                                ->label(__('fields.district'))
                                ->afterStateUpdated(fn($state, callable $set) => $set('district', strtoupper($state)))
                                ->extraAttributes(['style' => 'text-transform: uppercase;']),

                            TextInput::make('city')
                                ->label(__('fields.city'))
                                ->afterStateUpdated(fn($state, callable $set) => $set('city', strtoupper($state)))
                                ->extraAttributes(['style' => 'text-transform: uppercase;']),

                            TextInput::make('state')
                                ->label(__('fields.state'))
                                ->required()
                                ->maxLength(2)
                                ->afterStateUpdated(fn($state, $set) => $set('state', strtoupper($state)))
                                ->regex('/^[A-Za-z]{2}$/') // garante exatamente 2 letras
                                ->helperText('Informe apenas duas letras do estado'),

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
                                ->options([
                                    'purchase' => 'Compra',
                                    'sale' => 'Venda',
                                    'both' => 'Ambos'
                                ]),
                            Toggle::make('has_register_in_another_auctioneer')
                                ->label(__('fields.has_register_in_another_auctioneer')),
                            TextInput::make('auctioneer')
                                ->label(__('fields.auctioneer')),

                            // Textarea::make('note') ANOTAÇÕES AGORA SÃO NO RELATION MANAGER
                            //     ->label(__('fields.note'))
                            //     ->columnSpanFull(),
                        ])
                        ->columns(3),
                ])
                ->columns(2)
        ];
    }

    public static function personalInfo(): array
    {
        return [
            Document::make('cpf_cnpj')
                ->required()
                ->label(__('fields.cpf_cnpj'))
                ->dynamic()
                ->reactive()
                ->debounce(1000)
                // ->rule(
                //     fn($get, $record) =>
                //     Rule::unique('clients', 'cpf_cnpj')
                //         ->ignore($record?->id)
                // ) SE JÁ TIVER CPF CADASTRADO VAI EDITAR E NÃO CRIAR OUTRO LÁ NO handleRegistration da Register
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) return;

                    // Carrega cliente completo
                    $client = \App\Models\Client::with('address', 'registeredUser', 'documents.documentType')
                        ->where('cpf_cnpj', $state)
                        ->first();

                    if (!$client) return;

                    // Preenche automaticamente os campos do $fillable
                    foreach ($client->getFillable() as $field) {
                        if ($field === 'address_id') continue; // endereços serão preenchidos separadamente
                        $set($field, $client->$field);
                    }

                    // Preenche email do usuário associado
                    $set('email', $client->registeredUser?->email);

                    // Preenche campos de endereço
                    if ($client->address) {
                        foreach (
                            [
                                'postal_code',
                                'street',
                                'number',
                                'complement',
                                'reference',
                                'district',
                                'city',
                                'state'
                            ] as $field
                        ) {
                            $set($field, $client->address->$field ?? null);
                        }
                    }

                    // Preenche documentos de forma dinâmica
                    $documents = $client->documents ?? collect(); // garante coleção vazia
                    $documents = $documents->keyBy(fn($doc) => $doc->documentType->name);

                    $documentMapping = [
                        'cnh_rg'             => 'DOCUMENTO PESSOAL',
                        'document_income'    => 'COMPROVANTE DE RENDA',
                        'document_residence' => 'COMPROVANTE DE RESIDÊNCIA',
                    ];

                    foreach ($documentMapping as $field => $docName) {
                        $set($field, $documents[$docName]->path ?? null);
                    }
                }),

            TextInput::make('name')
                ->label(__('filament-panels::pages/auth/register.form.name.label'))
                ->required()
                ->maxLength(255)
                ->autofocus(),

            TextInput::make('email')
                ->label(__('filament-panels::pages/auth/register.form.email.label'))
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(table: 'users', column: 'email'),

            TextInput::make('password')
                ->label('Senha')
                ->password()
                ->required()
                ->rule('min:4')
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->same('passwordConfirmation')
                ->validationAttribute('senha'),
            TextInput::make('passwordConfirmation')
                ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required()
                ->dehydrated(false),
            TextInput::make('inscricaoestadual')
                ->label('Inscrição Estadual')
                ->maxLength(10),
            TextInput::make('rg')
                ->label(__('fields.rg'))
                ->numeric(),
            DatePicker::make('birth_date')
                ->label('Data de Nascimento')
                ->required()
                ->maxDate(now()->subYears(18)) // Impede selecionar quem tem menos de 18 anos
                ->rule('before_or_equal:' . now()->subYears(18)->toDateString(), 'O cliente deve ter pelo menos 18 anos.'),
            Radio::make('gender')
                ->label(__('fields.gender'))
                ->required()
                ->options(['male' => 'Masculino', 'female' => 'Feminino']),
            TextInput::make('establishment')
                ->label(__('fields.establishment')),
            TextInput::make('occupation')
                ->required()
                ->label(__('fields.occupation')),
            TextInput::make('note_occupation')
                ->label(__('fields.note_occupation')),
            // TextInput::make('income')->numeric()->label(__('fields.income')),
            // Money::make('income')
            //     ->label(__('fields.income'))
            //     ->live(condition: false),
            TextInput::make('income')
                ->prefix('R$')
                ->numeric()
                ->live()
                ->debounce(1000)
                // ->columnSpan(2)
                ->label(__('fields.income')),
            PhoneNumber::make('whatsapp')
                ->label(__('fields.whatsapp'))
                ->required()
                ->format('(99) 99999-9999'),
            PhoneNumber::make('cel_phone')
                ->label(__('fields.cel_phone'))
                ->required()
                ->format('(99) 99999-9999'),
            PhoneNumber::make('business_phone')
                ->label(__('fields.business_phone'))
                ->format('(99) 99999-9999'),
            PhoneNumber::make('home_phone')
                ->label(__('fields.home_phone'))
                ->format('(99) 99999-9999'),
            TextInput::make('mother')->label(__('fields.mother')),
            TextInput::make('father')->label(__('fields.father')),
            // Novos campos de redes sociais

        ];
    }

    public static function address(): array
    {
        return [
            Cep::make('postal_code')
                ->required()
                ->label(__('fields.cep')),
            // ->viaCep(
            //     mode: 'suffix',
            //     errorMessage: 'CEP inválido.',
            //     setFields: [
            //         'street' => 'logradouro',
            //         'district' => 'bairro',
            //         'city' => 'localidade',
            //         'state' => 'uf',
            //     ]
            // )
            // ->afterStateUpdated(function ($state, callable $set, callable $get) {
            //     $set('street', strtoupper((string) $get('street')));
            //     $set('district', strtoupper((string) $get('district')));
            //     $set('city', strtoupper((string) $get('city')));
            //     $set('state', strtoupper((string) $get('state')));
            // }),
            TextInput::make('street')
                ->required()
                ->label(__('fields.street'))->afterStateUpdated(fn($state, $set) => $set('street', strtoupper($state))),
            TextInput::make('number')->required()->label(__('fields.number'))->afterStateUpdated(fn($state, $set) => $set('number', strtoupper($state))),
            TextInput::make('complement')->label(__('fields.complement'))->afterStateUpdated(fn($state, $set) => $set('complement', strtoupper($state))),
            TextInput::make('reference')->label(__('fields.reference'))->afterStateUpdated(fn($state, $set) => $set('reference', strtoupper($state))),
            TextInput::make('district')->required()->label(__('fields.district'))->afterStateUpdated(fn($state, $set) => $set('district', strtoupper($state))),
            TextInput::make('city')->required()->label(__('fields.city'))->afterStateUpdated(fn($state, $set) => $set('city', strtoupper($state))),

            TextInput::make('state')
                ->label(__('fields.state'))
                ->required()
                ->maxLength(2)
                ->afterStateUpdated(fn($state, $set) => $set('state', strtoupper($state)))
                ->regex('/^[A-Za-z]{2}$/') // garante exatamente 2 letras
                ->helperText('Informe apenas duas letras do estado'),
        ];
    }

    public static function extra(): array
    {
        return [
            Select::make('bank_id')
                ->label(__('fields.bank'))
                ->options(Bank::pluck('name', 'id')->toArray())
                ->preload(),
            TextInput::make('bank_agency')->label(__('fields.bank_agency')),
            TextInput::make('current_account')->label(__('fields.current_account')),
            Toggle::make('has_register_in_another_auctioneer')->label(__('fields.has_register_in_another_auctioneer')),
            TextInput::make('auctioneer')->label(__('fields.auctioneer')),
            FileUpload::make('cnh_rg')
                ->label('Cópia da CNH ou RG')
                ->directory('documents'),
            FileUpload::make('document_income')
                ->label('Comprovante de Renda')
                ->directory('documents'),
            FileUpload::make('document_residence')
                ->label('Comprovante de Residência')
                ->directory('documents'),

            TextInput::make('instagram')
                ->label('Instagram')
                ->placeholder('Ex: @usuario')
                ->columnSpanFull()
                ->prefixIcon('heroicon-o-at-symbol')
                ->maxLength(100),

            TextInput::make('facebook')
                ->label('Facebook')
                ->columnSpanFull()
                ->placeholder('Ex: facebook.com/usuario')
                ->prefixIcon('heroicon-o-globe-alt')
                ->maxLength(100),
        ];
    }
}
