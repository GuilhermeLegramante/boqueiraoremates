@php
    $record = $getRecord();
    $animal = $record?->animal;
    $filePath = $animal?->file_fifth_generation;
    $recordId = $record?->id;
@endphp

<div class="flex items-center gap-2" wire:key="fifth-gen-pdf-{{ $recordId }}">
    @if ($filePath)
        {{-- Botão para Visualizar / Baixar o PDF --}}
        <a href="{{ \Illuminate\Support\Facades\Storage::url($filePath) }}" target="_blank"
            title="Visualizar PDF da 5ª Geração"
            class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-white !text-white bg-gray-900 hover:bg-black rounded-md shadow-sm transition-colors border border-gray-900 dark:bg-white dark:!text-gray-900 dark:hover:bg-gray-100 dark:border-white">
            <x-heroicon-m-document-text class="w-4 h-4 text-red-500 shrink-0" />
            <span class="leading-none">PDF</span>
        </a>

        {{-- Botão para Remover o PDF --}}
        <button type="button" wire:click="removeFifthGenerationPdf({{ $recordId }})"
            wire:confirm="Tem certeza de que deseja remover o PDF da 5ª geração?" title="Remover PDF"
            class="inline-flex items-center justify-center p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md border border-red-200 transition dark:border-red-900/50 dark:text-red-400 dark:hover:bg-red-950/50">
            <x-heroicon-m-trash class="w-4 h-4" />
        </button>
    @else
        {{-- Botão de Upload com ALTO CONTRASTE (Fundo escuro / Borda definida) --}}
        <label
            class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-800 bg-slate-100 border border-slate-400 hover:bg-slate-200 hover:border-slate-500 rounded-md shadow-sm transition dark:bg-slate-800 dark:text-slate-100 dark:border-slate-600 dark:hover:bg-slate-700">
            <x-heroicon-m-arrow-up-tray class="w-4 h-4 text-slate-600 dark:text-slate-300" />
            <span>Enviar PDF</span>

            {{-- Utiliza $wire.upload direto para garantir que o upload seja interceptado pelo Livewire --}}
            <input type="file" accept="application/pdf"
                wire:change="$upload('pdfUploads.{{ $recordId }}', $event.target.files[0])" class="sr-only" />
        </label>
    @endif

    {{-- Feedback visual de carregamento --}}
    <span wire:loading wire:target="pdfUploads.{{ $recordId }}"
        class="text-xs font-medium text-amber-600 animate-pulse">
        Enviando...
    </span>
    <span wire:loading wire:target="removeFifthGenerationPdf({{ $recordId }})"
        class="text-xs font-medium text-red-600 animate-pulse">
        Removendo...
    </span>
</div>
