<x-filament-panels::page>
    {{-- 1. Relation Manager no início da página --}}
    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers :active-locale="$this->activeLocale" :active-manager="$this->activeRelationManager" :content-tab-label="$this->getContentTabLabel()"
            :managers="$relationManagers" :owner-record="$record" :page-class="static::class" />
    @endif

    {{-- 2. Formulário do Evento logo abaixo --}}
    <x-filament-panels::form id="form" :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
</x-filament-panels::page>
