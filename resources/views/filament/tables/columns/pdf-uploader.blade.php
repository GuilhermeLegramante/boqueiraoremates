<div class="flex items-center justify-center w-full" @click.stop x-data="{ isUploading: false, progress: 0 }"
    x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">

    <label
        class="group relative inline-flex cursor-pointer items-center gap-2 rounded-md border border-gray-300 bg-white px-2.5 py-1 text-xs font-medium text-gray-700 shadow-xs transition-all hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">

        {{-- Conteúdo do Botão quando NÃO está a carregar --}}
        <div x-show="!isUploading" class="flex items-center gap-1.5">
            @if ($getState())
                {{-- PDF já anexado --}}
                <svg class="h-4 w-4 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span class="font-semibold text-gray-800 dark:text-gray-100">PDF</span>
                <span class="text-[10px] text-gray-500 underline group-hover:text-primary-600">(Trocar)</span>
            @else
                {{-- Sem PDF --}}
                <svg class="h-4 w-4 text-gray-400 group-hover:text-primary-500" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Anexar PDF</span>
            @endif
        </div>

        {{-- Estado de Loading (Com Alto Contraste) --}}
        <div x-show="isUploading" x-cloak class="flex items-center gap-2 text-primary-600 dark:text-primary-400">
            <svg class="h-4 w-4 animate-spin text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="font-bold text-xs text-gray-900 dark:text-white" x-text="progress + '%'"></span>
        </div>

        {{-- Input File --}}
        <input type="file" wire:model="mountedTableActionsData.0.{{ $getName() }}" class="hidden"
            accept=".pdf,application/pdf" />
    </label>
</div>
