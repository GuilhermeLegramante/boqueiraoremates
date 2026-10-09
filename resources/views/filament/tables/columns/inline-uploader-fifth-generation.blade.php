@php
    // $getRecord() nos dá o registro da linha (AnimalEvent)
    $record = $getRecord();
    $animal = $record?->animal;
    $filePath = $animal?->file_fifth_generation;
    $recordId = $record?->id;
@endphp

<div class="flex items-center gap-2 p-1" wire:key="fifth-gen-pdf-{{ $recordId }}">
    @if ($filePath)
        {{-- Botão para visualizar/baixar o PDF já existente --}}
        <a href="{{ \Illuminate\Support\Facades\Storage::url($filePath) }}" target="_blank"
            title="Visualizar PDF da 5ª Geração"
            class="inline-flex items-center justify-center p-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow transition">
            <x-heroicon-o-document-text class="w-4 h-4 mr-1" />
            PDF
        </a>
    @endif

    {{-- Input estilizado para upload do PDF --}}
    <label
        class="cursor-pointer inline-flex items-center justify-center p-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
        <x-heroicon-o-arrow-up-tray class="w-4 h-4 {{ $filePath ? '' : 'mr-1' }}" />
        @if (!$filePath)
            <span>Enviar PDF</span>
        @endif

        {{-- O wire:model faz o bind no array mountedTableActionsData.{id}.file_fifth_generation --}}
        <input type="file" accept="application/pdf"
            wire:model="mountedTableActionsData.{{ $recordId }}.file_fifth_generation" class="sr-only" />
    </label>

    {{-- Indicador visual de carregamento do Livewire --}}
    <span wire:loading wire:target="mountedTableActionsData.{{ $recordId }}.file_fifth_generation"
        class="text-xs text-primary-600 animate-pulse">
        Enviando...
    </span>
</div>
