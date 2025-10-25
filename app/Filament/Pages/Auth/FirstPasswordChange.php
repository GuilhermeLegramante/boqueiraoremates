<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class FirstPasswordChange extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.first-password-change';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Primeiro Acesso - Nova Senha';

    protected static ?string $slug = 'primeiro-acesso';

    public ?string $password = '';
    public ?string $password_confirmation = '';

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('password')
                ->label('Nova senha')
                ->password()
                ->required()
                ->rule('min:6')
                ->same('password_confirmation'),

            Forms\Components\TextInput::make('password_confirmation')
                ->label('Confirme a senha')
                ->password()
                ->required(),
        ];
    }

    public function save(): LoginResponse
    {
        $user = Filament::auth()->user();

        $user->update([
            'password' => Hash::make($this->password),
            'first_login' => false,
        ]);

        Notification::make()
            ->title('Senha atualizada com sucesso!')
            ->success()
            ->send();

        return app(LoginResponse::class);
    }
}
