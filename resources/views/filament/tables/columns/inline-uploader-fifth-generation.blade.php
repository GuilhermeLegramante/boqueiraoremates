@php
    $record = $getRecord();
    $animal = $record?->animal;
    $filePath = $animal?->file_fifth_generation;
    $recordId = $record?->id;
@endphp

<div class="flex items-center gap-1.5" wire:key="fifth-gen-pdf-{{ $recordId }}">
    @if ($filePath)
        {{-- Botão de PDF com visualização tratada para Light e Dark Mode --}}
        <a href="{{ \Illuminate\Support\Facades\Storage::url($filePath) }}" target="_blank"
            title="Visualizar PDF da 5ª Geração"
            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-md shadow-sm transition-colors bg-slate-900 text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
            style="color: inherit;">
            <x-heroicon-m-document-text class="w-4 h-4 text-red-500 shrink-0" />
            <span>PDF</span>
        </a>

        {{-- Botão de Excluir ajustado para Modo Claro e Modo Escuro --}}
        <button type="button" wire:click="removeFifthGenerationPdf({{ $recordId }})"
            wire:confirm="Tem certeza de que deseja remover o PDF da 5ª geração?" title="Remover PDF"
            class="inline-flex items-center justify-center p-1.5 rounded-md transition-colors border bg-red-50 border-red-200 text-red-600 hover:bg-red-100 hover:text-red-700 dark:bg-red-950/40 dark:border-red-800/60 dark:text-red-400 dark:hover:bg-red-900/60 dark:hover:text-red-300">
            <x-heroicon-m-trash class="w-4 h-4" />
        </button>
    @else
        {{-- Botão Enviar PDF com visual tratado para Light e Dark Mode --}}
        <label
            class="cursor-pointer inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors border bg-slate-100 border-slate-400 text-slate-800 hover:bg-slate-200 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-700">
            <x-heroicon-m-arrow-up-tray class="w-4 h-4 text-slate-600 dark:text-slate-300 shrink-0" />
            <span>Enviar PDF</span>

            <input type="file" accept="application/pdf"
                wire:change="$upload('pdfUploads.{{ $recordId }}', $event.target.files[0])" class="sr-only" />
        </label>
    @endif

    <span wire:loading wire:target="pdfUploads.{{ $recordId }}"
        class="text-xs font-medium text-amber-600 animate-pulse">
        Enviando...
    </span>
    <span wire:loading wire:target="removeFifthGenerationPdf({{ $recordId }})"
        class="text-xs font-medium text-red-600 animate-pulse">
        Removendo...
    </span>
</div>
