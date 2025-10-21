<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Forms\ClientForm;
use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{
    protected static string $view = 'register';

    public function getMaxWidth(): MaxWidth | string | null
    {
        return '7xl';
    }

    protected function getFormContainerWidth(): ?string
    {
        return 'max-w-full'; // largura total do container do formulário
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Informações Pessoais')->schema(ClientForm::personalInfo()),
                    Step::make('Endereço')->schema(ClientForm::address()),
                    Step::make('Informações Adicionais')
                        ->schema(array_merge(
                            ClientForm::extra(),
                            [
                                \Filament\Forms\Components\Actions::make([
                                    \Filament\Forms\Components\Actions\Action::make('register')
                                        ->label('Criar conta')
                                        ->submit('register')
                                        ->color('primary'),
                                ]),
                            ]
                        )),
                ])
                    ->columnSpanFull()
                    ->columns(1)   // opcional, mas recomendado
                    ->skippable(false)
                    ->persistStepInQueryString(),
            ])
            ->statePath('data');
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $user = $this->handleRegistration($data);

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    protected function handleRegistration(array $data): User
    {
        return DB::transaction(function () use ($data) {

            // Verifica se já existe cliente pelo CPF/CNPJ
            $client = Client::where('cpf_cnpj', $data['cpf_cnpj'])->first();

            if ($client) {
                // Atualiza o usuário relacionado
                $user = $client->registeredUser;
                $user->update([
                    'name'  => $data['name'],
                    'username' => $data['cpf_cnpj'],
                    'email' => $data['email'],
                    'password' => $data['password'], // já vem hasheada do form
                ]);

                // Atualiza endereço
                $addressData = collect($data)->only([
                    'postal_code',
                    'street',
                    'number',
                    'complement',
                    'reference',
                    'district',
                    'city',
                    'state',
                ])->toArray();

                $client->address()->update($addressData);

                // Atualiza dados do cliente
                $clientData = collect($data)->except([
                    'username',
                    'password',
                    'passwordConfirmation',
                    'postal_code',
                    'street',
                    'number',
                    'complement',
                    'reference',
                    'district',
                    'city',
                    'state',
                    'cnh_rg',
                    'document_income',
                    'document_residence',
                ])->toArray();

                $client->update($clientData);
            } else {
                // Cria novo usuário
                $user = User::create([
                    'name'     => $data['name'],
                    'username' => $data['cpf_cnpj'],
                    'email'    => $data['email'],
                    'password' => $data['password'], // já vem hasheada do form
                ]);

                $user->assignRole('client');

                // Cria endereço
                $addressData = collect($data)->only([
                    'postal_code',
                    'street',
                    'number',
                    'complement',
                    'reference',
                    'district',
                    'city',
                    'state',
                ])->toArray();

                $address = \App\Models\Address::create($addressData);

                // Cria cliente
                $clientData = collect($data)->except([
                    'username',
                    'password',
                    'passwordConfirmation',
                    'postal_code',
                    'street',
                    'number',
                    'complement',
                    'reference',
                    'district',
                    'city',
                    'state',
                    'cnh_rg',
                    'document_income',
                    'document_residence',
                ])->toArray();

                $clientData['situation']       = 'disabled';
                $clientData['register_origin'] = $clientData['register_origin'] ?? 'site';
                $clientData['address_id']      = $address->id;

                $client = new Client($clientData);
                $client->registeredUser()->associate($user);
                $client->save();
            }

            // Salva documentos (substituindo ou criando)
            $documentsMap = [
                'cnh_rg'            => 'DOCUMENTO PESSOAL',
                'document_income'   => 'COMPROVANTE DE RENDA',
                'document_residence' => 'COMPROVANTE DE RESIDÊNCIA',
            ];

            foreach ($documentsMap as $input => $docTypeName) {
                if (!empty($data[$input])) {
                    $docType = DocumentType::where('name', $docTypeName)->first();

                    Document::updateOrCreate(
                        [
                            'client_id' => $client->id,
                            'document_type_id' => $docType->id,
                        ],
                        [
                            'user_id' => $user->id,
                            'path'    => $data[$input],
                        ]
                    );
                }
            }

            return $user;
        });
    }
}
