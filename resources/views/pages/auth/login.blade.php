<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{-- Hook antes do formulário --}}
    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

    <x-filament-panels::form wire:submit="{{ $firstAccess ? 'saveNewPassword' : 'authenticate' }}">

        {{-- Exibe campos de login normalmente sempre --}}
        {{ $this->form->getComponent('username') }}
        {{ $this->form->getComponent('password') }}
        {{ $this->form->getComponent('remember') }}

        {{-- Mensagem de primeiro acesso --}}
        @if ($firstAccess)
            <div class="mb-4 p-4 rounded bg-blue-600 text-white text-center font-semibold">
                Você está no seu <strong>primeiro acesso</strong>. Por segurança, defina sua nova senha abaixo.
            </div>

            {{-- Campos de nova senha --}}
            {{ $this->form->getComponent('new_password')->visible(true) }}
            {{ $this->form->getComponent('new_password_confirmation')->visible(true) }}
        @endif

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />

    </x-filament-panels::form>

    {{-- Hook depois do formulário --}}
    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
</x-filament-panels::page.simple>
