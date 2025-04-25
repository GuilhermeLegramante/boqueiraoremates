<x-filament-panels::page>
    <form wire:submit="submit">

        {{ $this->form }}

        <br>
        <br>

        <x-filament::button type="button" wire:click="submit"
            x-on:click="window.open(@js($url), '_blank')">
            Gerar Extrato do Vendedor
        </x-filament::button>
    </form>

</x-filament-panels::page>
