<x-filament-panels::page>
    <form wire:submit="submit">

        {{ $this->form }}

        <br>
        <br>

        <x-filament::button type="submit" form="submit">
            Gerar Extrato do Vendedor
        </x-filament::button>
    </form>

</x-filament-panels::page>
