<div class="flex items-center justify-center w-full" @click.stop x-data="{ isUploading: false, progress: 0 }"
    x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">

    <label
        class="group relative inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

        @if ($getState())
            {{-- Estado quando o arquivo PDF está anexado --}}
            <svg class="h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span class="truncate max-w-[100px]" title="PDF Anexado">PDF Anexado</span>
            <span class="text-[10px] text-gray-400 group-hover:text-primary-500 underline ml-1">(Trocar)</span>
        @else
            {{-- Estado quando está vazio --}}
            <svg class="h-4 w-4 text-gray-400 group-hover:text-primary-500" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Anexar PDF</span>
        @endif

        {{-- Input oculto --}}
        <input type="file" wire:model="mountedTableActionsData.{{ $getRecord()->id }}.{{ $getName() }}"
            class="hidden" accept=".pdf,application/pdf" />

        {{-- Barra de progresso discreta --}}
        <div x-show="isUploading" x-transition
            class="absolute inset-0 z-20 flex items-center justify-center rounded-lg bg-gray-900/80 px-2 text-[10px] font-bold text-white backdrop-blur-xs"
            style="display: none;">
            <span x-text="'Enviando... ' + progress + '%'"></span>
        </div>
    </label>
</div>
