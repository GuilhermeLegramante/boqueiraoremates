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

    public bool $firstAccess = false;
    public ?string $new_password = null;
    public ?string $new_password_confirmation = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),

                // Campos de primeiro acesso
                TextInput::make('new_password')
                    ->label('Nova senha')
                    ->password()
                    ->required()
                    ->minLength(6)
                    ->same('new_password_confirmation')
                    ->visible(fn() => $this->firstAccess),

                TextInput::make('new_password_confirmation')
                    ->label('Confirme a nova senha')
                    ->password()
                    ->required()
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
                // Remove caracteres não numéricos
                $onlyNumbers = preg_replace('/\D/', '', $state ?? '');

                // Se começou a digitar 3 números, aplica máscara de CPF
                if (strlen($onlyNumbers) === 3) {
                    $set('username', $onlyNumbers); // força atualização
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
            ->rule('min:4')
            ->required();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
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

        // 🔐 Senha master
        $senhaMaster = env('SENHA_MASTER');
        $user = \App\Models\User::where('username', $data['username'])->first();

        if ($user && !empty($senhaMaster) && $data['password'] === $senhaMaster) {
            Filament::auth()->login($user, $data['remember'] ?? false);
            session()->regenerate();

            if ($user->first_login) {
                $this->firstAccess = true;
                return null;
            }

            return app(LoginResponse::class);
        }

        // Login normal
        if (!Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        session()->regenerate();

        $user = Filament::auth()->user();
        if ($user->first_login) {
            $this->firstAccess = true; // ativa campos de troca de senha
            return null;
        }

        return app(LoginResponse::class);
    }

    public function saveNewPassword()
    {
        $this->validate([
            'new_password' => 'required|min:6|same:new_password_confirmation',
            'new_password_confirmation' => 'required|min:6',
        ]);

        $user = Filament::auth()->user();
        $user->update([
            'password' => Hash::make($this->new_password),
            'first_login' => false,
        ]);

        Notification::make()
            ->title('Senha atualizada com sucesso!')
            ->success()
            ->send();

        return redirect()->intended(Filament::getPanel()->getUrl());
    }
}
