{{-- <x-filament-panels::page.simple>
    <x-slot name="heading">
        <img src="{{ asset('logo.png') }}" class="mx-auto h-16 mb-4" alt="Boqueirão Remates">
        <h2 class="text-2xl font-bold text-center text-white">
            Bem-vindo(a) à nova plataforma da Boqueirão Remates!
        </h2>
    </x-slot>

    <div class="text-gray-300 text-justify mb-6">
        <p>
            Nosso objetivo é oferecer mais <strong>modernidade, segurança e praticidade</strong> para todos os clientes.
        </p>
        <p class="mt-3">
            Por segurança, no seu <strong>primeiro acesso</strong> é necessário definir uma nova senha pessoal.
        </p>
    </div>

    {{ $this->form }}

    <div class="mt-6">
        <x-filament::button wire:click="save" class="w-full text-lg py-2">
            Salvar nova senha
        </x-filament::button>
    </div>
</x-filament-panels::page.simple> --}}

<section class="grid auto-cols-fr gap-y-8 py-8">
    <div>
        <header class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between ">
            <div>
                <h1
                    class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    Perfil
                </h1>
            </div>
        </header>

        <x-filament-panels::form wire:submit="save">
            <br>
            {{ $this->form }}

            <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" alignment="right" />
        </x-filament-panels::form>
    </div>
</section>
