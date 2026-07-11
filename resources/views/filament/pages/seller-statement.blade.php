{{-- <x-filament-panels::page>
    <form wire:submit="submit">

        {{ $this->form }}

        <br>
        <br>

        <x-filament::button type="submit" form="submit" x-on:click="document.getElementById('submit').target = '_blank'">
            Gerar Extrato do Vendedor
        </x-filament::button>
    </form>

</x-filament-panels::page> --}}

<x-filament-panels::page x-data x-on:open-pdf.window="window.open($event.detail.url, '_blank')">
    <form wire:submit="submit">
        {{ $this->form }}

        <x-filament::button type="submit">
            Gerar Extrato do Vendedor
        </x-filament::button>
    </form>
</x-filament-panels::page>
