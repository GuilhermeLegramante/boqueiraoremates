<x-filament-panels::page.simple>
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
</x-filament-panels::page.simple>
