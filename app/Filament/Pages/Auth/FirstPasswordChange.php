<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Pages\Auth\Concerns\HasLogo;
use Filament\Pages\Page;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class FirstPasswordChange extends Login
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.first-password-change';

    protected static bool $shouldRegisterNavigation = false; // 🚫 não aparece no menu
    protected static ?string $title = 'Definir nova senha';

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
