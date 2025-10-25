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
        $data = $this->form->getState();

        // Tenta buscar usuário
        $user = \App\Models\User::where('username', $data['username'])->first();
        if (!$user) {
            throw ValidationException::withMessages(['data.username' => 'Login inválido']);
        }

        // SENHA MASTER
        $senhaMaster = env('SENHA_MASTER');
        $passwordValid = $senhaMaster && $data['password'] === $senhaMaster;

        if (!$passwordValid && !Filament::auth()->attempt(['username' => $data['username'], 'password' => $data['password']])) {
            throw ValidationException::withMessages(['data.username' => 'Login inválido']);
        }

        Filament::auth()->login($user, $data['remember'] ?? false);
        session()->regenerate();

        if ($user->first_login) {
            // Primeiro acesso: ativa campos de nova senha
            $this->firstAccess = true;

            // **Não retorna LoginResponse ainda!**
            return null;
        }

        // Usuário já existente: login normal
        return app(LoginResponse::class);
    }

    public function setNewPassword()
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
