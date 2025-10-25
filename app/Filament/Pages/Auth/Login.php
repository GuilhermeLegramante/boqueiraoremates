<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as AuthLogin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login extends AuthLogin
{
    protected static string $view = 'pages.auth.login';

    // Controle do primeiro acesso
    public bool $firstAccess = false;
    public ?string $new_password = null;
    public ?string $new_password_confirmation = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Campos de login sempre visíveis
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),

                // Campos de primeira senha: visíveis apenas se $firstAccess for true
                TextInput::make('new_password')
                    ->label('Nova senha')
                    ->password()
                    ->required(fn() => $this->firstAccess)
                    ->minLength(6)
                    ->revealable(filament()->arePasswordsRevealable())
                    ->same('new_password_confirmation')
                    ->visible(fn() => $this->firstAccess),

                TextInput::make('new_password_confirmation')
                    ->label('Confirme a nova senha')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required(fn() => $this->firstAccess)
                    ->visible(fn() => $this->firstAccess),
            ])
            ->statePath('data');
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Login')
            ->required()
            ->maxLength(255)
            ->autofocus()
            ->reactive()
            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                $onlyNumbers = preg_replace('/\D/', '', $state ?? '');
                if (strlen($onlyNumbers) === 3) {
                    $set('username', $onlyNumbers);
                }
            })
            ->mask(fn($state) => preg_match('/^\d{3}/', $state ?? '') ? '999.999.999-99' : null)
            ->placeholder('Digite login ou CPF');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Senha')
            ->validationAttribute('senha')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->rule('min:4')
            ->required();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: [])
                    ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                        'seconds' => $exception->secondsUntilAvailable,
                        'minutes' => ceil($exception->secondsUntilAvailable / 60),
                    ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        $user = \App\Models\User::where('username', $data['username'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'data.username' => 'Login inválido.',
            ]);
        }

        $senhaMaster = env('SENHA_MASTER');
        $passwordValid = ($senhaMaster && $data['password'] === $senhaMaster) || Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false);

        if (!$passwordValid) {
            throw ValidationException::withMessages([
                'data.username' => 'Login inválido.',
            ]);
        }

        Filament::auth()->login($user, $data['remember'] ?? false);
        session()->regenerate();

        // PRIMEIRO ACESSO
        if ($user->first_login) {
            $this->firstAccess = true;

            // Se o usuário já preencheu a nova senha corretamente, atualiza
            if (!empty($this->new_password) && $this->new_password === $this->new_password_confirmation) {
                $user->update([
                    'password' => Hash::make($this->new_password),
                    'first_login' => false,
                ]);

                Notification::make()
                    ->title('Senha atualizada com sucesso!')
                    ->success()
                    ->send();

                return app(LoginResponse::class);
            }

            // Se ainda não preencheu a nova senha, exibe os campos
            return null;
        }

        return app(LoginResponse::class);
    }
}
