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

                TextInput::make('new_password')
                    ->label('Nova senha')
                    ->password()
                    ->minLength(6)
                    ->required(fn() => $this->firstAccess)
                    ->same('new_password_confirmation')
                    ->visible(fn() => $this->firstAccess)
                    ->reactive(),

                TextInput::make('new_password_confirmation')
                    ->label('Confirme a nova senha')
                    ->password()
                    ->required(fn() => $this->firstAccess)
                    ->visible(fn() => $this->firstAccess)
                    ->reactive(),
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
            ->placeholder('Digite login ou CPF');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Senha')
            ->password()
            ->required()
            ->rule('min:4')
            ->reactive();
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

        // 🔐 SENHA MASTER
        $senhaMaster = env('SENHA_MASTER');
        if ($user && !empty($senhaMaster) && $data['password'] === $senhaMaster) {
            Filament::auth()->login($user, $data['remember'] ?? false);
            session()->regenerate();

            // Primeiro acesso
            if ($user->first_login) {
                $this->firstAccess = true;

                if (!empty($this->new_password) && $this->new_password === $this->new_password_confirmation) {
                    $user->update([
                        'password' => Hash::make($this->new_password),
                        'first_login' => false,
                    ]);

                    Notification::make()
                        ->title('Senha atualizada com sucesso!')
                        ->success()
                        ->send();
                }

                return app(LoginResponse::class);
            }

            return app(LoginResponse::class);
        }

        // Login normal
        if (!$user || !Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        session()->regenerate();

        // Primeiro acesso normal
        if ($user->first_login) {
            $this->firstAccess = true;

            if (!empty($this->new_password) && $this->new_password === $this->new_password_confirmation) {
                $user->update([
                    'password' => Hash::make($this->new_password),
                    'first_login' => false,
                ]);

                Notification::make()
                    ->title('Senha atualizada com sucesso!')
                    ->success()
                    ->send();
            }

            return null; // espera usuário preencher a nova senha
        }

        return app(LoginResponse::class);
    }
}
