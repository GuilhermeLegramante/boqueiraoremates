@php
    $record = $getRecord();
    $animal = $record?->animal;
    $filePath = $animal?->file_fifth_generation;
    $recordId = $record?->id;
@endphp

<div class="flex items-center gap-1.5" wire:key="fifth-gen-pdf-{{ $recordId }}">
    @if ($filePath)
        {{-- Botão para Visualizar / Baixar o PDF --}}
        <a href="{{ \Illuminate\Support\Facades\Storage::url($filePath) }}" target="_blank"
            title="Visualizar PDF da 5ª Geração"
            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-gray-800 hover:bg-gray-900 border border-transparent rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white">
            <x-heroicon-m-document-text class="w-4 h-4 text-red-400" />
            <span>PDF</span>
        </a>

        {{-- Botão para Remover o PDF --}}
        <button type="button" wire:click="removeFifthGenerationPdf({{ $recordId }})"
            wire:confirm="Tem certeza de que deseja remover o PDF da 5ª geração?" title="Remover PDF"
            class="inline-flex items-center justify-center p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors dark:text-red-400 dark:hover:bg-red-950/50">
            <x-heroicon-m-trash class="w-4 h-4" />
        </button>
    @else
        {{-- Botão para Upload do PDF --}}
        <label
            class="cursor-pointer inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
            <x-heroicon-m-arrow-up-tray class="w-4 h-4 text-gray-500 dark:text-gray-400" />
            <span>Enviar PDF</span>

            <input type="file" accept="application/pdf"
                wire:model="mountedTableActionsData.{{ $recordId }}.file_fifth_generation" class="sr-only" />
        </label>
    @endif

    {{-- Indicador visual de carregamento --}}
    <span wire:loading wire:target="mountedTableActionsData.{{ $recordId }}.file_fifth_generation"
        class="text-xs text-primary-600 animate-pulse">
        Enviando...
    </span>
    <span wire:loading wire:target="removeFifthGenerationPdf({{ $recordId }})"
        class="text-xs text-red-600 animate-pulse">
        Removendo...
    </span>
</div>
