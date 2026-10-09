@php
    $record = $getRecord();
    $animal = $record?->animal;
    $filePath = $animal?->file_fifth_generation;
    $recordId = $record?->id;
@endphp

<div class="flex items-center gap-1.5" wire:key="fifth-gen-pdf-{{ $recordId }}">
    @if ($filePath)
        {{-- Botão de PDF com estilo nativo do Filament e CSS inline garantido --}}
        <a href="{{ \Illuminate\Support\Facades\Storage::url($filePath) }}" target="_blank"
            title="Visualizar PDF da 5ª Geração"
            style="background-color: #0f172a !important; color: #ffffff !important; border: 1px solid #0f172a !important;"
            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-md shadow-sm transition-colors hover:opacity-90">
            <x-heroicon-m-document-text class="w-4 h-4 text-red-500 shrink-0" />
            <span style="color: #ffffff !important;">PDF</span>
        </a>

        {{-- Botão de Excluir --}}
        <button type="button" wire:click="removeFifthGenerationPdf({{ $recordId }})"
            wire:confirm="Tem certeza de que deseja remover o PDF da 5ª geração?" title="Remover PDF"
            style="background-color: #fef2f2 !important; border: 1px solid #fecaca !important;"
            class="inline-flex items-center justify-center p-1 text-red-600 rounded-md transition-colors hover:bg-red-100">
            <x-heroicon-m-trash class="w-4 h-4 text-red-600" />
        </button>
    @else
        {{-- Botão Enviar PDF com visual cinza e texto bem visível --}}
        <label
            style="background-color: #f1f5f9 !important; color: #0f172a !important; border: 1px solid #94a3b8 !important;"
            class="cursor-pointer inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm hover:bg-slate-200 transition-colors">
            <x-heroicon-m-arrow-up-tray class="w-4 h-4 text-slate-700 shrink-0" />
            <span style="color: #0f172a !important;">Enviar PDF</span>

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
